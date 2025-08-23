<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

final readonly class PrintJob
{
    /**
     * @param array<Registry\Counters>   $counters
     * @param array<Registry\Gauges>     $gauges
     * @param array<Registry\Histograms> $histograms
     * @param array<Registry\Summaries>  $summaries
     */
    public function __construct(
        public array $counters,
        public array $gauges,
        public array $histograms,
        public array $summaries,
    ) {
    }
}
