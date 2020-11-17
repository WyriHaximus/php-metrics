<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Printer;

use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Printer;
use WyriHaximus\Metrics\Registry\Counters;
use WyriHaximus\Metrics\Registry\Gauges;
use WyriHaximus\Metrics\Registry\Histograms;
use WyriHaximus\Metrics\Registry\Summaries;

use function addslashes;
use function array_map;
use function count;
use function implode;
use function strlen;

final class Prometheus implements Printer
{
    private const NL              = "\n";
    private const NO_LABELS_COUNT = 0;

    public function counter(Counters $counters): string
    {
        $string = '';
        foreach ($counters->counters() as $counter) {
            $string    .= $counters->name() . '_total';
            $labels     = $counter->labels();
            $labelCount = count($labels);
            if ($labelCount !== self::NO_LABELS_COUNT) {
                $string .= '{';
                $string .= implode(',', array_map(static fn (Label $label) => $label->name() . '="' . addslashes($label->value()) . '"', $labels));
                $string .= '}';
            }

            $string .= ' ' . $counter->count() . self::NL;
        }

        if ($string !== '') {
            $head = '';
            if (strlen($counters->description()) > 0) {
                $head = '# HELP ' . $counters->name() . '_total ' . $counters->description() . self::NL;
            }

            $head .= '# TYPE ' . $counters->name() . '_total counter' . self::NL;

            $string = $head . $string;
        }

        return $string . self::NL;
    }

    public function gauge(Gauges $gauges): string
    {
        $string = '';

        foreach ($gauges->gauges() as $gauge) {
            $string    .= $gauge->name();
            $labels     = $gauge->labels();
            $labelCount = count($labels);
            if ($labelCount !== self::NO_LABELS_COUNT) {
                $string .= '{';
                $string .= implode(',', array_map(static fn (Label $label) => $label->name() . '="' . addslashes($label->value()) . '"', $labels));
                $string .= '}';
            }

            $string .= ' ' . $gauge->gauge() . self::NL;
        }

        if ($string !== '') {
            $head = '';

            if (strlen($gauges->description()) > 0) {
                $head = '# HELP ' . $gauges->name() . ' ' . $gauges->description() . self::NL;
            }

            $head .= '# TYPE ' . $gauges->name() . ' gauge' . self::NL;

            $string = $head . $string;
        }

        return $string . self::NL;
    }

    public function histogram(Histograms $histograms): string
    {
        $string = '';

        foreach ($histograms->histograms() as $histogram) {
            $labels       = $histogram->labels();
            $labelCount   = count($labels);
            $labelsString = '';
            if ($labelCount !== self::NO_LABELS_COUNT) {
                $labelsString = implode(',', array_map(static fn (Label $label) => $label->name() . '="' . addslashes($label->value()) . '"', $labels));
            }

            foreach ($histogram->buckets() as $bucket) {
                $string .= $histogram->name() . '_bucket{le="' . $bucket->le() . '"';
                if ($labelCount !== self::NO_LABELS_COUNT) {
                    $string .= ',' . $labelsString;
                }

                $string .= '} ' . $bucket->count() . self::NL;
            }

            $string .= $histogram->name() . '_sum';
            if ($labelCount !== self::NO_LABELS_COUNT) {
                $string .= '{' . $labelsString . '}';
            }

            $string .= ' ' . $histogram->summary() . self::NL;

            $string .= $histogram->name() . '_count';
            if ($labelCount !== self::NO_LABELS_COUNT) {
                $string .= '{' . $labelsString . '}';
            }

            $string .= ' ' . $histogram->count() . self::NL;
        }

        if ($string !== '') {
            $head = '';

            if (strlen($histograms->description()) > 0) {
                $head = '# HELP ' . $histograms->name() . ' ' . $histograms->description() . self::NL;
            }

            $head .= '# TYPE ' . $histograms->name() . ' histogram' . self::NL;

            $string = $head . $string;
        }

        return $string . self::NL;
    }

    public function summary(Summaries $summaries): string
    {
        $string = '';

        foreach ($summaries->summaries() as $summary) {
            $labels       = $summary->labels();
            $labelCount   = count($labels);
            $labelsString = '';
            if ($labelCount !== self::NO_LABELS_COUNT) {
                $labelsString = implode(',', array_map(static fn (Label $label) => $label->name() . '="' . addslashes($label->value()) . '"', $labels));
            }

            foreach ($summary->quantiles() as $quantile) {
                $string .= $summary->name() . '{quantile="' . $quantile->quantile() . '"';
                if ($labelCount !== self::NO_LABELS_COUNT) {
                    $string .= ',' . $labelsString;
                }

                $string .= '} ' . $quantile->value() . self::NL;
            }
        }

        if ($string !== '') {
            $head = '';

            if (strlen($summaries->description()) > 0) {
                $head = '# HELP ' . $summaries->name() . ' ' . $summaries->description() . self::NL;
            }

            $head .= '# TYPE ' . $summaries->name() . ' summary' . self::NL;

            $string = $head . $string;
        }

        return $string . self::NL;
    }
}
