<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics;

use Lcobucci\Clock\FrozenClock;
use PHPUnit\Framework\Attributes\Test;
use WyriHaximus\Metrics\Configuration;
use WyriHaximus\TestUtilities\TestCase;

final class ConfigurationTest extends TestCase
{
    #[Test]
    public function clockClone(): void
    {
        $clock                  = FrozenClock::fromUTC();
        $configuration          = Configuration::create();
        $configurationWithClock = $configuration->withClock($clock);

        self::assertNotSame($configuration, $configurationWithClock);
        self::assertSame($clock, $configurationWithClock->clock());
    }

    #[Test]
    public function clockSummary(): void
    {
        $summary                = new Configuration\Summary();
        $configuration          = Configuration::create();
        $configurationWithClock = $configuration->withSummary($summary);

        self::assertNotSame($configuration, $configurationWithClock);
        self::assertSame($summary, $configurationWithClock->summary());
    }

    #[Test]
    public function clockSummaryBucketCount(): void
    {
        $summary                = new Configuration\Summary();
        $summaryWithBucketCount = $summary->withBucketCount(13);

        self::assertNotSame($summary, $summaryWithBucketCount);
        self::assertSame(13, $summaryWithBucketCount->bucketCount());
    }

    #[Test]
    public function clockSummaryBucketTimeTemplate(): void
    {
        $summary                 = new Configuration\Summary();
        $summaryWithTimeTemplate = $summary->withBucketTimeTemplate('abc');

        self::assertNotSame($summary, $summaryWithTimeTemplate);
        self::assertSame('abc', $summaryWithTimeTemplate->bucketTimeTemplate());
    }
}
