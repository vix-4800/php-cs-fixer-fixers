<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\Tokenizer\Tokens;
use PhpCsFixer\WhitespacesFixerConfig;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

abstract class AbstractFixerTestCase extends TestCase
{
    /**
     * @param array<string, mixed>|null $configuration
     */
    final protected static function assertFixes(
        AbstractFixer $fixer,
        string $expected,
        string $input,
        ?array $configuration = null,
    ): void {
        if ($fixer instanceof ConfigurableFixerInterface) {
            $fixer->configure($configuration ?? []);
        }

        if ($fixer instanceof WhitespacesAwareFixerInterface) {
            $fixer->setWhitespacesConfig(new WhitespacesFixerConfig('    ', "\n"));
        }

        $tokens = Tokens::fromCode($input);
        $fixer->fix(new SplFileInfo(__FILE__), $tokens);

        self::assertSame($expected, $tokens->generateCode());
    }
}
