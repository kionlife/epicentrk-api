<?php

namespace Api;

require_once __DIR__ . '/vendor/autoload.php';

use Api\Methods\Methods;

class Main {
    public Methods $api;

    public function __construct() {
        $this->api = new Methods();
    }

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


    public function getAllProducts() {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://api.keepincrm.com/v1/materials');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'X-Auth-Token: 6Yusb91WgZAuM2spKCBmaxf4';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = json_decode(curl_exec($ch), true);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);

        foreach ($result['items'] as $product) {
            $data[$product['sku']] = $product['custom_fields']['Epicentrk ID'] ?? 0;
        }

        return $data;
    }

    public function getNewOrders()
    {
        $filter['statusCode'][] = 'confirmed_by_merchant';
        $orders = $this->api->getOrders($filter);


//        return json_encode($orders);
        return $orders;
    }

    public function getOrderByNumber($number)
    {
        $filter['number'] = $number;
        $orders = $this->api->getOrders($filter);

        return $orders;
    }

    public function checkNewOrders() {
        $clientExist = true;
        if ($_GET['number']) {
            $newOrders = $this->getOrderByNumber($_GET['number']);
        } else {
            $newOrders = $this->getNewOrders();
        }

        if ($newOrders['total'] > 0) {
            $keepinAll = $this->getAllProducts();
            foreach ($newOrders['items'] as $order) {
                $converted_order = $order;
                $npData = $this->api->getNpData(array($order['address']['shipment']['settlementId'], $order['address']['shipment']['officeId']));
                $converted_order['address']['shipment']['filiale'] = $npData->title;
                $converted_order['address']['shipment']['address'] = $npData->address;
                $converted_order['address']['shipment']['provider'] = 'Nova poshta';

                if (!$clientExist) {
                    $converted_order['address']['custom']['name'] = '0';
                    $converted_order['address']['custom']['phone'] = '0';
                    $converted_order['address']['custom']['email'] = '0@0.0';
                } else {
                    $converted_order['address']['custom']['name'] = $order['address']['lastName'] . ' ' . $order['address']['firstName'];
                    $converted_order['address']['custom']['phone'] = $order['address']['phone'];
                    $converted_order['address']['custom']['email'] = $order['address']['email'];
                }

                foreach ($order['items'] as $key => $product) {
                    $converted_order['items'][$key]['sku'] = array_search($product['productId'], $keepinAll) ?? '0';
                }
            }
            return $this->sendWebhook($converted_order);
        } else {
            return false;
        }

    }

    public function sendWebhook($order) {
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.keepincrm.com/callbacks/webhook/BegHiVZeLz0d',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => json_encode($order),
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function changeStatus($orderId) {
        $this->api->changeOrderStatus($orderId);
        $data = $this->api->getOrderById($orderId);

    }



}



$index = new Main();

if ($_GET['method'] == 'changeStatus') {
    $index->changeStatus($_POST['order_id']);
} else {
    var_dump($index->checkNewOrders());
}