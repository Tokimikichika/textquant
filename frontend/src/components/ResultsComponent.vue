<script>
export default {
  name: 'ResultsComponent',
  props: {
    result: {
      type: Object,
      default: null
    }
  }
}
</script>

<template>
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
</template>

<style scoped>
.card {
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
  border: 1px solid #e2e8f0;
}

.section-title {
  margin: 0 0 20px;
  font-size: 18px;
  font-weight: 600;
  color: #0f172a;
}

.source {
  margin: 0 0 12px;
  color: #64748b;
  font-weight: 500;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 10px;
}

.stat {
  text-align: center;
  padding: 16px;
  background: #f8fafc;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
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

.metric .k {
  color: #64748b;
  font-weight: 600;
}

.metric .v {
  color: #0f172a;
  font-weight: 700;
}

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
