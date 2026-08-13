# ALTO Scale

ALTO Scale computes mathematical progressions for typography, spacing, grids,
and other design values. Every scale can return a step, find the nearest step,
snap an arbitrary value, and generate a range.

```php
use Alto\Scale\Scale;

$type = Scale::majorThird(16);

$type->get(-1);       // 12.8
$type->get(0);        // 16.0
$type->get(1);        // 20.0
Scale::majorThird(16)->snap(19.8); // 20.0
```

## Introduction

- [Installation](installation.md): install the package and verify the runtime.
- [Getting started](getting-started.md): create and use a first scale.

## Scales

- [All scales](scales/index.md): choose a progression for the values you need.
- [Modular](scales/modular.md): generate a geometric progression from a base and ratio.
- [Linear](scales/linear.md): generate values separated by a constant increment.
- [Fibonacci](scales/fibonacci.md): generate a scaled Fibonacci sequence.
- [Multi-strand](scales/multi-strand.md): interleave several modular progressions.

## Analysis

- [Guessing](guessing.md): infer a modular scale from existing positive values.
- [Linting](linting.md): audit values and align them to a scale.
