<?php

namespace Gastro24\View\Helper;

use Jobs\Entity\Job;
use Jobs\Entity\Location;
use Orders\Entity\Order;

/**
 * Class OffeneStellenXmlExportHelper
 * @package Gastro24\View\Helper
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class OffeneStellenXmlExportHelper
{

    static public function getOffeneStellenXml($jobsPaginator, $ordersRepo)
    {
        $xmlStr='<?xml version="1.0" encoding="ISO-8859-1" ?><content></content>';
        $xml = simplexml_load_string($xmlStr);

        /* @var \Jobs\Entity\Job $jobObject */
        foreach ($jobsPaginator as $jobObject) {
            $org = $jobObject->getOrganization();

            // skip jobs from crawler orgs
            $skipOrgIds = [
                '602bd1ebdd454724e4444042', //offene-stellen.ch
            ];

            if($org && (in_array($org->getId(), $skipOrgIds) !== false)) {
                continue;
            }

//            if (empty($jobObject->getContactEmail()) && $jobObject->getAtsMode()->getMode() == 'uri') {
//                /** @var Order $order */
//                $order = $ordersRepo->findByJobId($jobObject->getId());
//                if (!$order) {
//                    continue;
//                }
//                $firmaEmail = $order->getInvoiceAddress()->getEmail();
//            };

            // skip jobs with empty firmaemail
            if (empty($jobObject->getContactEmail())) {
                if ($jobObject->getAtsMode()->getMode() == 'uri') {
                    /** @var Order $order */
                    $order = $ordersRepo->findByJobId($jobObject->getId());
                    if (!$order) {
                        continue;
                    }
                    $firmaEmail = $order->getInvoiceAddress()->getEmail();
                }
                else {
                    continue;
                }
            }
            else {
                $firmaEmail = $jobObject->getContactEmail();
            }

            // skip external jobs
            $link = $jobObject->getLink();
            $hasLink = false;
            if ($link && substr($link, -4) != '.pdf') {
                $hasLink = true;
            }

            if($hasLink && !$jobObject->getTemplateValues()->getHtml()) {
                continue;
            }

            /** @var Location $location */
            $location = $jobObject->getLocations()->first();

            $job = $xml->addChild('stelle');

            // fixed values
            $job->addChild('stellentypfid', 1);
            $job->addChild('aktivid', 1); // active = 1, inactive = 2

            // main job values
            $job->addChild('link', '<![CDATA[' . htmlspecialchars($this->jobUrl($jobObject,['linkOnly'=>true, 'absolute' => true])) . ']]>');
            $job->addChild('StelleTitel', '<![CDATA[' . htmlspecialchars($jobObject->getTitle()) . ']]>');
            $job->addChild('LandRegionID', self::getLandRegionId($jobObject));
            $job->addChild('levelid', self::getLevelId($jobObject));
            $job->addChild('KategorieRubrikID', 34); // Hotellerie / Gastronomie / Hotellerie / Gastronomie
            $job->addChild('brancheid', 32); // Gastronomie
            $job->addChild('stelleantrittsdatumfid', self::getBeginnFID($jobObject->getClassifications()->getEmploymentTypes()->getValues()));
            $publishDate = $job->addChild('stelleaktivvon', $jobObject->getDatePublishStart()->format('Y-m-d'));
            $publishDate->addAttribute('type', 'date');

            if ($location) {
                $job->addChild('StelleArbeitsort', $location->getCity());
            }
            else {
                $job->addChild('StelleArbeitsort', $jobObject->getLocation());
            }

            // contact data
            $job->addChild('kontakttext', 'Melden Sie sich bitte bei ');
            $job->addChild('kontaktemail', $jobObject->getContactEmail());
//    <kontaktname>Tom Fuchs</kontaktname>
//    <kontakttelno> 041 226 10 03</kontakttelno>
//    <kontaktemail>stellen@bjbag.ch</kontaktemail>

            // organization values
            $job->addChild('firmatxt', '<![CDATA[' . htmlspecialchars('Jobs ' . $jobObject->getCompany()) . ']]>');

            $job->addChild('firmaemail', $firmaEmail);
            if ($org) {
                $job->addChild('firmaadresse', $org->getContact()->getStreet() . ' ' . $org->getContact()->getHouseNumber());
                $job->addChild('firmaplZ', $org->getContact()->getPostalcode());
                $job->addChild('firmaOrt', $org->getContact()->getCity());
                $job->addChild('firmatelno', $org->getContact()->getPhone());
                $job->addChild('firmafax', $org->getContact()->getFax());
                $job->addChild('firmahomepage', $org->getContact()->getWebsite());
            }
            else {
                $job->addChild('firmahomepage', $jobObject->getTemplateValues()->get('companyWebsite'));
            }

            // additional company info (single jobs)
            if ($jobObject->getTemplateValues()->get('position')) {
                $job->addChild('stelleabsatz1titel', 'Stellenbeschreibung');
                $job->addChild('stelleabsatz1', '<![CDATA[' . htmlspecialchars($jobObject->getTemplateValues()->get('position')) . ']]>');
                $job->addChild('stelleabsatz4titel', 'Unternehmensinformation');
                $job->addChild('stelleabsatz4', '<![CDATA[' . htmlspecialchars($jobObject->getTemplateValues()->get('companyDescription')) . ']]>');
            }
            elseif ($jobObject->getTemplateValues()->getHtml()) {
                $job->addChild('stelleabsatz1', '<![CDATA[' . htmlspecialchars($jobObject->getTemplateValues()->getHtml()) . ']]>');
            }
        }

        return $xml;
    }

    /**
     * @see https://www.offene-stellen.ch/XMLInfo/brancheid.php
     *
     * @param Job $job
     * @return int
     */
    private static function getBrancheId($job)
    {
//        $industriesMap = [
//            '1_stern_hotel' => 79, // Hotellerie
//            '2_sterne_hotel' => 79, // Hotellerie
//            '3_sterne_hotel' => 79, // Hotellerie
//            '4_sterne_hotel' => 79, // Hotellerie
//            '5_sterne_hotel' => 79, // Hotellerie
//            'superior_hotel' => 79, // Hotellerie
//            'erlebnisgastronomie' => 32, // Gastronomie
//            'gourmet' => 32, // Gastronomie
//        ];

        return 32; // Gastronomie
    }

    /**
     * @see https://www.offene-stellen.ch/XMLInfo/kategorierubrik.php
     *
     * @param Job $job
     * @return int
     */
    private static function getRubrikId($job)
    {
        return 34; // Hotellerie / Gastronomie / Hotellerie / Gastronomie
    }

    /**
     * @see https://www.offene-stellen.ch/XMLInfo/levelid.php
     *
     * @param Job $job
     * @return int
     */
    private static function getLevelId($job)
    {
        $professions = $job->getClassifications()->getProfessions()->getValues();
        $levelMap = [
            'fachkraft' => 7, // Angelernt
            '0_3_jahre_berufserfahrung' => 3, // Berufsleute Junior
            '3_5_jahre_berufserfahrung' => 2, // Berufsleute mit mehrjähriger Erfahrung
            '_5_jahre_berufserfahrung' => 30, // Berufsleute Senior
            'fuehrungkraft_kaderstelle' => 29, // Kaderfunktion
            'schueler_studenten_praktikant' => 15, // Studenten
        ];

        foreach ($professions as $profession) {
            if (isset($levelMap[$profession])) {
                return $levelMap[$profession];
            }
        }

        return 17; // Unbestimmt
    }

    /**
     * @see https://www.offene-stellen.ch/XMLInfo/landregionid.php
     *
     * @param Job $job
     * @return int
     */
    private static function getLandRegionId($job)
    {
        /** @var Location $location */
        $location = $job->getLocations()->first();

        if ($location) {
            $locationCityString = $location->getCity();
        }
        else {
            $locationCityString = $job->getLocation();
        }

        // check for city first
        $cityMap = [
            'Bern' => 27, // Stadt Bern
            'St. Gallen' => 33, // Stadt St. Gallen
            'Winterthur' => 34, // Stadt Winterthur
            'Zürich' => 3, // Stadt Zürich
        ];

        foreach ($cityMap as $cityString => $cityId) {
            if (strpos($locationCityString, $cityString) !== false) {
                return $cityId;
            }
        }

         // check for kanton
        if ($location && $location->getRegion()) {
            $locationKantonString = $location->getRegion();
        }
        else {
            $locationKantonString = $job->getLocation();
        }

        $kantonMap = [
            'Aargau' => 12, // Kanton Aargau
            'Appenzell' => 15, // Kanton Aargau
            'Basel Land' => 10, // Kanton Aargau
            'Basel Stadt' => 11, // Kanton Aargau
            'Bern' => 8, // Kanton Bern
            'Freiburg' => 16, // Kanton Bern
            'Genf' => 17, // Kanton Bern
            'Glarus' => 31, // Kanton Bern
            'Graubünden' => 18, // Kanton Bern
            'Jura' => 19, // Kanton Bern
            'Luzern' => 6, // Kanton Bern
            'Neuenburg' => 20, // Kanton Bern
            'Nidwalden/Obwalden' => 24, // Kanton Bern
            'Schaffhausen' => 21, // Kanton Bern
            'Schwyz' => 13, // Kanton Bern
            'Solothurn' => 14, // Kanton Bern
            'St. Gallen' => 4, // Kanton Bern
            'Tessin' => 22, // Kanton Bern
            'Thurgau' => 23, // Kanton Bern
            'Uri' => 32, // Kanton Bern
            'Waadt' => 37, // Kanton Bern
            'Wallis' => 25, // Kanton Bern
            'Zug' => 5, // Kanton Bern
            'Zürich' => 2, // Kanton Bern
        ];

        foreach ($kantonMap as $kantonString => $kantonId) {
            if (strpos($locationKantonString, $kantonString) !== false) {
                return $cityId;
            }
            if (strpos($locationKantonString, 'Kanton ' . $kantonString) !== false) {
                return $cityId;
            }
        }

        // ganze schweiz = 36

        return 39; // Diverses
    }

    /**
     * @see https://www.offene-stellen.ch/XMLInfo/stellenantrittsdatum.php
     *
     * @param array $employmentTypes
     * @return int
     */
    private static function getBeginnFID($employmentTypes)
    {
        $map = [
            'vollzeit' => 3, // Per sofort oder nach Übereinkunft
            'per_sofort' => 4, // Per sofort
            'teilzeit' => 14, // Nach Vereinbarung
            'aushilfe' => 16, // Temporär
            'befristet' => 18, // Nach Übereinkunft
        ];

        foreach ($employmentTypes as $employmentType) {
            if (isset($map[$employmentType])) {
                return $map[$employmentType];
            }
        }

        return $map['aushilfe'];
    }

}
