<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\InMemory;

use WyriHaximus\Metrics\Histogram\Buckets;
use WyriHaximus\Metrics\InMemory\Registry\Counters;
use WyriHaximus\Metrics\InMemory\Registry\Gauges;
use WyriHaximus\Metrics\InMemory\Registry\Histograms;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\Metrics\Printer;
use WyriHaximus\Metrics\Registry as RegistryInterface;
use WyriHaximus\Metrics\Registry\Counters as CountersInterface;
use WyriHaximus\Metrics\Registry\Gauges as GaugesInterface;
use WyriHaximus\Metrics\Registry\Histograms as HistogramsInterface;

use function array_key_exists;
use function array_map;
use function implode;

final class Registry implements RegistryInterface
{
    private const SEPARATOR = 'w9fw9743c98tw3';

    /** @var array<string, Counters> */
    private array $counters = [];

    public function counter(string $name, string $description, Name ...$requiredLabelNames): CountersInterface
    {
        $key = $name . self::SEPARATOR . $description . self::SEPARATOR . implode(self::SEPARATOR, array_map(static fn (Name $name) => $name->name(), $requiredLabelNames));

        if (! array_key_exists($key, $this->counters)) {
            $this->counters[$key] = new Counters($name, $description, ...$requiredLabelNames);
        }

        return $this->counters[$key];
    }

    /** @var array<string, Gauges> */
    private array $gauges = [];

    public function gauge(string $name, string $description, Name ...$requiredLabelNames): GaugesInterface
    {
        $key = $name . self::SEPARATOR . $description . self::SEPARATOR . implode(self::SEPARATOR, array_map(static fn (Name $name) => $name->name(), $requiredLabelNames));

        if (! array_key_exists($key, $this->gauges)) {
            $this->gauges[$key] = new Gauges($name, $description, ...$requiredLabelNames);
        }

        return $this->gauges[$key];
    }

    /** @var array<string, Histograms> */
    private array $histograms = [];

    public function histogram(string $name, string $description, Buckets $buckets, Name ...$requiredLabelNames): HistogramsInterface
    {
        $key = $name . self::SEPARATOR . $description . self::SEPARATOR . implode(self::SEPARATOR, $buckets->buckets()) . self::SEPARATOR . implode(self::SEPARATOR, array_map(static fn (Name $name) => $name->name(), $requiredLabelNames));

        if (! array_key_exists($key, $this->histograms)) {
            $this->histograms[$key] = new Histograms($name, $description, $buckets, ...$requiredLabelNames);
        }

        return $this->histograms[$key];
    }

    public function print(Printer $printer): string
    {
        $string = '';

        foreach ($this->counters as $counter) {
            $string .= $printer->counter($counter);
        }

        foreach ($this->gauges as $gauge) {
            $string .= $printer->gauge($gauge);
        }

        foreach ($this->histograms as $histogram) {
            $string .= $printer->histogram($histogram);
        }

        return $string;
    }
}
