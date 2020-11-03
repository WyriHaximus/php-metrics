<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics;

use Lcobucci\Clock\FrozenClock;
use PHPUnit\Framework\TestCase;
use WyriHaximus\Metrics\Configuration;

final class ConfigurationTest extends TestCase
{
    /**
     * @test
     */
    public function clockClone(): void
    {
        $clock                  = FrozenClock::fromUTC();
        $configuration          = Configuration::create();
        $configurationWithClock = $configuration->withClock($clock);

        self::assertNotSame($configuration, $configurationWithClock);
        self::assertSame($clock, $configurationWithClock->clock());
    }

    /**
     * @test
     */
    public function clockSummary(): void
    {
        $summary                = new Configuration\Summary();
        $configuration          = Configuration::create();
        $configurationWithClock = $configuration->withSummary($summary);

        self::assertNotSame($configuration, $configurationWithClock);
        self::assertSame($summary, $configurationWithClock->summary());
    }

    /**
     * @test
     */
    public function clockSummaryBucketCount(): void
    {
        $summary                = new Configuration\Summary();
        $summaryWithBucketCount = $summary->withBucketCount(13);

        self::assertNotSame($summary, $summaryWithBucketCount);
        self::assertSame(13, $summaryWithBucketCount->bucketCount());
    }

    /**
     * @test
     */
    public function clockSummaryBucketTimeTemplate(): void
    {
        $summary                 = new Configuration\Summary();
        $summaryWithTimeTemplate = $summary->withBucketTimeTemplate('abc');

        self::assertNotSame($summary, $summaryWithTimeTemplate);
        self::assertSame('abc', $summaryWithTimeTemplate->bucketTimeTemplate());
    }
}
