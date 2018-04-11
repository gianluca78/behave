<?php
namespace App\CouchDb;

use GuzzleHttp\Client as GuzzleClient;

class Client {

    private $databaseName;
    private $guzzle;
    private $url;

    public function __construct(GuzzleClient $guzzle, $url, $databaseName)
    {
        $this->databaseName = $databaseName;
        $this->guzzle = $guzzle;
        $this->url = $url;
    }

    public function connect()
    {
        try {
            $response = $this->guzzle->request("GET", "/", array(
                'base_uri' => $this->url
            ));
            if($response->getStatusCode() == 200) {
                echo $response->getBody();
            }
        } catch (\GuzzleHttp\ExceptionRequestException $e) {
            throw new \Exception('no connection with CouchDb server');
        }

        return $this;
    }

    public function postDataToDatabase(array $data)
    {
        $response = $this->guzzle->request(
            "POST",
            "http://localhost:5984/" . $this->databaseName,
            [
                'base_uri' => $this->url,
                "json" => $data
            ]
        );

        if($response->getStatusCode() == 201) {
            return $this;
        }

        throw new \Exception('Post data to database ' . $this->databaseName . ' failed. Response:' . var_dump($response->getStatusCode()));
    }
} 