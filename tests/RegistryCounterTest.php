<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics;

use PHPUnit\Framework\TestCase;
use WyriHaximus\Metrics\Counter;
use WyriHaximus\Metrics\Factory;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;

use function array_map;

final class RegistryCounterTest extends TestCase
{
    /**
     * @test
     */
    public function counter(): void
    {
        $metricName        = 'name';
        $metricDescription = 'Description';

        $registry = Factory::create();
        $counter  = $registry->counter($metricName, $metricDescription, new Name('label'), new Name('node'));
        $counter->counter(new Label('node', 'mushroom'), new Label('label', 'label'))->incrTo(133);
        $counter->counter(new Label('node', 'mushroom'), new Label('label', 'label'))->incrTo(133);
        $counter->counter(new Label('node', 'mushroom'), new Label('label', 'labol'))->incr();
        $counters = [...$counter->counters()];
        self::assertCount(2, $counters);
        self::assertSame([133, 1], array_map(static fn (Counter $counter) => $counter->count(), $counters));
        self::assertSame([['label', 'mushroom'], ['labol', 'mushroom']], array_map(static fn (Counter $counter) => array_map(static fn (Label $label) => $label->value(), $counter->labels()), $counters));

        $counter->counter(new Label('node', 'mushroom'), new Label('label', 'labol'))->incrBy(63);
        $counters = [...$counter->counters()];
        self::assertCount(2, $counters);
        self::assertSame([133, 64], array_map(static fn (Counter $counter) => $counter->count(), $counters));
        self::assertSame([['label', 'mushroom'], ['labol', 'mushroom']], array_map(static fn (Counter $counter) => array_map(static fn (Label $label) => $label->value(), $counter->labels()), $counters));

        $counter->counter(new Label('node', 'mushroom'), new Label('label', 'labal'));
        $counters = [...$counter->counters()];
        self::assertCount(3, $counters);
        self::assertSame([133, 64, 0], array_map(static fn (Counter $counter) => $counter->count(), $counters));
        self::assertSame([['label', 'mushroom'], ['labol', 'mushroom'], ['labal', 'mushroom']], array_map(static fn (Counter $counter) => array_map(static fn (Label $label) => $label->value(), $counter->labels()), $counters));

        self::assertSame($metricName, $counter->name());
        self::assertSame($metricDescription, $counter->description());
        foreach ($counters as $uniqueCounter) {
            self::assertSame($metricName, $uniqueCounter->name());
            self::assertSame($metricDescription, $uniqueCounter->description());
        }
    }

    /**
     * @test
     */
    public function faultyLabels(): void
    {
        self::expectException(Label\GivenLabelsDontMatchExpectedLabels::class);

        $metricName        = 'name';
        $metricDescription = 'Description';

        $registry = Factory::create();
        $registry->counter($metricName, $metricDescription, new Name('label'))->counter(new Label('labiel', 'boem'));
    }
}
