<?php

namespace App\Controllers;

use App\Core\Controller;
use phpQuery as PhpQuery;

class HomeController extends Controller
{
    /**
     * Главная страница с поиском квартир.
     *
     * @link /index/index
     */
    public function indexAction()
    {
        $stations         = $this->station->getAll();
        $submit           = isset($_POST['submit']);
        $stationsSelected = $_POST['stations_selected'] ?? [];
        $flatFrom         = $_POST['flat_from'] ?? null;
        $flatTo           = $_POST['flat_to'] ?? null;
        $priceFrom        = $_POST['price_from'] ?? null;
        $priceTo          = $_POST['price_to'] ?? null;
        $flats            = null;

        if ($submit) {
            $flats = $this->flat->search($stationsSelected, $flatFrom, $flatTo, $priceFrom, $priceTo);
        }

        require VIEWS . '/home/index.php';
    }
}
