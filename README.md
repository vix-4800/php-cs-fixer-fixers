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
use Vix\PhpCsFixerFixers\Fixers;

$finder = Finder::create()->in(__DIR__ . '/src');

return (new Config())
    ->registerCustomFixers(Fixers::all())
    ->setRules([
        'VixFixer/no_yoda_comparison' => true,
    ])
    ->setFinder($finder);
```

## Fixers

- `VixFixer/blank_line_after_statement`
- `VixFixer/catch_exception_to_throwable`
- `VixFixer/fluent_chain_line_breaks`
- `VixFixer/isset_coalesce`
- `VixFixer/no_yoda_comparison`
- `VixFixer/numeric_literal_separator`
- `VixFixer/phpdoc_opening_line`
- `VixFixer/phpdoc_self_reference`
- `VixFixer/phpdoc_separate_throws`
- `VixFixer/remove_doc_block_tags`
- `VixFixer/remove_unused_catch_variable`
- `VixFixer/remove_unused_foreach_key`
- `VixFixer/require_null_safe_operator`

## Development

```bash
composer install
composer check
```
