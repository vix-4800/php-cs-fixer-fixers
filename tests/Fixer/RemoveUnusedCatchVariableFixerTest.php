<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\RemoveUnusedCatchVariableFixer;

/**
 * @internal
 */
#[CoversClass(RemoveUnusedCatchVariableFixer::class)]
final class RemoveUnusedCatchVariableFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function removesUnusedVariable(): void
    {
        self::assertFixes(
            new RemoveUnusedCatchVariableFixer(),
            "<?php\ntry {\n    risky();\n} catch (Throwable) {\n    return false;\n}\n",
            "<?php\ntry {\n    risky();\n} catch (Throwable \$e) {\n    return false;\n}\n",
        );
    }

    #[Test]
    public function keepsUsedVariable(): void
    {
        self::assertFixes(
            new RemoveUnusedCatchVariableFixer(),
            "<?php\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
            "<?php\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
        );
    }
}
