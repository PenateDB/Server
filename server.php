<?php

declare(strict_types=1);

require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Penate\Server\Controller;
use Penate\Server\Request;
use Penate\Server\Storage;
use Psr\Http\Message\ServerRequestInterface;
use React\EventLoop\Factory;
use React\Http\Server as HttpServer;
use React\Socket\Server as SocketServer;

$dotenv = new Dotenv(__DIR__);
$dotenv->load();

$loop = Factory::create();


$storage = new Storage();
$controller = new Controller($storage);


$loop->addPeriodicTimer(5, function () use ($storage) {
    $limit = (int) getenv('MEMORY_LIMIT');

    if ($storage->memoryLimitExceeded($limit)) {
        $storage->clearStorage();
    }
});

$loop->addPeriodicTimer(0.8, function () use ($storage) {
    $storage->updateTime();
    $storage->removeExpired();
});


$server = new HttpServer(function (ServerRequestInterface $serverRequest) use ($controller) {

    $request = new Request($serverRequest);

    return $controller->handler($request);
});

$socket = new SocketServer(getenv('PORT'), $loop);
$server->listen($socket);

echo "Penate server running\n";

$loop->run();