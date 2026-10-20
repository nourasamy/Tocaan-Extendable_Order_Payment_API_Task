<?php

namespace App\Http\Controllers\Api;

use App\Enums\DiscountType;
use App\Enums\OrderStatus;
use App\Enums\TaxType;
use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class ConfigController extends Controller
{
    public function getOrderStatuses(): JsonResponse
    {
        try {
            $statuses = [];
            foreach (OrderStatus::cases() as $status) {
                $statuses[] = [
                    'key' => $status->name,
                    'value' => $status->value,
                ];
            }

            return ResponseHelper::jsonWithSuccess($statuses, 'Order statuses retrieved successfully', 200);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve order statuses.');
        }
    }

    public function getTaxTypes(): JsonResponse
    {
        try {
            $statuses = [];
            foreach (TaxType::cases() as $status) {
                $statuses[] = [
                    'key' => $status->name,
                    'value' => $status->value,
                ];
            }

            return ResponseHelper::jsonWithSuccess($statuses, 'Tax types retrieved successfully', 200);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve tax types.');
        }
    }

    public function getDiscountTypes(): JsonResponse
    {
        try {
            $statuses = [];
            foreach (DiscountType::cases() as $status) {
                $statuses[] = [
                    'key' => $status->name,
                    'value' => $status->value,
                ];
            }

            return ResponseHelper::jsonWithSuccess($statuses, 'Discount types retrieved successfully', 200);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve discount types.');
        }
    }
}
