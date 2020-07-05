<?php

namespace SSLCZ\SSLCommerz;

trait SSLCommerzRequestField
{
    protected $required_fields = [
        'primary' => [
            'store_id',
            'store_passwd',
            'total_amount',
            'currency',
            'tran_id',
            'success_url',
            'fail_url',
            'cancel_url',
        ],
        'emi' => [
            'emi_option',
        ],
        'customer_information' => [
            'cus_name',
            'cus_email',
            'cus_add1',
            'cus_add2',
            'cus_city',
            'cus_postcode',
            'cus_country',
            'cus_phone',
        ],
        'shipment_information' => [
            'shipping_method',
            'num_of_item',
        ],
        'product_information' => [
            'product_name',
            'product_category',
            'product_profile',
        ],
        'additional_information' => []
    ];

    protected $optional_fields = [
        'primary' => [
            'ipn_url', // Important! Not mandatory, however better to use to avoid missing any payment notification
            'multi_card_name', // Do not Use! If you do not customize the gateway lis
            'allowed_bin', // Do not Use! If you do not control on transaction
        ],
        'emi' => [
            'emi_max_inst_option',
            'emi_selected_inst',
            'emi_allow_only',
        ],
        'customer_information' => [
            'cus_state',
            'cus_fax',
        ],
        'shipment_information' => [
            'ship_add2',
            'ship_state',
            'ship_name',
            'ship_add1',
            'ship_city',
            'ship_postcode',
            'ship_country',
        ],
        'product_information' => [
            'cart',
            'product_amount',
            'vat',
            'discount_amount',
            'convenience_fee',

            'hours_till_departure',
            'flight_type',
            'pnr',
            'journey_from_to',
            'third_party_booking',

            'hotel_name',
            'length_of_stay',
            'check_in_time',
            'hotel_city',

            'product_type',
            'topup_number',
            'country_topup'
        ],
        'additional_information' => [
            'value_a',
            'value_b',
            'value_c',
            'value_d',
        ]
    ];

    protected $dependent_required_fields = [ // dependent_to => field's
        'shipment_information' => [
            'shipping_method' => [
                'ship_name',
                'ship_add1',
                'ship_city',
                'ship_postcode',
                'ship_country',
            ]
        ],
        'product_information' => [
            'product_profile' => [
                'general',
                'physical-goods',
                'non-physical-goods',
                'airline-tickets' => [
                    'hours_till_departure',
                    'flight_type',
                    'pnr',
                    'journey_from_to',
                    'third_party_booking',
                ],
                'travel-vertical' => [
                    'hotel_name',
                    'length_of_stay',
                    'check_in_time',
                    'hotel_city',
                ],
                'telecom-vertical' => [
                    'product_type',
                    'topup_number',
                    'country_topup'
                ]
            ]
        ]
    ];
}
