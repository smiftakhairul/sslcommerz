<?php

namespace SSLCZ\SSLCommerz;
use Illuminate\Support\Facades\Http;

trait SSLCommerzService
{
    public function redirect($url, $permanent = false)
    {
        header('Location: ' . $url, true, $permanent ? 301 : 302);
        exit();
    }

    public function initPaymentApiRequest($data)
    {
        $response = Http::asForm()->post($this->getApiUrl(), $data);
        return $response->body();
    }

    public function initPaymentApiResponseFormat($response)
    {
        $sslcz = json_decode($response, true);
        $format_response = null;

        if ($this->getPaymentDisplayType() == 'checkout') {
            if (isset($sslcz['GatewayPageURL']) && !empty($sslcz['GatewayPageURL'])) {
                $format_response = json_encode(['status' => 'success', 'data' => $sslcz['GatewayPageURL'], 'logo' => $sslcz['storeLogo']]);
            } else {
                $format_response = json_encode(['status' => 'fail', 'data' => null, 'message' => "JSON Data parsing error!"]);
            }
        } else {
            $format_response = $sslcz;
        }

        return $format_response;
    }

    public function apiFormatResponseCheckout($response)
    {
        $sslcz = $response;
        $format_response = null;

        if ($this->getPaymentDisplayType() == 'checkout') {
            if (isset($sslcz['GatewayPageURL']) && !empty($sslcz['GatewayPageURL'])) {
                $format_response = json_encode(['status' => 'success', 'data' => $sslcz['GatewayPageURL'], 'logo' => $sslcz['storeLogo']]);
            } else {
                $format_response = json_encode(['status' => 'fail', 'data' => null, 'message' => "JSON Data parsing error!"]);
            }
        } else {
            $format_response = $sslcz;
        }

        return $format_response;
    }

    public function hash_verify(array $data)
    {
        if (empty($data)
            || empty($data['store_password'])
            || empty($data['verify_sign'])
            || empty($data['verify_key'])) {
            return 'Invalid post request data';
        }

        $new_data = [];
        $predefined_keys = explode(',', $data['verify_key']);

        if (!empty($predefined_keys)) {
            foreach ($predefined_keys as $value) {
                $new_data[$value] = $data[$value];
            }
        }

        $new_data['store_passwd'] = md5($data['store_password']);
        ksort($new_data);

        $hash_string = null;
        foreach ($new_data as $key => $value) {
            $hash_string .= $key . '=' . ($value) . '&';
        }
        $hash_string = rtrim($hash_string, '&');

        return (md5($hash_string) === $data['verify_sign']);
    }

    public function validateOrderParams(array $data)
    {
        if (empty($data)
            || empty($data['tran_id'])
            || empty($data['currency'])
            || empty($data['amount'])
            || (!empty($data['amount']) && $data['amount'] <= 0)) {
            return false;
        }
        return true;
    }

    public function orderValidateApiRequest($data)
    {
        $val_id = urlencode($data['val_id']);
        $store_id = urlencode($data['store_id']);
        $store_passwd = urlencode($data['store_password']);
        $api_domain = $this->isProductionMode()
            ? $this->config['api_domain']['production'] : $this->config['api_domain']['sandbox'];
        $requested_url = $api_domain . $this->config['api_url']['order_validate'];
        $requested_url .= '?val_id=' . $val_id . '&store_id=' . $store_id . '&store_passwd=' . $store_passwd . '&v=1&format=json';
        $response = Http::get($requested_url);

        return $response;
    }

    public function orderValidateApiResponseValidate($response, $data)
    {
        $result = json_decode($response->body());

        if ($result->status == 'VALID' || $result->status == 'VALIDATED') {
            if ($data['currency'] == 'BDT') {
                if (trim($data['tran_id']) == trim($result->tran_id)
                    && (abs($data['amount'] - $result->amount) < 1)
                    && trim($data['currency']) == trim('BDT')) {
                    return $this->response = [
                        'status' => 'success',
                        'message' => 'Successful transaction'
                    ];
                } else {
                    return $this->response = [
                        'status' => 'fail',
                        'message' => 'Data has been tempered'
                    ];
                }
            } else {
                if (trim($data['tran_id']) == trim($result->tran_id)
                    && (abs($data['amount'] - $result->currency_amount) < 1)
                    && trim($data['currency']) == trim($result->currency_type)) {
                    return $this->response = [
                        'status' => 'success',
                        'message' => 'Successful transaction'
                    ];
                } else {
                    return $this->response = [
                        'status' => 'fail',
                        'message' => 'Data has been tempered'
                    ];
                }
            }
        } else {
            return $this->response = [
                'status' => 'fail',
                'message' => 'Failed Transaction'
            ];
        }
    }
}
