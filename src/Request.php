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
     * @var null
     */
    public $key;

    /**
     * @var null
     */
    public $command;

    /**
     * @var null
     */
    public $api_key;

    /**
     * @var null
     */
    public $value;

    /**
     * @var
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
            if (property_exists(self::class, $key)) {
                $this->$key = $param;
            }
        }
    }
}