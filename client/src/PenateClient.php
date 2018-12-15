<?php

declare(strict_types=1);

namespace Penate\Client;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class @PenateClient
 */
class PenateClient
{

    /**
     * @var Client
     */
    private $client;

    /**
     * @var null|string
     */
    private $api;

    /**
     * @var string
     */
    private $url;

    /**
     * PenateClient constructor.
     *
     * @param string $url
     * @param string|null $api
     * @param array $options
     */
    public function __construct(string $url, string $api = null, array $options = [])
    {
        $this->url    = $url;
        $this->api    = $api;
        $this->client = new Client($options);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function getItem(string $key)
    {
        return $this->sendQuery([
            'command' => 'get',
            'key'     => $key,
        ]);
    }

    /**
     * @param array $query
     *
     * @return mixed
     */
    private function sendQuery(array $query)
    {
        try {
            $response = $this->client->request('POST', $this->url, [
                'query' => $query,
            ]);

            $contents = json_decode($response->getBody()->getContents(), true);

        } catch (GuzzleException $e) {
            $response = $e->getResponse();
            $contents = json_decode($response->getBody()->getContents(), true);
        }


        if(!key_exists('result',$contents)){
            return $contents;
        }

        return $contents['result'];
    }

    /**
     * @param array $keys
     *
     * @return mixed
     */
    public function getItems(array $keys)
    {
        return $this->sendQuery([
            'command' => 'gets',
            'key'     => $keys,
        ]);
    }

    /**
     * @param string $key
     * @param $value
     * @param int $life
     *
     * @return mixed
     */
    public function setItem(string $key, $value, int $life = 0)
    {
        return $this->sendQuery([
            'command' => 'set',
            'key'     => $key,
            'value'   => $value,
            'life'    => $life,
        ]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function increment(string $key)
    {
        return $this->sendQuery([
            'command' => 'increment',
            'key'     => $key,
        ]);
    }

    /**
     * @param string $key
     *
     * @return mixed
     */
    public function decrement(string $key)
    {
        return $this->sendQuery([
            'command' => 'decrement',
            'key'     => $key,
        ]);
    }
}