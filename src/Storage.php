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
     * @param null   $value
     * @param int    $life
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
        $item = $this->storage[$key] ?? null;

        return ($item !== null) ? $item->getValue() : null;
    }

    /**
     * @param string|null $key
     *
     * @return Item|null
     */
    public function increment(string $key = null): ?Item
    {
        if (!isset($this->storage[$key])) {
            return null;
        }

        return $this->storage[$key]->increment();
    }

    /**
     * @param string|null $key
     *
     * @return Item|null
     */
    public function decrement(string $key = null): ?Item
    {
        if (!isset($this->storage[$key])) {
            return null;
        }

        return $this->storage[$key]->decrement();
    }

    /**
     *
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
     * @param int $limitMemoryMbyte
     *
     * @return bool
     */
    public function memoryLimitExceeded(int $limitMemoryMbyte): bool
    {
        $currentMemoryUsage = memory_get_usage() / 1028 / 1028;

        return $limitMemoryMbyte < $currentMemoryUsage;
    }

    /**
     *
     */
    public function clearStorage(): void
    {
        $this->storage = [];
    }

}