<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Registry;

use ReactParallel\ObjectProxy\Attribute\Defer;
use WyriHaximus\Metrics\Counter;
use WyriHaximus\Metrics\Label;

interface Counters
{
    public function name(): string;

    public function description(): string;

    /**
     * @Defer()
     */
    public function counter(Label ...$labels): Counter;

    /**
     * @return iterable<Counter>
     */
    public function counters(): iterable;
}
