# Test utilities

![Continuous Integration](https://github.com/wyrihaximus/php-metrics/workflows/Continuous%20Integration/badge.svg)
[![Latest Stable Version](https://poser.pugx.org/wyrihaximus/metrics/v/stable.png)](https://packagist.org/packages/wyrihaximus/metrics)
[![Total Downloads](https://poser.pugx.org/wyrihaximus/metrics/downloads.png)](https://packagist.org/packages/wyrihaximus/metrics/stats)
[![Code Coverage](https://coveralls.io/repos/github/WyriHaximus/php-metrics/badge.svg?branchmaster)](https://coveralls.io/github/WyriHaximus/php-metrics?branch=master)
[![Type Coverage](https://shepherd.dev/github/WyriHaximus/php-metrics/coverage.svg)](https://shepherd.dev/github/WyriHaximus/php-metrics)
[![License](https://poser.pugx.org/wyrihaximus/metrics/license.png)](https://packagist.org/packages/wyrihaximus/metrics)

# Installation

To install via [Composer](http://getcomposer.org/), use the command below, it will automatically detect the latest version and bind it with `^`.

```
composer require wyrihaximus/metrics
```

# Usage

This package ships with an in-memory registry that can be created using the `Factory`:

```php
<?php

declare(strict_types=1);

use WyriHaximus\Metrics\Factory;

require 'vendor/autoload.php';

$registry = Factory::create();
```

From there it supports 3 types of metrics; `counters`, `gauges`, and `histograms`. Ach of them has to have at least
one label. For example for a counter:

```php
<?php

declare(strict_types=1);

use WyriHaximus\Metrics\Factory;
use WyriHaximus\Metrics\Label\Name;

require 'vendor/autoload.php';

$registry = Factory::create();
$counter = $registry->counter('name', 'description', new Name('label'));
```

Once the metric collection has been create you can create a metric for the counter with specific values for the
required labels:

```php
<?php

declare(strict_types=1);

use WyriHaximus\Metrics\Factory;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;

require 'vendor/autoload.php';

$registry = Factory::create();
$counterCollection = $registry->counter('name', 'description', new Name('label'));
$counter = $counterCollection->counter(new Label('label', 'value'));
```

With the metric we have now we can operate on it, in the cause of the counter we can increase it:

```php
<?php

declare(strict_types=1);

use WyriHaximus\Metrics\Factory;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;

require 'vendor/autoload.php';

$registry = Factory::create();
$counterCollection = $registry->counter('name', 'description', new Name('label'));
$counter = $counterCollection->counter(new Label('label', 'value'));
$counter->incr();
```

There is a full example in the [`examples`](examples/) directory that also includes gauges and histograms.

# License

The MIT License (MIT)

Copyright (c) 2020 Cees-Jan Kiewiet

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
