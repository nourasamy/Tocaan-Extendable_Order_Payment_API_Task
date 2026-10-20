<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Repositories\ItemRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $items = $this->itemRepository->paginate(15);

            return ResponseHelper::JsonWithPagination($items, ItemResource::collection($items), 'Data retrieved successfully');
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve items.');
        }
    }

    public function store(ItemRequest $request): JsonResponse
    {
        try {

            $item = $this->itemRepository->create($request->validated());

            return ResponseHelper::jsonWithSuccess(new ItemResource($item), 'Item created successfully', Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to create item.');
        }
    }

    public function show(int|string $id): JsonResponse
    {
        try {
            $item = $this->itemRepository->findById($id);

            if (! $item) {
                return ResponseHelper::JsonWithError('Item not found', Response::HTTP_NOT_FOUND);
            }

            return ResponseHelper::jsonWithSuccess(new ItemResource($item), 'Item retrieved successfully', Response::HTTP_OK);
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve item.');
        }
    }

    public function update(ItemRequest $request, int|string $id): JsonResponse
    {
        try {
            if (! $this->itemRepository->findById($id)) {
                return ResponseHelper::JsonWithError('Item not found', Response::HTTP_NOT_FOUND);
            }

            $item = $this->itemRepository->update($id, $request->validated());

            return ResponseHelper::jsonWithSuccess(new ItemResource($item), 'Item updated successfully', Response::HTTP_OK);
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to update item.');
        }
    }

    public function destroy(int|string $id): JsonResponse
    {
        try {

            $item = $this->itemRepository->findById($id);

            if (! $item) {
                return ResponseHelper::JsonWithError('Item not found', Response::HTTP_NOT_FOUND);
            }

            if ($item->orderDetails()->count() > 0) {
                return ResponseHelper::JsonWithError('Cannot delete item associated with orders', Response::HTTP_BAD_REQUEST);
            }

            $this->itemRepository->delete($id);

            return ResponseHelper::jsonWithSuccess(null, 'Item deleted successfully', Response::HTTP_OK);
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to delete item.');
        }
    }
}
