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
 * Geometric progression: value = base * (ratio ^ step).
 *
 * @author Simon André <smn.andre@gmail.com>
 */
final readonly class ModularScale extends AbstractScale
{
    public float $multiplier;

    public function __construct(
        public float $base = 1.0,
        public float|Ratio $ratio = Ratio::PerfectFifth,
    ) {
        if (0.0 === $this->base) {
            throw new ScaleException('Base cannot be zero.');
        }

        $multiplier = Ratio::resolve($this->ratio);
        if ($multiplier <= 0) {
            throw new ScaleException('Ratio multiplier must be positive.');
        }

        $this->multiplier = $multiplier;
    }

    public function get(int $step): float
    {
        return 0 === $step ? $this->base : $this->base * ($this->multiplier ** $step);
    }

    public function stepOf(float $value): int
    {
        if ($value <= 0) {
            throw new ScaleException('Cannot calculate log step of non-positive value.');
        }

        return (int) round(log($value / $this->base) / log($this->multiplier));
    }

    public function areHarmonic(float $a, float $b, float $epsilon = 0.001): bool
    {
        if (0.0 === $a || $b <= 0) {
            return false;
        }
        $diff = log($b / $a) / log($this->multiplier);

        return abs($diff - round($diff)) < $epsilon;
    }

    public function withBase(float $base): self
    {
        return new self($base, $this->ratio);
    }

    public function withRatio(float|Ratio $ratio): self
    {
        return new self($this->base, $ratio);
    }

    public function shift(int $steps): self
    {
        return new self($this->get($steps), $this->ratio);
    }

    public function __invoke(int $step): float
    {
        return $this->get($step);
    }
}
