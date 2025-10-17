<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use Tokimikichika\Find\Service\ResultFormatter;

/**
 * Тестирует ResultFormatter
 */
class ResultFormatterTest extends TestCase
{
    private ResultFormatter $resultFormatter;

    protected function setUp(): void
    {
        $this->resultFormatter = new ResultFormatter();
    }
    /**
     *
     * Тестирует форматирование числа 0
     */
    public function testFormatNumberZero(): void
    {
        $result = $this->resultFormatter->formatNumber(0);
        $this->assertEquals('0', $result);
    }

    /**
     *
     * Тестирует форматирование отрицательного числа
     */
    public function testFormatNumberNegative(): void
    {
        $result = $this->resultFormatter->formatNumber(-123);
        $this->assertEquals('-123', $result);
    }

    /**
     *
     * Тестирует форматирование большого отрицательного числа
     */
    public function testFormatNumberLargeNegative(): void
    {
        $result = $this->resultFormatter->formatNumber(-1234567);
        $this->assertEquals('-1,234,567', $result);
    }

    /**
     *
     * Тестирует форматирование очень большого числа
     */
    public function testFormatNumberVeryLarge(): void
    {
        $result = $this->resultFormatter->formatNumber(1234567890123);
        $this->assertEquals('1,234,567,890,123', $result);
    }

    /**
     *
     * Тестирует форматирование очень маленького числа
     */
    public function testFormatNumberVerySmall(): void
    {
        $result = $this->resultFormatter->formatNumber(1);
        $this->assertEquals('1', $result);
    }

    /**
     *
     * Тестирует форматирование числа с запятыми
     */
    public function testFormatNumberWithCommas(): void
    {
        $result = $this->resultFormatter->formatNumber(1234);
        $this->assertEquals('1,234', $result);
    }

    /**
     *
     * Тестирует форматирование числа с несколькими запятыми
     */
    public function testFormatNumberWithMultipleCommas(): void
    {
        $result = $this->resultFormatter->formatNumber(1234567);
        $this->assertEquals('1,234,567', $result);
    }

