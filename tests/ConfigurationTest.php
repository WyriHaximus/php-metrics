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
}
