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

    public function validateOrderParams(array $data)
    {
        if (empty($data) || empty($data['val_id'])) {
            return false;
        }
        return true;
    }

    public function orderValidateApiRequest($data)
    {
        $val_id = $data['val_id'];
        $store_id = $data['store_id'];
        $store_passwd = $data['store_password'];
        $req_v = $data['v'];
        $req_format = $data['format'];
        $requested_url = $this->getOrderValidateApiUrl();
        $requested_url .= '?val_id=' . $val_id . '&store_id=' . $store_id . '&store_passwd=' . $store_passwd . '&v=' . $req_v . '&format=' . $req_format;
        $response = Http::get($requested_url);

        return $response->body();
    }
}
