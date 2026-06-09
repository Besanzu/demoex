<?php
namespace App\Controllers;

use App\Models\UserDBStorage;
use App\Views\UserTemplate;
use App\Routers\Router;

class Users {
    public function getLogin(): string {
        return (new UserTemplate())->getLoginTemplate();
    }

    public function auth($login, $password) {
        $storage = new UserDBStorage();
        $user = $storage->getUser($login, $password);
        if ($user) {
            $_SESSION['user_id'] = $user['id_user'];
            $_SESSION['user_name'] = $user['fio'];
            $_SESSION['user_role'] = $user['role'];
            Router::addFlash("Добро пожаловать, {$user['fio']}!");
        } else {
            Router::addFlash("Неверный логин или пароль", "alert-danger");
        }
        return $user;
    }

    public function register() {
        $objTemplate = new UserTemplate();
        if (isset($_POST['email'], $_POST['password'], $_POST['fio'])) {
            $storage = new UserDBStorage();
            $result = $storage->registerUser($_POST);

            if ($result === true) {
                $user = $storage->getUser($_POST['email'], $_POST['password']);
                if ($user) {
                    $_SESSION['user_id'] = $user['id_user'];
                    $_SESSION['user_name'] = $user['fio'];
                    $_SESSION['user_role'] = $user['role'];
                }
                Router::addFlash("Регистрация успешна!");
                header('Location: /');
                return '';
            } elseif ($result === false) {
                Router::addFlash("Пользователь с таким email уже существует", "alert-danger");
            } elseif ($result === 'invalid_email') {
                Router::addFlash("Некорректный формат email", "alert-danger");
            } elseif ($result === 'short_password') {
                Router::addFlash("Пароль должен быть не менее 6 символов", "alert-danger");
            }
        }
        return $objTemplate->getRegisterTemplate();
    }

    public function logout() {
        unset($_SESSION['user_id'], $_SESSION['user_name'], $_SESSION['user_role']);
        Router::addFlash("Вы вышли из системы");
        header('Location: /');
        exit;
    }
}