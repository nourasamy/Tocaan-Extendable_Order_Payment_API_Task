<?php

namespace App\Repositories;

use App\Models\Client;

class ClientRepository
{

    public function all()
    {
        return Client::all();
    }

    public function paginate($perPage = 15)
    {
        return Client::paginate($perPage);
    }

    public function findById($id)
    {
        return Client::find($id);
    }

    public function findByPhone($phone)
    {
        return Client::where('phone', $phone)->first();
    }

    public function create(array $data)
    {
        return Client::create($data);
    }

    public function update($id, array $data)
    {
        $client = $this->findById($id);
        $client->update($data);
        return $client;
    }

    public function delete($id)
    {
        $client = $this->findById($id);
        return $client->delete();
    }

}
