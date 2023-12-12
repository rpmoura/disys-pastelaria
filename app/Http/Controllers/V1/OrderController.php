<?php

namespace App\Http\Controllers\V1;

use App\Events\OrderCreated;
use App\Http\Controllers\Controller;
use App\Http\Requests\Order\{CreateOrderRequest, EditOrderRequest};
use App\Http\Resources\Order\{OrderResource, OrderResourceCollection};
use App\Services\Client\ClientServiceInterface;
use App\Services\Order\OrderServiceInterface;
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{DB, Response};
use Symfony\Component\HttpFoundation\{JsonResponse, Response as HttpResponseEnum};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderServiceInterface $orderService,
        private readonly ClientServiceInterface $clientService,
        private readonly ProductServiceInterface $productService
    ) {
    }

    public function post(CreateOrderRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $client   = $this->clientService->findOneBy('uuid', $request->validated('client.uuid'));
            $products = $this->productService->findBy('uuid', $request->validated('products'));

            $order = $this->orderService->create($client, $products);

            $this->response['data']    = ['order' => new OrderResource($order)];
            $this->response['message'] = __('message.orders.created_successfully');
            DB::commit();

            event(new OrderCreated($order));

        } catch (\Exception $exception) {
            DB::rollback();

            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_BAD_REQUEST);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_CREATED);
    }

    public function put(EditOrderRequest $request): JsonResponse
    {
        try {
            DB::beginTransaction();
            $order    = $this->orderService->findOneBy('uuid', $request->route('uuid'));
            $client   = $this->clientService->findOneBy('uuid', $request->validated('client.uuid'));
            $products = $this->productService->findBy('uuid', $request->validated('products'));

            $order = $this->orderService->update($order, $client, $products);

            $this->response['data']    = ['order' => new OrderResource($order)];
            $this->response['message'] = __('message.orders.edited_successfully');
            DB::commit();
        } catch (NotFoundHttpException $exception) {
            DB::rollBack();

            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_NOT_FOUND);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }

    public function find(Request $request): JsonResponse
    {
        try {
            $order = $this->orderService->findOneBy('uuid', $request->route('uuid'));

            $this->response['data']    = ['order' => new OrderResource($order)];
            $this->response['message'] = __('message.orders.found_successfully');
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
            $orders = $this->orderService->findList()->paginate($perPage);
        } else {
            $orders = $this->orderService->findList()->get();
        }

        $this->response['data']    = ['orders' => new OrderResourceCollection($orders)];
        $this->response['message'] = __('message.orders.listed_successfully');

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $order = $this->orderService->findOneBy('uuid', $request->route('uuid'));

            $this->orderService->delete($order);
            $this->response['message'] = __('message.orders.deleted_successfully');
        } catch (NotFoundHttpException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_NOT_FOUND);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }
}
