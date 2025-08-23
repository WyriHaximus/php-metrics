<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Label;

use InvalidArgumentException;

final class GivenLabelsDontMatchExpectedLabels extends InvalidArgumentException
{
    public const string MESSAGE = 'Given labels don\'t match expected labels';
    //phpcs:enable

    /**
     * @param array<string> $expectedLabels
     * @param array<string> $labelNames
     */
    public static function create(array $expectedLabels, array $labelNames): self
    {
        return new self(self::MESSAGE, $expectedLabels, $labelNames);
    }

    /**
     * @param array<string> $expectedLabels
     * @param array<string> $labelNames
     */
    private function __construct(string $message, public readonly array $expectedLabels, public readonly array $labelNames)
    {
        parent::__construct($message);
    }
}
