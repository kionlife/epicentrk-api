<?php

namespace Api;

require_once __DIR__ . '/vendor/autoload.php';

use Api\Methods\Methods;

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
        $api = new Methods();
        $filter['statusCode'][] = 'canceled';
        $orders = $api->getOrders($filter);


        return $orders;
    }

}

$index = new Main();
var_dump($index->getNewOrders());