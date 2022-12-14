<?php

namespace Api;

class Paths
{
    public function login(): string
    {
        return 'https://core-api.epicentrm.cloud/v1/users/login';
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