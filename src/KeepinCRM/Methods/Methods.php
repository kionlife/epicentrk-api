<?php

namespace KeepinCrm\Methods;

use KeepinCrm\Client\Client;
use KeepinCrm\Paths\Paths;

class Methods {
    private Paths $paths;
    private Client $client;

    function __construct() {
        $this->paths = new Paths();
        $this->client = new Client();
	}
	
	public function getOrders($filter, $offset = 0, $limit = 50) {

        $params = [
            'offset' => 0,
            'limit'  => 50,
            'filter' => $filter
        ];

        return $this->client->get($this->paths->orders(), $params);
	}

	public function getProducts($filter, $offset = 0, $limit = 50) {

        $params = [
            'offset' => 0,
            'limit'  => 50,
            'filter' => $filter
        ];

        return $this->client->get($this->paths->products(), $params);
	}
}