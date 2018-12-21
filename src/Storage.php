<?php

declare(strict_types=1);

namespace Penate\Server;

/**
 * Class Storage
 */
class Storage
{
    /**
     * @var Item[]
     */
    private $storage = [];

    /**
     * @var int
     */
    private $currentTime;

    /**
     * Storage constructor.
     */
    public function __construct()
    {
        $this->updateTime();
    }

    /**
     *
     */
    public function updateTime(): void
    {
        $this->currentTime = time();
    }

    /**
     * @param string $key
     * @param null $value
     * @param int $life
     */
    public function setItem(string $key = null, $value = null, int $life = 0): void
    {
        $this->storage[$key] = new Item($this->currentTime, $value, $life * 60);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getItems(array $keys): array
    {
        return array_map(function ($key) {
            return $this->getItem($key);
        }, $keys);
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getItem(string $key = null)
    {
        return $this->index($key)->getValue();
    }

    /**
     * @param string $key
     *
     * @return Item|null
     */
    private function index(string $key)
    {
        return optional($this->storage[$key] ?? null);
    }

    /**
     * @param string|null $key
     *
     * @return Item|null
     */
    public function increment(string $key = null): ?Item
    {
        return $this->index($key)->increment();
    }

    /**
     * @param string|null $key
     *
     * @return Item|null
     */
    public function decrement(string $key = null): ?Item
    {
        return $this->index($key)->decrement();
    }

    /**
     * Enumerates values in the storage, deleting expired values.
     */
    public function removeExpired(): void
    {
        foreach ($this->storage as $key => $item) {
            if ($item->checkExpired($this->currentTime)) {
                unset($this->storage[$key]);
            }
        }
    }

    /**
     * Checks if memory usage limit is exceeded.
     *
     * @param int $limitMemoryMbyte
     *
     * @return bool
     */
    public function memoryLimitExceeded(int $limitMemoryMbyte): bool
    {
        return $limitMemoryMbyte < memory_get_usage() / 1028 / 1028;
    }

    /**
     * Clear all storage
     */
    public function clearStorage(): void
    {
        $this->storage = [];
    }

}