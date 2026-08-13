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
final class Scale
{
    public static function modular(float $base = 1.0, float|Ratio $ratio = Ratio::PerfectFifth): ModularScale
    {
        return new ModularScale($base, $ratio);
    }

    public static function linear(float $base = 0.0, float $increment = 8.0): LinearScale
    {
        return new LinearScale($base, $increment);
    }

    public static function fibonacci(float $multiplier = 1.0): FibonacciScale
    {
        return new FibonacciScale($multiplier);
    }

    /**
     * @param list<float>|array<float> $bases
     */
    public static function strands(array $bases, float|Ratio $ratio = Ratio::MajorThird): MultiStrandScale
    {
        return new MultiStrandScale($bases, $ratio);
    }

    /**
     * @param array<float> $values
     */
    public static function guess(array $values): ModularScale
    {
        return (new ScaleGuesser())->guess($values);
    }

    public static function majorThird(float $base = 1.0): ModularScale
    {
        return self::modular($base, Ratio::MajorThird);
    }

    public static function perfectFifth(float $base = 1.0): ModularScale
    {
        return self::modular($base, Ratio::PerfectFifth);
    }

    public static function golden(float $base = 1.0): ModularScale
    {
        return self::modular($base, Ratio::GoldenRatio);
    }
}
