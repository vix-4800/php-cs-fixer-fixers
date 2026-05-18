<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedForeachKeyFixer;

final class RemoveUnusedForeachKeyFixerTest extends AbstractFixerTestCase
{
    public function testRemovesUnusedKey(): void
    {
        self::assertFixes(
            new RemoveUnusedForeachKeyFixer(),
            "<?php\nforeach (\$items as \$value) {\n    echo \$value;\n}\n",
            "<?php\nforeach (\$items as \$key => \$value) {\n    echo \$value;\n}\n",
        );
    }

    public function testKeepsUsedKey(): void
    {
        self::assertFixes(
            new RemoveUnusedForeachKeyFixer(),
            "<?php\nforeach (\$items as \$key => \$value) {\n    echo \$key . \$value;\n}\n",
            "<?php\nforeach (\$items as \$key => \$value) {\n    echo \$key . \$value;\n}\n",
        );
    }

    public function testKeepsDestructuredKey(): void
    {
        self::assertFixes(
            new RemoveUnusedForeachKeyFixer(),
            "<?php\nforeach (\$items as [\$id, \$type] => \$value) {\n    echo \$value;\n}\n",
            "<?php\nforeach (\$items as [\$id, \$type] => \$value) {\n    echo \$value;\n}\n",
        );
    }
}
