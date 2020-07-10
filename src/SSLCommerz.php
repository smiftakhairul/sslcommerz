<?php

namespace SSLCZ\SSLCommerz;
use Illuminate\Support\Facades\Http;
use SSLCZ\SSLCommerz\SSLCommerzService;

class SSLCommerz extends SSLCommerzUtil
{
    use SSLCommerzService;
    use SSLCommerzRequestValidation;

    public function __construct($config = [])
    {
        $this->config = config(SSLCommerzEnum::$_CONFIG);
        $this->primary[SSLCommerzEnum::$_STORE_ID] = !empty($config[SSLCommerzEnum::$_STORE_ID])
            ? $config[SSLCommerzEnum::$_STORE_ID] : $this->config[SSLCommerzEnum::$_STORE_ID];
        $this->primary[SSLCommerzEnum::$_STORE_PASSWORD] = !empty($config[SSLCommerzEnum::$_STORE_PASSWORD])
            ? $config[SSLCommerzEnum::$_STORE_PASSWORD] : $this->config[SSLCommerzEnum::$_STORE_PASSWORD];
        $this->is_production = !empty($config[SSLCommerzEnum::$_IS_PRODUCTION])
            ? $config[SSLCommerzEnum::$_IS_PRODUCTION] : $this->config[SSLCommerzEnum::$_IS_PRODUCTION];
        $this->api_env = $this->is_production ? SSLCommerzEnum::$_ENV_PRODUCTION : SSLCommerzEnum::$_ENV_SANDBOX;
        $this->api_domain = $this->config[SSLCommerzEnum::$_API_DOMAIN][$this->api_env];
        $this->triggerUpdateApiUrls();
        $this->payment_display_type = SSLCommerzEnum::$_PAYMENT_DISPLAY_HOSTED;
        $this->response = null;
    }

    public function initPayment(object $data)
    {
        $post_data = [];

//        Primary Information
        if (!empty($data->primary)) {
            foreach ($data->primary as $field => $value) {
                $post_data[$field] = $value;
            }
        }

        $post_data['store_passwd'] = $post_data['store_password'];
        unset($post_data['store_password']);
        $post_data['tran_id'] = $data->tran_id;

//        Customer Information
        if (!empty($data->customer_information)) {
            foreach ($data->customer_information as $field => $value) {
                $post_data[$field] = $value;
            }
        }

//        Shipment Information
        if (!empty($data->shipment_information)) {
            foreach ($data->shipment_information as $field => $value) {
                $post_data[$field] = $value;
            }
        }

//        Product Information
        if (!empty($data->product_information)) {
            foreach ($data->product_information as $field => $value) {
                $post_data[$field] = $value;
            }
        }

        if (isset($post_data['cart'])) {
            $post_data['cart'] = json_encode($post_data['cart']);
        }

//        Additional Information
        if (!empty($data->additional_information)) {
            foreach ($data->additional_information as $field => $value) {
                $post_data[$field] = $value;
            }
        }

//        EMI Information
        if (!empty($data->emi)) {
            foreach ($data->emi as $field => $value) {
                $post_data[$field] = $value;
            }
        }

//        Make API Request
        $response = $this->initPaymentApiRequest($post_data);

        return json_decode($response, true);
    }

    public function orderValidate(array $data)
    {
        if (!$this->validateOrderParams($data)) {
            return $this->response = [
                'status' => 'FAIL',
                'message' => 'Please provide valid val_id or post request data'
            ];
        }

        $data['store_id'] = isset($data['store_id']) && !empty($data['store_id'])
            ? $data['store_id'] : $this->getStoreId();
        $data['store_password'] = isset($data['store_password']) && !empty($data['store_password'])
            ? $data['store_password'] : $this->getStorePassword();
        $data['v'] = (isset($data['v']) && !empty($data['v'])) ? $data['v'] : '1';
        $data['format'] = (isset($data['format']) && !empty($data['format'])) ? $data['format'] : 'json';

        $response = $this->orderValidateApiRequest($data);

        return json_decode($response, true);
    }

    public function formatCheckoutResponse($response)
    {
        return $this->apiFormatResponseCheckout($response);
    }

    public function transactionQueryById(array $data)
    {
        if (!$this->transactionQueryByIdParams($data)) {
            return $this->response = [
                'status' => 'FAIL',
                'message' => 'Please provide valid tran_id or post request data'
            ];
        }

        $data['store_id'] = isset($data['store_id']) && !empty($data['store_id'])
            ? $data['store_id'] : $this->getStoreId();
        $data['store_password'] = isset($data['store_password']) && !empty($data['store_password'])
            ? $data['store_password'] : $this->getStorePassword();

        $response = $this->transactionQueryByIdRequest($data);

        return json_decode($response, true);
    }

    public function transactionQueryBySessionId(array $data)
    {
        if (!$this->transactionQueryBySessionIdParams($data)) {
            return $this->response = [
                'status' => 'FAIL',
                'message' => 'Please provide valid sessionkey or post request data'
            ];
        }

        $data['store_id'] = isset($data['store_id']) && !empty($data['store_id'])
            ? $data['store_id'] : $this->getStoreId();
        $data['store_password'] = isset($data['store_password']) && !empty($data['store_password'])
            ? $data['store_password'] : $this->getStorePassword();

        $response = $this->transactionQueryBySessionIdRequest($data);

        return json_decode($response, true);
    }

    public function refundPayment(array $data)
    {
        if (!$this->refundPaymentParams($data)) {
            return $this->response = [
                'status' => 'FAIL',
                'message' => 'Please provide valid bank_tran_id or post request data'
            ];
        }

        $data['store_id'] = isset($data['store_id']) && !empty($data['store_id'])
            ? $data['store_id'] : $this->getStoreId();
        $data['store_password'] = isset($data['store_password']) && !empty($data['store_password'])
            ? $data['store_password'] : $this->getStorePassword();
        $data['refe_id'] = (isset($data['refe_id']) && !empty($data['refe_id'])) ? $data['refe_id'] : 'refe-id' . uniqid();
        $data['format'] = (isset($data['format']) && !empty($data['format'])) ? $data['format'] : 'json';

        $response = $this->refundPaymentRequest($data);

        return json_decode($response, true);
    }

    public function refundStatus(array $data)
    {
        if (!$this->refundStatusParams($data)) {
            return $this->response = [
                'status' => 'FAIL',
                'message' => 'Please provide valid refund_ref_id or post request data'
            ];
        }

        $data['store_id'] = isset($data['store_id']) && !empty($data['store_id'])
            ? $data['store_id'] : $this->getStoreId();
        $data['store_password'] = isset($data['store_password']) && !empty($data['store_password'])
            ? $data['store_password'] : $this->getStorePassword();

        $response = $this->refundStatusRequest($data);

        return json_decode($response, true);
    }
}
