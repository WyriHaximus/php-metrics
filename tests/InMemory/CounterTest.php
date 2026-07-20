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
        self::expectExceptionMessageIsOrContains(IncreaseToCountLowerThanCounterCount::MESSAGE);
        self::expectExceptionObject(IncreaseToCountLowerThanCounterCount::create(1, 2));

        $counter = new Counter(new Label('label', 'label'));
        $counter->incrTo(128);

        try {
            $counter->incrTo(64);
            self::fail('Should never reach behind the previous statement');
        } catch (IncreaseToCountLowerThanCounterCount $exception) {
            self::assertSame(128, $exception->count);
            self::assertSame(64, $exception->increaseToCount);

            throw $exception;
        }
    }
}
