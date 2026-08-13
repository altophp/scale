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

use Alto\Scale\ModularScale;
use Alto\Scale\ScaleInterface;
use Alto\Scale\ScaleLinter;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(ScaleLinter::class)]
final class ScaleLinterTest extends TestCase
{
    #[Test]
    public function itReportsValidAndInvalidValuesAsArray(): void
    {
        $scale = new ModularScale(10.0, 2.0);
        $linter = new ScaleLinter($scale, tolerance: 0.5);

        $results = $linter->lint([10.1, 22.0]);

        $this->assertCount(2, $results);

        // Result 1: 10.1 -> 10.0
        $this->assertTrue($results[0]['isValid']);
        $this->assertEquals(10.0, $results[0]['suggested']);
        $this->assertEqualsWithDelta(0.1, $results[0]['deviation'], 0.0001);

        // Result 2: 22.0 -> 20.0 (Invalid because deviation 2.0 > 0.5)
        $this->assertFalse($results[1]['isValid']);
        $this->assertEquals(20.0, $results[1]['suggested']);
        $this->assertEquals(2.0, $results[1]['deviation']);
    }

    #[Test]
    public function itAutoGuessesScaleIfNoneProvided(): void
    {
        // Implied: Base 16, Major Third (1.25)
        $input = [16.1, 19.9, 25.0];

        $linter = new ScaleLinter(tolerance: 0.2);
        $results = $linter->lint($input);

        // 19.9 should map to approx 20.0 (Step 1)
        // With Averaged Base logic, it might be 20.008
        $this->assertEqualsWithDelta(20.0, $results[1]['suggested'], 0.1);
        $this->assertEquals(1, $results[1]['step']);
    }

    #[Test]
    public function itHarmonizesValuesViaFix(): void
    {
        $input = [9.9, 20.2, 39.5];

        $linter = new ScaleLinter(tolerance: 1.0);
        $fixed = $linter->fix($input);

        // Expects smart base averaging results
        $this->assertEqualsWithDelta(9.96, $fixed[0], 0.1);
        $this->assertEqualsWithDelta(19.92, $fixed[1], 0.1);
        $this->assertEqualsWithDelta(39.83, $fixed[2], 0.1);
    }

    #[Test]
    public function itSkipsNegativeOrZeroValuesDuringLint(): void
    {
        $scale = $this->createStub(ScaleInterface::class);
        $scale->method('stepOf')->willReturn(1);
        $scale->method('get')->willReturn(10.0);

        $linter = new ScaleLinter($scale, tolerance: 0.5);

        $results = $linter->lint([-5.0, 0.0, 10.1]);

        $this->assertCount(1, $results);
        $this->assertEquals(10.0, $results[0]['suggested']);
        $this->assertTrue($results[0]['isValid']);
    }
}
