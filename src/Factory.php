<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

use WyriHaximus\Metrics\Histogram\Buckets;

final class Factory
{
    private const DEFAULT_BUCKETS = [
        0.001,
        0.0025,
        0.005,
        0.0075,
        0.01,
        0.025,
        0.05,
        0.075,
        0.1,
        0.25,
        0.5,
        0.75,
        1,
        2.5,
        5,
        7.5,
        10,
    ];

    public static function create(): Registry
    {
        return new InMemory\Registry();
    }

    public static function defaultBuckets(): Buckets
    {
        return new Buckets(...self::DEFAULT_BUCKETS);
    }
}
