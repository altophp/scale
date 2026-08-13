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
use Alto\Scale\ScaleGuesser;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ScaleGuesser::class)]
final class ScaleGuesserTest extends TestCase
{
    #[Test]
    public function itGuessesAStandardMajorThirdScale(): void
    {
        // 16, 20, 25, 31.25 (Major Third 1.25)
        $input = [16.0, 20.0, 25.0, 31.25];

        $guesser = new ScaleGuesser();
        $scale = $guesser->guess($input);

        $this->assertEquals(16.0, $scale->base);
        // Should detect the Enum value due to proximity
        $this->assertEquals(Ratio::MajorThird->value, $scale->multiplier);
    }

    #[Test]
    public function itGuessesPerfectFifthFromNoisyData(): void
    {
        // 10, 15, 22.5 -> Perfect Fifth (1.5)
        // Noisy: 10.1, 14.9, 22.6
        $input = [10.1, 14.9, 22.6];

        $guesser = new ScaleGuesser(tolerance: 0.1); // Looser tolerance
        $scale = $guesser->guess($input);

        // Base is averaged from inputs (approx 10.025)
        $this->assertEqualsWithDelta(10.025, $scale->base, 0.1);

        // 14.9 / 10.1 = ~1.47
        // 22.6 / 14.9 = ~1.51
        // Avg ~1.49 -> snaps to 1.5
        $this->assertEquals(1.5, $scale->multiplier);
    }

    #[Test]
    public function itReturnsRawRatioIfNoStandardMatch(): void
    {
        // Ratio 1.1 (Not standard)
        $input = [10.0, 11.0, 12.1];

        // Tight tolerance to avoid snapping to MajorSecond (1.125)
        $guesser = new ScaleGuesser(tolerance: 0.01);
        $scale = $guesser->guess($input);

        $this->assertEquals(10.0, $scale->base);
        $this->assertEquals(1.1, $scale->multiplier);
    }

    #[Test]
    public function itAlignsValuesToTheGuessedScale(): void
    {
        // User provides messy CSS values
        $input = [15.8, 20.2, 24.9];

        $guesser = new ScaleGuesser();

        // It should guess Base ~15.96 (averaged), Ratio ~1.25 (Major Third)
        // And snap them to exact steps.

        $aligned = $guesser->align($input);

        $this->assertCount(3, $aligned);
        $this->assertEqualsWithDelta(15.96, $aligned[0], 0.1); // Base is averaged

        // Check standard "Fixer" behavior with explicit scale
        $scale = new ModularScale(16.0, Ratio::MajorThird);
        $fixed = $guesser->align([15.9, 20.1, 24.9], $scale);

        $this->assertEquals(16.0, $fixed[0]); // Snapped 15.9 -> 16
        $this->assertEquals(20.0, $fixed[1]); // Snapped 20.1 -> 20
        $this->assertEquals(25.0, $fixed[2]); // Snapped 24.9 -> 25
    }

    #[Test]
    public function itThrowsOnInsufficientData(): void
    {
        $guesser = new ScaleGuesser();

        $this->expectException(ScaleException::class);
        $guesser->guess([10.0]);
    }
}
