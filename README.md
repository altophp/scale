# ALTO Scale

Strict mathematical scales for typography, spacing, grids, and rhythmic design systems.

&nbsp; ![PHP Version](https://img.shields.io/badge/PHP-8.4%2B-00B7FF?logoColor=00B7FF&labelColor=050608)
&nbsp; ![CI](https://img.shields.io/github/actions/workflow/status/altophp/scale/CI.yml?branch=main&label=Tests&labelColor=050608&color=00B7FF)
&nbsp; [![Packagist](https://img.shields.io/packagist/v/alto/scale?label=Packagist&labelColor=050608&color=00B7FF)](https://packagist.org/packages/alto/scale)
&nbsp; ![License](https://img.shields.io/github/license/altophp/scale?label=License&labelColor=050608&color=00B7FF)
&nbsp; [![GitHub Sponsors](https://img.shields.io/github/sponsors/smnandre?logo=githubsponsors&logoColor=00B7FF&label=%20Sponsor&labelColor=050608&color=00B7FF)](https://github.com/sponsors/smnandre)

ALTO Scale turns a mathematical progression into predictable design values. Build modular,
linear, Fibonacci, or multi-strand scales through one small API, then generate ranges, snap
arbitrary values, infer an existing scale, or lint a collection.

```php
use Alto\Scale\Scale;

$type = Scale::majorThird(16);

$type->range(-1, 2);
// [-1 => 12.8, 0 => 16.0, 1 => 20.0, 2 => 25.0]
```

The package has no runtime dependencies. Every scale implements the same typed interface and the
codebase is checked at PHPStan's maximum level.

## Installation

Install ALTO Scale with Composer:

```bash
composer require alto/scale
```

ALTO Scale requires PHP 8.4 or later.

## Quick Start

Create a scale through the `Scale` facade and request the values needed by your design system:

```php
use Alto\Scale\Scale;

$spacing = Scale::linear(base: 0, increment: 8);

echo $spacing->get(3);  // 24
echo $spacing->snap(19); // 16
```

All scales expose `get()`, `stepOf()`, `snap()`, and `range()` and can be iterated over steps zero
through ten.

## Scale Types

| Scale | Progression | Typical use |
| --- | --- | --- |
| Modular | Multiply by one ratio | Type sizes and proportional spacing |
| Linear | Add one increment | Baseline grids and fixed spacing |
| Fibonacci | Follow Fibonacci numbers | Integer rhythms and counts |
| Multi-strand | Interleave modular scales | Multiple coordinated bases |

Named constructors provide common ratios:

```php
$type = Scale::majorThird(16);
$display = Scale::perfectFifth(48);
$golden = Scale::golden(1);
```

Custom ratios are available through `Scale::modular()`. See the
[scale guide](docs/scales/index.md) for every progression and its constraints.

## Guessing

Infer a modular scale from existing positive values and align the originals to it:

```php
use Alto\Scale\ScaleGuesser;

$values = [15.9, 20.1, 24.8, 31.5];
$guesser = new ScaleGuesser(tolerance: 0.05);

$scale = $guesser->guess($values);
$aligned = $guesser->align($values, $scale);
```

Read [Guessing a scale](docs/guessing.md) for the estimation rules and limitations.

## Linting

Audit values against a known scale and normalize deviations:

```php
use Alto\Scale\Scale;
use Alto\Scale\ScaleLinter;

$linter = new ScaleLinter(Scale::linear(0, 8));

$report = $linter->lint([8, 15, 24]);
$fixed = $linter->fix([8, 15, 24]); // [8.0, 16.0, 24.0]
```

Read [Linting values](docs/linting.md) for the report format and inferred-scale behavior. The
[complete documentation](docs/index.md) also covers installation, the shared API, and each scale
type.

## Contributing

Contributions of all kinds are welcome. Visit the
[project on GitHub](https://github.com/altophp/scale) to
[report a bug](https://github.com/altophp/scale/issues/new),
[suggest a feature](https://github.com/altophp/scale/issues/new), or
[open a pull request](https://github.com/altophp/scale/pulls).

Before submitting code, run:

```bash
# Runs PHP CS Fixer, PHPStan, and PHPUnit
composer qa
```

Changes to public behavior should include tests and documentation.

## Support

ALTO Scale is open source. You can support its continued development through
[GitHub Sponsors](https://github.com/sponsors/smnandre).

Sharing this package with others or
[starring it on GitHub](https://github.com/altophp/scale) is also much
appreciated.

## License

ALTO Scale is released by [ALTO PHP](https://altophp.com) under the
[MIT License](LICENSE).
