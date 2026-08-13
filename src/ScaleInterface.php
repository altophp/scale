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

/**
 * @author Simon André <smn.andre@gmail.com>
 */
interface ScaleInterface
{
    /**
     * Get the scalar value at a specific step.
     */
    public function get(int $step): float;

    /**
     * Find the closest step index for a given value.
     */
    public function stepOf(float $value): int;

    /**
     * Snap a value to the nearest step in the scale.
     */
    public function snap(float $value): float;

    /**
     * Generate a range of values between steps.
     *
     * @return array<int, float>
     */
    public function range(int $min, int $max): array;
}
