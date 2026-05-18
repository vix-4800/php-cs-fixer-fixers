<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\RequireNullSafeOperatorFixer;

final class RequireNullSafeOperatorFixerTest extends AbstractFixerTestCase
{
    public function testConvertsSimpleTernary(): void
    {
        self::assertFixes(
            new RequireNullSafeOperatorFixer(),
            "<?php\n\$name = \$user?->getName();\n",
            "<?php\n\$name = \$user !== null ? \$user->getName() : null;\n",
        );
    }

    public function testConvertsWrappedLooseComparison(): void
    {
        self::assertFixes(
            new RequireNullSafeOperatorFixer(),
            "<?php\n\$city = \$user?->getAddress()->getCity();\n",
            "<?php\n\$city = (\$user != null) ? \$user->getAddress()->getCity() : null;\n",
        );
    }

    public function testKeepsDifferentTrueBranchVariableUnchanged(): void
    {
        self::assertFixes(
            new RequireNullSafeOperatorFixer(),
            "<?php\n\$name = \$user !== null ? \$profile->getName() : null;\n",
            "<?php\n\$name = \$user !== null ? \$profile->getName() : null;\n",
        );
    }
}
