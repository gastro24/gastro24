<?php

namespace Gastro24\Controller;

use Core\Controller\FileController as BaseFileController;
use Organizations\Entity\OrganizationImage;

/**
 * FileController.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 * @method \Acl\Controller\Plugin\Acl acl()
 */
class FileController extends BaseFileController
{

    /**
     * @return \Laminas\Http\PhpEnvironment\Response
     */
    public function indexAction()
    {
        /* @var \Laminas\Http\PhpEnvironment\Response $response */
        $response = $this->getResponse();
        /* @var \Core\Entity\FileEntity $file */
        $file     = $this->getFile();

        if (!$file) {
            return $response;
        }

        $this->acl($file);

        $headers=$response->getHeaders();

        //HINT: headerline Content-Length in Core FileController triggers Chrome Error
        $headers->addHeaderline('Content-Type', $file->getType());

        if ($file instanceof OrganizationImage) {
            $expireDate = new \DateTime();
            $expireDate->add(new \DateInterval('P1Y'));
        }

        $response->sendHeaders();

        $resource = $file->getResource();

        while (!feof($resource)) {
            echo fread($resource, 1024);
        }

        return $response;
    }
}