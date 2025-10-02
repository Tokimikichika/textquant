# Text Analyzer Library

Библиотека для анализа текста с подсчетом слов, символов, предложений и абзацев. Среднюю длину слова, среднюю длину предложения и топ 5 встречаемых слов.

## Требования
- PHP >= 8.0

## Установка
```bash
composer require tokimikichika/find
```

## Запуск проекта

### Веб-интерфейс
```bash
# Запуск встроенного PHP сервера
php -S localhost:8080

# Откройте в браузере
http://localhost:8080
```

## Использование

### Веб-интерфейс
1. Запустите сервер: `php -S localhost:8080`
2. Откройте http://localhost:8080 в браузере
3. Введите текст или загрузите .txt файл
4. Нажмите "Анализировать текст"

### Программное использование
```php
use Tokimikichika\Find\TextAnalyzer;
use Tokimikichika\Find\WordCounter;
use Tokimikichika\Find\CharacterCounter;
use Tokimikichika\Find\SentenceCounter;
use Tokimikichika\Find\ParagraphCounter;
use Tokimikichika\Find\TopWordAnalyzer;

$wordCounter = new WordCounter();
$characterCounter = new CharacterCounter();
$sentenceCounter = new SentenceCounter();
$paragraphCounter = new ParagraphCounter();
$topWordAnalyzer = new TopWordAnalyzer();

$analyzer = new TextAnalyzer(
    $wordCounter,
    $characterCounter,
    $sentenceCounter,
    $paragraphCounter,
    $topWordAnalyzer
);

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

### Запуск простых тестов
```bash
# Запуск базовых тестов
composer test
# или
php tests/TextAnalyzerTest.php
```

### Запуск юнит-тестов с PHPUnit
```bash
# Запуск всех юнит-тестов
composer test-unit
# или
./vendor/bin/phpunit

# Запуск с покрытием кода
composer test-coverage
# или
./vendor/bin/phpunit --coverage-html coverage

# Запуск конкретного теста
./vendor/bin/phpunit tests/Unit/WordCounterTest.php
```

### Тестирование с примером файла
```bash
# Анализ тестового файла
php bin/analyze.php --file="tests/test.txt"
```

Файл `tests/test.txt` содержит пример текста для демонстрации работы библиотеки.


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
