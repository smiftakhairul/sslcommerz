<?php

namespace SSLCZ\SSLCommerz;
use Illuminate\Support\Facades\Http;
use SSLCZ\SSLCommerz\SSLCommerzRequestField;

trait SSLCommerzRequestValidation
{
    use SSLCommerzRequestField;

    public function validateInformation(array $data, $info_type)
    {
        if (empty($data)) {
            return $this->response = [
                'status' => 'fail',
                'message' => 'Invalid data.'
            ];
        }

        foreach ($data as $field => $value) {
            if (!is_string($field)) {
                return $this->response = [
                    'status' => 'fail',
                    'message' => 'Invalid field.'
                ];
            }
            if (!in_array($field, $this->required_fields[$info_type])
                && !in_array($field, $this->optional_fields[$info_type])) {
                return $this->response = [
                    'status' => 'fail',
                    'message' => 'Field `' . $field . '` is not valid.'
                ];
            }
            if (in_array($field, $this->required_fields[$info_type]) && empty($value)) {
                return $this->response = [
                    'status' => 'fail',
                    'message' => 'Value of `' . $field . '` can not be empty.'
                ];
            }
        }

        return $this->response = [
            'status' => 'success',
            'message' => 'All ok.'
        ];
    }
}
