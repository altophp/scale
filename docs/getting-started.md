# Getting started

Create a scale through the `Scale` facade, then ask it for the values required
by the current design system.

```php
use Alto\Scale\Scale;

$spacing = Scale::linear(base: 0, increment: 8);

echo $spacing->get(1); // 8
echo $spacing->get(3); // 24
```

All scales share four operations:

```php
$spacing->get(4);        // 32.0: value at step 4
$spacing->stepOf(30);    // 4: nearest step to 30
$spacing->snap(30);      // 32.0: nearest scale value
$spacing->range(1, 4);   // [1 => 8.0, 2 => 16.0, 3 => 24.0, 4 => 32.0]
```

Scales are also iterable. Iteration yields steps 0 through 10:

```php
foreach ($spacing as $step => $value) {
    printf("%d: %g\n", $step, $value);
}
```

Choose a progression from [All scales](scales/index.md). If values already
exist, use [Guessing](guessing.md) or [Linting](linting.md).
