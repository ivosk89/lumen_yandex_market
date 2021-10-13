<?php

namespace App\Services;

use Exception;
use GuzzleHttp\ClientInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class YandexMarketService
{
    public const METHOD_POST = 'post',
        METHOD_GET = 'get';

    private const URL_SHIPMENTS_RESERVES = '/campaigns/%s/shipments/reserves.json';

    private const URL_STATS_ORDERS = '/campaigns/%s/stats/orders.json';

    private const URL_ITEMS_MAPPING_UPDATE = '/campaigns/%s/offer-mapping-entries/updates.json';

    public const ITEM_SHELFLIFE_UNITS = [
        'HOUR',
        'DAY',
        'WEEK',
        'MONTH',
        'YEAR',
    ];

    public const ITEM_AVAILABILITY_STATUSES = [
        'ACTIVE',
        'INACTIVE',
        'DELISTED',
    ];

    public const ITEM_STATUSES = [
        'CREATED',
        'CANCELLED',
        'DRAFT_FINALIZED',
    ];

    public const ORDER_STATUSES = [
        'CANCELLED_BEFORE_PROCESSING',
        'CANCELLED_IN_DELIVERY',
        'CANCELLED_IN_PROCESSING',
        'DELIVERY',
        'DELIVERED',
        'PARTIALLY_RETURNED',
        'PICKUP',
        'PROCESSING',
        'REJECTED',
        'RETURNED',
        'UNKNOWN',
    ];

    private ClientInterface $client;

    private string $yandexCampaignId;

    public function __construct(ClientInterface $client)
    {
        $this->client = $client;
        $this->yandexCampaignId = env('YANDEX_MARKET_CAMPAIGN_ID');
    }

    private function sendRequest(string $url, array $data = [], string $method = self::METHOD_POST): JsonResponse
    {
        $response = [];

        try {
            $result = $this->client->$method($url, $data);
            $response = json_decode($result->getBody(), true) ?? [];
        } catch (Exception $e) {
            $response['error'] = [
                'code' => $e->getCode(),
                'message' => $e->getMessage(),
            ];
        }

        Log::info('Request to Yandex API', $response);

        return response()->json($response);
    }

    public function getShipmentsReserves(array $data): JsonResponse
    {
        return $this->sendRequest(
            sprintf(static::URL_SHIPMENTS_RESERVES, $this->yandexCampaignId),
            $data,
            static::METHOD_GET
        );
    }

    public function getStatsOrders(array $data, string $queryParams): JsonResponse
    {
        return $this->sendRequest(
            sprintf(static::URL_STATS_ORDERS . '?' . $queryParams, $this->yandexCampaignId),
            $data
        );
    }

    public function updateOfferMappingEntries(array $data): JsonResponse
    {
        return $this->sendRequest(
            sprintf(static::URL_ITEMS_MAPPING_UPDATE, $this->yandexCampaignId),
            $data
        );
    }
}
