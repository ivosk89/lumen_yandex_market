<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use Illuminate\Support\Facades\Validator as Validate;

class StocksValidator
{
    /**
     * @param array $request
     * @return Validator
     */
    public static function make(array $request): Validator
    {
        return Validate::make($request, [
            'warehouseId' => 'required|integer',
            'skus' => 'required|array',
            'skus.*' => 'required|string',
        ]);
    }
}
