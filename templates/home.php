<h1>Анализатор текста</h1>
<p>Загрузите текстовый файл или введите текст для анализа</p>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label for="text">Введите текст для анализа:</label>
        <textarea name="text" id="text" placeholder="Введите ваш текст здесь..."><?= htmlspecialchars($data['text'] ?? '') ?></textarea>
    </div>
    
    <div class="form-group">
        <label for="file">Или загрузите файл (.txt):</label>
        <input type="file" name="file" id="file" accept=".txt">
    </div>
    
    <button type="submit">Анализировать текст</button>
</form>

<?php if (!empty($data['error'])): ?>
    <div class="error">
        <strong>Ошибка:</strong> <?= htmlspecialchars($data['error']) ?>
    </div>
<?php endif; ?>

<?php if ($data['results']): ?>
    <div class="results">
        <h2>Результаты анализа</h2>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-value"><?= number_format($data['results']['words']) ?></div>
                <div class="stat-label">Слов</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= number_format($data['results']['characters']) ?></div>
                <div class="stat-label">Символов</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= number_format($data['results']['sentences']) ?></div>
                <div class="stat-label">Предложений</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= number_format($data['results']['paragraphs']) ?></div>
                <div class="stat-label">Абзацев</div>
            </div>
        </div>
        
        <div class="stats">
            <div class="stat-item">
                <div class="stat-value"><?= number_format($data['results']['avg_word_length'], 1) ?></div>
                <div class="stat-label">Ср. длина слова</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= number_format($data['results']['avg_sentence_length'], 1) ?></div>
                <div class="stat-label">Ср. длина предложения</div>
            </div>
        </div>
        
        <h3>Топ-5 слов:</h3>
        <div class="stats">
            <?php foreach ($data['results']['top_words'] as $word): ?>
                <div class="stat-item">
                    <div class="stat-value"><?= htmlspecialchars($word['word']) ?></div>
                    <div class="stat-label">(<?= $word['count'] ?> раз)</div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <h3>Консольный вывод:</h3>
        <pre><?= htmlspecialchars($formatter->formatResults($data['results'])) ?></pre>
    </div>
<?php endif; ?>
