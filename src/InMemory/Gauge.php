<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\InMemory;

use WyriHaximus\Metrics\Gauge as GaugeInterface;
use WyriHaximus\Metrics\Label;

final class Gauge implements GaugeInterface
{
    private string $name;
    private string $description;
    private int $gauge = 0;
    /** @var array<Label> */
    private array $labels;

    public function __construct(string $name, string $description, Label ...$labels)
    {
        $this->name        = $name;
        $this->description = $description;
        $this->labels      = $labels;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function gauge(): int
    {
        return $this->gauge;
    }

    /**
     * @return array<Label>
     */
    public function labels(): array
    {
        return $this->labels;
    }

    public function incr(): void
    {
        $this->gauge++;
    }

    public function incrBy(int $incr): void
    {
        $this->gauge += $incr;
    }

    public function set(int $count): void
    {
        $this->gauge = $count;
    }

    public function dcrBy(int $dcr): void
    {
        $this->gauge -= $dcr;
    }

    public function dcr(): void
    {
        $this->gauge--;
    }
}
