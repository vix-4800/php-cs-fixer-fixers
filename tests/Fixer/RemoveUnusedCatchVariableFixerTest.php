<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedCatchVariableFixer;

final class RemoveUnusedCatchVariableFixerTest extends AbstractFixerTestCase
{
    public function testRemovesUnusedVariable(): void
    {
        self::assertFixes(
            new RemoveUnusedCatchVariableFixer(),
            "<?php\ntry {\n    risky();\n} catch (Throwable) {\n    return false;\n}\n",
            "<?php\ntry {\n    risky();\n} catch (Throwable \$e) {\n    return false;\n}\n",
        );
    }

    public function testKeepsUsedVariable(): void
    {
        self::assertFixes(
            new RemoveUnusedCatchVariableFixer(),
            "<?php\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
            "<?php\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
        );
    }
}
