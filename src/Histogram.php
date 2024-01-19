<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use ReactParallel\ObjectProxy\Attribute\Defer;
use WyriHaximus\Metrics\Histogram\Bucket;

interface Histogram
{
    public function name(): string;

    public function description(): string;

    /** @return iterable<Bucket> */
    public function buckets(): iterable;

    public function summary(): float;

    public function count(): int;

    /** @return array<Label> */
    public function labels(): array;

    /** @Defer() */
    public function observe(float $value): void;
}
