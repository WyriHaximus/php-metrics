<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics;

/** @api */
interface Printer
{
    public function print(PrintJob $print): string;
}
