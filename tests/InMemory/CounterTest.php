<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics\InMemory;

use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\Metrics\Counter\IncreaseToCountLowerThanCounterCount;
use WyriHaximus\Metrics\InMemory\Counter;
use WyriHaximus\Metrics\Label;
use WyriHaximus\TestUtilities\TestCase;

final class CounterTest extends TestCase
{
    #[Test]
    public function counter(): void
    {
        self::expectException(IncreaseToCountLowerThanCounterCount::class);
        self::expectExceptionMessage(IncreaseToCountLowerThanCounterCount::MESSAGE);

        $counter = new Counter(new Label('label', 'label'));
        $counter->incrTo(128);
        $counter->incrTo(64);
    }
}
