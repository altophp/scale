# Getting started

Create spacing tokens separated by eight units. After [installation](installation.md),
save this complete script as `spacing.php` beside `vendor/`:

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Alto\Scale\Scale;

$spacing = Scale::linear(base: 0, increment: 8);

printf("%g\n%g\n", $spacing->get(1), $spacing->get(3));
foreach ($spacing->range(1, 4) as $step => $value) {
    printf("%d: %g\n", $step, $value);
}
```

Run `php spacing.php`. It prints:

```text
8
24
1: 8
2: 16
3: 24
4: 32
```

## Align an existing value

Using the same `$spacing` object:

```php
printf("Step: %d; value: %g\n", $spacing->stepOf(30), $spacing->snap(30));
```

This prints `Step: 4; value: 32`. `get()` reads one step; `range()` includes
both endpoints and preserves step numbers as keys. Iterating a scale directly
yields steps 0 through 10.

Choose a progression from [Scales](scales.md). The common operations
do not imply identical domains: modular lookup needs positive values, while
linear spacing can cross zero. To work with an existing collection, continue
with [Guessing](guessing.md) or [Linting](linting.md).
