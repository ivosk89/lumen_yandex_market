<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Validators\StocksValidator;
use App\Http\Controllers\Controller;
use App\Services\YandexMarketService;
use App\Validators\StatsOrdersValidator;
use App\Validators\OfferMappingValidator;
use App\Validators\ShipmentReserveValidator;

class YandexMarket extends Controller
{
    protected YandexMarketService $service;

    /**
     * @param YandexMarketService $service
     */
    public function __construct(YandexMarketService $service)
    {
        $this->service = $service;
    }

    /**
     * Список товаров для подготовки к отгрузке (get campaigns id shipments reserves).
     *
     * @group Отгрузки товаров (shipments and reserves)
     *
     * @queryParam shipment_date_from datetime required
     *      Начальные дата и время отгрузки.
     *      Example: 2021-09-01T11:58:30+03:00
     * @queryParam shipment_date_to datetime Конечные дата и время отгрузки. Example: 2021-09-01T11:58:30+03:00
     * @queryParam status string Статус товаров: CREATED | CANCELLED | DRAFT_FINALIZED. Example: CREATED
     * @queryParam limit integer
     *      Количество зарезервированных товаров, которое должна содержать одна страница выходных данных (1 - 500).
     *      Example: 500
     * @queryParam page_token string Идентификатор страницы c результатами. Example: SXQZcyBjb21pbmcg
     *
     * @responseFile 200 responses/shipment-reserve.json
     */
    public function shipmentReserve(Request $request): JsonResponse
    {
        $validation = ShipmentReserveValidator::make($request->all());
        if ($validation->fails()) {
            return response()->json($validation->errors());
        }

        return $this->service->getShipmentsReserves($request->all());
    }

    /**
     * Отчет по заказам (post campaigns id stats orders).
     *
     * @group Отчеты (reports)
     *
     * @queryParam limit integer
     *      Количество зарезервированных товаров, которое должна содержать одна страница выходных данных (1 - 500).
     *      Example: 500
     * @queryParam page_token string Идентификатор страницы c результатами. Example: SXQZcyBjb21pbmcg
     *
     * @bodyParam dateFrom string Начальная дата, когда заказ был сформирован. Example: 2021-09-09
     * @bodyParam dateTo string Конечная дата, когда заказ был сформирован. Example: 2021-09-09
     * @bodyParam updateFrom string
     *      Начальная дата периода, за который были изменения статуса заказа.
     *      Example: 2021-09-09
     * @bodyParam updateTo string Конечная дата периода, за который были изменения статуса заказа. Example: 2021-09-09
     * @bodyParam orders integer[] Список идентификаторов заказов. Example: [123, 321]
     * @bodyParam statuses string[] required
     *      Список статусов заказов.
     *      Example: ["CANCELLED_BEFORE_PROCESSING", "CANCELLED_IN_DELIVERY"]
     * @bodyParam hasCis boolean required
     *      Вернуть только те заказы, в составе которых есть хотя бы один товар с кодом идентификации из «Честный ЗНАК».
     *      Example: false
     *
     * @responseFile 200 responses/stats-orders.json
     * @responseFile 500 responses/stats-orders-error.json
     */
    public function statsOrders(Request $request): JsonResponse
    {
        $validation = StatsOrdersValidator::make($request->all());
        if ($validation->fails()) {
            return response()->json($validation->errors());
        }

        return $this->service->getStatsOrders(
            $request->post(),
            http_build_query($request->query())
        );
    }

    /**
     * Запрос информации об остатках (post stocks).
     *
     * @group Остатки товаров (stocks)
     *
     * @bodyParam warehouseId integer required Идентификатор склада. Example: 2
     * @bodyParam skus string[] required Список ваших SKU товаров. Example: ["A200.190", "A287.14"]
     *
     * @responseFile 200 responses/items-stocks.json
     */
    public function itemsStocks(Request $request): JsonResponse
    {
        $validation = StocksValidator::make($request->all());
        if ($validation->fails()) {
            return response()->json($validation->errors());
        }

        return $this->service->getStatsOrders(
                $request->post(),
                http_build_query($request->query())
            );
    }

