<?php

namespace App\Http\Controllers\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\Client\{CreateClientRequest, EditClientRequest};
use App\Http\Resources\Client\{ClientResource, ClientResourceCollection};
use App\Services\Client\ClientServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\{JsonResponse, Response as HttpResponseEnum};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ClientController extends Controller
{
    public function __construct(private readonly ClientServiceInterface $clientService)
    {
        parent::__construct();
    }

    public function post(CreateClientRequest $request): JsonResponse
    {
        $client = $this->clientService->create($request->validated());

        $this->response['data']    = ['client' => new ClientResource($client)];
        $this->response['message'] = __('message.clients.created_successfully');

        return Response::json($this->response, HttpResponseEnum::HTTP_CREATED);
    }

    public function put(EditClientRequest $request): JsonResponse
    {
        try {
            $client = $this->clientService->findOneBy('uuid', $request->route('uuid'));

            $client = $this->clientService->update($client, $request->validated());

            $this->response['data']    = ['client' => new ClientResource($client)];
            $this->response['message'] = __('message.clients.edited_successfully');
        } catch (NotFoundHttpException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_NOT_FOUND);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }

    public function find(Request $request): JsonResponse
    {
        try {
            $client = $this->clientService->findOneBy('uuid', $request->route('uuid'));

            $this->response['data']    = ['client' => new ClientResource($client)];
            $this->response['message'] = __('message.clients.found_successfully');
        } catch (NotFoundHttpException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_NOT_FOUND);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }

    public function get(Request $request): JsonResponse
    {
        $page    = $request->query('page');
        $perPage = $request->query('per_page');

        if ($page) {
            $clients = $this->clientService->findList()->paginate($perPage);
        } else {
            $clients = $this->clientService->findList()->get();
        }

        $this->response['data']    = ['clients' => new ClientResourceCollection($clients)];
        $this->response['message'] = __('message.clients.listed_successfully');

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $client = $this->clientService->findOneBy('uuid', $request->route('uuid'));

            $this->clientService->delete($client);
            $this->response['message'] = __('message.clients.deleted_successfully');
        } catch (NotFoundHttpException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_NOT_FOUND);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }
}
