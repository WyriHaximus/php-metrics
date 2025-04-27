<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics\InMemory;

use DateInterval;
use DateTimeZone;
use Lcobucci\Clock\FrozenClock;
use Lcobucci\Clock\SystemClock;
use PHPUnit\Framework\TestCase;
use DateTimeImmutable;
use WyriHaximus\Metrics\Configuration;
use WyriHaximus\Metrics\Factory;
use WyriHaximus\Metrics\InMemory\Summary;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Summary\Quantile;

use function array_map;
use function array_values;

final class SummaryTest extends TestCase
{
    /** @test */
    public function summary(): void
    {
        $histogram = new Summary(Configuration::create()->withClock(SystemClock::fromUTC()), 'name', 'description', Factory::defaultQuantiles(), new Label('label', 'label'));
        for ($i = 0; $i < 900; $i++) {
            $histogram->observe($i);
        }

        $quantile = [];
        foreach ($histogram->quantiles() as $key => $bucket) {
            $quantile[$key] = $bucket;
        }

        self::assertSame(['0.1', '0.5', '0.9', '0.99'], array_values(array_map(static fn (Quantile $quantile): string => $quantile->quantile(), $quantile)));
        self::assertSame([89.0, 449.0, 809.0, 890.0], array_values(array_map(static fn (Quantile $quantile): float => $quantile->value(), $quantile)));
    }

    /** @test */
    public function summaryOverDays(): void
    {
        $clock     = FrozenClock::fromUTC();
        $histogram = new Summary(Configuration::create()->withClock($clock), 'name', 'description', Factory::defaultQuantiles(), new Label('label', 'label'));
        for ($i = 1; $i < 13; $i++) {
            $histogram->observe($i * 666);
            $clock->setTo((new DateTimeImmutable('now', new DateTimeZone('UTC')))->add(new DateInterval('PT' . $i . 'H')));
        }

        $quantile = [];
        foreach ($histogram->quantiles() as $key => $bucket) {
            $quantile[$key] = $bucket;
        }

        self::assertSame(['0.1', '0.5', '0.9', '0.99'], array_values(array_map(static fn (Quantile $quantile): string => $quantile->quantile(), $quantile)));
        self::assertSame([1998.0, 4662.0, 7326.0, 7326.0], array_values(array_map(static fn (Quantile $quantile): float => $quantile->value(), $quantile)));
    }
}
