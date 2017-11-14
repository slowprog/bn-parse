<?php

namespace App\Controllers;

use App\Core\Controller;

/**
 * Контроллер для страниц с ошибками.
 */
class ErrorsController extends Controller
{
    /**
     * Страница с ошибкой 404 – страница не найдена.
     */
    public function indexAction($message = null)
    {
        require VIEWS . '/errors/index.php';
    }
}
