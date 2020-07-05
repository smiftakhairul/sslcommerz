<?php

namespace SSLCZ\SSLCommerz;
use SSLCZ\SSLCommerz\SSLCommerzRequestValidation;
use Exception;

class SSLCommerzUtil
{
    use SSLCommerzRequestValidation;

    protected $config = [];
    protected $primary = [];
    protected $emi = [];
    protected $customer_information = [];
    protected $shipment_information = [];
    protected $product_information = [];
    protected $additional_information = [];
    protected $api_url = null;
    protected $payment_display_type = null;
    protected $is_production = false;
    protected $tran_id = null;
    protected $response;

    public function getApiUrl()
    {
        return $this->api_url;
    }

    public function getTranId()
    {
        return $this->tran_id;
    }

    public function setTranId($id)
    {
        $this->tran_id = $id;
        return true;
    }

    public function setApiUrl(string $url)
    {
        $this->api_url = $url;
        return true;
    }

    public function isProductionMode()
    {
        return $this->is_production;
    }

    public function setProductionMode(bool $is_production)
    {
        $this->is_production = $is_production;
        $this->setApiUrl(
            $is_production
                ? $this->config['api_domain']['production'] . $this->config['api_url']['init_payment']
                : $this->config['api_domain']['sandbox'] . $this->config['api_url']['init_payment']
        );
        return true;
    }

    public function getPaymentDisplayType()
    {
        return $this->payment_display_type;
    }

    public function setPaymentDisplayType(string $type)
    {
        $this->payment_display_type = $type;
    }

//    Primary Information
    public function getPrimaryInformation()
    {
        return $this->primary;
    }

    public function setPrimaryInformation(array $data)
    {
        $validated = $this->validateInformation($data, 'primary');
        if ($validated['status'] == 'fail') {
            throw new Exception($validated['message']);
        }

        foreach ($data as $field => $value) {
            $this->primary[$field] = $value;
        }
        return true;
    }

    public function getStoreId()
    {
        return $this->primary['store_id'] ?? null;
    }

    public function setStoreId(string $store_id)
    {
        $this->primary['store_id'] = $store_id;
        return true;
    }

    public function getStorePassword()
    {
        return $this->primary['store_password'] ?? null;
    }

    public function setStorePassword(string $store_password)
    {
        $this->primary['store_password'] = $store_password;
        return true;
    }

    public function getTotalAmount()
    {
        return $this->primary['total_amount'] ?? null;
    }

    public function setTotalAmount($amount)
    {
        $this->primary['total_amount'] = $amount;
        return true;
    }

    public function getCurrency()
    {
        return $this->primary['currency'] ?? null;
    }

    public function setCurrency(string $currency)
    {
        $this->primary['currency'] = $currency;
        return true;
    }

    public function getSuccessUrl()
    {
        return $this->primary['success_url'] ?? null;
    }

    public function setSuccessUrl(string $url)
    {
        $this->primary['success_url'] = $url;
        return true;
    }

    public function getFailUrl()
    {
        return $this->primary['fail_url'] ?? null;
    }

    public function setFailUrl(string $url)
    {
        $this->primary['fail_url'] = $url;
        return true;
    }

    public function getCancelUrl()
    {
        return $this->primary['cancel_url'] ?? null;
    }

    public function setCancelUrl(string $url)
    {
        $this->primary['cancel_url'] = $url;
        return true;
    }

    public function getIpnUrl()
    {
        return $this->primary['ipn_url'] ?? null;
    }

    public function setIpnUrl(string $url)
    {
        $this->primary['ipn_url'] = $url;
        return true;
    }

    public function getMultiCardName()
    {
        return $this->primary['multi_card_name'] ?? null;
    }

    public function setMultiCardName(string $name)
    {
        $this->primary['multi_card_name'] = $name;
        return true;
    }

    public function getAllowedBin()
    {
        return $this->primary['allowed_bin'] ?? null;
    }

    public function setAllowedBin(string $name)
    {
        $this->primary['allowed_bin'] = $name;
        return true;
    }

//    EMI Information
    public function getEmiInformation()
    {
        return $this->emi;
    }

