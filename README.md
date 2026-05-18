# PHP-CS-Fixer Fixers

Custom PHP-CS-Fixer fixers for strict development standards.

## Installation

```bash
composer require --dev vix/php-cs-fixer-fixers
```

## Usage

```php
<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use Vix\PhpCsFixerFixers\Fixer\NoYodaComparisonFixer;

$finder = Finder::create()->in(__DIR__ . '/src');

return (new Config())
    ->registerCustomFixers([
        new NoYodaComparisonFixer(),
    ])
    ->setRules([
        'CustomFixer/no_yoda_comparison' => true,
    ])
    ->setFinder($finder);
```

## Fixers

- `CustomFixer/blank_line_after_statement`
- `CustomFixer/catch_exception_to_throwable`
- `CustomFixer/fluent_chain_line_breaks`
- `CustomFixer/isset_coalesce`
- `CustomFixer/no_yoda_comparison`
- `CustomFixer/numeric_literal_separator`
- `CustomFixer/phpdoc_opening_line`
- `CustomFixer/phpdoc_self_reference`
- `CustomFixer/phpdoc_separate_throws`
- `CustomFixer/remove_doc_block_tags`
- `CustomFixer/remove_unused_catch_variable`
- `CustomFixer/remove_unused_foreach_key`
- `CustomFixer/require_null_safe_operator`

## Development

```bash
composer install
composer check
```
