<?php

namespace App\Controllers;

use PDO;
use App\Support\View;

abstract class BaseController
{
    protected $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    protected function render($template, $data = [])
    {
        View::render($template, $data);
    }

    protected function renderAdmin($template, $data = [])
    {
        View::renderAdmin($template, $data);
    }
}
