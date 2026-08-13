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
use Alto\Scale\LinearScale;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(LinearScale::class)]
final class LinearScaleTest extends TestCase
{
    #[Test]
    public function itCalculatesArithmeticSteps(): void
    {
        $scale = new LinearScale(4.0, 8.0);
        $this->assertEquals(4.0, $scale->get(0));
        $this->assertEquals(12.0, $scale->get(1));
        $this->assertEquals(20.0, $scale->get(2));
    }

    #[Test]
    public function itSnapsToNearestIncrement(): void
    {
        $scale = new LinearScale(0.0, 10.0);
        $this->assertEquals(10.0, $scale->snap(7.0));
        $this->assertEquals(10.0, $scale->snap(14.0));
        $this->assertEquals(20.0, $scale->snap(16.0));
    }

    #[Test]
    public function itCalculatesStepOfValue(): void
    {
        $scale = new LinearScale(10.0, 5.0);
        $this->assertSame(0, $scale->stepOf(10.0));
        $this->assertSame(2, $scale->stepOf(20.0));
        $this->assertSame(2, $scale->stepOf(18.0));
    }

    #[Test]
    public function itThrowsOnZeroIncrement(): void
    {
        $this->expectException(ScaleException::class);
        $this->expectExceptionMessage('Increment must be positive.');
        new LinearScale(0.0, 0.0);
    }
}
