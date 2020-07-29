<?php

namespace Gastro24\Entity\Decorator;

use Doctrine\Common\Collections\Collection;
use Jobs\Entity\JobInterface;
use Jobs\Entity\JsonLdProviderInterface;
use Jobs\Entity\TemplateValuesInterface;
use Laminas\Json\Json;
use Orders\Entity\InvoiceAddressInterface;

/**
 * Decorates a job with implementing a toJsonLd method.
 *
 * This decorator *does not* delegate other methods.
 *
 */
class JsonLdProvider implements JsonLdProviderInterface
{

    /**
     * the decorated job entity.
     *
     * @var \Jobs\Entity\JobInterface|\Jobs\Entity\Job
     */
    private $job;

    /**
     * @var \Orders\Entity\InvoiceAddress
     */
    private $invoiceAddress;

    /**
     * @param JobInterface $job
     */
    public function __construct(JobInterface $job, $invoiceAddress)
    {
        $this->job = $job;
        $this->invoiceAddress = $invoiceAddress;
    }

    public function toJsonLd()
    {
        /** @var \Organizations\Entity\Organization $organization */
        $organization = $this->job->getOrganization();
        $organizationName = $organization ? $organization->getOrganizationName()->getName() : $this->job->getCompany();

        $dateStart = $this->job->getDatePublishStart();
        $dateStart = $dateStart ? $dateStart->format('Y-m-d H:i:s') : null;
        $dateEnd = $this->job->getDatePublishEnd();
        $dateEnd = $dateEnd ? $dateEnd->format('Y-m-d H:i:s') : null;
        if (!$dateEnd) {
            $dateEnd = new \DateTime($dateStart);
            $dateEnd->add(new \DateInterval("P30D"));
            $dateEnd = $dateEnd->format('Y-m-d H:i:s');
        }
        $array=[
            '@context'=>'http://schema.org/',
            '@type' => 'JobPosting',
            'title' => $this->job->getTitle(),
            'description' => $this->getDescription($this->job->getTemplateValues()),
            'datePosted' => $dateStart,
            'identifier' => [
                '@type' => 'PropertyValue',
                'value' => $this->job->getApplyId(),
                'name' => $organizationName,
            ],
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name' => $organizationName,
                'logo' => $this->getLogo(),
                'address' => $this->getOrganizationAddress()
            ],
            'jobLocation' => $this->getLocations($this->job->getLocations()),
            'employmentType' => $this->job->getClassifications()->getEmploymentTypes()->getValues(),
            'validThrough' => $dateEnd
        ];

        if ($companyWebsite = $this->job->getTemplateValues()->get('companyWebsite')) {
            $array['hiringOrganization']['sameAs'] = $companyWebsite;
        }

        $array += $this->generateSalary();

        return Json::encode($array);
    }

    /**
     * @return array
     */
    private function getOrganizationAddress()
    {
        /** @var \Organizations\Entity\Organization $organization */
        $organization = $this->job->getOrganization();
        // single job
        if (!$organization) {
            return [
                '@type' => 'PostalAddress',
                'streetAddress' => $this->invoiceAddress->getStreet() . ' ' . $this->invoiceAddress->getHouseNumber(),
                'postalCode' => $this->invoiceAddress->getZipCode(),
                'addressLocality' => $this->invoiceAddress->getCity(),
                'addressCountry' => 'CH',
            ];
        }

        return [
            '@type' => 'PostalAddress',
            'streetAddress' => $organization->getContact()->getStreet() . ' ' . $organization->getContact()->getHouseNumber(),
            'postalCode' => $organization->getContact()->getPostalcode(),
            'addressLocality' => $organization->getContact()->getCity(),
            'addressCountry' => 'CH',
        ];
    }

    /**
     * try to get the logo of an organization.
     * Fallback: logoRef of job posting
     */
    private function getLogo()
    {
        $organization = $this->job->getOrganization();
        $organizationLogo = ($organization && $organization->getImage()) ? $organization->getImage()->getUri() : $this->job->getLogoRef();

        if (is_null($organizationLogo) && $this->job->getAttachedEntity('gastro24-template') && $this->job->getAttachedEntity('gastro24-template')->getLogo()) {
            // TODO: read server url dynamically
            $organizationLogo = 'https://www.gastrojob24.ch/' . $this->job->getAttachedEntity('gastro24-template')->getLogo()->getUri();
        }

        return $organizationLogo;
    }

    /**
     * Generates a location array
     *
     * @param Collection $locations,
     *
     * @return array
     */
    private function getLocations($locations)
    {
        $array=[];
        foreach ($locations as $location) { /* @var \Core\Entity\LocationInterface $location */
            $coords = $location->getCoordinates();
            $placeArray = [
                '@type' => 'Place',
                'address' => [
                    '@type' => 'PostalAddress',
                    'streetAddress' => $location->getStreetname() . ' ' . $location->getStreetnumber(),
                    'postalCode' => $location->getPostalCode(),
                    'addressLocality' => $location->getCity(),
                    'addressCountry' => 'CH',
                    'addressRegion' => $location->getCity(),
                ]
            ];
            if ($coords && isset($coords->getCoordinates()[0])) {
                $placeArray['geo']  = [
                    '@type' => 'GeoCoordinates',
                    'latitude' => $coords->getCoordinates()[0],
                    'longitude' => $coords->getCoordinates()[1]
                ];
            }
            array_push($array, $placeArray);
        }
        return $array;
    }

    /**
     * Generates a description from template values
     *
     * @param TemplateValuesInterface $values
     *
     * @return string
     */
    private function getDescription(TemplateValuesInterface $values)
    {
        $html = $values->getHtml();
        if ($html) {
            $description = sprintf("%s", $values->getHtml() );
        } else {
            $description = '';

            if ($jobDescription = $values->getDescription()){
                $description .= sprintf("<p>%s</p>", $jobDescription);
            }

            if ($introduction = $values->getIntroduction()){
                $description .= sprintf("<p>%s</p>", $introduction);
            }

            if ($position = $values->get('position')) {
                $description .= $position;
            }

            if ($requirements = $values->getRequirements()) {
                $description .= sprintf("<h3>Requirements</h3><p>%s</p>", $requirements);
            }

            if ($qualifications = $values->getQualifications()) {
                $description .= sprintf("<h3>Qualifications</h3><p>%s</p>", $qualifications);
            }

            if ($benefits = $values->getBenefits()) {
                $description .= sprintf("<h3>Benefits</h3><p>%s</p>", $benefits);
            }
        }

        return $description;
    }

    private function generateSalary()
    {
        // set fix values for now
        return [
            'baseSalary' => [
                '@type' => 'MonetaryAmount',
                'currency' => 'CHF',
                'value' => [
                    '@type' => 'QuantitativeValue',
                    'minValue' => 'None',
                    'maxValue' => 'None',
                    'unitText' => 'YEAR'
                ],
            ],
        ];

        /*$salary = $this->job->getSalary();

        if (!$salary || null === $salary->getValue()) {
            return [];
        }

        return [
            'baseSalary' => [
                '@type' => 'MonetaryAmount',
                'currency' => $salary->getCurrency(),
                'value' => [
                    '@type' => 'QuantitiveValue',
                    'value' => $salary->getValue(),
                    'unitText' => $salary->getUnit()
                ],
            ],
        ];*/
    }
}
