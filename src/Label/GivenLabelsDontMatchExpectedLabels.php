<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Label;

use InvalidArgumentException;

final class GivenLabelsDontMatchExpectedLabels extends InvalidArgumentException
{
    private const string MESSAGE = 'Given labels don\'t match expected labels';

    //phpcs:disable
    /** @var array<string> */
    public array $expectedLabels = [];

    /** @var array<string> */
    public array $labelNames = [];
    //phpcs:enable

    /**
     * @param array<string> $expectedLabels
     * @param array<string> $labelNames
     */
    public static function create(array $expectedLabels, array $labelNames): self
    {
        $self = new self(self::MESSAGE);

        $self->expectedLabels = $expectedLabels;
        $self->labelNames     = $labelNames;

        return $self;
    }
}
