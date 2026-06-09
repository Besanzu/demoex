<?php
namespace App\Views;

class UserTemplate extends BaseTemplate {
    public function getLoginTemplate(): string {
        $template = parent::getBaseTemplate();
        $str = '<div class="col-md-4 offset-md-4"><h3>Вход в систему</h3>
            <form method="post" action="/login">
                <div class="mb-3"><input name="login" class="form-control" placeholder="Email" required></div>
                <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Пароль" required></div>
                <button class="btn btn-primary">Войти</button>
            </form></div>';
        return sprintf($template, 'Вход', $str);
    }

    public function getRegisterTemplate(): string {
        $template = parent::getBaseTemplate();
        $str = '<div class="col-md-4 offset-md-4"><h3>Регистрация</h3>
        <form method="post" action="/register">
            <div class="mb-3"><input name="fio" class="form-control" placeholder="ФИО" required></div>
            <div class="mb-3"><input type="email" name="email" class="form-control" placeholder="Email" required></div>
            <div class="mb-3"><input type="password" name="password" class="form-control" placeholder="Пароль" minlength="6" required></div>
            <div class="mb-3"><input name="phone" class="form-control" placeholder="Телефон (8XXXXXXXXXX)" pattern="8[0-9]{10}" title="Введите номер в формате 8XXXXXXXXXX"></div>
            <button class="btn btn-primary">Зарегистрироваться</button>
        </form></div>';
        return sprintf($template, 'Регистрация', $str);
    }
}