<?php

namespace App\Model;

use phpQuery as PhpQuery;
use Exception;

class Station
{
    /**
     * @var [id => [title]]
     */
    private $stations = [];

    /**
     * Выдёргиваем из сессии массив постов.
     *
     * @return void
     */
    public function __construct()
    {
        if (isset($_SESSION['stations']) ) {
            $this->stations = $_SESSION['stations'];
        } else {
            $pq = PhpQuery::newDocument(file_get_contents('http://www.bn.ru'));

            $stationsText = $pq->find('.wrapper-popup--metro svg text');

            foreach ($stationsText as $station) {
                $stationHtml = pq($station);
                $title = trim(strip_tags($stationHtml->html()));
                $id = trim($stationHtml->attr('data-id'));

                if ($id && $title) {
                    $this->add($id, $title);
                }
            }
        }
    }

    /**
     * Складывает обратно в сессию массив с постами.
     *
     * @return void
     */
    public function __destruct()
    {
        $_SESSION['stations'] = $this->stations;
    }

    /**
     * Добавляет станцию метро.
     *
     * @param string $id
     * @param string $title
     *
     * @return void
     */
    public function add($id, $title)
    {
        $this->stations[$id] = [
            'title' => $title,
        ];
    }

    /**
     * Возвращает все станции.
     *
     * @return [id => [title]]
     */
    public function getAll()
    {
        return $this->stations;
    }

    /**
     * Возвращает данные станции по id.
     *
     * @param string $id
     *
     * @return [title]
     */
    public function getById($id)
    {
        if (!isset($this->stations[$id])) {
            throw new Exception('Станции не найдено.');
        }

        return $this->stations[$id];
    }

    /**
     * Удаляет пост по псевдониму.
     *
     * @param string $id
     *
     * @return void
     */
    public function delete($id)
    {
        if (!isset($this->stations[$id])) {
            throw new Exception('Станции не найдено.');
        }

        unset($this->stations[$id]);
    }

    /**
     * Возврвщает кол-во станций.
     *
     * @return int
     */
    public function count()
    {
        return count($this->stations);
    }
}
