<?php

namespace Gastro24\Service\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use Zend\Authentication\Result;
use Auth\Entity\Filter\CredentialFilter;
use Auth\Adapter\User as BaseAdapter;

/**
 * User.php
 *
 * @author Stefanie Drost <contact@stefaniedrost.com>
 */
class User extends BaseAdapter
{
    /**
     * User entity repository
     *
     * @var \Core\Repository\RepositoryInterface
     */
    protected $repository;

    /**
     * Initial user.
     *
     * @var null|\Auth\Entity\UserInterface
     */
    protected $defaultUser;

    /**
     * Creates a new user authentication adapter
     *
     * @param \Core\Repository\RepositoryInterface $repository User entity repository
     * @param null|string $identity
     * @param null|string $credential
     */
    public function __construct($repository, $identity = null, $credential = null)
    {
        $this->repository = $repository;
        $this->setIdentity($identity);
        $this->setCredential($credential);
    }

    /**
     * Gets the user repository
     *
     * @return \Core\Repository\RepositoryInterface
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * Sets default user login and password.
     *
     * If no password is provided,
     *
     * @param string $login
     * @param string $password
     * @param string $role (default='recruiter')
     *
     * @return \Auth\Adapter\User
     */
    public function setDefaultUser($login, $password, $role = \Auth\Entity\User::ROLE_RECRUITER)
    {
        $this->defaultUser = array($login, $password, $role);
        return $this;
    }

    /**
     * Performs an authentication attempt
     *
     * {@inheritDoc}
     *
     */
    public function authenticate()
    {
        /* @var $users \Auth\Repository\User */
        $identity    = $this->getIdentity();
        $users       = $this->getRepository();
        $user = $users->findByLogin($identity, ['allowDeactivated' => true]);

        if (!$user->getInfo()->isEmailVerified()) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $identity, array('User not verified'));
        }

        $filter      = new CredentialFilter();
        $credential  = $this->getCredential();

        if (!$user || $user->getCredential() != $filter->filter($credential)) {
            return new Result(Result::FAILURE_CREDENTIAL_INVALID, $identity, array('User not known or invalid credential'));
        }

        return new Result(Result::SUCCESS, $user->getId());
    }
}