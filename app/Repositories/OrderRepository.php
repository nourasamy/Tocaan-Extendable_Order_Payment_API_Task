<?php

namespace App\Repositories;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\TaxType;
use App\Models\Item;
use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class OrderRepository
{
    private array $relations = [
        'client',
        'orderDetails.item',
        'payments.gateway',
    ];

    public function paginate(int $perPage = 15, ?int $status = null): LengthAwarePaginator
    {
        $orders = Order::query()->with($this->relations);

        if ($status !== null) {
            $orders->status($status);
        }

        return $orders->paginate($perPage);
    }

    public function findById(int|string $id): ?Order
    {
        return Order::with($this->relations)->find($id);
    }

    public function create(array $data): Order
    {
        return DB::transaction(function () use ($data): Order {
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

            $items = Item::query()
                ->whereIn('id', array_column($data['items'], 'item_id'))
                ->get()
                ->keyBy('id');

            $order->orderDetails()->createMany(array_map(function ($item) use ($items) {
                $price = $items[$item['item_id']]->price;

                return [
                    'item_id' => $item['item_id'],
                    'price' => $price,
                    'qty' => $item['qty'],
                    'total' => $price * $item['qty'],
                ];
            }, $data['items']));

            $order->unsetRelation('orderDetails');
            $subtotal = $order->orderDetails->sum('total');
            $calculatedTax = $this->calculateTax($subtotal, $order->tax, $order->tax_type);
            $calculatedDiscount = $this->calculateDiscount($subtotal, $order->discount, $order->discount_type);

            $grandTotal = max(0, $subtotal + $calculatedTax - $calculatedDiscount);

            $order->subtotal = $subtotal;
            $order->grand_total = $grandTotal;
            $order->save();

            return $order->load($this->relations);
        });
    }

    public function update(int|string $id, array $data): Order
    {
        return DB::transaction(function () use ($id, $data): Order {
            $order = $this->findById($id);
            $order->update([
                'client_id' => $data['client_id'],
                'tax' => $data['tax'] ?? 0,
                'tax_type' => $data['tax_type'] ?? null,
                'discount' => $data['discount'] ?? 0,
                'discount_type' => $data['discount_type'] ?? null,
            ]);
            $order->orderDetails()->delete();

            $items = Item::query()
                ->whereIn('id', array_column($data['items'], 'item_id'))
                ->get()
                ->keyBy('id');

            $order->orderDetails()->createMany(array_map(function ($item) use ($items) {
                $price = $items[$item['item_id']]->price;

                return [
                    'item_id' => $item['item_id'],
                    'qty' => $item['qty'],
                    'price' => $price,
                    'total' => $price * $item['qty'],
                ];
            }, $data['items']));

            $order->unsetRelation('orderDetails');
            $subtotal = $order->orderDetails->sum('total');
            $calculatedTax = $this->calculateTax($subtotal, $order->tax, $order->tax_type);
            $calculatedDiscount = $this->calculateDiscount($subtotal, $order->discount, $order->discount_type);
            $grandTotal = max(0, $subtotal + $calculatedTax - $calculatedDiscount);

            $order->subtotal = $subtotal;
            $order->grand_total = $grandTotal;
            $order->save();

            return $order->load($this->relations);
        });
    }

    public function calculateTax($subtotal, $tax, $taxType)
    {
        if ($taxType === TaxType::Percent) {
            return ($subtotal * $tax) / 100;
        }

        return $tax;
    }

    public function calculateDiscount($subtotal, $discount, $discountType)
    {
        if ($discountType === DiscountType::Percent) {
            return ($subtotal * $discount) / 100;
        }

        return $discount;
    }

    public function changeStatus(int|string $id, array $data): Order
    {
        $order = $this->findById($id);
        $order->status = $data['status'];
        $order->save();

        return $order->load($this->relations);
    }

    public function delete(int|string $id): bool
    {
        return DB::transaction(function () use ($id): bool {
            $order = $this->findById($id);
            $order->orderDetails()->delete();
            $order->delete();

            return true;
        });
    }
}
