<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\PhpDocOpeningLineFixer;

final class PhpDocOpeningLineFixerTest extends AbstractFixerTestCase
{
    public function testSplitsInlineOpeningText(): void
    {
        self::assertFixes(
            new PhpDocOpeningLineFixer(),
            "<?php\n/**\n * Summary.\n * Details.\n */\nfunction foo(): void {}\n",
            "<?php\n/** Summary.\n * Details.\n */\nfunction foo(): void {}\n",
        );
    }

    public function testKeepsSingleLineDocblockUnchanged(): void
    {
        self::assertFixes(
            new PhpDocOpeningLineFixer(),
            "<?php\n/** @var string \$name */\n\$name = 'x';\n",
            "<?php\n/** @var string \$name */\n\$name = 'x';\n",
        );
    }

    public function testKeepsCorrectMultilineDocblockUnchanged(): void
    {
        self::assertFixes(
            new PhpDocOpeningLineFixer(),
            "<?php\n/**\n * Summary.\n */\nfunction foo(): void {}\n",
            "<?php\n/**\n * Summary.\n */\nfunction foo(): void {}\n",
        );
    }
}
