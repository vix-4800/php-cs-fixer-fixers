<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\CatchExceptionToThrowableFixer;

final class CatchExceptionToThrowableFixerTest extends AbstractFixerTestCase
{
    public function testIsRisky(): void
    {
        self::assertTrue((new CatchExceptionToThrowableFixer())->isRisky());
    }

    public function testConvertsSimpleCatch(): void
    {
        self::assertFixes(
            new CatchExceptionToThrowableFixer(),
            "<?php\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
            "<?php\ntry {\n    risky();\n} catch (Exception \$e) {\n    report(\$e);\n}\n",
        );
    }

    public function testConvertsImportedAlias(): void
    {
        self::assertFixes(
            new CatchExceptionToThrowableFixer(),
            "<?php\nuse Exception as E;\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable|RuntimeException \$e) {\n    report(\$e);\n}\n",
            "<?php\nuse Exception as E;\ntry {\n    risky();\n} catch (E|RuntimeException \$e) {\n    report(\$e);\n}\n",
        );
    }

    public function testKeepsExistingThrowableCatch(): void
    {
        self::assertFixes(
            new CatchExceptionToThrowableFixer(),
            "<?php\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
            "<?php\nuse Throwable;\ntry {\n    risky();\n} catch (Throwable \$e) {\n    report(\$e);\n}\n",
        );
    }
}
