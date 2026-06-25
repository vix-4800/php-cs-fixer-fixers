<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\BlankLineAfterStatementFixer;

/**
 * @internal
 */
#[CoversClass(BlankLineAfterStatementFixer::class)]
final class BlankLineAfterStatementFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function addsBlankLineAfterIfElseChain(): void
    {
        self::assertFixes(
            new BlankLineAfterStatementFixer(),
            "<?php\nif (\$enabled) {\n    enable();\n} else {\n    disable();\n}\n\nnextStep();\n",
            "<?php\nif (\$enabled) {\n    enable();\n} else {\n    disable();\n}\nnextStep();\n",
        );
    }

    #[Test]
    public function addsBlankLineAfterConfiguredReturn(): void
    {
        self::assertFixes(
            new BlankLineAfterStatementFixer(),
            "<?php\nfunction value(): int\n{\n    if (ready()) {\n        return 1;\n\n        afterReturn();\n    }\n}\n",
            "<?php\nfunction value(): int\n{\n    if (ready()) {\n        return 1;\n        afterReturn();\n    }\n}\n",
            ['statements' => ['return']],
        );
    }

    #[Test]
    public function doesNotAddBlankLineBeforeCloseBrace(): void
    {
        self::assertFixes(
            new BlankLineAfterStatementFixer(),
            "<?php\nif (\$enabled) {\n    run();\n}\n",
            "<?php\nif (\$enabled) {\n    run();\n}\n",
        );
    }
}
