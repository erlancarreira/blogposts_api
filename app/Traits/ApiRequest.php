<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

trait ApiRequest
{
    protected function failedValidation(Validator $validator)
    {
        $message = $validator->errors()->first();
        //$message = (new ValidationException($validator))->getMessage();
        
        throw new HttpResponseException(
            response()->json([
                'message' => $message,
            ], Response::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}