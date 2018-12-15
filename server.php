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

$loop    = Factory::create();
$storage = new Storage();

$loop->addPeriodicTimer(0.8, function () use ($storage) {
    $storage->removeExpired();
});

$server = new HttpServer(function (ServerRequestInterface $serverRequest) use ($storage) {

    $controller = new Controller($storage);
    $request    = new Request($serverRequest);

    return $controller->handler($request);
});

$socket = new SocketServer(getenv('PORT'), $loop);
$server->listen($socket);

echo "Penate server running\n";

$loop->run();