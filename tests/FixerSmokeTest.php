<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\WhitespacesFixerConfig;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Vix\PhpCsFixerFixers\Fixer\BlankLineAfterStatementFixer;
use Vix\PhpCsFixerFixers\Fixer\CatchExceptionToThrowableFixer;
use Vix\PhpCsFixerFixers\Fixer\FluentChainLineBreaksFixer;
use Vix\PhpCsFixerFixers\Fixer\NumericLiteralSeparatorFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocOpeningLineFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSelfReferenceFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSeparateThrowsFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedCatchVariableFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedForeachKeyFixer;
use Vix\PhpCsFixerFixers\Fixers;

final class FixerSmokeTest extends TestCase
{
    /**
     * @return iterable<string, array{AbstractFixer, string, bool}>
     */
    public static function fixerProvider(): iterable
    {
        yield 'blank_line_after_statement' => [new BlankLineAfterStatementFixer(), 'VixFixer/blank_line_after_statement', false];
        yield 'catch_exception_to_throwable' => [new CatchExceptionToThrowableFixer(), 'VixFixer/catch_exception_to_throwable', true];
        yield 'fluent_chain_line_breaks' => [new FluentChainLineBreaksFixer(), 'VixFixer/fluent_chain_line_breaks', false];
        yield 'numeric_literal_separator' => [new NumericLiteralSeparatorFixer(), 'VixFixer/numeric_literal_separator', false];
        yield 'phpdoc_opening_line' => [new PhpDocOpeningLineFixer(), 'VixFixer/phpdoc_opening_line', false];
        yield 'phpdoc_self_reference' => [new PhpDocSelfReferenceFixer(), 'VixFixer/phpdoc_self_reference', false];
        yield 'phpdoc_separate_throws' => [new PhpDocSeparateThrowsFixer(), 'VixFixer/phpdoc_separate_throws', false];
        yield 'remove_unused_catch_variable' => [new RemoveUnusedCatchVariableFixer(), 'VixFixer/remove_unused_catch_variable', false];
        yield 'remove_unused_foreach_key' => [new RemoveUnusedForeachKeyFixer(), 'VixFixer/remove_unused_foreach_key', false];
    }

    #[DataProvider('fixerProvider')]
    #[Test]
    public function fixerCanBeRegistered(AbstractFixer $fixer, string $ruleName, bool $isRisky): void
    {
        if ($fixer instanceof ConfigurableFixerInterface) {
            $fixer->configure([]);
        }

        if ($fixer instanceof WhitespacesAwareFixerInterface) {
            $fixer->setWhitespacesConfig(new WhitespacesFixerConfig('    ', "\n"));
        }

        $this->assertSame($ruleName, $fixer->getName());
        $this->assertNotSame('', $fixer->getDefinition()->getSummary());
        $this->assertGreaterThanOrEqual(-100, $fixer->getPriority());
        $this->assertSame($isRisky, $fixer->isRisky());
    }

    #[Test]
    public function allFixersCanBeUsedAsVixFixerRules(): void
    {
        $rules = [];

        foreach (Fixers::all() as $fixer) {
            $rules[$fixer->getName()] = true;
        }

        $factory = new FixerFactory();
        $factory->registerBuiltInFixers();
        $factory->registerCustomFixers(Fixers::all());
        $factory->useRuleSet(new RuleSet($rules));

        $this->assertCount(count($rules), $factory->getFixers());
    }
}
