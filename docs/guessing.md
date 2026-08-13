# Guessing a scale

`ScaleGuesser` estimates a modular scale from at least two positive values.
It averages consecutive ratios, chooses a nearby named `Ratio` when one falls
within tolerance, and estimates a shared base.

```php
use Alto\Scale\ScaleGuesser;

$guesser = new ScaleGuesser(tolerance: 0.05);
$scale = $guesser->guess([15.9, 20.1, 24.8, 31.5]);

echo $scale->base;
echo $scale->multiplier;
```

Use the facade for the default tolerance:

```php
use Alto\Scale\Scale;

$scale = Scale::guess([16, 20, 25, 31.25]);
```

## Align the original values

```php
$aligned = $guesser->align([15.9, 20.1, 24.8, 31.5], $scale);
```

`align()` snaps positive values and preserves zero or negative values. Omit
the second argument to infer the target scale from the same dataset.

Guessing is an estimate, not statistical model selection. Check the returned
base and ratio before making it part of a design-system contract.
