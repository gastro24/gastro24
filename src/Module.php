<?php

namespace Gastro24;

use Gastro24\Listener\SettingsChangedListener;
use Gastro24\View\Helper\IsCrawlerJob;
use Jobs\Controller\TemplateController;
use Jobs\Entity\Status;
use Yawik\Composer\AssetProviderInterface;
use Core\ModuleManager\ModuleConfigLoader;
use Gastro24\Options\Landingpages;
use Laminas\Console\Console;
use Laminas\Http\Response;
use Laminas\Mvc\MvcEvent;
use Laminas\Session\Container;
use Laminas\Stdlib\Parameters;

/**
 * Bootstrap class of our demo skin
 */
class Module implements AssetProviderInterface
{
    const TEXT_DOMAIN = __NAMESPACE__;

    /**
     * indicates, that the autoload configuration for this module should be loaded.
     * @see
     *
     * @var bool
     */
    public static $isLoaded=false;

    public function getPublicDir()
    {
        return __DIR__ . '/../public';
    }


    /**
     * Tells the autoloader, where to search for the Gastro24 classes
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {

        return array(
            'Laminas\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/../src',
                ),
            ),
        );
    }

    /**
     * Using the ModuleConfigLoader allow you to split the modules.config.php into several files.
     *
     * @return array
     */
    public function getConfig()
    {
        return ModuleConfigLoader::load(__DIR__ . '/../config');
    }

