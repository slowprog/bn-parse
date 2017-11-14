<?php

namespace App\Core;

use App\Model\Station;
use App\Model\Flat;

/**
 * Это базовый контроллер, от которого наследуются все другие контроллеры. Тут
 * инициализируется модель, которая может использоваться дальше в потомках.
 */
abstract class Controller
{
    /**
     * @var Station
     */
    protected $station;

    /**
     * Создание модели данных.
     */
    public function __construct()
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->station = new Station();
        $this->flat    = new Flat();
    }
}
