<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use ReactParallel\ObjectProxy\Attribute\Defer;

interface Gauge
{
    public function name(): string;

    public function description(): string;

    public function gauge(): int;

    /** @return array<Label> */
    public function labels(): array;

    /** @Defer() */
    public function incr(): void;

    /** @Defer() */
    public function incrBy(int $incr): void;

    /** @Defer() */
    public function set(int $count): void;

    /** @Defer() */
    public function dcrBy(int $dcr): void;

    /** @Defer() */
    public function dcr(): void;
}
