Tokimikichika/find — текстовый анализатор (PHP + Slim, Vue, Docker)

Мини‑библиотека и сервис для анализа текста: считает слова, символы, предложения и параграфы, средние длины и топ слов. Бэкенд на PHP (Slim 4, PHP‑FPM), фронтенд на Vue (Vite), контейнеризация через Docker Compose.

Структура

```
backend/            # PHP (Slim) приложение и библиотека
  public/index.php  # Входная точка Slim (PSR-7)
  src/              # Классы анализа текста и контроллеры
  composer.json     # Зависимости PHP
frontend/           # Vue (Vite) фронтенд
  src/              # Компоненты
  vite.config.js    # Прокси /api в dev-режиме
docker/
  nginx.conf        # Конфиг Nginx для prod
docker-compose.yml  # 
```

Быстрый старт (Docker)

1) Собрать и запустить:

```bash
docker compose up -d --build
```

2) Открыть фронтенд: `http://localhost:6123`

Фронт раздаётся Nginx, запросы `/api` проксируются в PHP‑FPM на `backend/public/index.php`.

Локальная разработка (без Docker)

- Backend
  - Установка зависимостей:
    ```bash
    cd backend
    composer install
    ```
  - Запуск Slim через встроенный сервер PHP (документ‑руут `public`):
    ```bash
    php -S localhost:8080 -t public
    ```
  - Проверка API:
    ```bash
    curl -s -X POST http://localhost:8080/api/v1/analyze/text \
      -H "Content-Type: application/json" \
      -d '{"text":"Привет мир! Hello world."}'
    ```

- Frontend
  - Dev‑сервер Vite (прокси на `/api` → `http://localhost:8080`):
    ```bash
    cd frontend
    npm ci
    npm run dev
    ```
  - Открыть URL Vite (`http://localhost:6173`).

Тестирование (PHPUnit)

```bash
cd backend
composer test-unit
# отчёт покрытия
composer test-coverage
```

API

- POST `/api/v1/analyze/text`
  - Тело: `{ "text": "строка" }`
  - Успех (пример):
    ```json
    {
      "source": "text",
      "words": 6,
      "characters": 28,
      "sentences": 2,
      "paragraphs": 1,
      "avg_word_length": 4.2,
      "avg_sentence_length": 3.0,
      "top_words": [{ "word": "hello", "count": 1 }]
    }
    ```
  - Ошибка 400: `{ "error": "Text is required" }`

Продакшен‑сборка фронтенда

Сборка выполняется в Dockerfile фронта (`npm run build` → `dist/`), далее Nginx раздаёт статику и проксирует `/api` в php‑fpm.

Лицензия: MIT


