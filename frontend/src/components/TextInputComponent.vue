<script>
export default {
  name: 'TextInputComponent',
  props: {
    modelValue: {
      type: String,
      default: ''
    },
    loading: {
      type: Boolean,
      default: false
    }
  },
  emits: ['update:modelValue', 'analyze', 'fillRandom', 'fileSelected'],
  data() {
    return {
      pickedFileName: ''
    };
  },
  methods: {
    handleFile(e) {
      this.pickedFileName = '';
      const file = e?.target?.files?.[0];
      if (!file) return;
      if (!/^text\//.test(file.type) && !/\.txt$/i.test(file.name)) {
        this.$emit('fileError', 'Поддерживаются только текстовые файлы (.txt)');
        e.target.value = '';
        return;
      }
      const reader = new FileReader();
      reader.onload = () => {
        const content = String(reader.result || '');
        this.$emit('update:modelValue', content);
        this.pickedFileName = file.name;
      };
      reader.onerror = () => {
        this.$emit('fileError', 'Не удалось прочитать файл');
      };
      reader.readAsText(file, 'utf-8');
    },
    submitAndClear() {
      this.$emit('analyze');
      this.$emit('update:modelValue', '');
      this.clearFile();
    },
    clearFile() {
      this.pickedFileName = '';
      if (this.$refs && this.$refs.fileInput) {
        try { this.$refs.fileInput.value = ''; } catch {}
      }
    }
  }
}
</script>

<template>
  <form @submit.prevent="submitAndClear" class="form">
    <label class="label" for="text-area">Текст</label>
    <textarea 
      id="text-area" 
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      rows="8" 
      class="textarea" 
      placeholder="Введите или вставьте текст для анализа..."
    ></textarea>
    
    <div class="file-row">
      <label class="file-label">
        <input ref="fileInput" class="file" type="file" accept=".txt,text/plain" @change="handleFile" />
        <span class="file-btn">Выбрать файл</span>
        <span class="file-name" v-if="pickedFileName">{{ pickedFileName }}</span>
        <span class="file-hint" v-else>Поддерживаются .txt</span>
      </label>
    </div>
    
    <div class="actions">
      <button 
        class="btn btn-primary" 
        type="button" 
        :disabled="loading" 
        @click="$emit('fillRandom')"
      >
        Сгенерировать текст
      </button>
      <button 
        class="btn btn-primary" 
        type="submit" 
        :disabled="loading || !(modelValue && modelValue.trim())"
      >
        {{ loading ? 'Анализ…' : 'Анализировать' }}
      </button>
    </div>
  </form>
</template>

<style scoped>
.form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.label {
  font-weight: 600;
  color: #0f172a;
  margin-bottom: 8px;
  display: block;
}

.textarea {
  padding: 12px;
  border: 2px solid #e2e8f0;
  border-radius: 10px;
  font-size: 16px;
  font-family: inherit;
  resize: vertical;
  min-height: 120px;
  transition: border-color 0.2s ease;
}

.textarea:focus {
  outline: none;
  border-color: #0ea5e9;
  box-shadow: 0 0 0 3px rgba(14, 165, 233, 0.1);
}

.actions {
  display: flex;
  justify-content: flex-start;
  gap: 12px;
}

.file-row { 
  display: flex; 
  justify-content: space-between; 
  align-items: center; 
}

.file-label { 
  display: inline-flex; 
  align-items: center; 
  gap: 10px; 
}

.file { 
  display: none; 
}

.file-btn {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  background: #0ea5e9;
  color: #fff;
  height: 44px;
  width: 180px;
  border-radius: 10px;
  font-weight: 600;
  cursor: pointer;
  box-shadow: 0 4px 12px rgba(14, 165, 233, .2);
}

.file-name { 
  color: #0f172a; 
  font-weight: 600; 
}

.file-hint { 
  color: #64748b; 
}

.btn {
  appearance: none;
  border: 0;
  border-radius: 10px;
  height: 44px;
  padding: 0 16px;
  width: 180px;
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
</style>
