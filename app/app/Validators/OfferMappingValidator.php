<?php

namespace App\Validators;

use Illuminate\Validation\Validator;
use App\Services\YandexMarketService;
use Illuminate\Support\Facades\Validator as Validate;

class OfferMappingValidator
{
    /**
     * @param array $request
     * @return Validator
     */
    public static function make(array $request): Validator
    {
        return Validate::make($request, [
            'offerMappingEntries' => 'required|array|max:50',
            'offerMappingEntries.*.offer' => 'required',
            'offerMappingEntries.*.offer.shopSku' => 'required|string|max:80',
            'offerMappingEntries.*.offer.name' => 'required|string',
            'offerMappingEntries.*.offer.category' => 'required|string',
            'offerMappingEntries.*.offer.manufacturer' => 'string',
            'offerMappingEntries.*.offer.manufacturerCountries' => 'required|array|max:5',
            'offerMappingEntries.*.offer.urls' => 'array|max:1',
            'offerMappingEntries.*.offer.pictures' => 'required|array|max:10',
            'offerMappingEntries.*.offer.vendor' => 'string',
            'offerMappingEntries.*.offer.vendorCode' => 'string',
            'offerMappingEntries.*.offer.barcodes' => 'array',
            'offerMappingEntries.*.offer.description' => 'string|max:3000',
            'offerMappingEntries.*.offer.shelfLife.timePeriod' => 'integer',
            'offerMappingEntries.*.offer.shelfLife.timeUnit' => 'string|in:' .
                implode(',', YandexMarketService::ITEM_SHELFLIFE_UNITS),
            'offerMappingEntries.*.offer.shelfLife.comment' => 'string',
            'offerMappingEntries.*.offer.lifeTime.timePeriod' => 'integer',
            'offerMappingEntries.*.offer.lifeTime.timeUnit' => 'string|in:' .
                implode(',', YandexMarketService::ITEM_SHELFLIFE_UNITS),
            'offerMappingEntries.*.offer.lifeTime.comment' => 'string',
            'offerMappingEntries.*.offer.guaranteePeriod.timePeriod' => 'integer',
            'offerMappingEntries.*.offer.guaranteePeriod.timeUnit' => 'string|in:' .
                implode(',', YandexMarketService::ITEM_SHELFLIFE_UNITS),
            'offerMappingEntries.*.offer.guaranteePeriod.comment' => 'string',
            'offerMappingEntries.*.offer.customsCommodityCodes' => 'array|max:1',
            'offerMappingEntries.*.offer.certificate' => 'string',
            'offerMappingEntries.*.offer.availability' => 'string|in:' .
                implode(',', YandexMarketService::ITEM_AVAILABILITY_STATUSES),
            'offerMappingEntries.*.offer.transportUnitSize' => 'integer',
            'offerMappingEntries.*.offer.minShipment' => 'integer',
            'offerMappingEntries.*.offer.quantumOfSupply' => 'integer',
            'offerMappingEntries.*.offer.supplyScheduleDays' => 'array|in:' .
                implode(',', ['MONDAY', 'TUESDAY', 'WEDNESDAY', 'THURSDAY', 'FRIDAY', 'SATURDAY', 'SUNDAY']),
            'offerMappingEntries.*.offer.deliveryDurationDays' => 'integer',
            'offerMappingEntries.*.offer.boxCount' => 'integer',
            'offerMappingEntries.*.mapping.marketSku' => 'integer',
        ]);
    }
}