    /**
     *
     * Тестирует форматирование пустого массива
     */
    public function testFormatResultsEmptyArray(): void
    {
        $results = [];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без source
     */
    public function testFormatResultsMissingSource(): void
    {
        $results = ['words' => 10];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без words
     */
    public function testFormatResultsMissingWords(): void
    {
        $results = ['source' => 'test.txt'];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без characters
     */
    public function testFormatResultsMissingCharacters(): void
    {
        $results = ['source' => 'test.txt', 'words' => 10];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без sentences
     */
    public function testFormatResultsMissingSentences(): void
    {
        $results = ['source' => 'test.txt', 'words' => 10, 'characters' => 50];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без paragraphs
     */
    public function testFormatResultsMissingParagraphs(): void
    {
        $results = ['source' => 'test.txt', 'words' => 10, 'characters' => 50, 'sentences' => 2];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без avg_word_length
     */
    public function testFormatResultsMissingAvgWordLength(): void
    {
        $results = ['source' => 'test.txt', 'words' => 10, 'characters' => 50, 'sentences' => 2, 'paragraphs' => 1];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без avg_sentence_length
     */
    public function testFormatResultsMissingAvgSentenceLength(): void
    {
        $results = ['source' => 'test.txt', 'words' => 10, 'characters' => 50, 'sentences' => 2, 'paragraphs' => 1, 'avg_word_length' => 5.0];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива без top_words
     */
    public function testFormatResultsMissingTopWords(): void
    {
        $results = ['source' => 'test.txt', 'words' => 10, 'characters' => 50, 'sentences' => 2, 'paragraphs' => 1, 'avg_word_length' => 5.0, 'avg_sentence_length' => 5.0];
        $this->expectException(\Error::class);
        $this->resultFormatter->formatResults($results);
    }

    /**
     *
     * Тестирует форматирование массива с пустым top_words
     */
    public function testFormatResultsWithEmptyTopWords(): void
    {
        $results              = $this->getSampleResults();
        $results['top_words'] = [];
        $formatted            = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('Top 5 words: ', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с одним top_word
     */
    public function testFormatResultsWithSingleTopWord(): void
    {
        $results              = $this->getSampleResults();
        $results['top_words'] = [['word' => 'test', 'count' => 1]];
        $formatted            = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('Top 5 words: test (1)', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с несколькими top_words
     */
    public function testFormatResultsWithMultipleTopWords(): void
    {
        $results              = $this->getSampleResults();
        $results['top_words'] = [
            ['word' => 'test', 'count' => 2],
            ['word' => 'word', 'count' => 1],
            ['word' => 'hello', 'count' => 1],
        ];
        $formatted = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('Top 5 words: test (2), word (1), hello (1)', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с нулевыми значениями
     */
    public function testFormatResultsWithZeroValues(): void
    {
        $results = [
            'source'              => 'empty.txt',
            'words'               => 0,
            'characters'          => 0,
            'sentences'           => 0,
            'paragraphs'          => 0,
            'avg_word_length'     => 0.0,
            'avg_sentence_length' => 0.0,
            'top_words'           => [],
        ];
        $formatted = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('Words: 0', $formatted);
        $this->assertStringContainsString('Characters: 0', $formatted);
        $this->assertStringContainsString('Sentences: 0', $formatted);
        $this->assertStringContainsString('Paragraphs: 0', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с отрицательными значениями
     */
    public function testFormatResultsWithNegativeValues(): void
    {
        $results = [
            'source'              => 'negative.txt',
            'words'               => -1,
            'characters'          => -1,
            'sentences'           => -1,
            'paragraphs'          => -1,
            'avg_word_length'     => -1.0,
            'avg_sentence_length' => -1.0,
            'top_words'           => [],
        ];
        $formatted = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('Words: -1', $formatted);
        $this->assertStringContainsString('Characters: -1', $formatted);
        $this->assertStringContainsString('Sentences: -1', $formatted);
        $this->assertStringContainsString('Paragraphs: -1', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с большими значениями
     */
    public function testFormatResultsWithLargeValues(): void
    {
        $results = [
            'source'              => 'large.txt',
            'words'               => 1000000,
            'characters'          => 5000000,
            'sentences'           => 100000,
            'paragraphs'          => 10000,
            'avg_word_length'     => 5.0,
            'avg_sentence_length' => 10.0,
            'top_words'           => [],
        ];
        $formatted = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('Words: 1,000,000', $formatted);
        $this->assertStringContainsString('Characters: 5,000,000', $formatted);
        $this->assertStringContainsString('Sentences: 100,000', $formatted);
        $this->assertStringContainsString('Paragraphs: 10,000', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с float значениями
     */
    public function testFormatResultsWithFloatValues(): void
    {
        $results = [
            'source'              => 'float.txt',
            'words'               => 10,
            'characters'          => 50,
            'sentences'           => 2,
            'paragraphs'          => 1,
            'avg_word_length'     => 5.123456789,
            'avg_sentence_length' => 10.987654321,
            'top_words'           => [],
        ];
        $formatted = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('Avg. word length: 5.123456789', $formatted);
        $this->assertStringContainsString('Avg. sentence length: 10.987654321', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с unicode значениями
     */
    public function testFormatResultsWithUnicodeSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 'тест.txt';
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: тест.txt', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с специальными символами в source
     */
    public function testFormatResultsWithSpecialCharactersInSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 'test@file#1.txt';
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: test@file#1.txt', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с пустым source
     */
    public function testFormatResultsWithEmptySource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = '';
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: ', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с null source
     */
    public function testFormatResultsWithNullSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = null;
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: ', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с boolean source
     */
    public function testFormatResultsWithBooleanSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = true;
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: 1', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с array source
     */
    public function testFormatResultsWithArraySource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = ['test', 'file'];
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: Array', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с object source
     */
    public function testFormatResultsWithObjectSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 'object';
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: object', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с resource source
     */
    public function testFormatResultsWithResourceSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = fopen('php://memory', 'r');
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: ', $formatted);
        fclose($results['source']);
    }

    /**
     *
     * Тестирует форматирование массива с callable source
     */
    public function testFormatResultsWithCallableSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 'callable';
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: callable', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с closure source
     */
    public function testFormatResultsWithClosureSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 'closure';
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: closure', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с iterable source
     */
    public function testFormatResultsWithIterableSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 'iterable';
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: iterable', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с stringable source
     */
    public function testFormatResultsWithStringableSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = new class () implements \Stringable {
            public function __toString(): string
            {
                return 'test.txt';
            }
        };
        $formatted = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: ', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с numeric source
     */
    public function testFormatResultsWithNumericSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 123;
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: 123', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с float source
     */
    public function testFormatResultsWithFloatSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = 123.456;
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: 123.456', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с infinity source
     */
    public function testFormatResultsWithInfinitySource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = INF;
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: ', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с NaN source
     */
    public function testFormatResultsWithNaNSource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = NAN;
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: ', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с negative infinity source
     */
    public function testFormatResultsWithNegativeInfinitySource(): void
    {
        $results           = $this->getSampleResults();
        $results['source'] = -INF;
        $formatted         = $this->resultFormatter->formatResults($results);
        $this->assertStringContainsString('File: ', $formatted);
    }

    /**
     *
     * Тестирует форматирование массива с sample данными
     */
    private function getSampleResults(): array
    {
        return [
            'source'              => 'test.txt',
            'words'               => 10,
            'characters'          => 50,
            'sentences'           => 2,
            'paragraphs'          => 1,
            'avg_word_length'     => 5.0,
            'avg_sentence_length' => 5.0,
            'top_words'           => [
                ['word' => 'test', 'count' => 3],
                ['word' => 'word', 'count' => 2],
            ],
        ];
    }
}
