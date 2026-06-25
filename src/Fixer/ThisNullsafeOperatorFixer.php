<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Fixer;

use Override;
use PhpCsFixer\AbstractFixer;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Token;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;
use Vix\PhpCsFixerFixers\Tests\Fixer\ThisNullsafeOperatorFixerTest;

/**
 * @see ThisNullsafeOperatorFixerTest
 */
final class ThisNullsafeOperatorFixer extends AbstractFixer
{
    #[Override]
    public function getName(): string
    {
        return 'VixFixer/this_nullsafe_operator';
    }

    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Replaces useless nullsafe operators on $this with regular object operators.',
            [
                new CodeSample(
                    "<?php\n\$this?->foo();\n",
                ),
            ],
            '$this is never null, so nullsafe access is redundant.',
        );
    }

    /**
     * @param Tokens<Token> $tokens
     */
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isTokenKindFound(T_NULLSAFE_OBJECT_OPERATOR);
    }

    #[Override]
    public function getPriority(): int
    {
        return 0;
    }

    /**
     * @param SplFileInfo   $file
     * @param Tokens<Token> $tokens
     */
    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        for ($index = $tokens->count() - 1; $index >= 0; --$index) {
            if (!$tokens[$index]->isGivenKind(T_NULLSAFE_OBJECT_OPERATOR)) {
                continue;
            }

            $previousIndex = $tokens->getPrevMeaningfulToken($index);

            if ($previousIndex === null) {
                continue;
            }

            if (!$tokens[$previousIndex]->equals([T_VARIABLE, '$this'])) {
                continue;
            }

            $tokens[$index] = new Token([T_OBJECT_OPERATOR, '->']);
        }
    }
}
