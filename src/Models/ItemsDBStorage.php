<?php
namespace App\Models;

class ItemsDBStorage extends DBStorage
{
    public function getAllItems() {
        global $user_id, $user_role;
        
        $sql = "SELECT i.id_item, i.user_id, i.date_item, 
                i.title, i.price, i.category, i.description,
                u.fio as user_fio, m.fio as manager_fio, i.status, i.image
            FROM items as i
            JOIN users u ON i.user_id = u.id_user
            LEFT JOIN users m ON i.manager_id = m.id_user
            WHERE i.deleted = 0";
        
        if ($user_role != 'editor') {
            $sql .= " AND i.user_id = " . (int)$user_id;
        }
        
        if (!empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql .= " AND i.title LIKE '%" . $search . "%'";
        }
        
        if (!empty($_GET['status'])) {
            $status = $_GET['status'];
            $sql .= " AND i.status = '" . $status . "'";
        }
        
        $sql .= " ORDER BY i.id_item DESC";
        
        return $this->connection->query($sql)->fetchAll();
    }

    public function addItem($row) {
        $sql = "INSERT INTO items (title, category, description, price, status, user_id, manager_id, date_item, image)
                VALUES (
                    '".$row['title']."',
                    '".$row['category']."',
                    '".$row['description']."',
                    '".$row['price']."',
                    '".$row['status']."',
                    '".$row['user_id']."',
                    '".$row['manager_id']."',
                    '".$row['date_item']."',
                    '".$row['image']."'
                )";
        return $this->connection->query($sql);
    }

    public function getItem($id) {
        $sql = "SELECT id_item, user_id, manager_id, date_item, title, price, category, description, status, image
                FROM items 
                WHERE id_item = " . (int)$id;
        return $this->connection->query($sql)->fetch();
    }

    public function saveItem($row) {
        $sql = "UPDATE items SET 
                title='".$row['title']."',
                category='".$row['category']."',
                description='".$row['description']."',
                price='".$row['price']."',
                status='".$row['status']."',
                user_id='".$row['user_id']."',
                manager_id='".$row['manager_id']."',
                date_item='".$row['date_item']."',
                image='".$row['image']."'
                WHERE id_item = " . (int)$row['id_item'];
        return $this->connection->query($sql);
    }

    public function deleteItem($id) {
        $sql = "UPDATE items SET deleted=1 WHERE id_item = " . (int)$id;
        return $this->connection->query($sql);
    }

    public function getUsers() {
        return $this->connection->query("SELECT id_user, fio FROM users WHERE role='user'")->fetchAll();
    }

    public function getManagers() {
        return $this->connection->query("SELECT id_user, fio FROM users WHERE role='editor'")->fetchAll();
    }
}