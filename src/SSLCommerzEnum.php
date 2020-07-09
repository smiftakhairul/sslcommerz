<?php

namespace SSLCZ\SSLCommerz;

trait SSLCommerzEnum
{
    public static $_CONFIG = 'sslcommerz';

    public static $_STORE_ID = 'store_id';
    public static $_STORE_PASSWORD = 'store_password';
    public static $_IS_PRODUCTION = 'is_production';

    public static $_ENV_SANDBOX = 'sandbox';
    public static $_ENV_PRODUCTION = 'production';

    public static $_API_DOMAIN = 'api_domain';
    public static $_API_URL = 'api_url';

    public static $_API_INIT_PAYMENT = 'init_payment';
    public static $_API_TRANSACTION_STATUS = 'transaction_status';
    public static $_API_ORDER_VALIDATE = 'order_validate';
    public static $_API_REFUND_PAYMENT = 'refund_payment';
    public static $_API_REFUND_STATUS = 'refund_status';

    public static $_PAYMENT_DISPLAY_HOSTED = 'hosted';
    public static $_PAYMENT_DISPLAY_CHECKOUT = 'checkout';
}
