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
 * Fibonacci sequence using Binet's formula.
 *
 * @author Simon André <smn.andre@gmail.com>
 */
final readonly class FibonacciScale extends AbstractScale
{
    private const float PHI = 1.618033988749895;
    private const float SQRT_5 = 2.2360679774997896;

    public function __construct(public readonly float $multiplier = 1.0)
    {
        if ($this->multiplier <= 0) {
            throw new ScaleException('Multiplier must be positive.');
        }
    }

    public function get(int $step): float
    {
        $n = abs($step);
        $val = round((self::PHI ** $n - (1 - self::PHI) ** $n) / self::SQRT_5);

        return $this->multiplier * ($step < 0 ? (pow(-1, $n + 1) * $val) : $val);
    }

    public function stepOf(float $value): int
    {
        if ($value <= 0) {
            return 0;
        }
        $norm = $value / $this->multiplier;

        return $norm < 1 ? 0 : (int) round(log($norm * self::SQRT_5, self::PHI));
    }
}
