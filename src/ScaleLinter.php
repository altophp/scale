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
readonly class ScaleLinter
{
    public function __construct(public ?ScaleInterface $scale = null, public float $tolerance = 0.05) {}

    /**
     * @param array<float> $values
     *
     * @return list<array{original: float, suggested: float, step: int, deviation: float, isValid: bool}>
     */
    public function lint(array $values): array
    {
        $scale = $this->scale ?? (new ScaleGuesser($this->tolerance))->guess($values);
        $results = [];
        foreach ($values as $value) {
            if ($value <= 0) {
                continue;
            }
            $step = $scale->stepOf($value);
            $suggested = $scale->get($step);
            $dev = abs($value - $suggested);
            $results[] = [
                'original' => $value, 'suggested' => $suggested, 'step' => $step,
                'deviation' => $dev, 'isValid' => $dev <= $this->tolerance,
            ];
        }

        return $results;
    }

    /**
     * @param array<float> $values
     *
     * @return array<float>
     */
    public function fix(array $values): array
    {
        $scale = $this->scale ?? (new ScaleGuesser($this->tolerance))->guess($values);

        return array_map(fn(float $v) => $v > 0 ? $scale->snap($v) : $v, $values);
    }
}
