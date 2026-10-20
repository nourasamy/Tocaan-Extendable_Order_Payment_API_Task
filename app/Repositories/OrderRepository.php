<?php

namespace App\Repositories;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\TaxType;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    public function paginate($perPage = 15)
    {
        $orders = Order::query();
        if(request()->has('status')){
            $orders = $orders->status(request()->get('status'));
        }
        return $orders->paginate($perPage);
    }

    public function findById($id)
    {
        return Order::find($id);
    }

    public function create($data)
    {
        try{
            DB::beginTransaction();

            $order = Order::create([
                'client_id' => $data['client_id'],
                'tax' => $data['tax'] ?? 0,
                'tax_type' => $data['tax_type'] ?? null,
                'discount' => $data['discount'] ?? 0,
                'discount_type' => $data['discount_type'] ?? null,
                'status' => OrderStatus::Pending,
                'subtotal' => 0,
                'grand_total' => 0,
            ]);

            $order->orderDetails()->createMany(array_map(function ($item){
                $item['price'] = Item::find($item['item_id'])->price;
                return [
                    'item_id' => $item['item_id'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'total' => $item['price'] * $item['qty'],
                ];
            }, $data['items']));

            $subtotal = $order->orderDetails->sum('total');
            $calculatedTax = $this->calculateTax($subtotal, $order->tax, $order->tax_type);
            $calculatedDiscount = $this->calculateDiscount($subtotal, $order->discount, $order->discount_type);

            $grandTotal = $subtotal + $calculatedTax - $calculatedDiscount;

            $order->subtotal = $subtotal;
            $order->grand_total = $grandTotal;
            $order->save();

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }

    }

    public function update($id, $data)
    {
       try {
            DB::beginTransaction();
            $order = $this->findById($id);
            $order->update([
                'client_id' => $data['client_id'],
                'tax' => $data['tax'] ?? 0,
                'tax_type' => $data['tax_type'] ?? null,
                'discount' => $data['discount'] ?? 0,
                'discount_type' => $data['discount_type'] ?? null,
            ]);
            $order->orderDetails()->delete();
            $order->orderDetails()->createMany(array_map(function ($item) {
                $item['price'] = Item::find($item['item_id'])?->price;
                return [
                    'item_id' => $item['item_id'],
                    'price' => $item['price'],
                    'qty' => $item['qty'],
                    'total' => $item['price'] * $item['qty'],
                ];
            }, $data['items']));

            $subtotal = $order->orderDetails->sum('total');
            $calculatedTax = $this->calculateTax($subtotal, $order->tax, $order->tax_type);
            $calculatedDiscount = $this->calculateDiscount($subtotal, $order->discount, $order->discount_type);
            $grandTotal = $subtotal + $calculatedTax - $calculatedDiscount;

            $order->subtotal = $subtotal;
            $order->grand_total = $grandTotal;
            $order->save();

            DB::commit();

            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            return null;
        }
    }

    public function calculateTax($subtotal, $tax, $taxType)
    {
        if ($taxType == TaxType::Percent) {
            return ($subtotal * $tax) / 100;
        }
        return $tax;
    }

    public function calculateDiscount($subtotal, $discount, $discountType)
    {
        if ($discountType == DiscountType::Percent) {
            return ($subtotal * $discount) / 100;
        }
        return $discount;
    }

    public function changeStatus($id, $data)
    {
        try {
            $order = $this->findById($id);
            $order->status = $data['status'];
            $order->save();

            return $order;
        } catch (\Exception $e) {
            return null;
        }
    }


    public function delete($id)
    {
        try{
            DB::beginTransaction();

            $order = $this->findById($id);
            $order->orderDetails()->delete();
            $order->delete();
            DB::commit();

            return true;

        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }

    }

}
