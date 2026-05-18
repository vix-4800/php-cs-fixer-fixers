<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\NumericLiteralSeparatorFixer;

final class NumericLiteralSeparatorFixerTest extends AbstractFixerTestCase
{
    public function testSeparatesLargeInteger(): void
    {
        self::assertFixes(
            new NumericLiteralSeparatorFixer(),
            "<?php\n\$price = 1_234_567;\n",
            "<?php\n\$price = 1234567;\n",
        );
    }

    public function testSeparatesFloatIntegerPart(): void
    {
        self::assertFixes(
            new NumericLiteralSeparatorFixer(),
            "<?php\n\$price = 1_234_567.89;\n",
            "<?php\n\$price = 1234567.89;\n",
        );
    }

    public function testKeepsLeadingZeroIntegerUnchanged(): void
    {
        self::assertFixes(
            new NumericLiteralSeparatorFixer(),
            "<?php\n\$mask = 012345;\n",
            "<?php\n\$mask = 012345;\n",
        );
    }

    public function testKeepsExistingSeparatorsAndBasesUnchanged(): void
    {
        self::assertFixes(
            new NumericLiteralSeparatorFixer(),
            "<?php\n\$a = 1_000_000;\n\$b = 0xFFFFFF;\n\$c = 0b11110000;\n",
            "<?php\n\$a = 1_000_000;\n\$b = 0xFFFFFF;\n\$c = 0b11110000;\n",
        );
    }
}
