<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use App\Services\YandexMarketService;
use Illuminate\Support\Facades\Validator as Validate;

class StatsOrdersValidator
{
    /**
     * @param array $request
     * @return Validator
     */
    public static function make(array $request): Validator
    {
        return Validate::make($request, [
            'limit' => 'sometimes|int|max:500',
            'page_token' => 'sometimes|string',
            'dateFrom' => 'required_with:dateTo|prohibits:updateFrom,updateTo|date|date_format:"Y-m-d"',
            'dateTo' => 'required_with:dateFrom|date|date_format:"Y-m-d"|gte:dateFrom',
            'updateFrom' => 'prohibits:dateFrom,dateTo|required_with:updateTo|date|date_format:"Y-m-d"',
            'updateTo' => 'required_with:updateFrom|date|date_format:"Y-m-d"|gte:updateFrom',
            'orders' => 'array|max:200',
            'orders.*' => 'required|integer',
            'statuses' => 'required|array',
            'statuses.*' => 'required|in:' . implode(',', YandexMarketService::ORDER_STATUSES),
            'hasCis' => 'required|boolean',
        ]);
    }
}
