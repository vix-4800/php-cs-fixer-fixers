<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\NoYodaComparisonFixer;

final class NoYodaComparisonFixerTest extends AbstractFixerTestCase
{
    public function testConvertsNullComparison(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif (\$value === null) {}\n",
            "<?php\nif (null === \$value) {}\n",
        );
    }

    public function testFlipsRelationalOperator(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif (\$count >= 10) {}\n",
            "<?php\nif (10 <= \$count) {}\n",
        );
    }

    public function testConvertsLongNullableChain(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif (\$service->repository()->find(\$id)?->name === null) {}\n",
            "<?php\nif (null === \$service->repository()->find(\$id)?->name) {}\n",
        );
    }

    public function testConvertsAssignmentExpression(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif ((\$value = \$factory->make()) !== null) {}\n",
            "<?php\nif (null !== (\$value = \$factory->make())) {}\n",
        );
    }

    public function testConvertsCastExpression(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif ((string) \$value === null) {}\n",
            "<?php\nif (null === (string) \$value) {}\n",
        );
    }

    public function testConvertsGreaterThanComparison(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif (\$count < 10) {}\n",
            "<?php\nif (10 > \$count) {}\n",
        );
    }

    public function testConvertsLiteralArrayComparison(): void
    {
        self::assertFixes(
            new NoYodaComparisonFixer(),
            "<?php\nif (\$items === []) {}\n",
            "<?php\nif ([] === \$items) {}\n",
        );
    }
}
