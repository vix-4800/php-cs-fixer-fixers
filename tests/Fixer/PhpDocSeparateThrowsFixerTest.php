<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Tests\Fixer;

use PHPUnit\Framework\Attributes\Test;
use Vix\PhpCsFixerFixers\Fixer\PhpDocSeparateThrowsFixer;

final class PhpDocSeparateThrowsFixerTest extends AbstractFixerTestCase
{
    #[Test]
    public function splitsSimpleUnion(): void
    {
        self::assertFixes(
            new PhpDocSeparateThrowsFixer(),
            "<?php\n/**\n * @throws RuntimeException when invalid\n * @throws LogicException when invalid\n */\nfunction foo(): void {}\n",
            "<?php\n/**\n * @throws RuntimeException|LogicException when invalid\n */\nfunction foo(): void {}\n",
        );
    }

    #[Test]
    public function keepsGenericInnerUnionTogether(): void
    {
        self::assertFixes(
            new PhpDocSeparateThrowsFixer(),
            "<?php\n/**\n * @throws AggregateException<RuntimeException|LogicException>\n * @throws InvalidArgumentException\n */\nfunction foo(): void {}\n",
            "<?php\n/**\n * @throws AggregateException<RuntimeException|LogicException>|InvalidArgumentException\n */\nfunction foo(): void {}\n",
        );
    }

    #[Test]
    public function leavesSingleTypeUnchanged(): void
    {
        self::assertFixes(
            new PhpDocSeparateThrowsFixer(),
            "<?php\n/**\n * @throws RuntimeException\n */\nfunction foo(): void {}\n",
            "<?php\n/**\n * @throws RuntimeException\n */\nfunction foo(): void {}\n",
        );
    }
}
