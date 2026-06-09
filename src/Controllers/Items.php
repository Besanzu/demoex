<?php
namespace App\Controllers;

use App\Models\ItemsDBStorage;
use App\Views\ItemsTemplate;

class Items {
    public function getAll(): string {
        $storage = new ItemsDBStorage();
        $items = $storage->getAllItems();
        return (new ItemsTemplate())->getListTemplate($items);
    }

    public function getForm($id = 0) {
        $storage = new ItemsDBStorage();
        $row = ($id > 0) ? $storage->getItem($id) : null;
        $users = $storage->getUsers();
        $managers = $storage->getManagers();
        return (new ItemsTemplate())->getFormTemplate($row, $users, $managers);
    }

    public function addItem($row) {
        return (new ItemsDBStorage())->addItem($row);
    }

    public function editItem($row) {
        return (new ItemsDBStorage())->saveItem($row);
    }

    public function deleteItem($id) {
        return (new ItemsDBStorage())->deleteItem($id);
    }
}