<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ItemRequest;
use App\Http\Resources\ItemResource;
use App\Repositories\ItemRepository;
use Illuminate\Http\Response;

class ItemController extends Controller
{
    private ItemRepository $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function index()
    {
        try {
            $items = $this->itemRepository->paginate(15);
            return ResponseHelper::JsonWithPagination($items, ItemResource::collection($items), 'Data retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(ItemRequest $request)
    {
        try {

            $item = $this->itemRepository->create($request->validated());
            return ResponseHelper::jsonWithSuccess(new ItemResource($item), 'Item created successfully', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        try {
            $item = $this->itemRepository->findById($id);

            if (!$item) {
                return ResponseHelper::JsonWithError('Item not found', Response::HTTP_NOT_FOUND);
            }

            return ResponseHelper::jsonWithSuccess(new ItemResource($item), 'Item retrieved successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update(ItemRequest $request, $id)
    {
        try {
            if (!$this->itemRepository->findById($id)) {
                return ResponseHelper::JsonWithError('Item not found', Response::HTTP_NOT_FOUND);
            }

            $item = $this->itemRepository->update($id, $request->validated());
            return ResponseHelper::jsonWithSuccess(new ItemResource($item), 'Item updated successfully', Response::HTTP_OK);
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy($id)
    {
        try {

            $item = $this->itemRepository->findById($id);

            if (!$item) {
                return ResponseHelper::JsonWithError('Item not found', Response::HTTP_NOT_FOUND);
            }

            if ($item->orderDetails()->count() > 0) {
                return ResponseHelper::JsonWithError('Cannot delete item associated with orders', Response::HTTP_BAD_REQUEST);
            }

            $this->itemRepository->delete($id);

            return ResponseHelper::jsonWithSuccess(null, 'Item deleted successfully', Response::HTTP_OK);
        } catch (\Exception $e) {

            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
