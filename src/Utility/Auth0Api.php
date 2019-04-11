<?php
namespace App\Utility;

use GuzzleHTTP\Client as GuzzleClient;

class Auth0Api {
    private $authorizationHeader;
    private $baseUri;
    private $guzzleClient;

    public function __construct($authorizationHeader, $baseUri, GuzzleClient $guzzleClient)
    {
        $this->authorizationHeader = $authorizationHeader;
        $this->baseUri = $baseUri;
        $this->guzzleClient = $guzzleClient;
    }

    public function getUserByUserId($userId)
    {
        $response = $this->guzzleClient->request('GET', $this->baseUri . 'users/' . $userId, array(
            'headers' => array (
                'Authorization' => $this->authorizationHeader
            ),
        ));

        return json_decode($response->getBody()->getContents());
    }

    public function getUserByUsername($username)
    {
        $response = $this->guzzleClient->request('GET', $this->baseUri . 'users', array(
            'headers' => array (
                'Authorization' => $this->authorizationHeader
            ),
            'query' => array(
                'q' => 'nickname=' . $username. '* OR nickname=' . $username . '*'
            )
        ));

        return json_decode($response->getBody()->getContents());
    }

    public function getUsers($query)
    {
        $response = $this->guzzleClient->request('GET', $this->baseUri . 'users', array(
            'headers' => array (
                'Authorization' => $this->authorizationHeader
            ),
            'query' => array(
                'q' => 'name=' . $query . '* OR email=' . $query . '*'
            )

            //[ 'query' => ['q' => 'email:"elynuccia@gmail.com"']]
        ));

        return json_decode($response->getBody()->getContents());
    }
} 