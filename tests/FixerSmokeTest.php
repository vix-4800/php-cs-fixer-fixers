<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerFactory;
use PhpCsFixer\RuleSet\RuleSet;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SplFileInfo;
use Vix\PhpCsFixerFixers\Fixer\BlankLineAfterStatementFixer;
use Vix\PhpCsFixerFixers\Fixer\CatchExceptionToThrowableFixer;
use Vix\PhpCsFixerFixers\Fixer\FluentChainLineBreaksFixer;
use Vix\PhpCsFixerFixers\Fixer\IssetCoalesceFixer;
use Vix\PhpCsFixerFixers\Fixer\NoYodaComparisonFixer;
use Vix\PhpCsFixerFixers\Fixer\NumericLiteralSeparatorFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocOpeningLineFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSelfReferenceFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSeparateThrowsFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedCatchVariableFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedForeachKeyFixer;
use Vix\PhpCsFixerFixers\Fixer\RequireNullSafeOperatorFixer;
use Vix\PhpCsFixerFixers\Fixers;

final class FixerSmokeTest extends TestCase
{
    /**
     * @return iterable<string, array{AbstractFixer, string}>
     */
    public static function fixerProvider(): iterable
    {
        yield 'blank_line_after_statement' => [new BlankLineAfterStatementFixer(), 'VixFixer/blank_line_after_statement'];
        yield 'catch_exception_to_throwable' => [new CatchExceptionToThrowableFixer(), 'VixFixer/catch_exception_to_throwable'];
        yield 'fluent_chain_line_breaks' => [new FluentChainLineBreaksFixer(), 'VixFixer/fluent_chain_line_breaks'];
        yield 'isset_coalesce' => [new IssetCoalesceFixer(), 'VixFixer/isset_coalesce'];
        yield 'no_yoda_comparison' => [new NoYodaComparisonFixer(), 'VixFixer/no_yoda_comparison'];
        yield 'numeric_literal_separator' => [new NumericLiteralSeparatorFixer(), 'VixFixer/numeric_literal_separator'];
        yield 'phpdoc_opening_line' => [new PhpDocOpeningLineFixer(), 'VixFixer/phpdoc_opening_line'];
        yield 'phpdoc_self_reference' => [new PhpDocSelfReferenceFixer(), 'VixFixer/phpdoc_self_reference'];
        yield 'phpdoc_separate_throws' => [new PhpDocSeparateThrowsFixer(), 'VixFixer/phpdoc_separate_throws'];
        yield 'remove_unused_catch_variable' => [new RemoveUnusedCatchVariableFixer(), 'VixFixer/remove_unused_catch_variable'];
        yield 'remove_unused_foreach_key' => [new RemoveUnusedForeachKeyFixer(), 'VixFixer/remove_unused_foreach_key'];
        yield 'require_null_safe_operator' => [new RequireNullSafeOperatorFixer(), 'VixFixer/require_null_safe_operator'];
    }

    #[DataProvider('fixerProvider')]
    public function testFixerCanBeRegistered(AbstractFixer $fixer, string $ruleName): void
    {
        if ($fixer instanceof ConfigurableFixerInterface) {
            $fixer->configure([]);
        }

        if ($fixer instanceof WhitespacesAwareFixerInterface) {
            $fixer->setWhitespacesConfig(new WhitespacesFixerConfig('    ', "\n"));
        }

        self::assertSame($ruleName, $fixer->getName());
        self::assertNotSame('', $fixer->getDefinition()->getSummary());
        self::assertGreaterThanOrEqual(-100, $fixer->getPriority());
    }

    public function testNoYodaComparisonFixerAppliesFix(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif (\$value === null) {}\n",
            "<?php\nif (null === \$value) {}\n",
        );
    }

    public function testAllFixersCanBeUsedAsVixFixerRules(): void
    {
        $rules = [];

        foreach (Fixers::all() as $fixer) {
            $rules[$fixer->getName()] = true;
        }

        $factory = new FixerFactory();
        $factory->registerBuiltInFixers();
        $factory->registerCustomFixers(Fixers::all());
        $factory->useRuleSet(new RuleSet($rules));

        self::assertCount(count($rules), $factory->getFixers());
    }

    private static function assertFixes(AbstractFixer $fixer, string $expected, string $input): void
    {
        $tokens = Tokens::fromCode($input);
        $fixer->fix(new SplFileInfo(__FILE__), $tokens);

        self::assertSame($expected, $tokens->generateCode());
    }
}
