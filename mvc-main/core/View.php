<?php
namespace Core;

class View {
    public static function render(string $view, array $data = []): string {
        extract($data);

        ob_start();
        include __DIR__ . '/../views/' . $view . '.php';
        return ob_get_clean();
    }
}
