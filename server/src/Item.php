<?php

declare(strict_types=1);

namespace Penate\Server;

/**
 * Class Item
 */
class Item
{

    /**
     * Хранимое значение
     * @var
     */
    public $value;

    /**
     * Время жизни
     *
     * @var int
     */
    private $life;

    /**
     * Время записи значения
     *
     * @var int
     */
    private $createdAt;

    /**
     * Item constructor.
     *
     * @param $value
     * @param int $life
     */
    public function __construct($value, int $life = 0)
    {
        $this->value     = $value;
        $this->life      = $life;
        $this->createdAt = time();
    }

    /**
     * @param int|null $time
     *
     * @return bool
     */
    public function checkExpired(int $time = null): bool
    {
        if ($this->life === 0) {
            return false;
        }

        $expiredTime = $this->getExpiredTime();
        $time        = $time ?? time();

        return $time > $expiredTime;
    }

    /**
     * @return int
     */
    private function getExpiredTime(): int
    {
        return $this->createdAt + $this->life;
    }

    /**
     * @return mixed|null
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @return $this
     */
    public function increment()
    {
        $this->value++;

        return $this;
    }

    /**
     * @return $this
     */
    public function decrement()
    {
        $this->value--;

        return $this;
    }
}