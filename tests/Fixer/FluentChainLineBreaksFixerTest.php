<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\FluentChainLineBreaksFixer;

final class FluentChainLineBreaksFixerTest extends AbstractFixerTestCase
{
    public function testLeavesSingleLineChainUnchanged(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$result = User::find()->where(['active' => true])->one();\n",
            "<?php\n\$result = User::find()->where(['active' => true])->one();\n",
        );
    }

    public function testSplitsLongMultilineChain(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$result = User::find()\n    ->where(['status' => \$status])\n    ->orderBy(['id' => SORT_DESC])\n    ->limit(10)\n    ->all();\n",
            "<?php\n\$result = User::find()->where(\n    ['status' => \$status]\n)->orderBy(['id' => SORT_DESC])->limit(10)->all();\n",
        );
    }

    public function testSplitsChainInsideMultilineArray(): void
    {
        self::assertFixes(
            new FluentChainLineBreaksFixer(),
            "<?php\n\$items = [\n    User::find()\n        ->active()\n        ->all(),\n];\n",
            "<?php\n\$items = [\n    User::find()->active()->all(),\n];\n",
        );
    }
}
