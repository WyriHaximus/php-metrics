<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use ReactParallel\ObjectProxy\Attribute\Defer;
use WyriHaximus\Metrics\Summary\Quantile;

interface Summary
{
    public function name(): string;

    public function description(): string;

    /**
     * @return iterable<Quantile>
     */
    public function quantiles(): iterable;

    /**
     * @return array<Label>
     */
    public function labels(): array;

    /**
     * @Defer()
     */
    public function observe(float $value): void;
}
