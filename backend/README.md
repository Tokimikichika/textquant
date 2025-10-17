# Text Analyzer Library

Библиотека для анализа текста с подсчетом слов, символов, предложений и абзацев. Среднюю длину слова, среднюю длину предложения и топ 5 встречаемых слов.

## Требования
- PHP >= 8.0

## Установка
```bash
composer require tokimikichika/find
```

## Использование

### Простое использование
```php
use Tokimikichika\Find\Service\TextAnalyzer;

$analyzer = new TextAnalyzer();
$results = $analyzer->analyze("Hello world!", "text");
```

### Анализ URL
```php
use Tokimikichika\Find\Service\UrlAnalysisService;
use Tokimikichika\Find\Service\TextAnalyzer;
use Tokimikichika\Find\Service\WebScraperService;

$analyzer = new TextAnalyzer();
$webScraper = new WebScraperService();
$urlAnalysis = new UrlAnalysisService($analyzer, $webScraper);

$results = $urlAnalysis->analyzeUrl("https://example.com");
```

### Генерация случайного текста
```php
use Tokimikichika\Find\Service\RandomTextService;

$randomService = new RandomTextService();
$randomText = $randomService->getRandomText();
```

## API Endpoints

### POST /api/v1/analyze/text
Анализ текста
```json
{
    "text": "Hello world!"
}
```

### POST /api/v1/analyze/url
Анализ URL
```json
{
    "url": "https://example.com"
}
```

### GET /api/v1/text/random
Получение случайного текста

## Пример вывода
```json
{
    "source": "text",
    "words": 2,
    "characters": 12,
    "sentences": 1,
    "paragraphs": 1,
    "avg_word_length": 5.5,
    "avg_sentence_length": 2.0,
    "top_words": [
        {"word": "hello", "count": 1},
        {"word": "world", "count": 1}
    ]
}
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

### Запуск юнит-тестов
```bash
composer test-unit
```

### Запуск с покрытием кода
```bash
composer test-coverage
```

### Запуск конкретного теста
```bash
./vendor/bin/phpunit tests/Unit/TextAnalyzerTest.php
```

## Структура классов

### Сервисы
- `TextAnalyzer` - главный класс для анализа текста
- `UrlAnalysisService` - анализ содержимого URL
- `WebScraperService` - извлечение текста из HTML
- `RandomTextService` - генерация случайного текста
- `TextReader` - чтение файлов
- `ResultFormatter` - форматирование вывода

### Контроллеры
- `TextController` - обработка анализа текста
- `UrlController` - обработка анализа URL
- `RandomController` - генерация случайного текста


## Зависимости
- `slim/slim` - веб-фреймворк
- `slim/psr7` - PSR-7 HTTP сообщения
- `tokimikichika/htmlsanitizer` - очистка HTML контента
- `tokimikichika/text-analysis` - анализ текста
- `tokimikichika/file-reader` - чтение файлов
- `tokimikichika/html-parser` - загрузка HTML/парсинг
- `tokimikichika/random-text` - генерация текста

## Лицензия
MIT