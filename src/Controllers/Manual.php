<?php
namespace App\Controllers;

class Manual {
    public function show(): string {
        return file_get_contents('manual.html');
    }
}