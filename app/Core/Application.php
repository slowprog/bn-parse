<?php

namespace App\Core;

use App\Controllers\ErrorsController;

class Application
{
    /**
     * Контроллер, методы которого должны исполнятся.
     *
     * @var string
     */
    private $controller;

    /**
     * Метод в контроллере, который необходимо вызвать.
     *
     * @var string
     */
    private $action;

    /**
     * @var array
     */
    private $params = [];

    /**
     * В конструкторе происходит разбор URL для понимания какой контроллер и
     * метода должен быть вызван и необходимо ли какие-то параметры передавать.
     *
     * @return void
     */
    public function __construct()
    {
        $this->getUrlSlice();

        // Контроллер и метод по умолчанию, если другого не указанно, index.
        $this->controller = $this->controller ? : 'home';
        $this->action     = $this->action ? : 'index';

        // Преобразуем название из нотации snake_case в CamelCase.
        $this->controller = $this->toCamelCase($this->controller, true);
        $this->action     = $this->toCamelCase($this->action);

        // Прибавляем константное окончание для контроллера и экшена.
        $this->controller .= 'Controller';
        $this->action     .= 'Action';

        // Прибавлем неймспей контроллеров
        $this->controller = 'App\\Controllers\\' . $this->controller;
    }

    /**
     * Метод для запуска отработки целевого метода контроллера. В этот момент
     * исполняется основная логика в контроллере.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Проверяем существует ли файл контроллера.
            $controllerObject = new $this->controller();

            // Проверяем существование метода у контроллера.
            if (method_exists($controllerObject, $this->action)) {
                if (!empty($this->params)) {
                    // Вызываем метод с параметрами с помощью специальной функции.
                    call_user_func_array(array($controllerObject, $this->action), $this->params);
                } else {
                    $controllerObject->{$this->action}();
                }
            } else {
                throw new \Exception('Метод у контроллера не найден.');
            }
        } catch (\Exception $e) {
            (new ErrorsController())->indexAction($e->getMessage());
        }
    }

    /**
     * Метод разбивки URL на контроллер и метод.
     *
     * @return void
     */
    private function getUrlSlice()
    {
        $url = explode('/', $_SERVER['REQUEST_URI']);
        $url = array_diff($url, array('', 'index.php'));
        $url = array_values($url);

        $this->controller = isset($url[0]) ? $url[0] : null;
        $this->action     = isset($url[1]) ? $url[1] : null;

        unset($url[0], $url[1]);

        $this->params = array_values($url);

        // echo 'Controller: ' . $this->controller . '<br>';
        // echo 'Action: ' . $this->action . '<br>';
        // echo 'Parameters: ' . print_r($this->params, true) . '<br>';
    }

    /**
     * Преобразует строчку из нотации snake_case в CamelCase.
     *
     * @param string $string      Преобразовываемая строка.
     * @param bool   $firstLetter Делать или не делать пурвую букву большой.
     *
     * @return void
     */
    private function toCamelCase($string, $firstLetter = false)
    {
        if ($firstLetter) {
            $string[0] = strtoupper($string[0]);
        }

        return preg_replace_callback(
            '/_([a-z])/',
            function (array $m) {
                return strtoupper($m[1]);
            },
            $string
        );
    }
}
