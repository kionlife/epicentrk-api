<?php

namespace Api\Client;

use Api\Paths\Paths;
use Dotenv\Dotenv;

class Client
{
    private $token, $refresh, $credentials;
    private Paths $paths;

    function __construct()
    {
        session_start();
        /**
         *
         */
        $config = Dotenv::createImmutable(dirname(__DIR__, 3));
        $config->load();
        $this->credentials = ['email' => $_ENV['EMAIL'], 'password' => $_ENV['PASSWORD']];
        $this->paths = new Paths();

        $this->auth();
    }

    public function auth()
    {
        $response = $this->post($this->paths->login(), $this->credentials);

        $this->token = $_SESSION['auth'] = $response->token->auth;
        $this->refresh = $_SESSION['refresh'] = $response->token->refresh;

        return $response;
    }

    public function post($url, $params = array())
    {

        if ($params)
            $fields = '?' . http_build_query($params);
        else
            $fields = '';

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url . $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_POST, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));
        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }

//TODO add check if auth success

    public function get($url, $params = array(), $array = false)
    {

        if ($params)
            $fields = '?' . http_build_query($params);
        else
            $fields = '';

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url . $fields);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);
        curl_setopt($curl, CURLOPT_POST, 0);
        curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer ' . $this->token));

        $response = curl_exec($curl);

        curl_close($curl);

        return json_decode($response, $array);

    }

}