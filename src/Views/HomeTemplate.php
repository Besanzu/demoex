<?php
namespace App\Views;

class HomeTemplate extends BaseTemplate {
    public function getHomeTemplate(): string {
        $template = parent::getBaseTemplate();
        $str = '<div class="row mt-5">';
        $str .= '<p>Добро пожаловать в универсальную информационную систему.</p>';
        $str .= '<p>Для работы необходимо <a href="/login">войти</a> или <a href="/register">зарегистрироваться</a>.</p>';
        $str .= '</div>';
        return sprintf($template, 'Главная', $str);
    }
}