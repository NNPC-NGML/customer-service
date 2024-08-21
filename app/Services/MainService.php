<?php

use App\Services\CustomerService;



class MainService
{


    private CustomerService $customer;

    public function create(array $data)
    {
        if ($data['entity'] === 'customer') {
            $this->customer->create($data);
        }
    }
}
