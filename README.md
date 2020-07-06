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

### Validate Order
Validate order from **success**, **fail**, **cancel** or **ipn** url.
```php
$sslcommerz = new SSLCommerz();
$response = $sslcommerz->orderValidate($request->all());
```
The response contains status and full information of order.
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
                    <span><b>cancel_url</b></span> and other optional information.
                </span>
            </td>
        </tr>
        <tr>
            <td><code>setPrimaryInformation()</code></td>
            <td style="text-align: center"><span><code>array()</code></span></td>
            <td>
                Set primary information.
                <br><br>
                Required parameter key elements:
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
                Optional parameter key elements:
                <ul>
                    <li><b>ipn_url</b></li>
                    <li><b>multi_card_name</b></li>
                    <li><b>allowed_bin</b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>getCustomerInformation()</code></td>
            <td style="text-align: center"></td>
            <td>
                Get customer information such as:
                <br>
                <span>
                    <span><b>cus_name</b></span>,
                    <span><b>cus_email</b></span>,
                    <span><b>cus_add1</b></span>,
                    <span><b>cus_add2</b></span>,
                    <span><b>cus_city</b></span>,
                    <span><b>cus_postcode</b></span>,
                    <span><b>cus_country</b></span>,
                    <span><b>cus_phone</b></span> and other optional information.
                </span>
            </td>
        </tr>
        <tr>
            <td><code>setCustomerInformation()</code></td>
            <td style="text-align: center"><span><code>array()</code></span></td>
            <td>
                Set customer information.
                <br><br>
                Required parameter key elements:
                <ul>
                    <li><b>cus_name</b></li>
                    <li><b>cus_email</b></li>
                    <li><b>cus_add1</b></li>
                    <li><b>cus_add2</b></li>
                    <li><b>cus_city</b></li>
                    <li><b>cus_postcode</b></li>
                    <li><b>cus_country</b></li>
                    <li><b>cus_phone</b></li>
                </ul>
                Optional parameter key elements:
                <ul>
                    <li><b>cus_state</b></li>
                    <li><b>cus_fax</b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>getProductInformation()</code></td>
            <td style="text-align: center"></td>
            <td>
                Get product information such as:
                <br>
                <span>
                    <span><b>product_name</b></span>,
                    <span><b>product_category</b></span>,
                    <span><b>product_profile</b></span> and other optional information.
                </span>
            </td>
        </tr>
        <tr>
            <td><code>setProductInformation()</code></td>
            <td style="text-align: center"><span><code>array()</code></span></td>
            <td>
                Set product information.
                <br><br>
                Required parameter key elements:
                <ul>
                    <li><b>product_name</b></li>
                    <li><b>product_category</b></li>
                    <li><b>product_profile</b></li>
                </ul>
                Optional parameter key elements:
                <ul>
                    <li><b>cart</b></li>
                    <li><b>product_amount</b></li>
                    <li><b>vat</b></li>
                    <li><b>discount_amount</b></li>
                    <li><b>convenience_fee</b></li>
                    <li><b>hours_till_departure</b></li>
                    <li><b>flight_type</b></li>
                    <li><b>pnr</b></li>
                    <li><b>journey_from_to</b></li>
                    <li><b>third_party_booking</b></li>
                    <li><b>hotel_name</b></li>
                    <li><b>length_of_stay</b></li>
                    <li><b>check_in_time</b></li>
                    <li><b>hotel_city</b></li>
                    <li><b>product_type</b></li>
                    <li><b>topup_number</b></li>
                    <li><b>country_topup</b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>getShipmentInformation()</code></td>
            <td style="text-align: center"></td>
            <td>
                Get shipment information such as:
                <br>
                <span>
                    <span><b>shipping_method</b></span>,
                    <span><b>num_of_item</b></span> and other optional information.
                </span>
            </td>
        </tr>
        <tr>
            <td><code>setShipmentInformation()</code></td>
            <td style="text-align: center"><span><code>array()</code></span></td>
            <td>
                Set shipment information.
                <br><br>
                Required parameter key elements:
                <ul>
                    <li><b>shipping_method</b></li>
                    <li><b>num_of_item</b></li>
                </ul>
                Optional parameter key elements:
                <ul>
                    <li><b>ship_name</b></li>
                    <li><b>ship_add1</b></li>
                    <li><b>ship_add2</b></li>
                    <li><b>ship_state</b></li>
                    <li><b>ship_city</b></li>
                    <li><b>ship_postcode</b></li>
                    <li><b>ship_country</b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>getEmiInformation()</code></td>
            <td style="text-align: center"></td>
            <td>
                Get EMI information such as:
                <br>
                <span>
                    <span><b>emi_option</b></span> and other optional information.
                </span>
            </td>
        </tr>
        <tr>
            <td><code>setEmiInformation()</code></td>
            <td style="text-align: center"><span><code>array()</code></span></td>
            <td>
                Set EMI information.
                <br><br>
                Required parameter key elements:
                <ul>
                    <li><b>emi_option</b></li>
                </ul>
                Optional parameter key elements:
                <ul>
                    <li><b>emi_max_inst_option</b></li>
                    <li><b>emi_selected_inst</b></li>
                    <li><b>emi_allow_only</b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>getAdditionalInformation()</code></td>
            <td style="text-align: center"></td>
            <td>
                Get additional information such as:
                <br>
                <span>
                    <span><b>value_a</b></span>,
                    <span><b>value_b</b></span>,
                    <span><b>value_c</b></span>,
                    <span><b>value_d</b></span>.
                </span>
            </td>
        </tr>
        <tr>
            <td><code>setAdditionalInformation()</code></td>
            <td style="text-align: center"><span><code>array()</code></span></td>
            <td>
                Set additional information.
                <br><br>
                Optional parameter key elements:
                <ul>
                    <li><b>value_a</b></li>
                    <li><b>value_b</b></li>
                    <li><b>value_c</b></li>
                    <li><b>value_d</b></li>
                </ul>
            </td>
        </tr>
        <tr>
            <td><code>getApiUrl()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>api_url</b>.</td>
        </tr>
        <tr>
            <td><code>setApiUrl()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>api_url</b>. By default, <b>api_url</b> sets based on <code>IS_PRODUCTION</code> value. If <code>IS_PRODUCTION = true</code>, live api url will be set and for <code>IS_PRODUCTION = false</code> sandbox api url will be set.</td>
        </tr>
        <tr>
            <td><code>isProductionMode()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>production_mode</b>.</td>
        </tr>
        <tr>
            <td><code>setProductionMode()</code><b>*</b></td>
            <td style="text-align: center"><span><code>boolean</code></span></td>
            <td>Set <b>production_mode</b>. By default, <b>production_mode</b> sets by <code>IS_PRODUCTION</code> value.</td>
        </tr>
        <tr>
            <td><code>getPaymentDisplayType()</code></td>
            <td style="text-align: center"></td>
            <td>Get payment display type.</td>
        </tr>
        <tr>
            <td><code>setPaymentDisplayType()</code><b>*</b></td>
            <td style="text-align: center"><span><code>enum('hosted', 'checkout')</code></span></td>
            <td>Set payment display type. Default value is <b>checkout</b>.</td>
        </tr>
        <tr>
            <td><code>getStoreId()</code></td>
            <td style="text-align: center"></td>
            <td>Get SSLCommerz <b>store_id</b>.</td>
        </tr>
        <tr>
            <td><code>setStoreId()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set SSLCommerz <b>store_id</b>. Default value sets by <code>STORE_ID</code> value.</td>
        </tr>
        <tr>
            <td><code>getStorePassword()</code></td>
            <td style="text-align: center"></td>
            <td>Get SSLCommerz <b>store_passwd</b>.</td>
        </tr>
        <tr>
            <td><code>setStorePassword()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set SSLCommerz <b>store_passwd</b>. Default value sets by <code>STORE_PASSWORD</code> value.</td>
        </tr>
        <tr>
            <td><code>getTotalAmount()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>total_amount</b> of transaction.</td>
        </tr>
        <tr>
            <td><code>setTotalAmount()</code><b>*</b></td>
            <td style="text-align: center"><span><code>decimal</code></span></td>
            <td>Set <b>total_amount</b> of transaction. The transaction amount must be from <b>10.00 BDT</b> to <b>500000.00 BDT</b></td>
        </tr>
        <tr>
            <td><code>getCurrency()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>currency</b> type. Example: BDT, USD, EUR, SGD, INR, MYR, etc</td>
        </tr>
        <tr>
            <td><code>setCurrency()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>currency</b> type.</td>
        </tr>
        <tr>
            <td><code>getTranId()</code></td>
            <td style="text-align: center"></td>
            <td>Get unique <b>tran_id</b> to identify order.</td>
        </tr>
        <tr>
            <td><code>setTranId()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>tran_id</b> to unify your order.</td>
        </tr>
        <tr>
            <td><code>getSuccessUrl()</code></td>
            <td style="text-align: center"></td>
            <td>Get callback <b>success_url</b>.</td>
        </tr>
        <tr>
            <td><code>setSuccessUrl()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set callback <b>success_url</b> where user will redirect after successful payment.</td>
        </tr>
        <tr>
            <td><code>getFailUrl()</code></td>
            <td style="text-align: center"></td>
            <td>Get callback <b>fail_url</b>.</td>
        </tr>
        <tr>
            <td><code>setFailUrl()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set callback <b>fail_url</b> where user will redirect after any failure occurs during payment.</td>
        </tr>
        <tr>
            <td><code>getCancelUrl()</code></td>
            <td style="text-align: center"></td>
            <td>Get callback <b>cancel_url</b>.</td>
        </tr>
        <tr>
            <td><code>setCancelUrl()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set callback <b>cancel_url</b> where user will redirect if user cancels the transaction.</td>
        </tr>
        <tr>
            <td><code>getIpnUrl()</code></td>
            <td style="text-align: center"></td>
            <td>Get Instant Payment Notification <b>ipn_url</b>.</td>
        </tr>
        <tr>
            <td><code>setIpnUrl()</code></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>ipn_url</b>. Enable instant payment notification option so that SSLCommerz can send the transaction's status to ipn_url.</td>
        </tr>
        <tr>
            <td><code>getMultiCardName()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>multi_card_name</b>.</td>
        </tr>
        <tr>
            <td><code>setMultiCardName()</code></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>multi_card_name</b>. Use it only if gateway list needs to be customized.</td>
        </tr>
        <tr>
            <td><code>getAllowedBin()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>allowed_bin</b>.</td>
        </tr>
        <tr>
            <td><code>setAllowedBin()</code></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>allowed_bin</b>. Use it only if transaction needs to be controlled.</td>
        </tr>
        <tr>
            <td><code>getCustomerName()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_name</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerName()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_name</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerEmail()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_email</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerEmail()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_email</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerAddress1()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_add1</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerAddress1()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_add1</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerAddress2()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_add2</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerAddress2()</code></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_add2</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerCity()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_city</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerCity()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_city</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerState()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_state</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerState()</code></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_state</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerPostCode()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_postcode</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerPostCode()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_postcode</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerCountry()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_country</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerCountry()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_country</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerPhone()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_phone</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerPhone()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_phone</b>.</td>
        </tr>
        <tr>
            <td><code>getCustomerFax()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cus_fax</b>.</td>
        </tr>
        <tr>
            <td><code>setCustomerFax()</code></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>cus_fax</b>.</td>
        </tr>
        <tr>
            <td><code>getProductName()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>product_name</b>.</td>
        </tr>
        <tr>
            <td><code>setProductName()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>product_name</b>.</td>
        </tr>
        <tr>
            <td><code>getProductCategory()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>product_category</b>.</td>
        </tr>
        <tr>
            <td><code>setProductCategory()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>product_category</b>.</td>
        </tr>
        <tr>
            <td><code>getProductProfile()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>product_profile</b>.</td>
        </tr>
        <tr>
            <td><code>setProductProfile()</code><b>*</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>product_profile</b>.
                <br><br>Available keys:
                <ol>
                    <li>general</li>
                    <li>physical-goods</li>
                    <li>non-physical-goods</li>
                    <li>airline-tickets</li>
                    <li>travel-vertical</li>
                    <li>telecom-vertical</li>
                </ol>
            </td>
        </tr>
        <tr>
            <td><code>getProductHoursTillDeparture()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>hours_till_departure</b>.</td>
        </tr>
        <tr>
            <td><code>setProductHoursTillDeparture()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>hours_till_departure</b>. <b>Required</b> if <code>product_profile</code> is <b>airline-tickets</b></td>
        </tr>
        <tr>
            <td><code>getProductFlightType()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>flight_type</b>.</td>
        </tr>
        <tr>
            <td><code>setProductFlightType()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>flight_type</b>. <b>Required</b> if <code>product_profile</code> is <b>airline-tickets</b></td>
        </tr>
        <tr>
            <td><code>getProductPnr()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>pnr</b>.</td>
        </tr>
        <tr>
            <td><code>setProductPnr()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>pnr</b>. <b>Required</b> if <code>product_profile</code> is <b>airline-tickets</b></td>
        </tr>
        <tr>
            <td><code>getProductJourneyFromTo()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>journey_from_to</b>.</td>
        </tr>
        <tr>
            <td><code>setProductJourneyFromTo()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>journey_from_to</b>. <b>Required</b> if <code>product_profile</code> is <b>airline-tickets</b></td>
        </tr>
        <tr>
            <td><code>getProductThirdPartyBooking()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>third_party_booking</b>.</td>
        </tr>
        <tr>
            <td><code>setProductThirdPartyBooking()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>third_party_booking</b>. <b>Required</b> if <code>product_profile</code> is <b>airline-tickets</b></td>
        </tr>
        <tr>
            <td><code>getProductHotelName()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>hotel_name</b>.</td>
        </tr>
        <tr>
            <td><code>setProductHotelName()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>hotel_name</b>. <b>Required</b> if <code>product_profile</code> is <b>travel-vertical</b></td>
        </tr>
        <tr>
            <td><code>getProductLengthOfStay()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>length_of_stay</b>.</td>
        </tr>
        <tr>
            <td><code>setProductLengthOfStay()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>length_of_stay</b>. <b>Required</b> if <code>product_profile</code> is <b>travel-vertical</b></td>
        </tr>
        <tr>
            <td><code>getProductCheckInTime()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>check_in_time</b>.</td>
        </tr>
        <tr>
            <td><code>setProductCheckInTime()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>check_in_time</b>. <b>Required</b> if <code>product_profile</code> is <b>travel-vertical</b></td>
        </tr>
        <tr>
            <td><code>getProductHotelCity()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>hotel_city</b>.</td>
        </tr>
        <tr>
            <td><code>setProductHotelCity()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>hotel_city</b>. <b>Required</b> if <code>product_profile</code> is <b>travel-vertical</b></td>
        </tr>
        <tr>
            <td><code>getProductType()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>product_type</b>.</td>
        </tr>
        <tr>
            <td><code>setProductType()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>product_type</b>. <b>Required</b> if <code>product_profile</code> is <b>telecom-vertical</b></td>
        </tr>
        <tr>
            <td><code>getProductTopUpNumber()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>topup_number</b>.</td>
        </tr>
        <tr>
            <td><code>setProductTopUpNumber()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>topup_number</b>. <b>Required</b> if <code>product_profile</code> is <b>telecom-vertical</b></td>
        </tr>
        <tr>
            <td><code>getProductCountryTopUp()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>country_topup</b>.</td>
        </tr>
        <tr>
            <td><code>setProductCountryTopUp()</code><b>**</b></td>
            <td style="text-align: center"><span><code>string</code></span></td>
            <td>Set <b>country_topup</b>. <b>Required</b> if <code>product_profile</code> is <b>telecom-vertical</b></td>
        </tr>
        <tr>
            <td><code>getCart()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>cart</b>.</td>
        </tr>
        <tr>
            <td><code>setCart()</code></td>
            <td style="text-align: center"><span><code>json</code></span></td>
            <td>Set <b>cart</b>. JSON data with two elements. <b>product</b>: Max 255 characters, <b>quantity</b>: Quantity in numeric value and <b>amount</b>: Decimal (12,2).<br><br>Example:<br><code>[{"product":"DHK TO BRS AC A1","quantity":"1","amount":"200.00"},{"product":"DHK TO BRS AC A2","quantity":"1","amount":"200.00"},{"product":"DHK TO BRS AC A3","quantity":"1","amount":"200.00"},{"product":"DHK TO BRS AC A4","quantity":"2","amount":"200.00"}]</code></td>
        </tr>
        <tr>
            <td><code>getProductAmount()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>product_amount</b>.</td>
        </tr>
        <tr>
            <td><code>setProductAmount()</code></td>
            <td style="text-align: center"><span><code>decimal</code></span></td>
            <td>Set <b>product_amount</b>.</td>
        </tr>
        <tr>
            <td><code>getVat()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>vat</b>.</td>
        </tr>
        <tr>
            <td><code>setVat()</code></td>
            <td style="text-align: center"><span><code>decimal</code></span></td>
            <td>Set <b>vat</b>.</td>
        </tr>
        <tr>
            <td><code>getDiscountAmount()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>discount_amount</b>.</td>
        </tr>
        <tr>
            <td><code>setDiscountAmount()</code></td>
            <td style="text-align: center"><span><code>decimal</code></span></td>
            <td>Set <b>discount_amount</b>.</td>
        </tr>
        <tr>
            <td><code>getConvenienceFee()</code></td>
            <td style="text-align: center"></td>
            <td>Get <b>convenience_fee</b>.</td>
        </tr>
        <tr>
            <td><code>setConvenienceFee()</code></td>
            <td style="text-align: center"><span><code>decimal</code></span></td>
            <td>Set <b>convenience_fee</b>.</td>
        </tr>
    </tbody>
</table>

## License
[MIT](https://choosealicense.com/licenses/mit/)
