<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

final class Factory
{
    public static function create(): Registry
    {
        return new InMemory\Registry();
    }
}
