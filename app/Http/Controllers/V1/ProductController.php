<?php

namespace App\Http\Controllers\V1;

use App\Exceptions\FileException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Product\{CreateProductRequest, EditProductRequest};
use App\Http\Resources\Product\{ProductResource, ProductResourceCollection};
use App\Services\Product\ProductServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\{JsonResponse, Response as HttpResponseEnum};
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductController extends Controller
{
    public function __construct(private readonly ProductServiceInterface $productService)
    {
    }

    public function post(CreateProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->create($request->validated());

            $this->response['data']    = ['product' => new ProductResource($product)];
            $this->response['message'] = __('message.products.created_successfully');
        } catch (FileException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_CREATED);
    }

    public function put(EditProductRequest $request): JsonResponse
    {
        try {
            $product = $this->productService->findOneBy('uuid', $request->route('uuid'));

            $product = $this->productService->update($product, $request->validated());

            $this->response['data']    = ['product' => new ProductResource($product)];
            $this->response['message'] = __('message.products.edited_successfully');
        } catch (NotFoundHttpException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_NOT_FOUND);
        } catch (FileException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_UNPROCESSABLE_ENTITY);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }

    public function find(Request $request): JsonResponse
    {
        try {
            $product = $this->productService->findOneBy('uuid', $request->route('uuid'));

            $this->response['data']    = ['product' => new ProductResource($product)];
            $this->response['message'] = __('message.products.found_successfully');
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
            $products = $this->productService->findList()->paginate($perPage);
        } else {
            $products = $this->productService->findList()->get();
        }

        $this->response['data']    = ['products' => new ProductResourceCollection($products)];
        $this->response['message'] = __('message.products.listed_successfully');

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }

    public function delete(Request $request): JsonResponse
    {
        try {
            $product = $this->productService->findOneBy('uuid', $request->route('uuid'));

            $this->productService->delete($product);
            $this->response['message'] = __('message.products.deleted_successfully');
        } catch (NotFoundHttpException $exception) {
            return $this->buildResponseError($exception, HttpResponseEnum::HTTP_NOT_FOUND);
        }

        return Response::json($this->response, HttpResponseEnum::HTTP_OK);
    }
}
