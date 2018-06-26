<?php
namespace App\Security;

use App\Utility\Auth0Api;
use HWI\Bundle\OAuthBundle\Security\Core\User\OAuthAwareUserProviderInterface;
use HWI\Bundle\OAuthBundle\OAuth\Response\UserResponseInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class UserProvider implements UserProviderInterface, OAuthAwareUserProviderInterface {

    protected $auth0Api;

    public function __construct(Auth0Api $auth0Api)
    {
        $this->auth0Api = $auth0Api;
    }

    public function loadUserByUsername($username)
    {
        $auth0User = $this->auth0Api->getUserByUsername($username);

        $username = (isset($auth0User[0]->username)) ? $auth0User[0]->username : $auth0User[0]->nickname;

        $user = new User();
        $user->setEmail($auth0User[0]->email);
        $user->setName($auth0User[0]->name);
        $user->setGivenName($auth0User[0]->given_name);
        $user->setPicture($auth0User[0]->picture);
        $user->setUserId($auth0User[0]->user_id);
        $user->setUsername($username);
        $user->setLastLogin($auth0User[0]->last_login);
        $user->setLastIp($auth0User[0]->last_ip);
        $user->setLoginCount($auth0User[0]->logins_count);

        return $user;
    }

    public function refreshUser(UserInterface $user)
    {
        return $this->loadUserByUsername($user->getUsername());
    }

    public function supportsClass($class)
    {
        return User::class == $class;
    }

    public function loadUserByOAuthUserResponse(UserResponseInterface $response)
    {
        return $this->loadUserByUsername($response->getNickname());
    }
} 