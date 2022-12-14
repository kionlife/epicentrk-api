<?php

namespace Api\Client;
require_once 'Paths.php';
use Api\Paths as Paths;

class Client {
	private $token, $refresh, $credentials;

    function __construct() {
        session_start();
        /**
         *
         */
        $this->credentials = [
            'email'    => '',
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
	
	public function get($url, $params = array()) {

        if ($params)
            $fields = '?' . http_build_query($params);
        else
            $fields = '';

        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url . $fields);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_POST, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));

            $response = curl_exec($curl);

            curl_close($curl);

            return json_decode($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
	}
	public function post($url, $params = array()) {

        if ($params)
            $fields = '?' . http_build_query($params);
        else
            $fields = '';

        try {
            $curl = curl_init();

            curl_setopt($curl, CURLOPT_URL, $url . $fields);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl,CURLOPT_TIMEOUT, 5);
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));

            $response = curl_exec($curl);

            curl_close($curl);

            return json_decode($response);
        } catch (\Exception $e) {
            return $e->getMessage();
        }
	}
		
	public function auth() {
        $response = $this->post($this->paths->login(), $this->credentials);

        $_SESSION['auth'] = $response->token->auth;
        $_SESSION['refresh'] = $response->token->refresh;

        return $response;
	}

}