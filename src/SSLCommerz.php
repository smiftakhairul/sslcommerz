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
        $this->config = config('sslcommerz');
        $this->primary['store_id'] = !empty($config['store_id']) ? $config['store_id'] : $this->config['store_id'];
        $this->primary['store_password'] = !empty($config['store_password']) ? $config['store_password'] : $this->config['store_password'];
        $this->is_production = !empty($config['is_production']) ? $config['is_production'] : $this->config['is_production'];
        $this->api_url = $this->is_production
            ? $this->config['api_domain']['production'] . $this->config['api_url']['init_payment']
            : $this->config['api_domain']['sandbox'] . $this->config['api_url']['init_payment'];
        $this->payment_display_type = 'checkout';
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
//        $formattedResponse = $this->initPaymentApiResponseFormat($response);
//
//        if ($data->payment_display_type == 'checkout') {
//            echo $formattedResponse;
//        } else {
//            if (isset($formattedResponse['GatewayPageURL']) && !empty($formattedResponse['GatewayPageURL'])) {
//                $this->redirect($formattedResponse['GatewayPageURL']);
//            } else {
//                $error_message = "No redirect URL found!";
//                return $error_message;
//            }
//        }
        return json_decode($response, true);
    }

    public function orderValidate(array $data)
    {
        if (!$this->validateOrderParams($data)) {
            return $this->response = [
                'status' => 'fail',
                'message' => 'Please provide valid transaction ID or post request data'
            ];
        }

        $data['store_id'] = $this->getStoreId();
        $data['store_password'] = $this->getStorePassword();

        if ($this->hash_verify($data)) {
            $response = $this->orderValidateApiRequest($data);

            if ($response->status() === 200 && $response->ok() && $response->successful()) {
                return $this->orderValidateApiResponseValidate($response, $data);
            } else {
                return $this->response = [
                    'status' => 'fail',
                    'message' => 'Failed to connect with SSLCommerz'
                ];
            }
        } else {
            return $this->response = [
                'status' => 'fail',
                'message' => 'Hash validation failed'
            ];
        }
    }

    public function formatCheckoutResponse($response)
    {
        return $this->apiFormatResponseCheckout($response);
    }
}
