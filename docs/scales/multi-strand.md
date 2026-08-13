# Multi-strand scales

A multi-strand scale interleaves several positive bases that share one modular
ratio. Use it when two or more families of values must grow together without
collapsing into one progression.

```php
use Alto\Scale\Ratio;
use Alto\Scale\Scale;

$scale = Scale::strands(
    bases: [12, 16],
    ratio: Ratio::PerfectFifth,
);

$scale->range(0, 5);
// [12.0, 16.0, 18.0, 24.0, 27.0, 36.0]
```

The bases are filtered to positive values, deduplicated, and sorted before
steps are assigned. Their original array order is therefore not significant.

```php
$scale->stepOf(25); // 3
$scale->snap(25);   // 24.0
```

The ratio must be greater than `1.0`, and at least one positive base must
remain. Invalid input raises `ScaleException`.
