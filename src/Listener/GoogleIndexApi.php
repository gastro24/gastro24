<?php

namespace Gastro24\Listener;

use Jobs\Entity\Status;
use Jobs\Listener\Events\JobEvent;
use Jobs\View\Helper\JobUrl;

/**
 * GoogleIndexApi.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class GoogleIndexApi
{
    const GOOGLE_API_UPDATED_TYPE = 'URL_UPDATED';
    const GOOGLE_API_UPDATE_URL = 'https://indexing.googleapis.com/v3/urlNotifications:publish';
    const AUTH_FILE = "gastrojob24-apis-be4469f06c2b.json";

    private $jobUrlHelper;

    /**
     * @var \Zend\Log\Logger
     */
    private $logger;
    private $configPath;

    /**
     * @var \Gastro24\View\Helper\JobTemplate
     */
    private $jobTemplateHelper;

    /**
     * @var \Gastro24\View\Helper\IsEmbeddable
     */
    private $embeddableHelper;

    private $jobActivationRepository;

    public function __construct($jobUrlHelper, $logger, $configPath, $jobTemplateHelper, $embeddableHelper, $jobActivationRepository)
    {
        $this->jobUrlHelper = $jobUrlHelper;
        $this->logger = $logger;
        $this->configPath = $configPath;
        $this->jobTemplateHelper = $jobTemplateHelper;
        $this->embeddableHelper = $embeddableHelper;
        $this->jobActivationRepository = $jobActivationRepository;
    }

    public function __invoke(JobEvent $event)
    {
        $job = $event->getJobEntity();
        if ($this->jobHasRedirectOrLink($job)) {
            return;
        }

        $jobUrl = $this->jobUrlHelper->__invoke(
            $job,
            [
                'linkOnly'=> true,
                'absolute' => true,
            ]
        );

        // DEV Mode
        if (!file_exists($this->configPath . self::AUTH_FILE)) {
            $this->logger->info('DEV MODE: GOOGLE INDEX API: Job created. Send ' . self::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
            return;
        }
        else {
            $this->logger->info('GOOGLE INDEX API: Job created. Send ' . self::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
            // send request to google index api
            $response = $this->sendGoogleRequest($jobUrl);
        }
    }

    public function onUpdate(JobEvent $event)
    {
        $job = $event->getJobEntity();

        if ($event->getParam('statusWas') && (Status::ACTIVE != $event->getParam('statusWas')) ) {
            return;
        }

        if (is_null($event->getParam('statusWas')) && $job->getStatus() && $job->getStatus()->getName() != Status::ACTIVE) {
            return;
        }

        if ($this->jobHasRedirectOrLink($job)) {
            return;
        }

        $jobUrl = $this->jobUrlHelper->__invoke(
            $job,
            [
                'linkOnly'=> true,
                'absolute' => true,
            ]
        );

        // DEV Mode
        if (!file_exists($this->configPath . self::AUTH_FILE)) {
            $this->logger->info('DEV MODE: GOOGLE INDEX API: Job updated. Send ' . self::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
            return;
        }
        else {
            $this->logger->info('GOOGLE INDEX API: Job updated. Send ' . self::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
            // send request to google index api
            $response = $this->sendGoogleRequest($jobUrl);
        }
    }

    public function onAdminApproved(JobEvent $event)
    {
        $job = $event->getJobEntity();
        $user = $job->getUser();
        if ($user) {
            /** @var \Gastro24\Entity\JobActivation $jobActivation */
            $jobActivation = $this->jobActivationRepository->findOneByUserId($user->getId());
            if ($jobActivation->isAutomaticJobActivation()) {
                return;
            }
        }

        if ($this->jobHasRedirectOrLink($job)) {
            return;
        }

        $jobUrl = $this->jobUrlHelper->__invoke(
            $job,
            [
                'linkOnly'=> true,
                'absolute' => true,
            ]
        );

        // DEV Mode
        if (!file_exists($this->configPath . self::AUTH_FILE)) {
            $this->logger->info('DEV MODE: GOOGLE INDEX API: Job approved. Send ' . self::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
            return;
        }
        else {
            $this->logger->info('GOOGLE INDEX API: Job approved. Send ' . self::GOOGLE_API_UPDATED_TYPE . ' for ' . $jobUrl);
            // send request to google index api
            $response = $this->sendGoogleRequest($jobUrl);
        }
    }

    private function jobHasRedirectOrLink($job)
    {
        $hasJobTemplate = $this->jobTemplateHelper->__invoke($job->getOrganization());
        $isIntern = (!$job->getLink() || $hasJobTemplate);
        $isEmbeddable = $this->embeddableHelper->__invoke($job->getLink());
        $jobHasExternLink = (!$isIntern && !$isEmbeddable);

        if ($hasJobTemplate) {
            return false;
        }

        if (!$isIntern) {
            return true;
        }

        if ($jobHasExternLink) {
            return true;
        }

        return false;
    }

    private function sendGoogleRequest($jobUrl)
    {
        $client = new \Google_Client();
        $client->setApplicationName("gastrojob24-apis");
        $client->setAuthConfig($this->configPath . self::AUTH_FILE);
        $client->addScope(\Google_Service_Indexing::INDEXING);

        $service = new \Google_Service_Indexing($client);

        $notification = new \Google_Service_Indexing_UrlNotification();
        $notification->setType(self::GOOGLE_API_UPDATED_TYPE);
        $notification->setUrl($jobUrl);

        try {
            $response = $service->urlNotifications->publish($notification);
        }
        catch (\Exception $e) {
            $this->logger->err('Google Indexing API : An error occurred. Message: "{message}"', ['exception' => $e, 'message' => $e->getMessage()]);
            $response = false;
        }
        $this->logger->info('Google Indexing API : successful.', ['latestUpdate' => $response->getUrlNotificationMetadata()->getLatestUpdate()]);

        return $response;
    }
}