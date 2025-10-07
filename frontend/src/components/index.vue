<script>
export default {
  name: 'IndexAnalyzer',
  data() {
    return {
      text: '',
      loading: false,
      error: '',
      result: null,
      pickedFileName: '',
    };
  },
  methods: {
    handleFile(e) {
      this.error = '';
      this.pickedFileName = '';
      const file = e?.target?.files?.[0];
      if (!file) return;
      if (!/^text\//.test(file.type) && !/\.txt$/i.test(file.name)) {
        this.error = 'Поддерживаются только текстовые файлы (.txt)';
        e.target.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = () => {
        const content = String(reader.result || '');
        this.text = content;
        this.pickedFileName = file.name;
      };
      reader.onerror = () => {
        this.error = 'Не удалось прочитать файл';
      };
      reader.readAsText(file, 'utf-8');
    },
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
  <main class="page">
    <header class="header">
      <h1 class="title">Текстовый анализатор</h1>
      <p class="subtitle">Вставьте текст и получите статистику</p>
    </header>

    <section class="card">
      <form @submit.prevent="analyze" class="form">
        <label class="label" for="ta">Текст</label>
        <textarea id="ta" v-model="text" rows="8" class="textarea" placeholder="Введите или вставьте текст для анализа..."></textarea>
        <div class="file-row">
          <label class="file-label">
            <input class="file" type="file" accept=".txt,text/plain" @change="handleFile" />
            <span class="file-btn">Выбрать файл</span>
            <span class="file-name" v-if="pickedFileName">{{ pickedFileName }}</span>
            <span class="file-hint" v-else>Поддерживаются .txt</span>
          </label>
        </div>
        <div class="actions">
          <button class="btn btn-primary" type="submit" :disabled="loading || !(text && text.trim())">
            {{ loading ? 'Анализ…' : 'Анализировать' }}
          </button>
        </div>
      </form>
      <p v-if="error" class="alert">{{ error }}</p>
    </section>

    <section v-if="result" class="card result">
      <h2 class="section-title">Результат</h2>
      <div class="grid">
        <div class="metric"><span class="k">Источник</span><span class="v">{{ result.source }}</span></div>
        <div class="metric"><span class="k">Слова</span><span class="v">{{ result.words }}</span></div>
        <div class="metric"><span class="k">Символы</span><span class="v">{{ result.characters }}</span></div>
        <div class="metric"><span class="k">Предложения</span><span class="v">{{ result.sentences }}</span></div>
        <div class="metric"><span class="k">Параграфы</span><span class="v">{{ result.paragraphs }}</span></div>
        <div class="metric"><span class="k">Ср. длина слова</span><span class="v">{{ result.avg_word_length }}</span></div>
        <div class="metric"><span class="k">Ср. длина предложения</span><span class="v">{{ result.avg_sentence_length }}</span></div>
      </div>
      <div v-if="result.top_words?.length" class="top">
        <h3 class="section-subtitle">Топ слов</h3>
        <ol class="top-list">
          <li v-for="(w, i) in result.top_words" :key="i"><span class="word">{{ w.word }}</span><span class="count">{{ w.count }}</span></li>
        </ol>
      </div>
    </section>
  </main>
</template>

<style scoped>
.page {
  min-height: 100vh;
  min-width: 150%;
  padding: 24px 16px 48px;
  position: relative;
  overflow-x: hidden;
}

.page::before {
  content: "";
  position: fixed;
  inset: 0;
  background:
    radial-gradient(1200px 600px at -10% -10%, #e8f0ff 0%, transparent 60%),
    radial-gradient(900px 500px at 110% -20%, #fde8ff 0%, transparent 60%),
    #f7f9fc;
  z-index: -1;
}

.header {
  max-width: 880px;
  margin: 0 auto 16px;
  text-align: center;
}

.title {
  margin: 0 0 6px;
  font-size: 28px;
  font-weight: 700;
  letter-spacing: 0.2px;
  color: #0f172a;
}

.subtitle {
  margin: 0;
  color: #475569;
}

.card {
  max-width: 880px;
  margin: 16px auto;
  background: #fff;
  border-radius: 14px;
  box-shadow: 0 10px 25px rgba(2, 6, 23, 0.06), 0 2px 6px rgba(2, 6, 23, 0.04);
  padding: 18px;
}

.form {
  display: grid;
  gap: 10px;
}

.label {
  font-size: 13px;
  color: #334155;
}

.textarea {
  resize: vertical;
  min-height: 140px;
  font-family: ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, "Apple Color Emoji", "Segoe UI Emoji";
  font-size: 14px;
  line-height: 1.5;
  padding: 12px 14px;
  color: #0f172a;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
  outline: none;
  transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
}

.textarea:focus {
  background: #ffffff;
  border-color: #60a5fa;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.15);
}

.actions {
  display: flex;
  justify-content: flex-start;
}

.file-row { display: flex; justify-content: space-between; align-items: center; }
.file-label { display: inline-flex; align-items: center; gap: 10px; }
.file { display: none; }
.file-btn {
  display: inline-block;
  background: #0ea5e9;
  color: #fff;
  padding: 8px 12px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(14, 165, 233, .2);
}
.file-name { color: #0f172a; font-weight: 600; }
.file-hint { color: #64748b; }

.btn {
  appearance: none;
  border: 0;
  border-radius: 10px;
  padding: 10px 14px;
  font-weight: 600;
  cursor: pointer;
  transition: transform .05s ease, box-shadow .2s ease, opacity .2s ease;
}

.btn:disabled {
  opacity: 1;
  cursor: not-allowed;
}

.btn-primary[disabled] {
  background: #0ea5e9;
  box-shadow: 0 6px 16px rgba(14, 165, 233, .2);
}

.btn-primary {
  color: #fff;
  background: #0ea5e9;
  box-shadow: 0 6px 16px rgba(14, 165, 233, .2);
}

.btn-primary:not(:disabled):active {
  transform: translateY(1px);
}

.alert {
  margin-top: 10px;
  color: #b91c1c;
}

.section-title {
  margin: 0 0 10px;
  font-size: 18px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 10px;
}

.metric {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 10px 12px;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 10px;
}

.metric .k { color: #475569; }
.metric .v { font-weight: 700; color: #0f172a; }

.top { margin-top: 14px; }

.section-subtitle {
  margin: 0 0 6px;
  font-weight: 600;
}

.top-list {
  list-style: none;
  padding: 0;
  margin: 0;
  display: grid;
  gap: 6px;
}

.top-list li {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 8px 10px;
  border: 1px dashed #dbe3ee;
  border-radius: 8px;
}

.word { color: #0f172a; }
.count { font-weight: 700; color: #1f2937; }
</style>
