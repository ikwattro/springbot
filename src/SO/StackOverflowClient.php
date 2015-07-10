<?php

namespace Ikwattro\SpringBot\SO;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;

class StackOverflowClient
{
    const STACKEXCHANGE_URL = 'https://api.stackexchange.com/2.2/questions';

    private $client;

    private $tags;

    public function __construct(array $tags)
    {
        $this->tags = $tags;
        $this->client = new Client();
    }

    public function getQuestions()
    {
        try {
            $response = $this->client->get(self::STACKEXCHANGE_URL, $this->getConfig());
            print_r($response);

            return json_decode((string) $response->getBody(), true);
        } catch (RequestException $e) {
            echo $e->getMessage();
        }

    }

    private function getConfig()
    {
        $defaultTags = [
            'spring-data-neo4j-4'
        ];

        $tags = array_unique(array_merge($this->tags, $defaultTags), SORT_REGULAR);

        $defaultConfig = [
            'query' => [
                'site' => 'stackoverflow',
                'sort' => 'creation',
                'order' => 'desc',
                'tagged' => implode(';', $tags)
            ]
        ];

        print_r($defaultConfig);
        exit();

        return $defaultConfig;
    }
}