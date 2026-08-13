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

namespace Alto\Scale\Tests;

use Alto\Scale\Exception\ScaleException;
use Alto\Scale\MultiStrandScale;
use Alto\Scale\Ratio;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(MultiStrandScale::class)]
final class MultiStrandScaleTest extends TestCase
{
    #[Test]
    public function itInterleavesMultipleBases(): void
    {
        // Bases 10 and 12, Ratio 2.0 (Octave)
        // 10 -> 10, 20, 40, 80
        // 12 -> 12, 24, 48, 96
        // Combined Sorted: 10, 12, 20, 24, 40, 48, 80, 96
        $scale = new MultiStrandScale([10, 12], Ratio::Octave);

        $this->assertEquals(10.0, $scale->get(0));
        $this->assertEquals(12.0, $scale->get(1));
        $this->assertEquals(20.0, $scale->get(2));
        $this->assertEquals(24.0, $scale->get(3));
        $this->assertEquals(40.0, $scale->get(4));
        $this->assertEquals(48.0, $scale->get(5));

        // Negative steps (Shifted down)
        // -1: 12 / 2 = 6.0 (from 12)
        // -2: 10 / 2 = 5.0 (from 10)
        // Index -1 -> index 1, cycle -1. 12 * 2^-1 = 6.
        // Index -2 -> index 0, cycle -1. 10 * 2^-1 = 5.
        $this->assertEquals(6.0, $scale->get(-1));
        $this->assertEquals(5.0, $scale->get(-2));
    }

    #[Test]
    public function itCalculatesStepOfValues(): void
    {
        // Bases 10, 12. Ratio 2.0.
        // 10, 12, 20, 24, 40, 48.
        // Steps: 0, 1, 2, 3, 4, 5.
        $scale = new MultiStrandScale([10, 12], Ratio::Octave);

        $this->assertEquals(0, $scale->stepOf(10.0));
        $this->assertEquals(1, $scale->stepOf(12.0));
        $this->assertEquals(2, $scale->stepOf(20.0));
        $this->assertEquals(3, $scale->stepOf(24.0));
        $this->assertEquals(0, $scale->stepOf(10.1)); // Closes to 10
        $this->assertEquals(0, $scale->stepOf(9.9));  // Closest to 10

        // Check midpoint
        // 10 to 12. Midpoint 11.
        // 11 is diff 1 from 10, diff 1 from 12.
        // Implementation finds "bestStep". If diffs equal? First one?
        // 10 is index 0. 12 index 1.
        $this->assertEquals(0, $scale->stepOf(10.9)); // Closer to 11? No.

        $this->assertEquals(0, $scale->stepOf(0.0)); // Returns 0 for non-positive
    }

    #[Test]
    public function itThrowsOnInvalidRatio(): void
    {
        $this->expectException(ScaleException::class);
        $this->expectExceptionMessage('Ratio must be > 1.0');
        new MultiStrandScale([10], 1.0);
    }

    #[Test]
    public function itThrowsOnNoPositiveBases(): void
    {
        $this->expectException(ScaleException::class);
        $this->expectExceptionMessage('At least one positive base required');
        new MultiStrandScale([0.0, -10.0], 2.0);
    }

    #[Test]
    public function itFiltersInvalidBasesButSucceedsIfValidExist(): void
    {
        $scale = new MultiStrandScale([10.0, -5.0, 0.0], 2.0);
        $this->assertEquals(10.0, $scale->get(0));
    }
}
