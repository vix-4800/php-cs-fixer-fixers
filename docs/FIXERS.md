# Rules

Detailed documentation for all custom fixers shipped by this package.

Each section includes a short description, configuration parameters, and a minimal before/after example.

## Table of Contents

- [Rules](#rules)
  - [Table of Contents](#table-of-contents)
  - [VixFixer/blank\_line\_after\_statement](#vixfixerblank_line_after_statement)
  - [VixFixer/catch\_exception\_to\_throwable](#vixfixercatch_exception_to_throwable)
  - [VixFixer/fluent\_chain\_line\_breaks](#vixfixerfluent_chain_line_breaks)
  - [VixFixer/numeric\_literal\_separator](#vixfixernumeric_literal_separator)
  - [VixFixer/phpdoc\_opening\_line](#vixfixerphpdoc_opening_line)
  - [VixFixer/phpdoc\_self\_reference](#vixfixerphpdoc_self_reference)
  - [VixFixer/phpdoc\_separate\_throws](#vixfixerphpdoc_separate_throws)
  - [VixFixer/remove\_unused\_catch\_variable](#vixfixerremove_unused_catch_variable)
  - [VixFixer/remove\_unused\_foreach\_key](#vixfixerremove_unused_foreach_key)

## VixFixer/blank_line_after_statement

Adds a blank line after selected control structures to make the next statement easier to scan.

Parameters:

- `statements`: `list<string>`, default `['do', 'for', 'foreach', 'if', 'switch', 'try', 'while']`

Before:

```php
if ($enabled) {
    enableFeature();
}
nextStep();
```

After:

```php
if ($enabled) {
    enableFeature();
}

nextStep();
```

## VixFixer/catch_exception_to_throwable

Replaces `Exception` in `catch` clauses with `Throwable` so the handler covers all throwable errors.

Parameters:

- None.
- Risky: yes.

Before:

```php
try {
    runTask();
} catch (Exception $exception) {
    report($exception);
}
```

After:

```php
try {
    runTask();
} catch (Throwable $exception) {
    report($exception);
}
```

## VixFixer/fluent_chain_line_breaks

Normalizes multi-line fluent chains so every chained call starts on its own line with consistent indentation.

Parameters:

- None.

Before:

```php
$users = User::query()
    ->where('active', true)->orderBy('id')->limit(10)->get();
```

After:

```php
$users = User::query()
    ->where('active', true)
    ->orderBy('id')
    ->limit(10)
    ->get();
```

## VixFixer/numeric_literal_separator

Adds underscore separators to long numeric literals to improve readability.

Parameters:

- `min_digits`: `int`, default `5`

Before:

```php
$population = 1234567;
```

After:

```php
$population = 1_234_567;
```

## VixFixer/phpdoc_opening_line

For multi-line PHPDoc blocks, moves the opening `/**` onto its own line.

Parameters:

- None.

Before:

```php
/** Summary.
 * More details.
 */
```

After:

```php
/**
 * Summary.
 * More details.
 */
```

## VixFixer/phpdoc_self_reference

Inside a class PHPDoc, replaces references to the current class short name with `self`.

Parameters:

- None.

Before:

```php
final class Report
{
    /** @var list<Report> */
    private array $children = [];
}
```

After:

```php
final class Report
{
    /** @var list<self> */
    private array $children = [];
}
```

## VixFixer/phpdoc_separate_throws

Splits a union `@throws` annotation into one `@throws` tag per exception type.

Parameters:

- None.

Before:

```php
/**
 * @throws RuntimeException|LogicException When validation fails.
 */
```

After:

```php
/**
 * @throws RuntimeException When validation fails.
 * @throws LogicException When validation fails.
 */
```

## VixFixer/remove_unused_catch_variable

Drops an unused exception variable from a `catch` block and uses the non-capturing PHP syntax instead.

Parameters:

- None.

Before:

```php
try {
    runTask();
} catch (Throwable $exception) {
    return false;
}
```

After:

```php
try {
    runTask();
} catch (Throwable) {
    return false;
}
```

## VixFixer/remove_unused_foreach_key

Removes an unused key variable from a `foreach` loop when only the value is used.

Parameters:

- None.

Before:

```php
foreach ($items as $index => $item) {
    echo $item;
}
```

After:

```php
foreach ($items as $item) {
    echo $item;
}
```
