<?php

declare(strict_types=1);

namespace WyriHaximus\Metrics\Configuration;

final class Summary
{
    private const BUCKET_COUNT = 10;

    private int $buckets = self::BUCKET_COUNT;

    public function withBucketCount(int $buckets): self
    {
        $clone          = clone $this;
        $clone->buckets = $buckets;

        return $clone;
    }

    public function bucketCount(): int
    {
        return $this->buckets;
    }
}
