<?php

namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use App\Enums\OrderStatus;
use App\Enums\TaxType;
use App\Enums\DiscountType;
use App\Helpers\ResponseHelper;

class ConfigController extends Controller
{
    public function getOrderStatuses()
    {
        try
        {
            $statuses = [];
            foreach (OrderStatus::cases() as $status) {
                $statuses[] = [
                    'key' => $status->name,
                    'value' => $status->value,
                ];
            }

            return ResponseHelper::jsonWithSuccess($statuses, 'Order statuses retrieved successfully', 200);

        }catch (\Exception $e){
            return ResponseHelper::JsonWithError($e->getMessage(), 500);
        }
    }

    public function getTaxTypes()
    {
        try
        {
            $statuses = [];
            foreach (TaxType::cases() as $status) {
                $statuses[] = [
                    'key' => $status->name,
                    'value' => $status->value,
                ];
            }

            return ResponseHelper::jsonWithSuccess($statuses, 'Tax types retrieved successfully', 200);

        }catch (\Exception $e){
            return ResponseHelper::JsonWithError($e->getMessage(), 500);
        }
    }
    public function getDiscountTypes()
    {
        try
        {
            $statuses = [];
            foreach (DiscountType::cases() as $status) {
                $statuses[] = [
                    'key' => $status->name,
                    'value' => $status->value,
                ];
            }

            return ResponseHelper::jsonWithSuccess($statuses, 'Discount types retrieved successfully', 200);

        }catch (\Exception $e){
            return ResponseHelper::JsonWithError($e->getMessage(), 500);
        }
    }
}
