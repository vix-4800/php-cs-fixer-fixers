# Project Guidelines

## Scope

- This file is the project-wide instruction file for coding agents in this repository.
- Keep global agent guidance here. Do not add a second project-wide `copilot-instructions.md` unless this file is removed.

## Project Shape

- This repository is a PHP library of custom PHP-CS-Fixer fixers.
- Target runtime is PHP 8.4+; CI currently runs on PHP 8.4.
- Production code lives in `src/`. Fixer tests live in `tests/`.
- `src/Fixers.php` is the registry for all custom fixers.
- `tests/FixerSmokeTest.php` verifies fixer registration and rule-name wiring.

## Build And Test

- Install dependencies with `composer install`.
- Use `composer test` for PHPUnit.
- Use `composer static-analysis` for PHPStan.
- Use `composer check` when a change affects runtime behavior, public API, or fixer output.

## Conventions

- Match the existing repository style and keep diffs small.
- Keep `declare(strict_types=1);` in PHP files.
- Follow the rules already enforced by `.php-cs-fixer.php`, including short arrays, single quotes, ordered imports, and final classes where appropriate.
- Keep namespaces under `Vix\PhpCsFixerFixers\`.
- Preserve existing fixer names unless the task explicitly changes rule semantics.
- Prefer focused fixer implementations over new abstractions.

## Fixer Changes

- When adding a new fixer, register it in `src/Fixers.php`.
- When adding a new fixer, add or update the matching coverage in `tests/FixerSmokeTest.php`.
- For behavior changes, update the dedicated fixer test in `tests/Fixer/`.
- Tests should cover both transformation cases and no-op cases where relevant.

## Agent Expectations

- Read the touched files before editing.
- Fix root causes, not only symptoms.
- Do not refactor unrelated code.
- If you cannot run the relevant checks, say so explicitly in the final response.
