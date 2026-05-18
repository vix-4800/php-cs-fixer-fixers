<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\BlankLineAfterStatementFixer;

final class BlankLineAfterStatementFixerTest extends AbstractFixerTestCase
{
    public function testAddsBlankLineAfterIfElseChain(): void
    {
        self::assertFixes(
            new BlankLineAfterStatementFixer(),
            "<?php\nif (\$enabled) {\n    enable();\n} else {\n    disable();\n}\n\nnextStep();\n",
            "<?php\nif (\$enabled) {\n    enable();\n} else {\n    disable();\n}\nnextStep();\n",
        );
    }

    public function testAddsBlankLineAfterConfiguredReturn(): void
    {
        self::assertFixes(
            new BlankLineAfterStatementFixer(),
            "<?php\nfunction value(): int\n{\n    if (ready()) {\n        return 1;\n\n        afterReturn();\n    }\n}\n",
            "<?php\nfunction value(): int\n{\n    if (ready()) {\n        return 1;\n        afterReturn();\n    }\n}\n",
            ['statements' => ['return']],
        );
    }

    public function testDoesNotAddBlankLineBeforeCloseBrace(): void
    {
        self::assertFixes(
            new BlankLineAfterStatementFixer(),
            "<?php\nif (\$enabled) {\n    run();\n}\n",
            "<?php\nif (\$enabled) {\n    run();\n}\n",
        );
    }
}
