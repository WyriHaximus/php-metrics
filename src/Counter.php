<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use ReactParallel\ObjectProxy\Attribute\Defer;

interface Counter
{
    public function name(): string;

    public function description(): string;

    public function count(): int;

    /** @return array<Label> */
    public function labels(): array;

    /** @Defer() */
    public function incr(): void;

    /** @Defer() */
    public function incrBy(int $incr): void;

    /** @Defer() */
    public function incrTo(int $count): void;
}
