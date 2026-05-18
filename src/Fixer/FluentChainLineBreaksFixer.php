<?php

declare(strict_types=1);

namespace Vix\PhpCsFixerFixers\Fixer;

use Override;
use PhpCsFixer\AbstractFixer;
use PhpCsFixer\Fixer\ConfigurableFixerInterface;
use PhpCsFixer\Fixer\WhitespacesAwareFixerInterface;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolver;
use PhpCsFixer\FixerConfiguration\FixerConfigurationResolverInterface;
use PhpCsFixer\FixerConfiguration\FixerOptionBuilder;
use PhpCsFixer\FixerDefinition\CodeSample;
use PhpCsFixer\FixerDefinition\FixerDefinition;
use PhpCsFixer\FixerDefinition\FixerDefinitionInterface;
use PhpCsFixer\Tokenizer\Tokens;
use SplFileInfo;

/**
 * @implements ConfigurableFixerInterface<array{break_on_first_call?: bool, min_chain_calls?: int|null}, array{break_on_first_call: bool, min_chain_calls: int|null}>
 */
final class FluentChainLineBreaksFixer extends AbstractFixer implements ConfigurableFixerInterface, WhitespacesAwareFixerInterface
{
    /**
     * @var array{break_on_first_call: bool, min_chain_calls: int|null}
     */
    protected array $configuration = [
        'break_on_first_call' => false,
        'min_chain_calls' => null,
    ];

    /**
     * Returns fixer name used in php-cs-fixer config.
     */
    #[Override]
    public function getName(): string
    {
        return 'VixFixer/fluent_chain_line_breaks';
    }

    /**
     * Describes fixer purpose and provides a representative sample.
     */
    public function getDefinition(): FixerDefinitionInterface
    {
        return new FixerDefinition(
            'Splits multiline fluent chains so each chained method call starts on its own line.',
            [
                new CodeSample(
                    "<?php\n\nLinkHelpDeskCategoryResponsibles::find()->where(\n    ['category_id' => \$this->category_id, 'responsible_id' => Yii::\$app->user->id],\n)->exists();\n",
                ),
            ],
            'When a fluent chain already spans multiple lines, each chained call is moved to its own line. '
                . 'Single-line chains are left untouched.',
        );
    }

    /**
     * Performs a cheap pre-check before the fixer runs.
     *
     * @param Tokens $tokens
     */
    public function isCandidate(Tokens $tokens): bool
    {
        return $tokens->isAnyTokenKindsFound([T_OBJECT_OPERATOR, T_NULLSAFE_OBJECT_OPERATOR]);
    }

    /**
     * @param array{break_on_first_call?: bool, min_chain_calls?: int|null} $configuration
     */
    public function configure(array $configuration): void
    {
        /** @var array{break_on_first_call: bool, min_chain_calls: int|null} $configuration */
        $configuration = $this->getConfigurationDefinition()->resolve($configuration);

        $this->configuration = $configuration;
    }

    public function getConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return $this->createConfigurationDefinition();
    }

    /**
     * Runs before built-in whitespace fixers so they can polish the final layout.
     */
    #[Override]
    public function getPriority(): int
    {
        return 10;
    }

    /**
     * Splits multiline fluent chains and compacts argument wrappers when that is safe.
     *
     * @param SplFileInfo $file
     * @param Tokens      $tokens
     */
    protected function applyFix(SplFileInfo $file, Tokens $tokens): void
    {
        $collector = new FluentChainCollector($tokens);
        $formatter = new FluentChainWhitespaceFormatter(
            $tokens,
            $this->whitespacesConfig->getLineEnding(),
            $this->whitespacesConfig->getIndent(),
        );
        $breakOnFirstCall = $this->configuration['break_on_first_call'];
        $minChainCalls = $this->configuration['min_chain_calls'];

        do {
            $chains = $collector->collectChains();
            $hasChanges = false;

            for ($chainIndex = count($chains) - 1; $chainIndex >= 0; --$chainIndex) {
                $chain = $chains[$chainIndex];

                if (!$collector->shouldFormatChain($chain['start'], $chain['end'])) {
                    continue;
                }

                if ($minChainCalls !== null && count($chain['calls']) < $minChainCalls) {
                    continue;
                }

                $targetWhitespace = $collector->detectChainIndentation($chain['start'])
                    . $this->whitespacesConfig->getIndent();

                for ($callIndex = count($chain['calls']) - 1; $callIndex >= 0; --$callIndex) {
                    $call = $chain['calls'][$callIndex];

                    $hasChanges = $formatter->compactArgumentsIfPossible($call['open'], $call['close']) || $hasChanges;

                    if (!$breakOnFirstCall && $callIndex <= 0) {
                        continue;
                    }

                    $hasChanges = $formatter->ensureLineBreakBeforeOperator($call['operator'], $targetWhitespace) || $hasChanges;
                }

                if ($hasChanges) {
                    break;
                }
            }
        } while ($hasChanges);
    }

    private function createConfigurationDefinition(): FixerConfigurationResolverInterface
    {
        return new FixerConfigurationResolver([
            new FixerOptionBuilder('break_on_first_call', 'Whether the first chained method call should start on a new line.')
                ->setAllowedTypes(['bool'])
                ->setDefault(false)
                ->getOption(),
            new FixerOptionBuilder('min_chain_calls', 'Minimum number of chained method calls required before formatting. Null disables the limit.')
                ->setAllowedTypes(['null', 'int'])
                ->setAllowedValues([
                    static fn(mixed $value): bool => $value === null || (is_int($value) && $value > 0),
                ])
                ->setDefault(null)
                ->getOption(),
        ]);
    }
}
