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
enum Ratio: string
{
    case MinorSecond = '1.067';
    case MajorSecond = '1.125';
    case MinorThird = '1.200';
    case MajorThird = '1.250';
    case PerfectFourth = '1.333';
    case AugmentedFourth = '1.414';
    case PerfectFifth = '1.500';
    case MinorSixth = '1.600';
    case GoldenRatio = '1.618';
    case MajorSixth = '1.667';
    case MinorSeventh = '1.778';
    case MajorSeventh = '1.875';
    case Octave = '2.000';

    /**
     * Helper to resolve custom floats or Enum instances.
     */
    public static function resolve(float|self $ratio): float
    {
        return $ratio instanceof self ? (float) $ratio->value : $ratio;
    }
}
