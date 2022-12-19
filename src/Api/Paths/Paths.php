<?php

namespace Api\Paths;

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

    public function np($data): string
    {
        return sprintf('https://core-api.epicentrm.cloud/v2/deliveries/nova_poshta/settlements/%s/offices/%s',$data[0], $data[1]);
    }
}