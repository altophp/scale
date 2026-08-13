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

use Alto\Scale\Ratio;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

#[CoversClass(Ratio::class)]
final class RatioTest extends TestCase
{
    #[Test]
    public function itResolvesEnumInstances(): void
    {
        $this->assertEquals(1.5, Ratio::resolve(Ratio::PerfectFifth));
        $this->assertEquals(2.0, Ratio::resolve(Ratio::Octave));
    }

    #[Test]
    public function itResolvesFloatValues(): void
    {
        $this->assertEquals(1.5, Ratio::resolve(1.5));
        $this->assertEquals(3.14, Ratio::resolve(3.14));
    }
}
