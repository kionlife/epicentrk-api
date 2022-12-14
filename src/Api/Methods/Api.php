<?php

namespace Api\Methods;

/*use Api\Paths as Paths;
use Api\Client;*/

class Methods {
	private $token, $refresh, $credentials;
    private Paths $paths;

    function __construct() {
        session_start();
        /**
         *
         */
        $this->credentials = [
            'email'    => 'sales',
            'password' => ''
        ];
        $this->paths = new Paths();

        if (isset($_SESSION['auth']) && isset($_SESSION['refresh'])) {
            $this->token = $_SESSION['auth'];
            $this->refresh = $_SESSION['refresh'];
        } else {
            $this->auth();
        }
	}
	
	public function request($url, $params = array(), $method = 'GET') {
        $post = [
            'POST' => 1,
            'GET'  => 0
        ];
        if ($params)
            $fields = '?' . http_build_query($params);
        else
            $fields = '';

        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url . $fields);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_POST, $post[$method]);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));

            $response = curl_exec($curl);

            curl_close($curl);

            return json_decode($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
	}
		
	public function auth() {
        $response = $this->request($this->paths->login(), $this->credentials, 'POST');

        $_SESSION['auth'] = $response->token->auth;
        $_SESSION['refresh'] = $response->token->refresh;

        return $response;
	}
	
	public function getOrders($filter, $offset = 0, $limit = 50) {

        $params = [
            'offset' => 0,
            'limit'  => 50,
            'filter' => $filter
        ];

        return $this->request($this->paths->orders(), $params);
	}

	public function getProducts($filter, $offset = 0, $limit = 50) {

        $params = [
            'offset' => 0,
            'limit'  => 50,
            'filter' => $filter
        ];

        return $this->request($this->paths->products(), $params);
	}
}