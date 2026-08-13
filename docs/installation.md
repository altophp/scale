# Installation

ALTO Scale requires PHP 8.4 or later and has no runtime dependencies.

```bash
composer require alto/scale
```

## Verify the installation

```php
<?php

require __DIR__.'/vendor/autoload.php';

use Alto\Scale\Scale;

$scale = Scale::linear(base: 0, increment: 8);

echo $scale->get(3);
```

The script prints `24`.

Invalid bases, ratios, increments, ranges, or datasets raise
`Alto\Scale\Exception\ScaleException`.
