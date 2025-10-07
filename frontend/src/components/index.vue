<script>
export default {
  name: 'IndexAnalyzer',
  data() {
    return {
      text: 'Привет мир! Hello world.',
      loading: false,
      error: '',
      result: null,
    };
  },
  methods: {
    async analyze() {
      this.error = '';
      this.result = null;
      this.loading = true;
      try {
        const payloadText = (this.text || '').trim();
        if (!payloadText) {
          this.error = 'Введите текст';
          return;
        }
        const res = await fetch('/api/v1/analyze/text', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ text: payloadText })
        });
        let json;
        try {
          json = await res.json();
        } catch {
          throw new Error('Некорректный ответ сервера');
        }
        if (!res.ok) throw new Error(json.error || 'Request failed');
        this.result = json;
      } catch (e) {
        this.error = e.message || String(e);
      } finally {
        this.loading = false;
      }
    }
  }
}
</script>

<template>
  <main class="container">
    <h1>Текстовый анализатор (Vue + API)</h1>
    <form @submit.prevent="analyze" class="form">
      <textarea v-model="text" rows="6" placeholder="Введите текст..."></textarea>
      <button type="submit" :disabled="loading || !(text && text.trim())">{{ loading ? 'Анализ...' : 'Анализировать' }}</button>
    </form>

    <p v-if="error" class="error">{{ error }}</p>

    <section v-if="result" class="result">
      <h2>Результат</h2>
      <ul>
        <li><b>Источник:</b> {{ result.source }}</li>
        <li><b>Слова:</b> {{ result.words }}</li>
        <li><b>Символы:</b> {{ result.characters }}</li>
        <li><b>Предложения:</b> {{ result.sentences }}</li>
        <li><b>Параграфы:</b> {{ result.paragraphs }}</li>
        <li><b>Средняя длина слова:</b> {{ result.avg_word_length }}</li>
        <li><b>Средняя длина предложения:</b> {{ result.avg_sentence_length }}</li>
      </ul>
      <div v-if="result.top_words?.length">
        <h3>Топ слов</h3>
        <ol>
          <li v-for="(w, i) in result.top_words" :key="i">{{ w.word }} — {{ w.count }}</li>
        </ol>
      </div>
    </section>
  </main>
</template>

<style scoped>
.container {
  max-width: 760px;
  margin: 32px auto;
  padding: 0 16px;
}

.form {
  display: grid;
  gap: 12px;
  margin-bottom: 16px;
}

textarea {
  width: 100%;
  font-family: inherit;
  font-size: 14px;
  padding: 8px;
}

button {
  padding: 8px 12px;
  cursor: pointer;
}

.error {
  color: #d33;
}

.result ul {
  list-style: none;
  padding: 0;
}
</style>
