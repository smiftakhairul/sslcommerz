# SSLCommerz
[SSLCommerz](https://www.sslcommerz.com/) is the first payment gateway in Bangladesh opening doors for merchants to receive payments on the internet via their online stores.

Official documentation [here](https://developer.sslcommerz.com/).

## Installation
```bash
composer require smiftakhairul/sslcommerz
```

## Vendor
```bash
php artisan vendor:publish
```
A file `sslcommerz.php` will be added to `config` directory after running above command. We need to setup our configuration to `.env` file as follows:

```bash
STORE_ID="your-store-id"
STORE_PASSWORD="your-store-password"
IS_PRODUCTION=false
```
For deveopment mode we need to set `IS_PRODUCTION=false`, and for production mode `IS_PRODUCTION=true`. Please go through the official [docs](https://developer.sslcommerz.com/) of SSLCommerz for further information.

## Usage
#### *Initiate a payment*
```php
$sslcommerz = new SSLCommerz();
$sslcommerz->setPaymentDisplayType('hosted'); // enum('hosted', 'checkout')
$sslcommerz->setPrimaryInformation([
    'total_amount' => 1000,
    'currency' => 'BDT',
]);
$sslcommerz->setTranId('your-transaction-id'); // set your transaction id here
$sslcommerz->setSuccessUrl('http://www.example.com/success');
$sslcommerz->setFailUrl('http://www.example.com/fail');
$sslcommerz->setCancelUrl('http://www.example.com/cancel');
$sslcommerz->setCustomerInformation([
    'cus_name' => 'John Doe',
    'cus_email' => 'john.doe@yahoo.com',
    'cus_add1' => 'Dhaka',
    'cus_add2' => 'Dhaka',
    'cus_city' => 'Dhaka',
    'cus_state' => 'Dhaka',
    'cus_postcode' => '1000',
    'cus_country' => 'Bangladesh',
    'cus_phone' => '+880**********',
]);
$sslcommerz->setShipmentInformation([
    'ship_name' => 'Store Test',
    'ship_add1' => 'Dhaka',
    'ship_add2' => 'Dhaka',
    'ship_city' => 'Dhaka',
    'ship_state' => 'Dhaka',
    'ship_postcode' => '1000',
    'ship_country' => 'Bangladesh',
    'shipping_method' => 'NO',
]);
$sslcommerz->setAdditionalInformation([
    'value_a' => 'CPT-112-A',
    'value_b' => 'CPT-112-B',
    'value_c' => 'CPT-112-C',
    'value_d' => 'CPT-112-D',
]);
$sslcommerz->setEmiOption(1); // enum(1, 0)
$sslcommerz->setProductInformation([
    'product_name' => 'Computer',
    'product_category' => 'Goods',
    'product_profile' => 'physical-goods',
]);
$sslcommerz->setProductCart([
    ['product' => 'Product X', 'amount' => '2000.00'],
    ['product' => 'Product Y', 'amount' => '4000.00'],
    ['product' => 'Product Z', 'amount' => '8000.00'],
]);
$sslcommerz->setProductAmount('1000');
$sslcommerz->setProductVat('100');
$sslcommerz->setProductDiscountAmount('0');
$sslcommerz->setProductConvenienceFee('50');

$response = $sslcommerz->initPayment($sslcommerz);
```
#### *Set store information dynamically*

```php
$sslcommerz = new SSLCommerz([
    'store_id' => 'your-store-id',
    'store_password' => 'your-store-password',
    'is_production' => false
]);
```

#### *Response*
> You will get a response after initiating a payment by which you can deal with. You can see a sample response format in the official documentation.

## License
[MIT](https://choosealicense.com/licenses/mit/)