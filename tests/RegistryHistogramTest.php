<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics;

use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\Metrics\Factory;
use WyriHaximus\Metrics\Histogram;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\TestUtilities\TestCase;

use function array_map;
use function array_merge;
use function array_values;

final class RegistryHistogramTest extends TestCase
{
    #[Test]
    public function histogram(): void
    {
        $metricName        = 'name';
        $metricDescription = 'Description';

        $registry  = Factory::create();
        $histogram = $registry->histogram($metricName, $metricDescription, new Histogram\Buckets(0.1, 1, 2, 3), new Name('label'), new Name('node'));
        $histogram->histogram(new Label('node', 'reuzenzwam'), new Label('label', 'label'))->observe(1);
        $histograms = [...$histogram->histograms()];
        self::assertCount(1, $histograms);
        self::assertSame([0, 0, 1, 1, 1], array_values(array_map(static fn (Histogram\Bucket $bucket) => $bucket->count(), array_merge(...array_map(static fn (Histogram $histogram) => [...$histogram->buckets()], $histograms)))));
        self::assertSame([1], array_map(static fn (Histogram $histogram) => $histogram->count(), $histograms));
        self::assertSame([1.0], array_map(static fn (Histogram $histogram) => $histogram->summary(), $histograms));
        self::assertSame([['label', 'reuzenzwam']], array_map(static fn (Histogram $histogram) => array_map(static fn (Label $label) => $label->value(), $histogram->labels()), $histograms));

        $histogram->histogram(new Label('node', 'reuzenzwam'), new Label('label', 'label'))->observe(5);
        $histograms = [...$histogram->histograms()];
        self::assertCount(1, $histograms);
        self::assertSame([0, 0, 1, 1, 2], array_values(array_map(static fn (Histogram\Bucket $bucket) => $bucket->count(), array_merge(...array_map(static fn (Histogram $histogram) => [...$histogram->buckets()], $histograms)))));
        self::assertSame([2], array_map(static fn (Histogram $histogram) => $histogram->count(), $histograms));
        self::assertSame([6.0], array_map(static fn (Histogram $histogram) => $histogram->summary(), $histograms));
        self::assertSame([['label', 'reuzenzwam']], array_map(static fn (Histogram $histogram) => array_map(static fn (Label $label) => $label->value(), $histogram->labels()), $histograms));

        $histogram->histogram(new Label('node', 'reuzenzwam'), new Label('label', 'labol'))->observe(0.2);
        $histograms = [...$histogram->histograms()];
        self::assertCount(2, $histograms);
        self::assertSame([0, 0, 1, 1, 1, 0, 1, 1, 1], array_values(array_map(static fn (Histogram\Bucket $bucket) => $bucket->count(), array_merge(...array_map(static fn (Histogram $histogram) => [...$histogram->buckets()], $histograms)))));
        self::assertSame([2, 1], array_map(static fn (Histogram $histogram) => $histogram->count(), $histograms));
        self::assertSame([6.0, 0.2], array_map(static fn (Histogram $histogram) => $histogram->summary(), $histograms));
        self::assertSame([['label', 'reuzenzwam'], ['labol', 'reuzenzwam']], array_map(static fn (Histogram $histogram) => array_map(static fn (Label $label) => $label->value(), $histogram->labels()), $histograms));

        self::assertSame($metricName, $histogram->name());
        self::assertSame($metricDescription, $histogram->description());
    }

    #[Test]
    public function faultyLabels(): void
    {
        self::expectException(Label\GivenLabelsDontMatchExpectedLabels::class);
        self::expectExceptionMessage(Label\GivenLabelsDontMatchExpectedLabels::MESSAGE);

        $metricName        = 'name';
        $metricDescription = 'Description';

        $registry = Factory::create();
        $registry->histogram($metricName, $metricDescription, new Histogram\Buckets(0.1), new Name('label'))->histogram(new Label('labiel', 'boem'));
    }
}
