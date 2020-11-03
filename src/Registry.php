<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use ReactParallel\ObjectProxy\Attribute\Defer;
use WyriHaximus\Metrics\Histogram\Buckets;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\Metrics\Registry\Counters;
use WyriHaximus\Metrics\Registry\Gauges;
use WyriHaximus\Metrics\Registry\Histograms;
use WyriHaximus\Metrics\Registry\Summaries;
use WyriHaximus\Metrics\Summary\Quantiles;

interface Registry
{
    /**
     * @Defer()
     */
    public function counter(string $name, string $description, Name ...$requiredLabelNames): Counters;

    /**
     * @Defer()
     */
    public function gauge(string $name, string $description, Name ...$requiredLabelNames): Gauges;

    /**
     * @Defer()
     */
    public function histogram(string $name, string $description, Buckets $buckets, Name ...$requiredLabelNames): Histograms;

    /**
     * @Defer()
     */
    public function summary(string $name, string $description, Quantiles $quantiles, Name ...$requiredLabelNames): Summaries;

    public function print(Printer $printer): string;
}
