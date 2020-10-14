<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Registry;

use ReactParallel\ObjectProxy\Attribute\Defer;
use WyriHaximus\Metrics\Gauge;
use WyriHaximus\Metrics\Label;

interface Gauges
{
    public function name(): string;

    public function description(): string;

    /**
     * @Defer()
     */
    public function gauge(Label ...$labels): Gauge;

    /**
     * @return iterable<Gauge>
     */
    public function gauges(): iterable;
}