    /**
     * Добавление и редактирование товаров в каталоге (offer mapping entries).
     *
     * @group Управление каталогом товаров (range management)
     *
     * @bodyParam offerMappingEntries object[] Список товаров.
     * @bodyParam offerMappingEntries[].offer object Информация о товаре.
     * @bodyParam offerMappingEntries[].offer.shopSku string required Ваш SKU. Example: Vendor002.26194
     * @bodyParam offerMappingEntries[].offer.name string required
     *      Название товара.
     *      Example: Комбинезон LEO размер 62, розовый
     * @bodyParam offerMappingEntries[].offer.category string required
     *      Категория товара.
     *      Example: Декоративная косметика
     * @bodyParam offerMappingEntries[].offer.manufacturer string
     *      Изготовитель товара.
     *      Example: ООО "Яндекс", Россия, Москва, ул. Льва Толстого, 16
     * @bodyParam offerMappingEntries[].offer.manufacturerCountries string[] required
     *      Список стран, в которых произведен товар.
     *      Example: ["Россия", "Китай"]
     * @bodyParam offerMappingEntries[].offer.urls string[]
     *      URL фотографии товара или страницы с описанием на вашем сайте.
     *      Example: ["https://example.yandex/images/sumka-poiasnaia-yandex-razmer-s-krasnyi.jpg"]
     * @bodyParam offerMappingEntries[].offer.pictures string[] required
     *      Ссылки (URL) изображений товара в хорошем качестве.
     *      Example: ["https://test.com/image1.png", "https://test.com/image2.png"]
     * @bodyParam offerMappingEntries[].offer.vendor string Бренд товара. Example: Яндекс
     * @bodyParam offerMappingEntries[].offer.vendorCode string Артикул товара от производителя. Example: Yandex
     * @bodyParam offerMappingEntries[].offer.barcodes string[]
     *      Штрихкоды товара от производителя.
     *      Example: [2400000032632, 2400000032633]
     * @bodyParam offerMappingEntries[].offer.description string Описание товара. Example: Краткое описание товара
     * @bodyParam offerMappingEntries[].offer.shelfLife object Информация о сроке годности.
     * @bodyParam offerMappingEntries[].offer.shelfLife.timePeriod integer
     *      Срок годности в единицах, указанных в параметре timeUnit.
     *      Example: 10
     * @bodyParam offerMappingEntries[].offer.shelfLife.timeUnit string
     *      Единица измерения срока годности: HOUR, DAY, WEEK, MONTH, YEAR.
     *      Example: DAY
     * @bodyParam offerMappingEntries[].offer.shelfLife.comment string
     *      Дополнительные условия использования в течение срока годности.
     *      Example: Хранить в сухом помещении
     * @bodyParam offerMappingEntries[].offer.lifeTime object Информация о сроке службы.
     * @bodyParam offerMappingEntries[].offer.lifeTime.timePeriod integer
     *      Срок службы в единицах, указанных в параметре timeUnit.
     *      Example: 10
     * @bodyParam offerMappingEntries[].offer.lifeTime.timeUnit string
     *      Единица измерения срока службы: HOUR, DAY, WEEK, MONTH, YEAR.
     *      Example: DAY
     * @bodyParam offerMappingEntries[].offer.lifeTime.comment string
     *      Дополнительные условия использования в течение срока службы.
     *      Example: Использовать при температуре не ниже -10 градусов.
     * @bodyParam offerMappingEntries[].offer.guaranteePeriod object Информация о гарантийном сроке.
     * @bodyParam offerMappingEntries[].offer.guaranteePeriod.timePeriod integer
     *      Гарантийный срок в единицах, указанных в параметре timeUnit.
     *      Example: 10
     * @bodyParam offerMappingEntries[].offer.guaranteePeriod.timeUnit string
     *      Единица измерения гарантийного срока: HOUR, DAY, WEEK, MONTH, YEAR.
     *      Example: DAY
     * @bodyParam offerMappingEntries[].offer.guaranteePeriod.comment string
     *      Дополнительные условия гарантии.
     *      Example: Гарантия на аккумулятор — 6 месяцев.
     * @bodyParam offerMappingEntries[].offer.customsCommodityCodes string[]
     *      Список кодов товара в ТН ВЭД.
     *      Example: [15685135151]
     * @bodyParam offerMappingEntries[].offer.certificate string Номер документа на товар. Example: 1321056
     * @bodyParam offerMappingEntries[].offer.availability string Планы по поставкам. Example: ACTIVE
     * @bodyParam offerMappingEntries[].offer.transportUnitSize integer
     *      Количество единиц товара в одной упаковке, которую вы поставляете на склад.
     *      Example: 6
     * @bodyParam offerMappingEntries[].offer.minShipment integer
     *      Минимальное количество единиц товара, которое вы поставляете на склад.
     *      Example: 60
     * @bodyParam offerMappingEntries[].offer.quantumOfSupply integer
     *      Добавочная партия: по сколько единиц товара можно добавлять к минимальному количеству minShipment.
     *      Example: 12
     * @bodyParam offerMappingEntries[].offer.supplyScheduleDays string[]
     *      Дни недели, в которые вы поставляете товары на склад.
     *      Example: ["MONDAY","TUESDAY","WEDNESDAY","THURSDAY","FRIDAY","SATURDAY"]
     * @bodyParam offerMappingEntries[].offer.deliveryDurationDays integer
     *      Срок, за который вы поставляете товары на склад, в днях.
     *      Example: 3
     * @bodyParam offerMappingEntries[].offer.boxCount integer
     *      Сколько мест (если больше одного) занимает товар.
     *      Example: 2
     * @bodyParam offerMappingEntries[].mapping object Информация о карточке товара на Маркете.
     * @bodyParam offerMappingEntries[].mapping.marketSku integer SKU на Яндексе. Example: 100345202774
     *
     * @responseFile 200 responses/status-ok.json
     */
    public function updateOfferMapping(Request $request): JsonResponse
    {
        $validation = OfferMappingValidator::make($request->post());
        if ($validation->fails()) {
            return response()->json($validation->errors());
        }

        return $this->service->updateOfferMappingEntries($request->post());
    }
}
