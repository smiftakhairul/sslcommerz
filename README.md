# SSLCommerz
[SSLCommerz](https://www.sslcommerz.com/) is the first payment gateway in Bangladesh opening doors for merchants to receive payments on the internet via their online stores.

Official documentation [here](https://developer.sslcommerz.com/).

## Installation
```bash
$ composer require smiftakhairul/sslcommerz
```

## Vendor
```bash
$ php artisan vendor:publish --provider="SSLCZ\SSLCommerz\SSLCommerzServiceProvider"
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
$sslcommerz->setCart([
    ['product' => 'Product X', 'amount' => '2000.00'],
    ['product' => 'Product Y', 'amount' => '4000.00'],
    ['product' => 'Product Z', 'amount' => '8000.00'],
]);
$sslcommerz->setProductAmount('1000');
$sslcommerz->setVat('100');
$sslcommerz->setDiscountAmount('0');
$sslcommerz->setConvenienceFee('50');

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

### Hosted Payment Integration
```php
// Controller
$sslcommerz = new SSLCommerz();
$sslcommerz->setPaymentDisplayType('hosted');
// ---

$response = $sslcommerz->initPayment($sslcommerz);
return redirect($response['GatewayPageURL']); // redirect to gateway page url
```

### Easy Checkout Integration
```javascript
// View(js) - Step 1
(function (window, document) {
    var loader = function () {
        var script = document.createElement("script"), tag = document.getElementsByTagName("script")[0];
        script.src = "{{ 'Sandbox or Live(Production) Script' }}" + Math.random().toString(36).substring(7);
        tag.parentNode.insertBefore(script, tag);
    };

    window.addEventListener ? window.addEventListener("load", loader, false) : window.attachEvent("onload", loader);
})(window, document);

/*
Sandbox Script URL: https://sandbox.sslcommerz.com/embed.min.js?
Live or Production Script URL: https://seamless-epay.sslcommerz.com/embed.min.js?
 */
```

```html
<!-- View(js) - Step 2 -->
<button class="your-button-class" id="sslczPayBtn"
        token="if you have any token validation"
        postdata="your javascript arrays or objects which requires in backend"
        order="If you already have the transaction generated for current order"
        endpoint="{{ 'your-easy-checkout-pay-url' }}"> Pay Now
</button>
```

```php
// Controller
$sslcommerz = new SSLCommerz();
$sslcommerz->setPaymentDisplayType('hosted');
// ---

$response = $sslcommerz->initPayment($sslcommerz);
echo $sslcommerz->formatCheckoutResponse($response); // show easycheckout pay popup
```

### Disable CSRF Protection
Disable `CSRF` protection for the following URL's. 
- `init-payment-via-ajax` url
- `success` url
- `fail` url
- `cancel` url
- `ipn` url

Disable them from `VerifyCsrfToken` middleware.
```php
// VerifyCsrfToken.php
protected $except = [
    '/init-payment-via-ajax', 
    '/success', 
    '/cancel', 
    '/fail', 
    '/ipn'
];
```
> You are all set!

## Available Methods
<table>
    <thead>
        <tr>
            <th>Method Name</th>
            <th style="text-align: center">Param Info</th>
            <th>Description</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td><code>getPrimaryInformation()</code></td>
            <td style="text-align: center"></td>
            <td>
                Get primary information such as:
                <br>
                <span>
                    <span><b>store_id</b></span>,
                    <span><b>store_passwd</b></span>,
                    <span><b>total_amount</b></span>,
                    <span><b>currency</b></span>,
                    <span><b>tran_id</b></span>,
                    <span><b>success_url</b></span>,
                    <span><b>fail_url</b></span>,
                    <span><b>cancel_url</b></span>,
                    <span><b>ipn_url</b></span>,
                    <span><b>multi_card_name</b></span>,
                    <span><b>allowed_bin</b></span>,
                </span>
            </td>
        </tr>
        <tr>
            <td><code>setCustomerInformation()</code></td>
            <td style="text-align: center"><span><code>array()</code></span></td>
            <td>
                Set primary information.
                <br><br>
                Required parameter elements:
                <ul>
                    <li><b>store_id</b></li>
                    <li><b>store_passwd</b></li>
                    <li><b>total_amount</b></li>
                    <li><b>currency</b></li>
                    <li><b>tran_id</b></li>
                    <li><b>success_url</b></li>
                    <li><b>fail_url</b></li>
                    <li><b>cancel_url</b></li>
                </ul>
                Optional parameter elements:
                <ul>
                    <li><b>ipn_url</b></li>
                    <li><b>multi_card_name</b></li>
                    <li><b>allowed_bin</b></li>
                </ul>
            </td>
        </tr>
    </tbody>
</table>

## License
[MIT](https://choosealicense.com/licenses/mit/)
