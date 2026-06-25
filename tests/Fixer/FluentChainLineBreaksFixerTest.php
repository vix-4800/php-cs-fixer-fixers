<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\FluentChainLineBreaksFixer;

final class FluentChainLineBreaksFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function leavesSingleLineChainUnchanged(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$result = User::find()->where(['active' => true])->one();\n",
            "<?php\n\$result = User::find()->where(['active' => true])->one();\n",
        );
    }

    #[Test]
    public function splitsLongMultilineChain(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$result = User::find()->where(['status' => \$status])\n    ->orderBy(['id' => SORT_DESC])\n    ->limit(10)\n    ->all();\n",
            "<?php\n\$result = User::find()->where(\n    ['status' => \$status]\n)->orderBy(['id' => SORT_DESC])->limit(10)->all();\n",
        );
    }

    #[Test]
    public function splitsFirstCallWhenConfigured(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$result = User::find()\n    ->where(['status' => \$status])\n    ->orderBy(['id' => SORT_DESC])\n    ->limit(10)\n    ->all();\n",
            "<?php\n\$result = User::find()->where(\n    ['status' => \$status]\n)->orderBy(['id' => SORT_DESC])->limit(10)->all();\n",
            ['break_on_first_call' => true],
        );
    }

    #[Test]
    public function splitsChainInsideMultilineArray(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$items = [\n    User::find()\n        ->active()\n        ->all(),\n];\n",
            "<?php\n\$items = [\n    User::find()->active()->all(),\n];\n",
            ['break_on_first_call' => true],
        );
    }

    #[Test]
    public function keepsFirstCallOnSameLineInsideMultilineArrayByDefault(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$items = [\n    User::find()->active()\n        ->all(),\n];\n",
            "<?php\n\$items = [\n    User::find()->active()->all(),\n];\n",
        );
    }

    #[Test]
    public function leavesChainInsideArrayOffsetUnchanged(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\nforeach (PageTypeEnum::cases() as \$case) {\n    \$this->pageMap[\$case->description()] = \$case->value;\n}\n",
            "<?php\nforeach (PageTypeEnum::cases() as \$case) {\n    \$this->pageMap[\$case->description()] = \$case->value;\n}\n",
        );
    }

    #[Test]
    public function skipsChainBelowMinimumCallCount(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$result = User::find()->where(\n    ['status' => \$status]\n)->all();\n",
            "<?php\n\$result = User::find()->where(\n    ['status' => \$status]\n)->all();\n",
            ['min_chain_calls' => 3],
        );
    }

    #[Test]
    public function splitsChainAtMinimumCallCount(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$result = User::find()->where(['status' => \$status])\n    ->orderBy(['id' => SORT_DESC])\n    ->all();\n",
            "<?php\n\$result = User::find()->where(\n    ['status' => \$status]\n)->orderBy(['id' => SORT_DESC])->all();\n",
            ['min_chain_calls' => 3],
        );
    }
}
