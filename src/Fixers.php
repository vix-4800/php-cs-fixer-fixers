<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers;

use PhpCsFixer\Fixer\FixerInterface;
use Vix\PhpCsFixerFixers\Fixer\BlankLineAfterStatementFixer;
use Vix\PhpCsFixerFixers\Fixer\CatchExceptionToThrowableFixer;
use Vix\PhpCsFixerFixers\Fixer\FluentChainLineBreaksFixer;
use Vix\PhpCsFixerFixers\Fixer\IssetCoalesceFixer;
use Vix\PhpCsFixerFixers\Fixer\NumericLiteralSeparatorFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocOpeningLineFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSelfReferenceFixer;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSeparateThrowsFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedCatchVariableFixer;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedForeachKeyFixer;
use Vix\PhpCsFixerFixers\Fixer\RequireNullSafeOperatorFixer;

final class Fixers
{
    /**
     * @return list<FixerInterface>
     */
    public static function all(): array
    {
        return [
            new BlankLineAfterStatementFixer(),
            new CatchExceptionToThrowableFixer(),
            new FluentChainLineBreaksFixer(),
            new IssetCoalesceFixer(),
            new NumericLiteralSeparatorFixer(),
            new PhpDocOpeningLineFixer(),
            new PhpDocSelfReferenceFixer(),
            new PhpDocSeparateThrowsFixer(),
            new RemoveUnusedCatchVariableFixer(),
            new RemoveUnusedForeachKeyFixer(),
            new RequireNullSafeOperatorFixer(),
        ];
    }
}
