<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

final class Label
{
    private string $name;
    private string $value;

    public function __construct(string $name, string $value)
    {
        $this->name  = $name;
        $this->value = $value;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function value(): string
    {
        return $this->value;
    }
}
