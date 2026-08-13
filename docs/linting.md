# Linting values

`ScaleLinter` compares positive values with a supplied scale and reports the
nearest step, suggested value, absolute deviation, and validity.

```php
use Alto\Scale\Scale;
use Alto\Scale\ScaleLinter;

$linter = new ScaleLinter(
    scale: Scale::linear(0, 8),
    tolerance: 0.5,
);

$report = $linter->lint([8.0, 15.0, 24.0]);
```

Each report entry contains:

```php
[
    'original' => 15.0,
    'suggested' => 16.0,
    'step' => 2,
    'deviation' => 1.0,
    'isValid' => false,
];
```

Non-positive values are omitted from the report.

## Fix a collection

```php
$fixed = $linter->fix([8.0, 15.0, 24.0]);
// [8.0, 16.0, 24.0]
```

`fix()` preserves non-positive values and snaps every positive value. If no
scale is supplied to the constructor, the linter first uses `ScaleGuesser`
with the same tolerance.
