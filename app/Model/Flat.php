<?php

namespace App\Model;

use phpQuery as PhpQuery;

class Flat
{
    /**
     * Производит поиск на сайте данных и в результате возвращает массив данных.
     *
     * @param int[] $stationsSelected
     * @param int   $flatFrom
     * @param int   $flatTo
     * @param int   $priceFrom
     * @param int   $priceTo
     *
     * @return array
     */
    public function search($stationsSelected, $flatFrom, $flatTo, $priceFrom, $priceTo)
    {
        $url = 'http://www.bn.ru/kvartiry-vtorichka/?';

        if (count($stationsSelected)) {
            foreach ($stationsSelected as $stationSelected) {
                $url .= 'metro[]=' . $stationSelected . '&';
            }
        }

        // Возможные для поиска следующие значения:
        //
        // -1 – комната.
        // 0 – студия.
        // 1 – 1-комнатная.
        // 2 – 2-комнатная.
        // 3 – 3-комнатная.
        // 4 – 4-комнатная и больше.
        if ($flatFrom && $flatTo) {
            foreach (range($flatFrom, $flatTo) as $number) {
                $url .= 'kkv[]=' . $number . '&';
            }
        }

        if ($priceFrom) {
            $url .= 'from[]=' . $priceFrom . '&';
        }

        if ($priceTo) {
            $url .= 'to[]=' . $priceTo . '&';
        }

        $pq      = PhpQuery::newDocument(file_get_contents($url));
        $objects = $pq->find('.catalog_filter_object');
        $flats   = [];

        foreach ($objects as $object) {
            $flat = [
                'id'             => null,
                'phone'          => null,
                'address'        => null,
                'addition'       => null,
                'house_type'     => null,
                'metro'          => null,
                'room'           => null,
                'floor'          => null,
                'square_total'   => null,
                'square_live'    => null,
                'square_kitchen' => null,
                'bathroom'       => null,
                'publication'    => null,
                'subject'        => null,
                'contact'        => null,
            ];

            $objectHtml = pq($object);
            // Добавляет id предложения
            $flat['id'] = $objectHtml->attr('data-offerid');

            $phone = file_get_contents('http://www.bn.ru/api2/get-phone/?id=' . $flat['id']);

            if ($phone) {
                $phone = json_decode($phone, true);
                // Добавляет телефон для связи
                $flat['phone'] = $phone['response']['phone'];
            }

            // Получаем страницу с полными данными по квартире.
            $flatHtml = PhpQuery::newDocument(file_get_contents('http://www.bn.ru/detail/flats/' . $flat['id'] . '/'));

            // Получаем адрес квартиры.
            $address = $flatHtml->find('.address_info');
            if ($address) {
                $flat['address'] = $address->text();
            }

            // Получаем доп. текст про квартиру.
            $addition = $flatHtml->find('.object_right-info-param_right-hide p');
            if ($addition) {
                $flat['addition'] = $addition->text();
            }

            // Получаем расположенные рядом станции метро.
            $metros = $flatHtml->find('.metro_block .metro');
            foreach ($metros as $metro) {
                $flat['metro'][] = pq($metro)->text();
            }

            $params = $flatHtml->find('.object_right-info-params__row .object_right-info-params__cell--bold');
            foreach ($params as $param) {
                $paramHtml = pq($param);

                switch ($paramHtml->text()) {
                    case 'Комнат':
                        $flat['room'] = $paramHtml->next()->text();
                        break;

                    case 'Этаж':
                        $flat['floor'] = $paramHtml->next()->text();
                        break;

                    case 'Тип дома':
                        $flat['house_type'] = $paramHtml->next()->text();
                        break;

                    case 'Площадь общая':
                        $flat['square_total'] = $paramHtml->next()->text();
                        break;

                    case 'Жилая':
                        $flat['square_live'] = $paramHtml->next()->text();
                        break;

                    case 'Кухня':
                        $flat['square_kitchen'] = $paramHtml->next()->text();
                        break;

                    case 'Санузел':
                        $flat['bathroom'] = $paramHtml->next()->text();
                        break;

                    // Глазами я такой параметр не видел
                    case 'Издание':
                        $flat['publication'] = $paramHtml->next()->text();
                        break;

                    // Глазами я такой параметр не видел
                    case 'Субъект':
                        $flat['subject'] = $paramHtml->next()->text();
                        break;

                    // Глазами я такой параметр не видел
                    case 'Контакт':
                        $flat['contact'] = $paramHtml->next()->text();
                        break;
                }
            }

            $flats[] = $flat;
        }

        return $flats;
    }
}
