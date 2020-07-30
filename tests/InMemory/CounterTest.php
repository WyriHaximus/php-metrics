<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics\InMemory;

use PHPUnit\Framework\TestCase;
use WyriHaximus\Metrics\Counter\IncreaseToCountLowerThanCounterCount;
use WyriHaximus\Metrics\InMemory\Counter;
use WyriHaximus\Metrics\Label;

final class CounterTest extends TestCase
{
    /**
     * @test
     */
    public function counter(): void
    {
        self::expectException(IncreaseToCountLowerThanCounterCount::class);

        $counter = new Counter('name', 'description', new Label('label', 'label'));
        $counter->incrTo(128);
        $counter->incrTo(64);
    }
}
