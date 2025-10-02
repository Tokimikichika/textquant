<?php

require_once __DIR__ . '/vendor/autoload.php';

use Tokimikichika\Find\TextAnalyzer;
use Tokimikichika\Find\TextReader;
use Tokimikichika\Find\ResultFormatter;
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

$formatter = new ResultFormatter();
$textReader = new TextReader();

$method = $_SERVER['REQUEST_METHOD'];
$text = '';
$source = 'text';
$error = '';

if ($method === 'POST') {
    if (isset($_POST['text']) && !empty(trim($_POST['text']))) {
        $text = trim($_POST['text']);
        $source = 'text';
    } elseif (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        try {
            $text = $textReader->readFromFile($_FILES['file']['tmp_name']);
            $source = $_FILES['file']['name'];
        } catch (Exception $e) {
            $error = 'Ошибка чтения файла: ' . $e->getMessage();
        }
    } else {
        $error = 'Пожалуйста, введите текст или выберите файл';
    }
} else {
    $text = "Это тестовый текст для анализа. Он содержит несколько предложений и слов для демонстрации работы библиотеки.";
    $source = 'example';
}

$results = null;
if (!empty($text) && empty($error)) {
    try {
        $results = $analyzer->analyze($text, $source);
    } catch (Exception $e) {
        $error = 'Ошибка анализа: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Анализатор текста - Tokimikichika Find</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container">
        <h1>Анализатор текста</h1>
        <p style="text-align: center; color: #666; margin-bottom: 30px;">
            Проанализируйте текст или загрузите файл для получения статистики
        </p>
        
        <form method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="text">Введите текст для анализа:</label>
                <textarea name="text" id="text" placeholder="Введите ваш текст здесь..."><?= htmlspecialchars($text) ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="file">Или загрузите файл (.txt):</label>
                <input type="file" name="file" id="file" accept=".txt">
            </div>
            
            <button type="submit">Анализировать текст</button>
        </form>
        
        <?php if (!empty($error)): ?>
            <div class="error">
                <strong>Ошибка:</strong> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>
        
        <?php if ($results): ?>
            <div class="results">
                <h2>Результаты анализа</h2>
                
                <div class="stats">
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format($results['words']) ?></div>
                        <div class="stat-label">Слов</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format($results['characters']) ?></div>
                        <div class="stat-label">Символов</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format($results['sentences']) ?></div>
                        <div class="stat-label">Предложений</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= number_format($results['paragraphs']) ?></div>
                        <div class="stat-label">Абзацев</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= $results['avg_word_length'] ?></div>
                        <div class="stat-label">Ср. длина слова</div>
                    </div>
                    <div class="stat-item">
                        <div class="stat-value"><?= $results['avg_sentence_length'] ?></div>
                        <div class="stat-label">Ср. длина предложения</div>
                    </div>
                </div>
                
                <h3>Топ-5 слов:</h3>
                <div class="stats">
                    <?php foreach ($results['top_words'] as $word): ?>
                        <div class="stat-item">
                            <div class="stat-value"><?= htmlspecialchars($word['word']) ?></div>
                            <div class="stat-label">(<?= $word['count'] ?> раз)</div>
                        </div>
                    <?php endforeach; ?>
                </div>
                
                <h3>Консольный вывод:</h3>
                <pre><?= htmlspecialchars($formatter->formatResults($results)) ?></pre>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
