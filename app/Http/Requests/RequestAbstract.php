<?php

namespace App\Http\Requests;

use App\Enums\ErrorEnum;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Symfony\Component\HttpFoundation\Response;

abstract class RequestAbstract extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    protected function failedValidation(Validator $validator)
    {
        $response['type']    = 'error';
        $response['code']    = ErrorEnum::VALIDATE_FORM;
        $response['message'] = $validator->errors()->all()[0];
        $response['field']   = $validator->errors()->keys()[0];

        throw new HttpResponseException(response()->json($response, Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