    public function setEmiInformation(array $data)
    {
        $validated = $this->validateInformation($data, 'emi');
        if ($validated['status'] == 'fail') {
            throw new Exception($validated['message']);
        }

        foreach ($data as $field => $value) {
            $this->emi[$field] = $value;
        }
        return true;
    }

    public function getEmiOption()
    {
        return $this->emi['emi_option'] ?? null;
    }

    public function setEmiOption(int $option)
    {
        $this->emi['emi_option'] = $option;
        return true;
    }

    public function getEmiMaxInstOption()
    {
        return $this->emi['emi_max_inst_option'] ?? null;
    }

    public function setEmiMaxInstOption(int $option)
    {
        $this->emi['emi_max_inst_option'] = $option;
        return true;
    }

    public function getEmiSelectedInst()
    {
        return $this->emi['emi_selected_inst'] ?? null;
    }

    public function setEmiSelectedInst(int $option)
    {
        $this->emi['emi_selected_inst'] = $option;
        return true;
    }

    public function getEmiAllowOnly()
    {
        return $this->emi['emi_allow_only'] ?? null;
    }

    public function setEmiAllowOnly(int $option)
    {
        $this->emi['emi_allow_only'] = $option;
        return true;
    }

//    Customer Information
    public function getCustomerInformation()
    {
        return $this->customer_information;
    }

    public function setCustomerInformation(array $data)
    {
        $validated = $this->validateInformation($data, 'customer_information');
        if ($validated['status'] == 'fail') {
            throw new Exception($validated['message']);
        }

        foreach ($data as $field => $value) {
            $this->customer_information[$field] = $value;
        }
        return true;
    }

    public function getCustomerName()
    {
        return $this->customer_information['cus_name'] ?? null;
    }

    public function setCustomerName(string $name)
    {
        $this->customer_information['cus_name'] = $name;
        return true;
    }

    public function getCustomerEmail()
    {
        return $this->customer_information['cus_email'] ?? null;
    }

    public function setCustomerEmail(string $name)
    {
        $this->customer_information['cus_email'] = $name;
        return true;
    }

    public function getCustomerAddress1()
    {
        return $this->customer_information['cus_add1'] ?? null;
    }

    public function setCustomerAddress1(string $name)
    {
        $this->customer_information['cus_add1'] = $name;
        return true;
    }

    public function getCustomerAddress2()
    {
        return $this->customer_information['cus_add2'] ?? null;
    }

    public function setCustomerAddress2(string $name)
    {
        $this->customer_information['cus_add2'] = $name;
        return true;
    }

    public function getCustomerCity()
    {
        return $this->customer_information['cus_city'] ?? null;
    }

    public function setCustomerCity(string $name)
    {
        $this->customer_information['cus_city'] = $name;
        return true;
    }

    public function getCustomerState()
    {
        return $this->customer_information['cus_state'] ?? null;
    }

    public function setCustomerState(string $name)
    {
        $this->customer_information['cus_state'] = $name;
        return true;
    }

    public function getCustomerPostCode()
    {
        return $this->customer_information['cus_postcode'] ?? null;
    }

    public function setCustomerPostCode(string $name)
    {
        $this->customer_information['cus_postcode'] = $name;
        return true;
    }

    public function getCustomerCountry()
    {
        return $this->customer_information['cus_country'] ?? null;
    }

    public function setCustomerCountry(string $name)
    {
        $this->customer_information['cus_country'] = $name;
        return true;
    }

    public function getCustomerPhone()
    {
        return $this->customer_information['cus_phone'] ?? null;
    }

    public function setCustomerPhone(string $name)
    {
        $this->customer_information['cus_phone'] = $name;
        return true;
    }

    public function getCustomerFax()
    {
        return $this->customer_information['cus_fax'] ?? null;
    }

    public function setCustomerFax(string $name)
    {
        $this->customer_information['cus_fax'] = $name;
        return true;
    }

//    Shipment Information
    public function getShipmentInformation()
    {
        return $this->shipment_information;
    }

    public function setShipmentInformation(array $data)
    {
        $validated = $this->validateInformation($data, 'shipment_information');
        if ($validated['status'] == 'fail') {
            throw new Exception($validated['message']);
        }

        foreach ($data as $field => $value) {
            $this->shipment_information[$field] = $value;
        }
        return true;
    }

