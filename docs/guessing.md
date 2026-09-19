# Guessing a scale

`ScaleGuesser` estimates a modular scale from at least two positive values.
It averages consecutive ratios, chooses a nearby named `Ratio` when one falls
within tolerance, and estimates a shared base.

```php
use Alto\Scale\ScaleGuesser;

$guesser = new ScaleGuesser(tolerance: 0.05);
$scale = $guesser->guess([15.9, 20.1, 24.8, 31.5]);

printf("Base: %.3f; ratio: %.2f\n", $scale->base, $scale->multiplier);
```

With Composer's autoloader loaded, this prints `Base: 15.995; ratio: 1.25`.
The inferred base is an estimate, not necessarily one of the original values.

Use the facade for the default tolerance:

```php
use Alto\Scale\Scale;

$scale = Scale::guess([16, 20, 25, 31.25]);
```

## Align the original values

```php
$aligned = $guesser->align([15.9, 20.1, 24.8, 31.5], $scale);
echo implode(', ', array_map(static fn (float $value): string => sprintf('%.2f', $value), $aligned));
```

After the facade example, `$scale` has base 16 and ratio 1.25, so this
prints `16.00, 20.00, 25.00, 31.25`.

`align()` snaps positive values and preserves zero or negative values. Omit
the second argument to infer the target scale from the same dataset.

Guessing is an estimate, not statistical model selection. Check the returned
base and ratio before making it part of a design-system contract.

If guessing fails, provide at least two positive values and check the
configured tolerance. Keep zeros and negative values out of the inferred
dataset; use a linear scale directly for progressions crossing zero.
