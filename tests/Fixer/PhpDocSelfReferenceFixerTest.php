<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSelfReferenceFixer;

final class PhpDocSelfReferenceFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function replacesUnionsAndGenerics(): void
    {
        self::assertFixes(
            new PhpDocSelfReferenceFixer(),
            "<?php\nclass Foo\n{\n    /**\n     * @var list<self>|null\n     * @return self\n     */\n    private Foo \$foo;\n}\n",
            "<?php\nclass Foo\n{\n    /**\n     * @var list<Foo>|null\n     * @return Foo\n     */\n    private Foo \$foo;\n}\n",
        );
    }

    #[Test]
    public function keepsFqcnUnchanged(): void
    {
        self::assertFixes(
            new PhpDocSelfReferenceFixer(),
            "<?php\nclass Foo\n{\n    /** @param \\App\\Foo \$foo */\n    public function set(Foo \$foo): void {}\n}\n",
            "<?php\nclass Foo\n{\n    /** @param \\App\\Foo \$foo */\n    public function set(Foo \$foo): void {}\n}\n",
        );
    }
}
