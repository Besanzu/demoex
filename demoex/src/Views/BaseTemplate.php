<?php
namespace App\Views;

class BaseTemplate {  
    public function getBaseTemplate() {
        global $user_id, $user_name, $user_role;

        $template = <<<END
        <!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>%s</title>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        </head>
        <body>
        <div class="container">
            <nav class="navbar navbar-expand-lg bg-light mb-2">
                <div class="container-fluid">
                    <a class="navbar-brand" href="/">Универсальная ИС</a>
                    <div class="collapse navbar-collapse">
                        <div class="navbar-nav">
                            <a class="nav-link" href="/">Главная</a>
                            <a class="nav-link" href="/manual">Руководство</a>
END;
        if ($user_id > 0) {
            $template .= '<a class="nav-link" href="/items">Записи</a>';
        }
        $template .= '</div></div>';

        if ($user_id > 0) {
            $template .= <<<LINE
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">{$user_name} ({$user_role})</li>
                    <li class="nav-item"><a class="nav-link" href="/logout">Выход</a></li>
                </ul>
LINE;
        } else {
            $template .= <<<LINE
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="/login">Вход</a></li>
                    <li class="nav-item"><a class="nav-link" href="/register">Регистрация</a></li>
                </ul>
LINE;
        }
        $template .= '</nav>';

        if (isset($_SESSION['flash'])) {
            $class = $_SESSION['flash_class'] ?? 'alert-info';
            $template .= "<div class='alert {$class} alert-dismissible'>{$_SESSION['flash']}
                <button type='button' class='btn-close' data-bs-dismiss='alert'></button></div>";
            unset($_SESSION['flash'], $_SESSION['flash_class']);
        }

        $template .= '%s</div></body></html>';
        return $template;
    }
}