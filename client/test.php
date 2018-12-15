<?php

require 'vendor/autoload.php';

$client = new \Penate\Client\PenateClient('http://localhost:8000');

$response = $client->setItem('test',100);
var_dump($response);

$response = $client->getItem('test');
var_dump($response);

$response = $client->increment('test');
var_dump($response);

$response = $client->decrement('test');
var_dump($response);

$response = $client->setItem('test2',100,10);
var_dump($response);

sleep(20);
$response = $client->getItem('test2');
var_dump($response);

