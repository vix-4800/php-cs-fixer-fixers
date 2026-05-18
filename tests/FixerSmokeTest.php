<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
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
use Vix\PhpCsFixerFixers\Fixer\RemoveDocBlockTagsFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedCatchVariableFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedForeachKeyFixer;
use Vix\PhpCsFixerFixers\Fixer\RequireNullSafeOperatorFixer;

final class FixerSmokeTest extends TestCase
{
    /**
     * @return iterable<string, array{AbstractFixer, string}>
     */
    public static function fixerProvider(): iterable
    {
        yield 'blank_line_after_statement' => [new BlankLineAfterStatementFixer(), 'CustomFixer/blank_line_after_statement'];
        yield 'catch_exception_to_throwable' => [new CatchExceptionToThrowableFixer(), 'CustomFixer/catch_exception_to_throwable'];
        yield 'fluent_chain_line_breaks' => [new FluentChainLineBreaksFixer(), 'CustomFixer/fluent_chain_line_breaks'];
        yield 'isset_coalesce' => [new IssetCoalesceFixer(), 'CustomFixer/isset_coalesce'];
        yield 'no_yoda_comparison' => [new NoYodaComparisonFixer(), 'CustomFixer/no_yoda_comparison'];
        yield 'numeric_literal_separator' => [new NumericLiteralSeparatorFixer(), 'CustomFixer/numeric_literal_separator'];
        yield 'phpdoc_opening_line' => [new PhpDocOpeningLineFixer(), 'CustomFixer/phpdoc_opening_line'];
        yield 'phpdoc_self_reference' => [new PhpDocSelfReferenceFixer(), 'CustomFixer/phpdoc_self_reference'];
        yield 'phpdoc_separate_throws' => [new PhpDocSeparateThrowsFixer(), 'CustomFixer/phpdoc_separate_throws'];
        yield 'remove_doc_block_tags' => [new RemoveDocBlockTagsFixer(), 'CustomFixer/remove_doc_block_tags'];
        yield 'remove_unused_catch_variable' => [new RemoveUnusedCatchVariableFixer(), 'CustomFixer/remove_unused_catch_variable'];
        yield 'remove_unused_foreach_key' => [new RemoveUnusedForeachKeyFixer(), 'CustomFixer/remove_unused_foreach_key'];
        yield 'require_null_safe_operator' => [new RequireNullSafeOperatorFixer(), 'CustomFixer/require_null_safe_operator'];
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

    private static function assertFixes(AbstractFixer $fixer, string $expected, string $input): void
    {
        $tokens = Tokens::fromCode($input);
        $fixer->fix(new SplFileInfo(__FILE__), $tokens);

        self::assertSame($expected, $tokens->generateCode());
    }
}
