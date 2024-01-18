<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\InMemory;

use WyriHaximus\Metrics\Histogram as HistogramInterface;
use WyriHaximus\Metrics\Histogram\Bucket;
use WyriHaximus\Metrics\Histogram\Buckets;
use WyriHaximus\Metrics\Label;

use function array_map;

final class Histogram implements HistogramInterface
{
    /** @var array<Bucket> */
    private array $buckets;
    /** @var array<Label> */
    private array $labels;
    private float $summary = 0;
    private int $count     = 0;

    public function __construct(private string $name, private string $description, Buckets $buckets, Label ...$labels)
    {
        $this->buckets = array_map(static fn (float $quantile) => new Bucket((string) $quantile), $buckets->buckets());
        $this->labels  = $labels;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    /** @return iterable<Bucket> */
    public function buckets(): iterable
    {
        yield from $this->buckets;
        yield '+Inf' => Bucket::createWithCount('+Inf', $this->count);
    }

    public function summary(): float
    {
        return $this->summary;
    }

    public function count(): int
    {
        return $this->count;
    }

    /** @return array<Label> */
    public function labels(): array
    {
        return $this->labels;
    }

    public function observe(float $value): void
    {
        foreach ($this->buckets as $bucket) {
            if ($bucket->le() <= $value) {
                continue;
            }

            $bucket->incr();
        }

        $this->summary += $value;
        $this->count++;
    }
}
