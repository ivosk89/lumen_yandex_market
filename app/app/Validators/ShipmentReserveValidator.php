<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use App\Services\YandexMarketService;
use Illuminate\Support\Facades\Validator as Validate;

class ShipmentReserveValidator
{
    /**
     * @param array $request
     * @return Validator
     */
    public static function make(array $request): Validator
    {
        return Validate::make($request, [
            'shipment_date_from' => 'required|date|date_format:"Y-m-d\TH:i:sP"',
            'shipment_date_to' => 'sometimes|date|date_format:"Y-m-d\TH:i:sP"',
            'status' => 'sometimes|string|in:' . implode(',', YandexMarketService::ITEM_STATUSES),
            'limit' => 'sometimes|int|max:500',
            'page_token' => 'sometimes|string',
        ]);
    }
}
