<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GatewayRequest;
use App\Http\Resources\GatewayResource;
use App\Repositories\GatewayRepository;
use Illuminate\Http\Response;

class GatewayController extends Controller
{
    private GatewayRepository $gatewayRepository;

    public function __construct(GatewayRepository $gatewayRepository)
    {
        $this->gatewayRepository = $gatewayRepository;
    }

    public function index()
    {
        try {
            $gateways = $this->gatewayRepository->paginate(15);
            return ResponseHelper::JsonWithPagination($gateways,GatewayResource::collection($gateways),'Data retrieved successfully');
        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(GatewayRequest $request)
    {
        try {

            $gateway = $this->gatewayRepository->create($request->validated());
            return ResponseHelper::jsonWithSuccess(new GatewayResource($gateway), 'Gateway created successfully', Response::HTTP_CREATED);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function show($id)
    {
        try {
            $gateway = $this->gatewayRepository->findById($id);

            if (!$gateway) {
                return ResponseHelper::JsonWithError('Gateway not found', Response::HTTP_NOT_FOUND);
            }

            return ResponseHelper::jsonWithSuccess(new GatewayResource($gateway), 'Gateway retrieved successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function update(GatewayRequest $request, $id)
    {
        try {
            if (!$this->gatewayRepository->findById($id)) {
                return ResponseHelper::JsonWithError('Gateway not found', Response::HTTP_NOT_FOUND);
            }

            $gateway = $this->gatewayRepository->update($id, $request->validated());
            return ResponseHelper::jsonWithSuccess(new GatewayResource($gateway), 'Gateway updated successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
    public function destroy($id)
    {
        try {
            if (!$this->gatewayRepository->findById($id)) {
                return ResponseHelper::JsonWithError('Gateway not found', Response::HTTP_NOT_FOUND);
            }

            $this->gatewayRepository->delete($id);

            return ResponseHelper::jsonWithSuccess(null, 'Gateway deleted successfully', Response::HTTP_OK);

        } catch (\Exception $e) {
            return ResponseHelper::JsonWithError($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
