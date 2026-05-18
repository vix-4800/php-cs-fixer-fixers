<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\RemoveDocBlockTagsFixer;

final class RemoveDocBlockTagsFixerTest extends AbstractFixerTestCase
{
    public function testRemovesDefaultTagsAndContinuation(): void
    {
        self::assertFixes(
            new RemoveDocBlockTagsFixer(),
            "<?php\n/**\n * Summary.\n * @param string \$name\n */\nfunction foo(string \$name): void {}\n",
            "<?php\n/**\n * Summary.\n * @author Jane Doe\n * continuation line\n * @param string \$name\n */\nfunction foo(string \$name): void {}\n",
        );
    }

    public function testRespectsEmptyConfiguredTagList(): void
    {
        self::assertFixes(
            new RemoveDocBlockTagsFixer(),
            "<?php\n/**\n * @author Jane Doe\n */\nclass Foo {}\n",
            "<?php\n/**\n * @author Jane Doe\n */\nclass Foo {}\n",
            ['tags' => []],
        );
    }

    public function testSupportsCustomTagList(): void
    {
        self::assertFixes(
            new RemoveDocBlockTagsFixer(),
            "<?php\n/**\n * Public API.\n */\nclass Foo {}\n",
            "<?php\n/**\n * Public API.\n * @internal\n */\nclass Foo {}\n",
            ['tags' => ['internal']],
        );
    }
}