    function onBootstrap(MvcEvent $e)
    {
        self::$isLoaded=true;
        $eventManager = $e->getApplication()->getEventManager();
        $services     = $e->getApplication()->getServiceManager();

        /*
         * remove Submenu from "applications" and "jobs"
         */
        $config=$services->get('config');
        unset($config['navigation']['default']['apply']['pages']);
        unset($config['navigation']['default']['jobs']['pages']);
        $services->setAllowOverride(true);
        $services->setService('config', $config);
        $services->setAllowOverride(false);

        if (!Console::isConsole()) {
            $sharedManager = $eventManager->getSharedManager();

            $eventManager->attach(MvcEvent::EVENT_DISPATCH_ERROR, array($this, 'onDispatchError'));

            /*
             * use a neutral layout, when rendering the application form and its result page.
             * Also the application preview should be rendered in this layout.
             *
             * We need a post dispatch hook on the controller here as we need to have
             * the application entity to determine how to set the layout in the preview page.
             */
            $listener = function ($event) {
	            $viewModel  = $event->getViewModel();
	            $template   = 'layout/application-form';
	            $controller = $event->getTarget();

	            if ($controller instanceof \Applications\Controller\ApplyController) {
		            $viewModel->setTemplate($template);
		            return;
	            }

	            if ($controller instanceof \Applications\Controller\ManageController
	                && 'detail' == $event->getRouteMatch()->getParam('action')
	                && 200 == $event->getResponse()->getStatusCode()
	            ) {
		            $result = $event->getResult();
		            if (!is_array($result)) {
			            $result = $result->getVariables();
		            }
		            if ($result['application']->isDraft()) {
			            $viewModel->setTemplate($template);
		            }
	            }

            };

            $sharedManager->attach(
                'Applications',
                MvcEvent::EVENT_DISPATCH,
                $listener,
                -2 /*postDispatch, but before most of the other zf2 listener*/
            );
            $sharedManager->attach(
            	'CamMediaintown',
	            MvcEvent::EVENT_DISPATCH,
                $listener,
	            -2);

            $eventManager->attach(MvcEvent::EVENT_ROUTE, function(MvcEvent $event) {
                $routeMatch = $event->getRouteMatch();

                if (!$routeMatch) { return; }

                $matchedRouteName = $routeMatch->getMatchedRouteName();

                $container = new Container('gastro24_jobboardcontainer');

                if ('lang/landingPage' == $matchedRouteName) {
                    $services = $event->getApplication()->getServiceManager();
                    $options = $services->get(Landingpages::class);
                    $term = $routeMatch->getParam('q');

                    if (!$term) {
                        return;
                    }

                    $query = $options->getQueryParameters($term);
                    $routeMatch->setParam('wpId', $options->getIdMap($term));
                    $routeMatch->setParam('isLandingPage', true);
                    $routeMatch->setParam('term', $term);

                    if ($query) {
                        $origQuery = $event->getRequest()->getQuery()->toArray();
                        if (count($origQuery)) {
                            $routeMatch->setParam('isFilteredLandingPage', true);
                            $query = array_merge($origQuery, $query);
                        }
                        $query['clear'] = 1;
                        $event->getRequest()->setQuery(new Parameters($query));
                    } else {
                        return;
                    }

                    $container->landingPageTerm = $term;
                    $container->landingPageSearchQuery = $query['q'];
                }

                if ('lang/jobboard' == $matchedRouteName || 'lang/landingPage' == $matchedRouteName) {
                    $services = $event->getApplication()->getServiceManager();
                    $options = $services->get(Landingpages::class);
                    $query = $event->getRequest()->getQuery();

                    foreach ([
                        'r' => '__region_MultiString',
                        'c' => '__organizationTag',
                        'p' => '__profession_MultiString',
                        'i' => '__industry_MultiString',
                        't' => '__employmentType_MultiString',
                        ] as $shortName => $longName) {

                        if ($v = $query->get($shortName)) {
                            $query->set($longName, $v);
                            $query->offsetUnset($shortName);
                        }
                    }

                    $dateFilterValue = $query->get('publishDateFilter');
                    if ($dateFilterValue && key($dateFilterValue) != 'all') {
                        $publishStartFilter = strtotime('-' . key($dateFilterValue) . ' minutes');
                        $query->set('publishedSince', date('Y-m-d H:i:s', $publishStartFilter));
                    }

                    // remove location from session (from search form)
                    if (!$query->get('l')) {
                        $jobsBoardContainer = new Container('Jobs_Board');
                        unset($jobsBoardContainer->params['l']);
                    }

                    if (!$routeMatch->getParam('isLandingPage')) {
                        $query = $query->toArray();
                        unset($query['clear']);
                        if (isset($query['q'])) {
                            $query['q'] = strtolower($query['q']);
                        }
                        $map = $options->getQueryMap();

                        foreach ($map as $term => $spec) {
                            if (isset($spec['q'])) { $spec['q'] = strtolower($spec['q']); }
                            if ($spec === $query) {
                                /* \Laminas\Http\PhpEnvironment\Response $response */
                                $url = $event->getRouter()->assemble(['q' => $term, 'format' => 'html'], ['name' => 'lang/landingPage']);
                                $response = $event->getResponse();
                                $response->getHeaders()->addHeaderLine('Location', $url);
                                $response->setStatusCode(302);
                                $event->setResult($response);
                                return $response;
                            }
                        }

                        // clear values in session container
                        $container->exchangeArray([]);
                    }

                }

                if ('lang/jobs/view' == $matchedRouteName) {
                    $query = $event->getRequest()->getQuery();
                    $query->set('id', $routeMatch->getParam('id') ?: $event->getRequest()->getQuery('id'));
                }

                /* remove favorite list in navigation */
                if ('lang' != $matchedRouteName) {
                    $services = $event->getApplication()->getServiceManager();
                    $config=$services->get('config');
                    unset($config['navigation']['default']['saved-jobs']);
                    $services->setAllowOverride(true);
                    $services->setService('config', $config);
                    $services->setAllowOverride(false);
                }

            }, -9999);

            $eventManager->attach(MvcEvent::EVENT_DISPATCH, function(MvcEvent $e) {

                $services = $e->getApplication()->getServiceManager();
                $paginatorFactory = $services->get('ControllerPluginManager')->get('paginator');
                $searchFormFactory = $services->get('ControllerPluginManager')->get('searchform');
                $searchForm = call_user_func($searchFormFactory, 'Jobs/JobboardSearch');

                $viewModel = $e->getViewModel();
                if (!$viewModel->getVariable('jobs')) {
                    /** @var \Laminas\Paginator\Paginator $paginator */
                    $paginator = call_user_func($paginatorFactory, 'Jobs/Board', [], [
                        'q',
                        'count' => 10,
                        'page' => 1,
                        'l',
                        'd' => 10
                    ]);
                    $viewModel->setVariable('jobs', $paginator);
                }

                $viewModel->setVariable('filterForm', $searchForm);

            }, -10);

            $eventManager->attach(MvcEvent::EVENT_RENDER, function(MvcEvent $e) {
                $services     = $e->getApplication()->getServiceManager();
                $navigation   = $services->get('Core/Navigation');

                $page = [
                    'label'      => 'Rechnungsanschrift',
                    'order'      => 100,
                    'resource'   => 'route/lang/settings',
                    'route'      => 'lang/settings',
                    'router'     => $e->getRouter(),
                    'action'     => 'index',
                    'controller' => 'index',
                    'params'     => ['module' => 'Orders'],
                ];
                $navigation->addPage($page);

                $jobPage = [
                    'label'      => 'Inserieren',
                    'order'      => -11,
                    'route'      => 'lang/jobs/manage',
                    'router'     => $e->getRouter(),
                    'action'     => 'edit',
                ];
                $navigation->addPage($jobPage);

            }, 5);
        }

        // attach a listener to check for error codes
        $eventManager->attach(MvcEvent::EVENT_RENDER, array($this, 'onRenderError'));

    }

