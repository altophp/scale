# Fibonacci scales

A Fibonacci scale generates the familiar integer sequence and applies an
optional positive multiplier.

```php
use Alto\Scale\Scale;

$sequence = Scale::fibonacci();

$values = Scale::fibonacci()->range(0, 8);
// [0.0, 1.0, 1.0, 2.0, 3.0, 5.0, 8.0, 13.0, 21.0]
```

Use a multiplier when the sequence represents a larger unit:

```php
$rhythm = Scale::fibonacci(multiplier: 4);

$rhythm->get(5);    // 20.0
$rhythm->stepOf(19); // 5
Scale::fibonacci(multiplier: 4)->snap(19); // 20.0
```

Negative steps follow the negafibonacci sequence. Lookup of a non-positive
value returns step zero.

The implementation uses Binet's formula and rounds to Fibonacci integers
before applying the multiplier. The multiplier must be positive.
