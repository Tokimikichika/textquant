# Text Analyzer Library

Библиотека для анализа текста с подсчетом слов, символов, предложений и абзацев. Среднюю длину слова, среднюю длину предложения и топ 5 встречаемых слов.

## Требования
- PHP >= 8.0

## Установка
```bash
composer require tokimikichika/find
```

## Использование
```php
use Tokimikichika\Find\TextAnalyzer;

$analyzer = new TextAnalyzer();
$results = $analyzer->analyze("Hello world!", "text");
```

## Консольное использование
```bash
php bin/analyze.php --text="Hello world"
php bin/analyze.php --file="document.txt"
php bin/analyze.php --help
```

## Пример вывода
```
File: text
────────────────────────────────────
Words: 2
Characters: 12
Sentences: 1
Paragraphs: 1
Avg. word length: 5.5
Avg. sentence length: 2.0
Top 5 words: hello (1), world (1)
```

## Тестирование
```bash
# Запуск всех тестов
php tests/TextAnalyzerTest.php
php bin/analyze.php --file="tests/test.txt"

# Или через Composer
composer test
```

## Структура классов
- `TextAnalyzer` - главный класс для анализа
- `WordCounter` - подсчет слов и средней длины
- `CharacterCounter` - подсчет символов
- `SentenceCounter` - подсчет предложений
- `ParagraphCounter` - подсчет абзацев
- `TopWordAnalyzer` - анализ частоты слов
- `TextReader` - чтение файлов
- `ResultFormatter` - форматирование вывода

## Лицензия
MIT
```
