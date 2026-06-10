<?php

namespace View;

class View
{
    public static function render(string $viewPath, array $data = [], string $layout = 'Layout/main'): void
    {
        $viewFile = ROOT . '/src/View/' . $viewPath . '.php';
        $layoutFile = ROOT . '/src/View/' . $layout . '.php';

        if (!file_exists($viewFile)) {
            throw new \RuntimeException("View not found: {$viewFile}");
        }

        if (!file_exists($layoutFile)) {
            throw new \RuntimeException("Layout not found: {$layoutFile}");
        }

        $data = array_merge([
            'pageTitle' => 'Vite & Gourmand',
            'metaDescription' => '',
            'cssFiles' => [],
            'jsFiles' => [],
        ], $data);

        ob_start();
        extract($data, EXTR_SKIP);
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }
}