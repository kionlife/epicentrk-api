<?php

namespace Api;

require_once 'Api.php';

use Api\Methods;

class Main {

    /**
     * Фільтр замовлень
     * Статус замовлення ключ - ['statusCode'][] =
     *      'new' - Нові товари
     *      'confirmed_by_merchant' - Підтверджено продавцем
     *      'sent' - Відправлено
     *      'canceled' - Скасовано
     * Номер замовлення - ['number'] = xxxxxxxxxx
     * Телефон покупця - ['phone'] = 380XXXXXXXXX
     */

    public function getNewOrders()
    {
        $api = new \Api\Methods;
        $filter['statusCode'][] = 'canceled';
        $orders = $api->getOrders($filter);

        return $orders;
    }

}

$index = new main();

var_dump($index->getNewOrders());