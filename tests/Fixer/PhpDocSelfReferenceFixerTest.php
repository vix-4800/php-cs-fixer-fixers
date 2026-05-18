<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use Vix\PhpCsFixerFixers\Fixer\PhpDocSelfReferenceFixer;

final class PhpDocSelfReferenceFixerTest extends AbstractFixerTestCase
{
    public function testReplacesUnionsAndGenerics(): void
    {
        self::assertFixes(
            new PhpDocSelfReferenceFixer(),
            "<?php\nclass Foo\n{\n    /**\n     * @var list<self>|null\n     * @return self\n     */\n    private Foo \$foo;\n}\n",
            "<?php\nclass Foo\n{\n    /**\n     * @var list<Foo>|null\n     * @return Foo\n     */\n    private Foo \$foo;\n}\n",
        );
    }

    public function testKeepsFqcnUnchanged(): void
    {
        self::assertFixes(
            new PhpDocSelfReferenceFixer(),
            "<?php\nclass Foo\n{\n    /** @param \\App\\Foo \$foo */\n    public function set(Foo \$foo): void {}\n}\n",
            "<?php\nclass Foo\n{\n    /** @param \\App\\Foo \$foo */\n    public function set(Foo \$foo): void {}\n}\n",
        );
    }
}
