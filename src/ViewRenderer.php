<?php

namespace Tokimikichika\Find;

/**
 * Рендерер для шаблонов
 * Отвечает за отображение HTML шаблонов с данными
 */
class ViewRenderer
{
    private string $templatesPath;

    public function __construct(string $templatesPath = 'templates')
    {
        $this->templatesPath = rtrim($templatesPath, '/');
    }

    /**
     * Рендерит шаблон с данными
     */
    public function render(string $template, array $data = []): string
    {
        $templatePath = $this->templatesPath . '/' . $template;
        
        if (!file_exists($templatePath)) {
            throw new \RuntimeException("Шаблон не найден: {$template}");
        }

        extract($data, EXTR_SKIP);

        ob_start();
        include $templatePath;
        $content = ob_get_clean();

        return $content;
    }

    /**
     * Рендерит шаблон в базовый layout
     */
    public function renderWithLayout(string $template, array $data = [], string $layout = 'layout'): string
    {
        $content = $this->render($template, $data);
        $layoutData = array_merge($data, ['content' => $content]);
        
        return $this->render($layout, $layoutData);
    }

    /**
     * Проверяет существование шаблона
     */
    public function templateExists(string $template): bool
    {
        return file_exists($this->templatesPath . '/' . $template);
    }

    /**
     * Возвращает путь к папке шаблонов
     */
    public function getTemplatesPath(): string
    {
        return $this->templatesPath;
    }
}
