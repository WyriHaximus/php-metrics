<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Counter;

use InvalidArgumentException;

final class IncreaseToCountLowerThanCounterCount extends InvalidArgumentException
{
    private const string MESSAGE = 'Increase to count higher than counter count';

    //phpcs:disable
    /** @psalm-suppress MissingConstructor */
    public int $increaseToCount = 0;
    public int $count = 0;
    //phpcs:enable

    public static function create(int $increaseToCount, int $count): self
    {
        $self = new self(self::MESSAGE);

        $self->increaseToCount = $increaseToCount;
        $self->count           = $count;

        return $self;
    }
}
