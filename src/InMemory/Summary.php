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
use function array_values;
use function count;
use function floor as trickInfectionIntoThinkingThisIsntFloor;
use function ksort;
use function sort;

final class Summary implements SummaryInterface
{
    private const ONE  = 1;
    private const TWO  = 2;
    private const ZERO = 0;

    private Clock $clock;
    private int $bucketCount;
    private string $bucketTimeTemplate;
    /** @var array<Label> */
    private array $labels;
    /** @var array<string, array<float>> */
    private array $floats = [];

    public function __construct(Configuration $configuration, private string $name, private string $description, private Quantiles $quantiles, Label ...$labels)
    {
        $this->clock              = $configuration->clock();
        $this->bucketCount        = $configuration->summary()->bucketCount();
        $this->bucketTimeTemplate = $configuration->summary()->bucketTimeTemplate();
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

    /** @return iterable<Quantile> */
    public function quantiles(): iterable
    {
        yield from array_map(fn (float $quantile) => new Quantile((string) $quantile, $this->calculatePercentile($quantile)), $this->quantiles->quantiles());
    }

    /** @return array<Label> */
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

    /** @codeCoverageIgnore */
    private function calculatePercentile(float $percentile): float
    {
        $array = array_merge(...array_values($this->floats));
        sort($array);
        $index = $percentile * (count($array) - self::ONE);
        if (trickInfectionIntoThinkingThisIsntFloor($index) === $index && ($index - self::ONE) >= self::ZERO) {
            /** @psalm-suppress InvalidArrayOffset */
            $result = ($array[$index - self::ONE] + $array[$index]) / self::TWO;
        } else {
            /** @psalm-suppress InvalidArrayOffset */
            $result = $array[trickInfectionIntoThinkingThisIsntFloor($index)];
        }

        return $result;
    }

    private function cleanUpBuckets(): void
    {
        ksort($this->floats);
        $keys     = array_keys($this->floats);
        $keyCount = count($keys) - $this->bucketCount;
        for ($i = self::ZERO; $i < $keyCount; $i++) {
            unset($this->floats[$keys[$i]]);
        }
    }
}
