<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ResponseHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\ClientRequest;
use App\Http\Resources\ClientResource;
use App\Repositories\ClientRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ClientController extends Controller
{
    private ClientRepository $clientRepository;

    public function __construct(ClientRepository $clientRepository)
    {
        $this->clientRepository = $clientRepository;
    }

    public function index(): JsonResponse
    {
        try {
            $clients = $this->clientRepository->paginate(15);

            return ResponseHelper::JsonWithPagination($clients, ClientResource::collection($clients), 'Data retrieved successfully');
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve clients.');
        }
    }

    public function store(ClientRequest $request): JsonResponse
    {
        try {
            $client = $this->clientRepository->create($request->validated());

            return ResponseHelper::jsonWithSuccess(new ClientResource($client), 'Client created successfully', Response::HTTP_CREATED);
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to create client.');
        }
    }

    public function show(int|string $id): JsonResponse
    {
        try {
            $client = $this->clientRepository->findById($id);

            if (! $client) {
                return ResponseHelper::JsonWithError('Client not found', Response::HTTP_NOT_FOUND);
            }

            return ResponseHelper::jsonWithSuccess(new ClientResource($client), 'Client retrieved successfully', Response::HTTP_OK);

        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to retrieve client.');
        }
    }

    public function update(ClientRequest $request, int|string $id): JsonResponse
    {
        try {
            if (! $this->clientRepository->findById($id)) {
                return ResponseHelper::JsonWithError('Client not found', Response::HTTP_NOT_FOUND);
            }

            $client = $this->clientRepository->update($id, $request->validated());

            return ResponseHelper::jsonWithSuccess(new ClientResource($client), 'Client updated successfully', Response::HTTP_OK);
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to update client.');
        }
    }

    public function destroy(int|string $id): JsonResponse
    {
        try {
            $client = $this->clientRepository->findById($id);
            if (! $client) {
                return ResponseHelper::JsonWithError('Client not found', Response::HTTP_NOT_FOUND);
            }
            if ($client->orders()->count() > 0) {
                return ResponseHelper::JsonWithError('Cannot delete client with existing orders', Response::HTTP_BAD_REQUEST);
            }
            $this->clientRepository->delete($id);

            return ResponseHelper::jsonWithSuccess(null, 'Client deleted successfully', Response::HTTP_OK);
        } catch (\Throwable $exception) {
            return $this->serverError($exception, 'Unable to delete client.');
        }
    }
}
