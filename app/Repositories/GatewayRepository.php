<?php

namespace App\Repositories;

use App\Models\PaymentGateway;

class GatewayRepository
{

    public function all()
    {
        return PaymentGateway::all();
    }


    public function paginate($perPage = 15)
    {
        return PaymentGateway::paginate($perPage);
    }


    public function findById($id)
    {
        return PaymentGateway::find($id);
    }


    public function findByKey($handlerKey)
    {
        return PaymentGateway::where('handler_key', $handlerKey)->first();
    }


    public function create(array $data)
    {
        return PaymentGateway::create($data);
    }


    public function update($id, array $data)
    {
        $gateway = $this->findById($id);
        $gateway->update($data);
        return $gateway;
    }


    public function delete($id)
    {
        $gateway = $this->findById($id);
        return $gateway->delete();
    }

}
