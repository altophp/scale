<?php

declare(strict_types=1);

/*
 * This file is part of the ALTO library.
 *
 * © 2026-present Simon André
 *
 * For full copyright and license information, please see
 * the LICENSE file distributed with this source code.
 */

namespace Alto\Scale;

use Alto\Scale\Exception\ScaleException;

/**
 * @author Simon André <smn.andre@gmail.com>
 */
final readonly class LinearScale extends AbstractScale
{
    public function __construct(
        public float $base = 0.0,
        public float $increment = 8.0,
    ) {
        if ($this->increment <= 0) {
            throw new ScaleException('Increment must be positive.');
        }
    }

    public function get(int $step): float
    {
        return $this->base + ($step * $this->increment);
    }

    public function stepOf(float $value): int
    {
        // n = (value - base) / increment
        return (int) round(($value - $this->base) / $this->increment);
    }
}
