<?php

namespace View;

class View
{
    /**
     * Returns the appropriate dashboard for the connected user.
     * Public pages fall back to the home page.
     */
    public static function dashboardUrl(): string
    {
        return match ((int) ($_SESSION['id_role'] ?? 0)) {
            1 => '?page=espace_utilisateur',
            2 => '?page=espace_employe',
            3 => '?page=espace_admin',
            default => '?page=home',
        };
    }

    public static function render(string $viewPath, array $data = [], string $layout = 'Layout/main', bool $showMenu = true): void
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
            'showMenu' => $showMenu,
            'dashboardUrl' => self::dashboardUrl(),
        ], $data);

        ob_start();
        extract($data, EXTR_SKIP);
        require $viewFile;
        $content = ob_get_clean();

        require $layoutFile;
    }
}
