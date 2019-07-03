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
    private $logger;
    private $configPath;

    public function __construct($jobUrlHelper, $logger, $configPath)
    {
        $this->jobUrlHelper = $jobUrlHelper;
        $this->logger = $logger;
        $this->configPath = $configPath;
    }

    public function __invoke(JobEvent $event)
    {
        $job = $event->getJobEntity();
        $jobUrl = $this->jobUrlHelper->__invoke(
            $job,
            [
                'linkOnly'=> true,
                'absolute' => true,
            ]
        );

        //$jobUrl = 'https://www.gastrojob24.ch/de/job-chef-de-rang-mit-bar-erfahrung--80-100---m-w-5d1b67c43c050f2da147be99.html';

        $type = self::GOOGLE_API_UPDATED_TYPE;

        // DEV Mode
        if (!file_exists($this->configPath . self::AUTH_FILE)) {
            $this->logger->info('DEV MODE: GOOGLE INDEX API: send ' . $type . ' for ' . $jobUrl);
            return;
        }
        else {
            $this->logger->info('GOOGLE INDEX API: send ' . $type . ' for ' . $jobUrl);
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

        $jobUrl = $this->jobUrlHelper->__invoke(
            $job,
            [
                'linkOnly'=> true,
                'absolute' => true,
            ]
        );

        //$jobUrl = 'https://www.gastrojob24.ch/de/job-chef-de-rang-mit-bar-erfahrung--80-100---m-w-5d1b67c43c050f2da147be99.html';

        $type = self::GOOGLE_API_UPDATED_TYPE;

        // DEV Mode
        if (!file_exists($this->configPath . self::AUTH_FILE)) {
            $this->logger->info('DEV MODE: GOOGLE INDEX API: send ' . $type . ' for ' . $jobUrl);
            return;
        }
        else {
            $this->logger->info('GOOGLE INDEX API: send ' . $type . ' for ' . $jobUrl);
            // send request to google index api
            $response = $this->sendGoogleRequest($jobUrl);
        }
    }

    public function onAdminApproved(JobEvent $event)
    {
        $job = $event->getJobEntity();
        $jobUrl = $this->jobUrlHelper->__invoke(
            $job,
            [
                'linkOnly'=> true,
                'absolute' => true,
            ]
        );

        //$jobUrl = 'https://www.gastrojob24.ch/de/job-chef-de-rang-mit-bar-erfahrung--80-100---m-w-5d1b67c43c050f2da147be99.html';

        $type = self::GOOGLE_API_UPDATED_TYPE;

        // DEV Mode
        if (!file_exists($this->configPath . self::AUTH_FILE)) {
            $this->logger->info('DEV MODE: GOOGLE INDEX API: send ' . $type . ' for ' . $jobUrl);
            return;
        }
        else {
            $this->logger->info('GOOGLE INDEX API: send ' . $type . ' for ' . $jobUrl);
            // send request to google index api
            $response = $this->sendGoogleRequest($jobUrl);
        }
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

        return $service->urlNotifications->publish($notification);
    }
}