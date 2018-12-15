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
     * @param string $key
     * @param null $value
     * @param int $life
     */
    public function setItem(string $key = null, $value = null, int $life = 0)
    {
        $this->storage[$key] = new Item($value, $life * 60);
    }

    /**
     * @param array $keys
     *
     * @return array
     */
    public function getItems(array $keys)
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->getItem($key);
        }

        return $result;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getItem(string $key = null)
    {
        if (!isset($this->storage[$key])) {
            return null;
        }

        return $this->storage[$key]->getValue();
    }

    /**
     * @param string|null $key
     *
     * @return Item|null
     */
    public function increment(string $key = null)
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
    public function decrement(string $key = null)
    {
        if (!isset($this->storage[$key])) {
            return null;
        }

        return $this->storage[$key]->decrement();
    }

    /**
     *
     */
    public function removeExpired()
    {
        $time = time();

        foreach ($this->storage as $key => $item) {
            if ($item->checkExpired($time)) {
                unset($this->storage[$key]);
            }
        }
    }
}