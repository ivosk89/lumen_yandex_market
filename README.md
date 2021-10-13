# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://img.shields.io/packagist/dt/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://img.shields.io/packagist/v/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://img.shields.io/packagist/l/laravel/framework)](https://packagist.org/packages/laravel/lumen-framework)

# Управление проектом
````
docker-compose up --build

docker-compose down
````

# Yandex параметры в .env
https://yandex.ru/dev/market/partner-marketplace-dp/doc/dg/concepts/authorization.html

Параметр                    | Описание
----------------------------|-----------------------------------------
YANDEX_CLIENT_ID            | Yandex идентификатор приложения в параметре oauth_client_id
YANDEX_MARKET_TOKEN         | Yandex OAuth-токен в параметре oauth_token
YANDEX_MARKET_CAMPAIGN_ID   | Идентификатор кампании Yandex https://yandex.ru/dev/market/partner-marketplace-dp/doc/dg/reference/get-campaigns-id-shipments-reserves.html
YANDEX_MARKET_API_URL       | Базовая часть адреса для отправки API запросов к Yandex


# API документация

Чтобы сгенерировать документацию и коллекцию для Postman выполните команду:
````
php artisan scribe:generate
````

После чего документация будет доступна:

Описание               | URL
-----------------------|-----------------------------------------
Документация           | `http//YOUR_HOST/docs/index.html`
Postman коллекция      | `http//YOUR_HOST/docs/collection.json`

# Доступные API запросы
- https://yandex.ru/dev/market/partner-marketplace-dp/doc/dg/reference/get-campaigns-id-shipments-reserves.html

`GET http//YOUR_HOST/api/shipment-reserve?shipment_date_from={Y-m-d\TH:i:sP}`

- https://yandex.ru/dev/market/partner-marketplace-dp/doc/dg/reference/post-campaigns-id-stats-orders.html

`POST http//YOUR_HOST/api/stats-orders`

- https://yandex.ru/dev/market/partner-marketplace-dp/doc/dg/reference/post-stocks.html

`POST http//YOUR_HOST/api/stocks`

- https://yandex.ru/dev/market/partner-marketplace-dp/doc/dg/reference/post-campaigns-id-offer-mapping-entries-updates.html

`POST http//YOUR_HOST/api/update-offer`

# Требования

- Lumen >= 8.0
- PHP >= 8.0
- Composer