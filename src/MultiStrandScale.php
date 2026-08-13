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
 * Interleaved Modular Scales sharing a ratio.
 *
 * @author Simon André <smn.andre@gmail.com>
 */
final readonly class MultiStrandScale extends AbstractScale
{
    /**
     * @var list<float>
     */
    private array $sortedBases;
    public float $ratio;

    /**
     * @param list<float>|array<float> $bases
     */
    public function __construct(array $bases, float|Ratio $ratio = Ratio::MajorThird)
    {
        $this->ratio = Ratio::resolve($ratio);
        if ($this->ratio <= 1.0) {
            throw new ScaleException('Ratio must be > 1.0');
        }

        $validBases = array_filter($bases, fn($b) => $b > 0);
        if (empty($validBases)) {
            throw new ScaleException('At least one positive base required.');
        }

        $unique = array_unique($validBases);
        sort($unique);
        // sort() re-indexes the array, so it is already a list.
        $this->sortedBases = $unique;
    }

    public function get(int $step): float
    {
        $count = count($this->sortedBases);
        $cycle = (int) floor($step / $count);
        $index = $step % $count;
        if ($index < 0) {
            $index += $count;
        }

        return $this->sortedBases[$index] * ($this->ratio ** $cycle);
    }

    public function stepOf(float $value): int
    {
        if ($value <= 0) {
            return 0;
        }

        $bestStep = 0;
        $minDiff = INF;
        $count = count($this->sortedBases);

        foreach ($this->sortedBases as $i => $base) {
            // Find closest cycle for this base
            $cycle = round(log($value / $base) / log($this->ratio));
            $candidate = $base * ($this->ratio ** $cycle);
            $diff = abs($value - $candidate);

            if ($diff < $minDiff) {
                $minDiff = $diff;
                $bestStep = (int) ($cycle * $count + $i);
            }
        }

        return $bestStep;
    }
}
