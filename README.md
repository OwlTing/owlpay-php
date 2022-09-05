# OwlPay PHP

The developer can use OwlPay PHP library to convenient access to the OwlPay API.

## Requirements

PHP 7.1.0 and later.


## Composer
You can install via [Composer](http://getcomposer.org/). Run the following command:

```bash
composer require owlting/owlpay-php
```

## Dependencies

The bindings require the following extensions in order to work properly:

-   [`curl`](https://secure.php.net/manual/en/book.curl.php)
-   [`json`](https://secure.php.net/manual/en/book.json.php)
-   [`mbstring`](https://secure.php.net/manual/en/book.mbstring.php)

If you use Composer, these dependencies should be handled automatically.

## Getting Started

get *sk_test_xxxxxxxx* from [OwlPay](https://dashboard.owlpay.com) ***Developer > API Key*** tab


Simple usage looks like:

```php
$owlpay = new \OwlTing\OwlPay('sk_test_xxxxxxxx', 'v1');

$name = 'OwlPay_Vendor_TEST_'.time();

$email = 'OwlPay_Vendor_TEST_'.time().'_'.random_int(1, 10000).'@owlpay.com';

$country_iso = "TW";

// create Vendor
$vendor = $owlpay->vendors()->create([
    'country_iso' => $country_iso,
    'name' => $name,
    'email' => $email,
    'application_vendor_uuid' => time() . random_int(1, 10000),
]);

// create vendor's order
$order = $owlpay->orders()->create([
    'vendor_uuid' => $vendor['uuid'],
    'currency' => 'USD',
    'total' => 10,
    'order_serial' => 'ORS00000001_'.time(),
    'description' => 'ORS00000001 on owlpay\'s application',
    'meta_data' => [
        'customer_name' => 'OwlPay vendor\'s customer',
        'store_branch_no' => 'SBN_0001',
    ],
    'order_created_at' => date('c', time()),
    'allow_transfer_time_at' => date('c', time()),
]);

echo json_decode($order);
```

That will response:

```json
{
    "object": "order",
    "uuid": "ord_xxxxxxxx",
    "status": "owlpay.received_order",
    "status_group": "unable_submit",
    "is_checked": false,
    "is_allow_transfer": false,
    "is_allow_close": true,
    "is_allow_confirmed": false,
    "is_allow_correction": false,
    "deny_transfer_reasons": [
        "Vendor KYC is not verified"
    ],
    "order_serial": "ORS00000001_1658396581",
    "application_order_serial": "ORS00000001_1658396581",
    "application_order_created_at": "2022-07-21T09:43:01+00:00",
    "allow_transfer_time_at": "2022-07-21T09:43:01+00:00",
    "last_reject_transfer_reason": null,
    "is_rejected": false,
    "currency": "TWD",
    "total": 1000,
    "original_total": 1000,
    "display_currency": "TWD",
    "created_at": "2022-07-21T09:43:01+00:00",
    "updated_at": "2022-07-21T09:43:01+00:00",
    "vendor_uuid": "ven_xxxxxxxxx",
    "vendor_name": "VendorTest_1658396581",
    "application_vendor_uuid": "16583965812367",
    "is_vendor_kyc_passed": false,
    "description": "ORS00000001 on owlpay's application",
    "meta_data": {
        "customer_name": "Maras Chen",
        "store_branch_no": "SBN_0001"
    },
    "status_logs": [
        {
            "name": "Available for reconciliation",
            "is_current": false,
            "updated_time_at": null
        },
        {
            "name": "Awaiting review",
            "is_current": false,
            "updated_time_at": null
        },
        {
            "name": "Review approved",
            "is_current": false,
            "updated_time_at": null
        },
        {
            "name": "Awaiting payout",
            "is_current": false,
            "updated_time_at": null
        },
        {
            "name": "Payout successful",
            "is_current": false,
            "updated_time_at": null
        }
    ],
    "order_extras": {
        "inclusive": {
            "total": 0,
            "items": []
        },
        "exclusive": {
            "total": 0,
            "items": []
        }
    },
    "closed_transfer_reason": null,
    "procedures": [],
    "draft_audits": [],
    "order_transfer_uuid": null,
    "accounting_uuid": null,
    "vendor_status": "uncheck",
    "is_has_need_correction": false,
    "import_uuid": null,
    "is_from_import": false
}
```

## Documentation

See the [OwlPay API docs](http://owlpay-external-doc.s3-website-ap-northeast-1.amazonaws.com/#introduction).

