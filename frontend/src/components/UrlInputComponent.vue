<script>
export default {
  name: 'UrlInputComponent',
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
  emits: ['update:modelValue', 'analyze'],
  methods: {
    submitAndClear() {
      this.$emit('analyze');
      this.$emit('update:modelValue', '');
    }
  }
}
</script>

<template>
  <form @submit.prevent="submitAndClear" class="form">
    <label class="label" for="url">URL веб-страницы</label>
    <textarea 
      id="url" 
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
      rows="8" 
      class="textarea" 
      placeholder="Введите URL веб-страницы для анализа..."
    ></textarea>
    
    <div class="actions">
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
