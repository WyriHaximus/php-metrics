<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use WyriHaximus\Metrics\Registry\Counters;
use WyriHaximus\Metrics\Registry\Gauges;
use WyriHaximus\Metrics\Registry\Histograms;

interface Printer
{
    public function counter(Counters $counters): string;

    public function gauge(Gauges $gauges): string;

    public function histogram(Histograms $histograms): string;
}