    public function getConsoleUsage(\Laminas\Console\Adapter\AdapterInterface $console)
    {
        return [
            'Expire jobs',
            'jobs expire [--days] [--limit] [--info]'  => 'Expire jobs',
            'jobs clear'  => 'Remove expired jobs from database',
            ['--days=INT', 'expire jobs after <days> days. Default 30'],
            ['--limit=INT[,<offset>]', 'Limit jobs to expire per run starting from <offset>. Default 10. 0 means no limit'],
            ['--info', 'Does not manipulate the database, but prints a list of all matched jobs.'],
            'Google Index Api',
            'jobs google-index' => 'Index crawler jobs'
        ];
    }

    public function onDispatchError($e)
    {
        // has facebook share param - redirect to startpage
        if ($e->getRequest()->getQuery('fbclid')) {
            $basePath= $e->getRouter()->getBaseUrl();
            $origUri = str_replace($basePath, '', $e->getRequest()->getRequestUri());
            $origUri = str_replace('?fbclid', 'fbclid', $origUri);
            //$lang = $options->isDetectLanguage() ? $this->detectLanguage($e):$options->getDefaultLanguage();
            $langUri = rtrim("$basePath/de$origUri", '/');

            /* \Laminas\Http\PhpEnvironment\Response $response */
            //$url = $e->getRouter()->assemble([], ['name' => 'lang']);
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $langUri);
            $response->setStatusCode(302);
            $e->setResult($response);
            return $response;
        }

        if ($e->getRequest()->getQuery('gclid')) {
            $basePath= $e->getRouter()->getBaseUrl();
            $origUri = str_replace($basePath, '', $e->getRequest()->getRequestUri());
            $origUri = str_replace('?gclid', 'gclid', $origUri);
            //$lang = $options->isDetectLanguage() ? $this->detectLanguage($e):$options->getDefaultLanguage();
            $langUri = rtrim("$basePath/de$origUri", '/');

            /* \Laminas\Http\PhpEnvironment\Response $response */
            //$url = $e->getRouter()->assemble([], ['name' => 'lang']);
            $response = $e->getResponse();
            $response->getHeaders()->addHeaderLine('Location', $langUri);
            $response->setStatusCode(302);
            $e->setResult($response);
            return $response;
        }
    }

    public function onRenderError(MvcEvent $e)
    {
        $services     = $e->getApplication()->getServiceManager();
        /** @var IsCrawlerJob $isCrawlerHelper */
        $viewHelpers = $services->get('ViewHelperManager');
        $mailer = $services->get('Core/MailService');
        $isCrawlerJobHelper = $viewHelpers->get(IsCrawlerJob::class);
        $response = $e->getResponse();
        $viewModel = $e->getViewModel();

        if (get_class($response) === \Laminas\Console\Response::class ) {
            return;
        }

        /*if ($response->getStatusCode() == Response::STATUS_CODE_500) {
            // send mail to admin
            $mail = new \Core\Mail\Message();
            $mail->setSubject('Gastro24 - 500 Exception');
            $mail->setFrom('noreply@gastrojob24.ch');
            $mail->setTo('contact@stefaniedrost.com');
            $exception = $e->getParams()['exception'];
            $mail->setBody($exception->getMessage() . "\n\n " . $exception->getFile() . "\n\n " . $exception->getTraceAsString());
            $mailer->send($mail);
        }*/

        $matchedRouteName = $e->getRouteMatch()->getMatchedRouteName();
        $job = null;

        if (isset($viewModel->getChildren()[0]) && isset($viewModel->getChildren()[0]->getVariables()['job'])) {
            $job = $viewModel->getChildren()[0]->getVariables()['job'];
        }

        $isCrawlerJob = false;
        if ($job && $job->getOrganization()) {
            $isCrawlerJob = $isCrawlerJobHelper->__invoke($job->getOrganization());
        }

        /**
         * @see TemplateController
         * overwrite 404 error on expired
         */
        if ($matchedRouteName == 'lang/job-view-extern' &&
            $job && $job->getStatus()->getName() == Status::EXPIRED &&
            $response->getStatusCode() == Response::STATUS_CODE_410 && !$isCrawlerJob) {

            $response->setStatusCode(Response::STATUS_CODE_200);
            $e->setResponse($response);
        }

        if ($matchedRouteName == 'lang/job-view-extern' &&
            $job && $job->getStatus()->getName() == Status::EXPIRED &&
            $response->getStatusCode() == Response::STATUS_CODE_200 && $isCrawlerJob) {

            $response->setStatusCode(Response::STATUS_CODE_410);
            $e->setResponse($response);
        }

        if ($response->getStatusCode() == Response::STATUS_CODE_404) {
            $response->setStatusCode(Response::STATUS_CODE_410);
            $e->setResponse($response);
        }
    }
}
