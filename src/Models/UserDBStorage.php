<?php
namespace App\Models;

use PDO;

class UserDBStorage extends DBStorage
{
    public function getUser($email, $password) {
        $stmt = $this->connection->prepare("SELECT * FROM users WHERE email=:email AND password=:password");
        $stmt->execute(['email' => $email, 'password' => $password]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function registerUser($row) {
        if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
            return 'invalid_email';
        }
        if (strlen($row['password']) < 6) {
            return 'short_password';
        }

        $check = $this->connection->query("SELECT id_user FROM users WHERE email='".$row['email']."'");
        if ($check->fetch()) {
            return false;
        }

        $sql = "INSERT INTO users (email, password, fio, role) VALUES ('".$row['email']."', '".$row['password']."', '".$row['fio']."', 'user')";
        $result = $this->connection->query($sql);
        return $result ? true : false;
    }

    public function getAllUsers() {
        return $this->connection->query("SELECT id_user, fio FROM users WHERE role='user'")->fetchAll();
    }
    
    public function getManagers() {
        return $this->connection->query("SELECT id_user, fio FROM users WHERE role='editor'")->fetchAll();
    }
}