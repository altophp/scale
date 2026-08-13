# Linear scales

A linear scale adds the same increment at every step:

```text
value = base + step × increment
```

Use it for baseline grids, fixed spacing systems, and other arithmetic
progressions.

```php
use Alto\Scale\Scale;

$grid = Scale::linear(base: 0, increment: 8);

$values = Scale::linear(base: 0, increment: 8)->range(-1, 4);
// [-1 => -8.0, 0 => 0.0, 1 => 8.0, 2 => 16.0, 3 => 24.0, 4 => 32.0]
```

Unlike logarithmic scales, a linear scale can contain zero and negative
values.

```php
$grid->stepOf(19); // 2
$grid->snap(19);   // 16.0
```

Nearest-step lookup uses normal rounding. Values exactly between two steps
follow PHP's default half-up rounding.

The increment must be positive. An invalid increment raises `ScaleException`.
