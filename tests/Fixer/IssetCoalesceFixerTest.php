<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\IssetCoalesceFixer;

final class IssetCoalesceFixerTest extends AbstractFixerTestCase
{
    public function testConvertsNotNullComparison(): void
    {
        self::assertFixes(
            new IssetCoalesceFixer(),
            "<?php\nif (isset(\$chat['poll'])) {}\n",
            "<?php\nif ((\$chat['poll'] ?? null) !== null) {}\n",
        );
    }

    public function testConvertsYodaNullIdenticalComparison(): void
    {
        self::assertFixes(
            new IssetCoalesceFixer(),
            "<?php\nif (!isset(\$user?->profile)) {}\n",
            "<?php\nif (null === (\$user?->profile ?? null)) {}\n",
        );
    }

    public function testLeavesFunctionCallExpressionUnchanged(): void
    {
        self::assertFixes(
            new IssetCoalesceFixer(),
            "<?php\nif ((getValue() ?? null) !== null) {}\n",
            "<?php\nif ((getValue() ?? null) !== null) {}\n",
        );
    }
}
