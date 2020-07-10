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
        $query_params = [
            'val_id' => $data['val_id'],
            'store_id' => $data['store_id'],
            'store_passwd' => $data['store_password'],
            'v' => $data['v'],
            'format' => $data['format']
        ];

        $response = Http::get($this->getOrderValidateApiUrl(), $query_params);

        return $response->body();
    }

    public function transactionQueryByIdParams(array $data)
    {
        if (empty($data) || empty($data['tran_id'])) {
            return false;
        }
        return true;
    }

    public function transactionQueryByIdRequest($data)
    {
        $query_params = [
            'tran_id' => $data['tran_id'],
            'store_id' => $data['store_id'],
            'store_passwd' => $data['store_password'],
        ];

        $response = Http::get($this->getTransactionStatusApiUrl(), $query_params);

        return $response->body();
    }

    public function transactionQueryBySessionIdParams(array $data)
    {
        if (empty($data) || empty($data['sessionkey'])) {
            return false;
        }
        return true;
    }

    public function transactionQueryBySessionIdRequest($data)
    {
        $query_params = [
            'sessionkey' => $data['sessionkey'],
            'store_id' => $data['store_id'],
            'store_passwd' => $data['store_password'],
        ];

        $response = Http::get($this->getTransactionStatusApiUrl(), $query_params);

        return $response->body();
    }

    public function refundPaymentParams(array $data)
    {
        if (empty($data)
            || empty($data['bank_tran_id'])
            || empty($data['refund_amount'])
            || empty($data['refund_remarks'])) {
            return false;
        }
        return true;
    }

    public function refundPaymentRequest($data)
    {
        $query_params = [
            'bank_tran_id' => $data['bank_tran_id'],
            'store_id' => $data['store_id'],
            'store_passwd' => $data['store_password'],
            'refund_amount' => $data['refund_amount'],
            'refund_remarks' => $data['refund_remarks'],
            'refe_id' => $data['refe_id'],
            'format' => $data['format'],
        ];

        $response = Http::get($this->getRefundPaymentApiUrl(), $query_params);

        return $response->body();
    }

    public function refundStatusParams(array $data)
    {
        if (empty($data) || empty($data['refund_ref_id'])) {
            return false;
        }
        return true;
    }

    public function refundStatusRequest($data)
    {
        $query_params = [
            'refund_ref_id' => $data['refund_ref_id'],
            'store_id' => $data['store_id'],
            'store_passwd' => $data['store_password'],
        ];

        $response = Http::get($this->getRefundStatusApiUrl(), $query_params);

        return $response->body();
    }
}
