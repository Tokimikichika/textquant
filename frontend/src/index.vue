<script>
import TextInputComponent from './components/TextInputComponent.vue'
import UrlInputComponent from './components/UrlInputComponent.vue'
import ResultsComponent from './components/ResultsComponent.vue'

export default {
  name: 'IndexAnalyzer',
  components: {
    TextInputComponent,
    UrlInputComponent,
    ResultsComponent
  },
  data() {
    return {
      text: '',
      loading: false,
      error: '',
      result: null,
      inputType: 'text',
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
          this.error = this.inputType === 'url' ? 'Введите URL' : 'Введите текст';
          return;
        }

        let endpoint, body;
        if (this.inputType === 'url') {
          endpoint = '/api/v1/analyze/url';
          body = { url: payloadText };
        } else {
          endpoint = '/api/v1/analyze/text';
          body = { text: payloadText };
        }

        const res = await fetch(endpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(body)
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
    },
    async fillRandom() {
      this.error = '';
      this.loading = true;
      try {
        const res = await fetch(`/api/v1/text/random`);
        let json;
        try {
          json = await res.json();
        } catch {
          throw new Error('Некорректный ответ сервера');
        }
        if (!res.ok) throw new Error(json.error || 'Request failed');
        this.text = String(json.text || '');
      } catch (e) {
        this.error = e.message || String(e);
      } finally {
        this.loading = false;
      }
    },
    switchInputType(type) {
      this.inputType = type;
      this.text = '';
      this.error = '';
      this.result = null;
    },
    handleFileError(error) {
      this.error = error;
    }
  }
}
</script>

<template>
  <main class="page">
    <header class="header">
      <h1 class="title">Текстовый анализатор</h1>
      <p class="subtitle">Вставьте текст или URL и получите статистику</p>
    </header>
    
    <div class="main-grid" :class="{ single: !result }">
      <section class="card">
        <div class="input-type-switcher">
          <button 
            type="button" 
            class="switcher-btn" 
            :class="{ active: inputType === 'text' }"
            @click="switchInputType('text')"
          >
            Текст
          </button>
          <button 
            type="button" 
            class="switcher-btn" 
            :class="{ active: inputType === 'url' }"
            @click="switchInputType('url')"
          >
            URL
          </button>
        </div>
        
        <TextInputComponent 
          v-if="inputType === 'text'"
          v-model="text"
          :loading="loading"
          @analyze="analyze"
          @fillRandom="fillRandom"
          @fileError="handleFileError"
        />
        
        <UrlInputComponent 
          v-if="inputType === 'url'"
          v-model="text"
          :loading="loading"
          @analyze="analyze"
        />
        
        <p v-if="error" class="alert">{{ error }}</p>
      </section>

      <ResultsComponent v-if="result" :result="result" />
    </div>
  </main>
</template>

<style>
.page {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  padding: 20px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.header {
  text-align: center;
  margin-bottom: 40px;
  max-width: 1140px;
  width: 100%;
}

.title {
  font-size: 48px;
  font-weight: 700;
  color: #fff;
  margin: 0 0 16px;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.subtitle {
  font-size: 20px;
  color: rgba(255, 255, 255, 0.9);
  margin: 0;
  font-weight: 400;
}

.main-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 32px;
  max-width: 1140px;
  width: 100%;
}

.main-grid.single {
  grid-template-columns: 1fr;
  max-width: 600px;
}

.card {
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border: 1px solid rgba(255, 255, 255, 0.2);
}

.input-type-switcher {
  display: flex;
  gap: 8px;
  margin-bottom: 16px;
}

.switcher-btn {
  appearance: none;
  border: 2px solid #e2e8f0;
  background: #fff;
  color: #64748b;
  border-radius: 8px;
  padding: 8px 16px;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s ease;
}

.switcher-btn:hover {
  border-color: #0ea5e9;
  color: #0ea5e9;
}

.switcher-btn.active {
  background: #0ea5e9;
  border-color: #0ea5e9;
  color: #fff;
}

.alert {
  margin-top: 10px;
  color: #b91c1c;
  font-weight: 500;
}

@media (max-width: 768px) {
  .main-grid {
    grid-template-columns: 1fr;
    gap: 20px;
  }
  
  .title {
    font-size: 32px;
  }
  
  .subtitle {
    font-size: 16px;
  }
}
</style>
