<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\InMemory\Registry;

use WyriHaximus\Metrics\Counter as CounterInterface;
use WyriHaximus\Metrics\InMemory\Counter;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\Metrics\Registry\Counters as CountersInterface;

use function array_key_exists;
use function array_map;
use function array_values;
use function implode;
use function Safe\usort;
use function strcmp;

final class Counters implements CountersInterface
{
    private const SEPARATOR = '#@$%^&*()';

    private string $name;
    private string $description;
    /** @var array<string> */
    private array $requiredLabelNames;
    /** @var array<Counter> */
    private array $counters = [];

    public function __construct(string $name, string $description, Name ...$requiredLabelNames)
    {
        $this->name               = $name;
        $this->description        = $description;
        $this->requiredLabelNames = array_map(static fn (Name $name) => $name->name(), $requiredLabelNames);
    }

    public function name(): string
    {
        return $this->name;
    }

    public function description(): string
    {
        return $this->description;
    }

    public function counter(Label ...$labels): CounterInterface
    {
        Label\Utils::validate($this->requiredLabelNames, ...$labels);

        usort($labels, static fn (Label $a, Label $b) => strcmp($a->name(), $b->name()));
        $key = implode(
            self::SEPARATOR,
            array_map(
                static fn (Label $label) => $label->value(),
                $labels
            )
        );

        if (! array_key_exists($key, $this->counters)) {
            $this->counters[$key] = new Counter($this->name, $this->description, ...$labels);
        }

        return $this->counters[$key];
    }

    /**
     * @return iterable<Counter>
     */
    public function counters(): iterable
    {
        yield from array_values($this->counters);
    }
}
