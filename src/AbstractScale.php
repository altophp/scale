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
 * @implements \IteratorAggregate<int, float>
 *
 * @author Simon André <smn.andre@gmail.com>
 */
abstract readonly class AbstractScale implements ScaleInterface, \IteratorAggregate
{
    abstract public function get(int $step): float;

    abstract public function stepOf(float $value): int;

    public function snap(float $value): float
    {
        return $this->get($this->stepOf($value));
    }

    public function range(int $min, int $max): array
    {
        if ($min > $max) {
            throw new ScaleException("Min step ($min) cannot be greater than max step ($max).");
        }

        $range = [];
        for ($i = $min; $i <= $max; ++$i) {
            $range[$i] = $this->get($i);
        }

        return $range;
    }

    public function getIterator(): \Generator
    {
        yield from $this->range(0, 10);
    }
}
