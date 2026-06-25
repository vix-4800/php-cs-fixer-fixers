<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\ThisNullsafeOperatorFixer;

/**
 * @internal
 */
#[CoversClass(ThisNullsafeOperatorFixer::class)]
final class ThisNullsafeOperatorFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function replacesNullsafeOperatorOnThis(): void
    {
        self::assertFixes(
            new ThisNullsafeOperatorFixer(),
            "<?php\n\$this->foo();\n",
            "<?php\n\$this?->foo();\n",
        );
    }

    #[Test]
    public function replacesNullsafeOperatorInChain(): void
    {
        self::assertFixes(
            new ThisNullsafeOperatorFixer(),
            "<?php\n\$this->foo()?->bar();\n",
            "<?php\n\$this?->foo()?->bar();\n",
        );
    }

    #[Test]
    public function keepsNullsafeOperatorOnNullableVariable(): void
    {
        self::assertFixes(
            new ThisNullsafeOperatorFixer(),
            "<?php\n\$user?->foo();\n",
            "<?php\n\$user?->foo();\n",
        );
    }
}
