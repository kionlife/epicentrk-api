<?php

namespace KeepinCrm\Paths;

class Paths
{
    public $baseUrl;

    public function __construct()
    {
        $this->baseUrl = 'https://api.keepincrm.com/v1';
    }

    public function login(): string
    {
        return $this->baseUrl . '';
    }

    public function orders(): string
    {
        return 'https://core-api.epicentrm.cloud/v2/oms/orders';
    }

    public function products(): string
    {
        return 'https://core-api.epicentrm.cloud/v2/pim/products';
    }
}