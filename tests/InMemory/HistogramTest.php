<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics\InMemory;

use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\Metrics\Histogram\Bucket;
use WyriHaximus\Metrics\Histogram\Buckets;
use WyriHaximus\Metrics\InMemory\Histogram;
use WyriHaximus\Metrics\Label;
use WyriHaximus\TestUtilities\TestCase;

use function array_map;
use function array_values;

final class HistogramTest extends TestCase
{
    #[Test]
    public function histogram(): void
    {
        $histogram = new Histogram(new Buckets(0.1, 1.0, 2.0, 3.0, 4.0, 5.0, 6.0), new Label('label', 'label'));
        $buckets   = [...$histogram->buckets()];

        self::assertSame(['0.1', '1', '2', '3', '4', '5', '6', '+Inf'], array_values(array_map(static fn (Bucket $bucket): string => $bucket->le(), $buckets)));
    }
}
