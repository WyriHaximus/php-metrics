<?php

declare(strict_types=1);

namespace WyriHaximus\Tests\Metrics\Printer;

use PHPUnit\Framework\TestCase;
use WyriHaximus\Metrics\Factory;
use WyriHaximus\Metrics\Label;
use WyriHaximus\Metrics\Label\Name;
use WyriHaximus\Metrics\Printer\Prometheus;

use function Safe\file_get_contents;

use const DIRECTORY_SEPARATOR;

final class PrometheusTest extends TestCase
{
    /**
     * @test
     */
    public function print(): void
    {
        $registry = Factory::create();
        $registry->counter('counter', '', new Name('label'))->counter(new Label('label', 'label'))->incr();
        $registry->counter('counter', '', new Name('label'))->counter(new Label('label', 'labol'))->incrBy(133);
        $registry->counter('cuonter', 'simple counter counting things')->counter()->incr();
        $registry->counter('cuonter', 'simple counter counting things')->counter()->incrBy(133);
        $registry->gauge('gauge', '', new Name('label'))->gauge(new Label('label', 'label'))->incr();
        $registry->gauge('gauge', '', new Name('label'))->gauge(new Label('label', 'labol'))->incrBy(300);
        $registry->gauge('guage', 'simple gauge gauging things')->gauge()->incr();
        $registry->gauge('guage', 'simple gauge gauging things')->gauge()->incrBy(300);
        $registry->histogram('histogram', '', Factory::defaultBuckets(), new Name('label'))->histogram(new Label('label', 'label'))->observe(0.6);
        $registry->histogram('histogram', '', Factory::defaultBuckets(), new Name('label'))->histogram(new Label('label', 'label'))->observe(3.3);
        $registry->histogram('hostigram', 'simple histogram histogramming things', Factory::defaultBuckets())->histogram()->observe(0.6);
        $registry->histogram('hostigram', 'simple histogram histogramming things', Factory::defaultBuckets())->histogram()->observe(3.3);

        self::assertSame(file_get_contents(__DIR__ . DIRECTORY_SEPARATOR . 'prometheus.txt'), $registry->print(new Prometheus()));
    }
}
