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

use Alto\Scale\FibonacciScale;
use Alto\Scale\LinearScale;
use Alto\Scale\ModularScale;
use Alto\Scale\MultiStrandScale;
use Alto\Scale\Ratio;
use Alto\Scale\Scale;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Scale::class)]
final class ScaleFacadeTest extends TestCase
{
    #[Test]
    public function itCreatesModularScales(): void
    {
        $scale = Scale::modular(10.0, 2.0);
        $this->assertInstanceOf(ModularScale::class, $scale);
        $this->assertEquals(10.0, $scale->base);
        $this->assertEquals(2.0, $scale->multiplier);
    }

    #[Test]
    public function itCreatesLinearScales(): void
    {
        $scale = Scale::linear(0.0, 10.0);
        $this->assertInstanceOf(LinearScale::class, $scale);
        $this->assertEquals(10.0, $scale->increment);
    }

    #[Test]
    public function itCreatesFibonacciScales(): void
    {
        $scale = Scale::fibonacci(2.0);
        $this->assertInstanceOf(FibonacciScale::class, $scale);
        $this->assertEquals(2.0, $scale->multiplier);
    }

    #[Test]
    public function itCreatesMultiStrandScales(): void
    {
        $scale = Scale::strands([10, 12], 2.0);
        $this->assertInstanceOf(MultiStrandScale::class, $scale);
    }

    #[Test]
    public function itGuessesScales(): void
    {
        $scale = Scale::guess([10.0, 20.0, 40.0]);
        $this->assertInstanceOf(ModularScale::class, $scale);
        $this->assertEquals(10.0, $scale->base);
        $this->assertEquals(2.0, $scale->multiplier);
    }

    #[Test]
    public function itProvidesShortcuts(): void
    {
        $major = Scale::majorThird(10.0);
        $this->assertEquals(Ratio::MajorThird->value, $major->multiplier); // string cast to float in test expectation usually fine or strict?
        // Ratio::MajorThird->value is string "1.250". multiplier is float 1.25.
        // assertEquals handles this.

        $fifth = Scale::perfectFifth(10.0);
        $this->assertEquals(Ratio::PerfectFifth->value, $fifth->multiplier);

        $golden = Scale::golden(10.0);
        $this->assertEquals(Ratio::GoldenRatio->value, $golden->multiplier);
    }
}
