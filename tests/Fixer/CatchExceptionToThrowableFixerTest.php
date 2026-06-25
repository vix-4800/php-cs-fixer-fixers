<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\CatchExceptionToThrowableFixer;

/**
 * @internal
 */
#[CoversClass(CatchExceptionToThrowableFixer::class)]
final class CatchExceptionToThrowableFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function isRisky(): void
    {
        $this->assertTrue(new CatchExceptionToThrowableFixer()->isRisky());
    }

    #[Test]
    public function convertsSimpleCatch(): void
    {
        self::assertFixes(
            new CatchExceptionToThrowableFixer(),
            "<?php\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
            "<?php\ntry {\n    risky();\n} catch (Exception \$e) {\n    report(\$e);\n}\n",
        );
    }

    #[Test]
    public function convertsImportedAlias(): void
    {
        self::assertFixes(
            new CatchExceptionToThrowableFixer(),
            "<?php\nuse Exception as E;\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable|RuntimeException \$e) {\n    report(\$e);\n}\n",
            "<?php\nuse Exception as E;\ntry {\n    risky();\n} catch (E|RuntimeException \$e) {\n    report(\$e);\n}\n",
        );
    }

    #[Test]
    public function keepsExistingThrowableCatch(): void
    {
        self::assertFixes(
            new CatchExceptionToThrowableFixer(),
            "<?php\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
            "<?php\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
        );
    }
}
