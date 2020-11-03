<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\InMemory;

use Lcobucci\Clock\Clock;
use WyriHaximus\Metrics\Configuration;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Summary as SummaryInterface;
use WyriHaximus\Metrics\Summary\Quantile;
use WyriHaximus\Metrics\Summary\Quantiles;

use function array_keys;
use function array_map;
use function array_merge;
use function count;
use function floor;
use function Safe\ksort;
use function Safe\sort;

use const WyriHaximus\Constants\Numeric\ONE;
use const WyriHaximus\Constants\Numeric\TWO;
use const WyriHaximus\Constants\Numeric\ZERO;

final class Summary implements SummaryInterface
{
    private Clock $clock;
    private int $bucketCount;
    private string $bucketTimeTemplate;
    private string $name;
    private string $description;
    private Quantiles $quantiles;
    /** @var array<Label> */
    private array $labels;
    /** @var array<string, array<float>> */
    private array $floats = [];

    public function __construct(Configuration $configuration, string $name, string $description, Quantiles $quantiles, Label ...$labels)
    {
        $this->clock              = $configuration->clock();
        $this->bucketCount        = $configuration->summary()->bucketCount();
        $this->bucketTimeTemplate = $configuration->summary()->bucketTimeTemplate();
        $this->name               = $name;
        $this->description        = $description;
        $this->quantiles          = $quantiles;
        $this->labels             = $labels;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    /**
     * @return iterable<Quantile>
     */
    public function quantiles(): iterable
    {
        yield from array_map(fn (float $quantile) => new Quantile((string) $quantile, $this->calculatePercentile($quantile)), $this->quantiles->quantiles());
    }

    /**
     * @return array<Label>
     */
    public function labels(): array
    {
        return $this->labels;
    }

    public function observe(float $value): void
    {
        $this->floats[$this->clock->now()->format($this->bucketTimeTemplate)][] = $value;

        if (count($this->floats) <= $this->bucketCount) {
            return;
        }

        $this->cleanUpBuckets();
    }

    /**
     * @codeCoverageIgnore
     */
    private function calculatePercentile(float $percentile): float
    {
        $array = array_merge(...$this->floats);
        sort($array);
        $index = $percentile * (count($array) - ONE);
        if (floor($index) === $index) {
            /** @psalm-suppress InvalidArrayOffset */
            $result = ($array[$index - ONE] + $array[$index]) / TWO;
        } else {
            /** @psalm-suppress InvalidArrayOffset */
            $result = $array[floor($index)];
        }

        return $result;
    }

    private function cleanUpBuckets(): void
    {
        ksort($this->floats);
        $keys = array_keys($this->floats);
        for ($i = ZERO; $i < count($keys) - $this->bucketCount; $i++) {
            unset($this->floats[$keys[$i]]);
        }
    }
}
