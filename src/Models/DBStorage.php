<?php
namespace App\Models;

use PDO;

class DBStorage 
{
    // АДАПТАЦИЯ: измени имя БД, пользователя и пароль
    const DNS = 'mysql:dbname=exam_template;host=localhost';
    const USER = 'root';
    const PASSWORD = '';

    protected $connection;

    public function __construct(){
        $this->connection = new PDO(self::DNS, self::USER, self::PASSWORD);
        $this->connection->exec("set names utf8mb4");
    }
}