<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedForeachKeyFixer;

final class RemoveUnusedForeachKeyFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function removesUnusedKey(): void
    {
        self::assertFixes(
            new RemoveUnusedForeachKeyFixer(),
            "<?php\nforeach (\$items as \$value) {\n    echo \$value;\n}\n",
            "<?php\nforeach (\$items as \$key => \$value) {\n    echo \$value;\n}\n",
        );
    }

    #[Test]
    public function keepsUsedKey(): void
    {
        self::assertFixes(
            new RemoveUnusedForeachKeyFixer(),
            "<?php\nforeach (\$items as \$key => \$value) {\n    echo \$key . \$value;\n}\n",
            "<?php\nforeach (\$items as \$key => \$value) {\n    echo \$key . \$value;\n}\n",
        );
    }

    #[Test]
    public function keepsDestructuredKey(): void
    {
        self::assertFixes(
            new RemoveUnusedForeachKeyFixer(),
            "<?php\nforeach (\$items as [\$id, \$type] => \$value) {\n    echo \$value;\n}\n",
            "<?php\nforeach (\$items as [\$id, \$type] => \$value) {\n    echo \$value;\n}\n",
        );
    }
}
