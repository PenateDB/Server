<?php

declare(strict_types=1);

namespace Penate\Server;

use React\Http\Response;

/**
 * Class Commander
 */
class Controller
{
    /**
     * @var array
     */
    protected const COMMANDS = [
        'get'       => 'commandGet',
        'gets'      => 'commandGets',
        'set'       => 'commandSet',
        'increment' => 'commandIncrement',
        'decrement' => 'commandDecrement',
    ];
    /**
     * @var array
     */
    protected const PARAMETERS = [
        'command' => 'required|string',
        'key'     => 'required|string|array',
        'value'   => 'required only set|any',
        'life'    => 'integer',
    ];
    /**
     * @var Storage
     */
    protected $storage;

    /**
     * Controller constructor.
     *
     * @param Storage $storage
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function handler(Request $request): Response
    {
        $auth = $this->checkAuth($request);

        if ($auth instanceof Response) {
            return $auth;
        }

        if (!array_key_exists($request->command, self::COMMANDS)) {
            return $this->badRequest();
        }

        $command = self::COMMANDS[$request->command];

        return $this->$command($request);
    }

    /**
     * @param Request $request
     *
     * @return Response|null
     */
    protected function checkAuth(Request $request): ?Response
    {
        if (!filter_var(getenv('AUTH'), FILTER_VALIDATE_BOOLEAN)) {
            return null;
        }

        $api_key = getenv('API_KEY');

        if ($request->api_key === $api_key) {
            return null;
        }

        return $this->makeResponse([
            'errors' => 'No access, check api_key',
        ], 403);
    }

    /**
     * @param array $content
     * @param int $code
     *
     * @return Response
     */
    protected function makeResponse(array $content, int $code = 200): Response
    {
        return new Response(
            $code,
            ['Content-Type' => 'application/json'],
            json_encode($content)
        );
    }

    /**
     * @return Response
     */
    protected function badRequest(): Response
    {
        return $this->makeResponse([
            'errors'           => 'Command not found',
            'allowed COMMANDS' => array_keys(self::COMMANDS),
        ], 400);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function commandDecrement(Request $request): Response
    {
        $this->storage->decrement($request->key);

        return $this->commandGet($request);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function commandIncrement(Request $request): Response
    {
        $this->storage->increment($request->key);

        return $this->commandGet($request);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function commandSet(Request $request): Response
    {
        $this->storage->setItem($request->key, $request->value, (int) $request->life);

        return $this->commandGet($request);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function commandGets(Request $request): Response
    {
        $value = $this->storage->getItems($request->key);

        return $this->makeResponse([
            'result' => $value,
        ]);
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function commandGet(Request $request): Response
    {
        $value = $this->storage->getItem($request->key);

        return $this->makeResponse([
            'result' => $value,
        ]);
    }
}