    public function getShippingMethod()
    {
        return $this->shipment_information['shipping_method'] ?? null;
    }

    public function setShippingMethod(string $method)
    {
        $this->shipment_information['shipping_method'] = $method;
        return true;
    }

    public function getShippingItemNumber()
    {
        return $this->shipment_information['num_of_item'] ?? null;
    }

    public function setShippingItemNumber(int $number)
    {
        $this->shipment_information['num_of_item'] = $number;
        return true;
    }

    public function getShippingName()
    {
        return $this->shipment_information['ship_name'] ?? null;
    }

    public function setShippingName(string $name)
    {
        $this->shipment_information['ship_name'] = $name;
        return true;
    }

    public function getShippingAddress1()
    {
        return $this->shipment_information['ship_add1'] ?? null;
    }

    public function setShippingAddress1(string $name)
    {
        $this->shipment_information['ship_add1'] = $name;
        return true;
    }

    public function getShippingAddress2()
    {
        return $this->shipment_information['ship_add2'] ?? null;
    }

    public function setShippingAddress2(string $name)
    {
        $this->shipment_information['ship_add2'] = $name;
        return true;
    }

    public function getShippingCity()
    {
        return $this->shipment_information['ship_city'] ?? null;
    }

    public function setShippingCity(string $name)
    {
        $this->shipment_information['ship_city'] = $name;
        return true;
    }

    public function getShippingState()
    {
        return $this->shipment_information['ship_state'] ?? null;
    }

    public function setShippingState(string $name)
    {
        $this->shipment_information['ship_state'] = $name;
        return true;
    }

    public function getShippingPostCode()
    {
        return $this->shipment_information['ship_postcode'] ?? null;
    }

    public function setShippingPostCode(string $code)
    {
        $this->shipment_information['ship_postcode'] = $code;
        return true;
    }

    public function getShippingCountry()
    {
        return $this->shipment_information['ship_country'] ?? null;
    }

    public function setShippingCountry(string $name)
    {
        $this->shipment_information['ship_country'] = $name;
        return true;
    }

//    Product Information
    public function getProductInformation()
    {
        return $this->product_information;
    }

    public function setProductInformation(array $data)
    {
        $validated = $this->validateInformation($data, 'product_information');
        if ($validated['status'] == 'fail') {
            throw new Exception($validated['message']);
        }

        foreach ($data as $field => $value) {
            $this->product_information[$field] = $value;
        }
        return true;
    }

    public function getProductName()
    {
        return $this->product_information['product_name'] ?? null;
    }

    public function setProductName(string $name)
    {
        $this->product_information['product_name'] = $name;
        return true;
    }

    public function getProductCategory()
    {
        return $this->product_information['product_category'] ?? null;
    }

    public function setProductCategory(string $name)
    {
        $this->product_information['product_category'] = $name;
        return true;
    }

    public function getProductProfile()
    {
        return $this->product_information['product_profile'] ?? null;
    }

    public function setProductProfile(string $name)
    {
        $this->product_information['product_profile'] = $name;
        return true;
    }

    public function getProductHoursTillDeparture()
    {
        return $this->product_information['hours_till_departure'] ?? null;
    }

    public function setProductHoursTillDeparture(string $name)
    {
        $this->product_information['hours_till_departure'] = $name;
        return true;
    }

    public function getProductFlightType()
    {
        return $this->product_information['flight_type'] ?? null;
    }

    public function setProductFlightType(string $name)
    {
        $this->product_information['flight_type'] = $name;
        return true;
    }

    public function getProductPnr()
    {
        return $this->product_information['pnr'] ?? null;
    }

    public function setProductPnr(string $name)
    {
        $this->product_information['pnr'] = $name;
        return true;
    }

    public function getProductJourneyFromTo()
    {
        return $this->product_information['journey_from_to'] ?? null;
    }

    public function setProductJourneyFromTo(string $name)
    {
        $this->product_information['journey_from_to'] = $name;
        return true;
    }

