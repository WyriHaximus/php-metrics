<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use Lcobucci\Clock\Clock;
use Lcobucci\Clock\SystemClock;

final class Configuration
{
    private Clock $clock;

    public static function create(): Configuration
    {
        return new self();
    }

    private function __construct()
    {
        $this->clock = SystemClock::fromUTC();
    }

    public function withClock(Clock $clock): Configuration
    {
        $clone        = clone $this;
        $clone->clock = $clock;

        return $clone;
    }

    public function clock(): Clock
    {
        return $this->clock;
    }
}
