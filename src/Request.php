<?php

declare(strict_types=1);

namespace Penate\Server;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Request
 */
class Request
{
    /**
     * @var string|array
     */
    public $key;

    /**
     * @var string
     */
    public $command;

    /**
     * @var string|null
     */
    public $api_key;

    /**
     * @var mixed|null
     */
    public $value;

    /**
     * The lifetime of the value is indicated in minutes;
     * if the value is zero, then storage is unlimited.
     *
     * @var int|null
     */
    public $life = 0;

    /**
     * Request constructor.
     *
     * @param ServerRequestInterface $request
     */
    public function __construct(ServerRequestInterface $request)
    {
        foreach ($request->getQueryParams() as $key => $param) {
            $this->$key = property_exists(self::class, $key) ? $param : null;
        }
    }
}