    public function getProductThirdPartyBooking()
    {
        return $this->product_information['third_party_booking'] ?? null;
    }

    public function setProductThirdPartyBooking(string $name)
    {
        $this->product_information['third_party_booking'] = $name;
        return true;
    }

    public function getProductHotelName()
    {
        return $this->product_information['hotel_name'] ?? null;
    }

    public function setProductHotelName(string $name)
    {
        $this->product_information['hotel_name'] = $name;
        return true;
    }

    public function getProductLengthOfStay()
    {
        return $this->product_information['length_of_stay'] ?? null;
    }

    public function setProductLengthOfStay(string $name)
    {
        $this->product_information['length_of_stay'] = $name;
        return true;
    }

    public function getProductCheckInTime()
    {
        return $this->product_information['check_in_time'] ?? null;
    }

    public function setProductCheckInTime(string $name)
    {
        $this->product_information['check_in_time'] = $name;
        return true;
    }

    public function getProductHotelCity()
    {
        return $this->product_information['hotel_city'] ?? null;
    }

    public function setProductHotelCity(string $name)
    {
        $this->product_information['hotel_city'] = $name;
        return true;
    }

    public function getProductType()
    {
        return $this->product_information['product_type'] ?? null;
    }

    public function setProductType(string $name)
    {
        $this->product_information['product_type'] = $name;
        return true;
    }

    public function getProductTopUpNumber()
    {
        return $this->product_information['topup_number'] ?? null;
    }

    public function setProductTopUpNumber(string $name)
    {
        $this->product_information['topup_number'] = $name;
        return true;
    }

    public function getProductCountryTopUp()
    {
        return $this->product_information['country_topup'] ?? null;
    }

    public function setProductCountryTopUp(string $name)
    {
        $this->product_information['country_topup'] = $name;
        return true;
    }

    public function getProductCart()
    {
        return $this->product_information['cart'] ?? [];
    }

    public function setCart(array $data)
    {
        $this->product_information['cart'] = $data;
        return true;
    }

    public function getProductAmount()
    {
        return $this->product_information['product_amount'] ?? null;
    }

    public function setProductAmount($amount)
    {
        $this->product_information['product_amount'] = $amount;
        return true;
    }

    public function getProductVat()
    {
        return $this->product_information['vat'] ?? null;
    }

    public function setVat($amount)
    {
        $this->product_information['vat'] = $amount;
        return true;
    }

    public function getProductDiscountAmount()
    {
        return $this->product_information['discount_amount'] ?? null;
    }

    public function setDiscountAmount($amount)
    {
        $this->product_information['discount_amount'] = $amount;
        return true;
    }

    public function getProductConvenienceFee()
    {
        return $this->product_information['convenience_fee'] ?? null;
    }

    public function setConvenienceFee($amount)
    {
        $this->product_information['convenience_fee'] = $amount;
        return true;
    }

//    Additional Information
    public function getAdditionalInformation()
    {
        return $this->additional_information;
    }

    public function setAdditionalInformation(array $data)
    {
        $validated = $this->validateInformation($data, 'additional_information');
        if ($validated['status'] == 'fail') {
            throw new Exception($validated['message']);
        }

        foreach ($data as $field => $value) {
            $this->additional_information[$field] = $value;
        }
        return true;
    }

    public function getAdditionalValueA()
    {
        return $this->additional_information['value_a'] ?? null;
    }

    public function setAdditionalValueA(string $value)
    {
        $this->additional_information['value_a'] = $value;
        return true;
    }

    public function getAdditionalValueB()
    {
        return $this->additional_information['value_b'] ?? null;
    }

    public function setAdditionalValueB(string $value)
    {
        $this->additional_information['value_b'] = $value;
        return true;
    }

    public function getAdditionalValueC()
    {
        return $this->additional_information['value_c'] ?? null;
    }

    public function setAdditionalValueC(string $value)
    {
        $this->additional_information['value_c'] = $value;
        return true;
    }

    public function getAdditionalValueD()
    {
        return $this->additional_information['value_d'] ?? null;
    }

    public function setAdditionalValueD(string $value)
    {
        $this->additional_information['value_d'] = $value;
        return true;
    }
}
