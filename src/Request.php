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
     * @var string
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
            if (property_exists(self::class, $key)) {
                $this->$key = $param;
            }
        }
    }
}