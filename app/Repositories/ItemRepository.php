<?php

namespace App\Repositories;

use App\Models\Item;

class ItemRepository
{
    public function all()
    {
        return Item::all();
    }

    public function paginate($perPage = 15)
    {
        return Item::paginate($perPage);
    }

    public function findById($id)
    {
        return Item::find($id);
    }


    public function create(array $data)
    {
        return Item::create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->findById($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        $item = $this->findById($id);
        return $item->delete();
    }

}
