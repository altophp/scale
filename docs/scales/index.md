# Scale types

Choose the progression according to the relationship between consecutive
values.

| Scale | Relationship | Typical use |
| --- | --- | --- |
| [Modular](modular.md) | Multiply by one ratio | Type sizes, proportional spacing |
| [Linear](linear.md) | Add one increment | Baseline grids, fixed spacing |
| [Fibonacci](fibonacci.md) | Follow Fibonacci numbers | Integer rhythms and counts |
| [Multi-strand](multi-strand.md) | Interleave several modular scales | Multiple coordinated bases |

Every type implements `ScaleInterface`, so application code can accept any
scale while using `get()`, `stepOf()`, `snap()`, and `range()`.

```php
use Alto\Scale\ScaleInterface;

function tokens(ScaleInterface $scale): array
{
    return $scale->range(-2, 5);
}
```

The shared contract does not imply identical domains. Modular and multi-strand
scales require positive values for logarithmic lookup. Linear scales can cross
zero. Fibonacci lookup maps non-positive values to step zero.
