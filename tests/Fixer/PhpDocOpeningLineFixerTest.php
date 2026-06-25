<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\PhpDocOpeningLineFixer;

/**
 * @internal
 */
#[CoversClass(PhpDocOpeningLineFixer::class)]
final class PhpDocOpeningLineFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function splitsInlineOpeningText(): void
    {
        self::assertFixes(
            new PhpDocOpeningLineFixer(),
            "<?php\n/**\n * Summary.\n * Details.\n */\nfunction foo(): void {}\n",
            "<?php\n/** Summary.\n * Details.\n */\nfunction foo(): void {}\n",
        );
    }

    #[Test]
    public function keepsSingleLineDocblockUnchanged(): void
    {
        self::assertFixes(
            new PhpDocOpeningLineFixer(),
            "<?php\n/** @var string \$name */\n\$name = 'x';\n",
            "<?php\n/** @var string \$name */\n\$name = 'x';\n",
        );
    }

    #[Test]
    public function keepsCorrectMultilineDocblockUnchanged(): void
    {
        self::assertFixes(
            new PhpDocOpeningLineFixer(),
            "<?php\n/**\n * Summary.\n */\nfunction foo(): void {}\n",
            "<?php\n/**\n * Summary.\n */\nfunction foo(): void {}\n",
        );
    }
}
