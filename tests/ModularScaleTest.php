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
use Alto\Scale\ModularScale;
use Alto\Scale\Ratio;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ModularScale::class)]
final class ModularScaleTest extends TestCase
{
    #[Test]
    public function itCalculatesStepsDynamicallyViaHooks(): void
    {
        $scale = new ModularScale(10.0, Ratio::Octave);

        // Ratio::Octave is 2.0
        $this->assertEquals(20.0, $scale->get(1));
        $this->assertEquals(2.0, $scale->multiplier); // Test the hook
    }

    #[Test]
    public function itIsCallable(): void
    {
        $scale = new ModularScale(1.0, 2.0);
        $this->assertEquals(4.0, $scale(2));
    }

    #[Test]
    public function itCalculatesStepOfArbitraryValues(): void
    {
        $scale = new ModularScale(1.0, 2.0);

        // Exact matches
        $this->assertSame(0, $scale->stepOf(1.0));
        $this->assertSame(3, $scale->stepOf(8.0));

        // Rounding logic (log2(7.0) ≈ 2.807 -> rounds to 3)
        $this->assertSame(3, $scale->stepOf(7.0));

        // Rounding logic (log2(5.0) ≈ 2.32 -> rounds to 2)
        $this->assertSame(2, $scale->stepOf(5.0));
    }

    #[Test]
    public function itIdentifiesHarmonicRelations(): void
    {
        $scale = new ModularScale(10.0, 2.0); // 10, 20, 40...

        // Direct matches
        $this->assertTrue($scale->areHarmonic(10.0, 20.0));
        $this->assertTrue($scale->areHarmonic(10.0, 40.0));

        // Non-harmonic
        $this->assertFalse($scale->areHarmonic(10.0, 30.0));

        // Edge cases
        $this->assertFalse($scale->areHarmonic(0.0, 10.0));
        $this->assertFalse($scale->areHarmonic(10.0, -5.0));
    }

    #[Test]
    public function itSupportsImmutableManipulation(): void
    {
        $scale = new ModularScale(10.0, 2.0);

        $newBase = $scale->withBase(5.0);
        $newRatio = $scale->withRatio(3.0);

        // Assert Immutability
        $this->assertEquals(10.0, $scale->base);
        $this->assertEquals(2.0, $scale->multiplier);

        $this->assertEquals(5.0, $newBase->base);
        $this->assertEquals(3.0, $newRatio->multiplier);
        $this->assertEquals(10.0, $newRatio->base);
    }

    #[Test]
    public function itCanShiftPerspective(): void
    {
        // Base 10, Ratio 2
        $scale = new ModularScale(10.0, 2.0);

        // Shift up 1 step. New base should be 20.
        $shifted = $scale->shift(1);

        $this->assertEquals(20.0, $shifted->base);
        $this->assertEquals(40.0, $shifted->get(1));
    }

    #[Test]
    public function itIteratesDefaultRangeViaTraversable(): void
    {
        $scale = new ModularScale(1.0, 2.0);
        $values = iterator_to_array($scale); // Implicitly calls getIterator (range 0-10)

        $this->assertCount(11, $values);
        $this->assertEquals(1.0, $values[0]);
        $this->assertEquals(1024.0, $values[10]);
    }

    // -- Exception Paths --

    #[Test]
    public function itThrowsOnInvalidBases(): void
    {
        $this->expectException(ScaleException::class);
        $this->expectExceptionMessage('Base cannot be zero');
        new ModularScale(0.0);
    }

    #[Test]
    public function itThrowsOnInvalidRangeSteps(): void
    {
        $scale = new ModularScale();
        $this->expectException(ScaleException::class);
        $this->expectExceptionMessage('Min step (5) cannot be greater than max step (1)');

        foreach ($scale->range(5, 1) as $val) {
            // No-op
        }
    }

    #[Test]
    public function itThrowsOnNonPositiveRatioViaHooks(): void
    {
        $this->expectException(ScaleException::class);
        $this->expectExceptionMessage('Ratio multiplier must be positive');

        $scale = new ModularScale(1.0, -2.0);
    }

    #[Test]
    public function itThrowsOnNonPositiveStepCalculation(): void
    {
        $scale = new ModularScale();

        $this->expectException(ScaleException::class);
        $this->expectExceptionMessage('Cannot calculate log step of non-positive value');

        $scale->stepOf(-5.0);
    }

    #[Test]
    public function itThrowsOnNonPositiveSnapInput(): void
    {
        $scale = new ModularScale();

        // Snap calls stepOf internally, so it should bubble the exception
        $this->expectException(ScaleException::class);
        $scale->snap(0.0);
    }
}
