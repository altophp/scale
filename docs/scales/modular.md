# Modular scales

A modular scale is a geometric progression:

```text
value = base × ratio ^ step
```

Use it when each step should be proportionally larger or smaller than the
previous one.

```php
use Alto\Scale\Ratio;
use Alto\Scale\Scale;

$type = Scale::modular(base: 16, ratio: Ratio::MajorThird);

$values = Scale::modular(base: 16, ratio: Ratio::MajorThird)->range(-2, 3);
// [10.24, 12.8, 16.0, 20.0, 25.0, 31.25], keyed from -2 to 3
```

Named constructors cover common ratios:

```php
$majorThird = Scale::majorThird(16);
$perfectFifth = Scale::perfectFifth(16);
$golden = Scale::golden(16);
```

`Ratio` includes thirteen established musical and typographic ratios from
`MinorSecond` (`1.067`) through `Octave` (`2.0`). A positive custom float is
also accepted.

## Derive another scale

Modular scales are immutable:

```php
$compact = $type->withBase(14);
$wider = $type->withRatio(Ratio::PerfectFifth);
$shifted = $type->shift(2);
```

`shift(2)` returns an equivalent scale whose base is the original step two.
The object is callable, so `$type(2)` is equivalent to `$type->get(2)`.

## Compare values

```php
$type->areHarmonic(16, 25);   // true
$type->areHarmonic(16, 24);   // false
```

The method checks whether the logarithmic distance between two positive values
is a whole number of scale steps within the supplied epsilon.
