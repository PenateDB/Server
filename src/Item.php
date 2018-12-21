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
     *
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
     * @param int $createdAt
     * @param     $value
     * @param int $life
     */
    public function __construct(int $createdAt, $value, int $life = 0)
    {
        $this->createdAt = $createdAt;
        $this->value     = $value;
        $this->life      = $life;
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
    public function increment(): self
    {
        $this->value++;

        return $this;
    }

    /**
     * @return $this
     */
    public function decrement(): self
    {
        $this->value--;

        return $this;
    }
}