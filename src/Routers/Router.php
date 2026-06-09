<?php
namespace App\Routers;

use App\Controllers\Home;
use App\Controllers\Users;
use App\Controllers\Items;

class Router {
    public function route(string $url): ?string {
        $path = parse_url($url, PHP_URL_PATH);
        $pieces = explode("/", $path);
        $resource = $pieces[1] ?? '';
        
        $imageName = '';
        if (!empty($_FILES['image']['name'])) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true);
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $imageName = uniqid('img_') . '.' . $ext;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], 'uploads/' . $imageName)) {
                $imageName = '';
                self::addFlash("Ошибка загрузки изображения", "alert-danger");
            }
        }

        switch ($resource) {
            case 'login':
                $ctrl = new Users();
                if (isset($_POST['login'], $_POST['password'])) {
                    if ($ctrl->auth($_POST['login'], $_POST['password'])) {
                        header('Location: /');
                        return '';
                    }
                }
                return $ctrl->getLogin();

            case 'register':
                return (new Users())->register();

            case 'logout':
                return (new Users())->logout();

            case 'items':
                return (new Items())->getAll();

            case 'add_item':
                $ctrl = new Items();
                if (!empty($_POST['title'])) {
                    $row = [
                        'title' => $_POST['title'],
                        'category' => $_POST['category'] ?? '',
                        'description' => $_POST['description'] ?? '',
                        'price' => $_POST['price'] ?? 0,
                        'status' => $_POST['status'],
                        'user_id' => $_POST['user_id'],
                        'manager_id' => $_POST['manager_id'] ?? null,
                        'date_item' => $_POST['date_item'],
                        'image' => $imageName
                    ];
                    if ($ctrl->addItem($row)) {
                        self::addFlash("Запись добавлена");
                        header('Location: /items');
                        return '';
                    }
                }
                return $ctrl->getForm();

            case 'edit_item':
                $ctrl = new Items();
                if (!empty($_POST['title']) && !empty($_POST['id_item'])) {
                    $existingImage = $_POST['existing_image'] ?? '';
                    $row = [
                        'id_item' => $_POST['id_item'],
                        'title' => $_POST['title'],
                        'category' => $_POST['category'] ?? '',
                        'description' => $_POST['description'] ?? '',
                        'price' => $_POST['price'] ?? 0,
                        'status' => $_POST['status'],
                        'user_id' => $_POST['user_id'],
                        'manager_id' => $_POST['manager_id'] ?? null,
                        'date_item' => $_POST['date_item'],
                        'image' => $imageName ?: $existingImage
                    ];
                    if ($ctrl->editItem($row)) {
                        self::addFlash("Запись обновлена");
                        header('Location: /items');
                        return '';
                    }
                }
                return $ctrl->getForm($_POST['id_item'] ?? 0);

            case 'delete_item':
                $ctrl = new Items();
                if (!empty($_POST['id_item'])) {
                    $ctrl->deleteItem($_POST['id_item']);
                    self::addFlash("Запись удалена");
                    header('Location: /items');
                    return '';
                }
                break;

            case 'manual':
                return (new \App\Controllers\Manual())->show();

            default:
                return (new Home())->get();
        }
        return '';
    }

    public static function addFlash($str, $type = 'alert-info') {
        $_SESSION['flash'] = $str;
        $_SESSION['flash_class'] = $type;
    }
}