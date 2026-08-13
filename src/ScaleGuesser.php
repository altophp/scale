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
readonly class ScaleGuesser
{
    public function __construct(public float $tolerance = 0.05) {}

    /**
     * @param array<float> $values
     */
    public function guess(array $values): ModularScale
    {
        $clean = array_values(array_unique(array_filter($values, fn($v) => $v > 0)));
        sort($clean);
        if (count($clean) < 2) {
            throw new ScaleException('Need 2+ values to guess.');
        }

        // 1. Determine Ratio
        $ratios = [];
        for ($i = 0; $i < count($clean) - 1; ++$i) {
            $ratios[] = $clean[$i + 1] / $clean[$i];
        }
        $avgRatio = array_sum($ratios) / count($ratios);

        $bestRatio = $avgRatio;
        $minDiff = INF;
        $foundEnum = null;

        // Find closest standard ratio
        foreach (Ratio::cases() as $r) {
            $diff = abs($avgRatio - (float) $r->value);
            if ($diff < $minDiff && $diff <= $this->tolerance) {
                $minDiff = $diff;
                $bestRatio = (float) $r->value;
                $foundEnum = $r;
            }
        }

        // 2. Determine Base
        // Average the implied bases for better accuracy
        $ratioVal = $bestRatio;
        $impliedBases = [];
        $firstVal = $clean[0];

        foreach ($clean as $val) {
            // Step relative to first val
            $step = round(log($val / $firstVal) / log($ratioVal));
            $impliedBases[] = $val / ($ratioVal ** $step);
        }

        $avgBase = array_sum($impliedBases) / count($impliedBases);

        return new ModularScale($avgBase, $foundEnum ?? $bestRatio);
    }

    /**
     * @param array<float> $values
     *
     * @return array<float>
     */
    public function align(array $values, ?ScaleInterface $scale = null): array
    {
        $target = $scale ?? $this->guess($values);

        return array_map(fn(float $v) => $v > 0 ? $target->snap($v) : $v, $values);
    }
}
