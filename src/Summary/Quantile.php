<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Summary;

final class Quantile
{
    private string $quantile;
    private float $value;

    public function __construct(string $quantile, float $value)
    {
        $this->quantile = $quantile;
        $this->value    = $value;
    }

    public function quantile(): string
    {
        return $this->quantile;
    }

    public function value(): float
    {
        return $this->value;
    }
}
