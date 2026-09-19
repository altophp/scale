# Alto Scale

Alto Scale turns mathematical progressions into predictable values for
typography, spacing, grids, and rhythmic design systems.

```php
use Alto\Scale\Scale;

$type = Scale::majorThird(16);
echo $type->snap(19.8);
```

The result is `20`. Every scale can return a step, find the nearest step, snap
an arbitrary value, and generate a keyed range. Modular, linear, Fibonacci,
and multi-strand progressions keep their own domain and inverse behavior.

## Documentation

- [Installation](installation.md)
- [Getting started](getting-started.md)
- [Scales](scales.md)
- [Guessing](guessing.md)
- [Linting](linting.md)
