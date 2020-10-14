<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Registry;

use ReactParallel\ObjectProxy\Attribute\Defer;
use WyriHaximus\Metrics\Histogram;
use WyriHaximus\Metrics\Label;

interface Histograms
{
    public function name(): string;

    public function description(): string;

    /**
     * @Defer()
     */
    public function histogram(Label ...$labels): Histogram;

    /**
     * @return iterable<Histogram>
     */
    public function histograms(): iterable;
}
