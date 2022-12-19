<?php

namespace Api\Methods;

use Api\Client\Client;
use Api\Paths\Paths;

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

        return $this->client->get($this->paths->orders(), $params, true);
	}

	public function getProducts($filter, $offset = 0, $limit = 50) {

        $params = [
            'offset' => 0,
            'limit'  => 50,
            'filter' => $filter
        ];

        return $this->client->get($this->paths->products(), $params);
	}

	public function getNpData($data) {

        return $this->client->get($this->paths->np($data));
	}
}