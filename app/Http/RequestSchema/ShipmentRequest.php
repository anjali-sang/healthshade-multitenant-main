<?php

namespace App\Http\RequestSchema;

class ShipmentRequest
{

  protected $mergeLabelDocOption = 'LABELS_AND_DOCS';

  protected $requestedShipment;

  protected $labelResponseOptions = "LABEL";

  protected $accountNumber;

  protected $shipAction = 'CONFIRM';

  protected $processingOptionType = 'ALLOW_ASYNCHRONOUS';

  protected $shipmentRequest;

  protected $parsedData;

  protected $shipper;

  protected $recipients;

  protected $pickupType = 'USE_SCHEDULED_PICKUP';

  protected $serviceType = 'PRIORITY_OVERNIGHT';

  protected $packagingType = 'YOUR_PACKAGING';

  protected $shippingChargesPayment = ['paymentType' => 'SENDER'];

  protected $labelSpecification;

  protected $rateRequestType = ['LIST', 'INCENTIVE', 'ACCOUNT', 'PREFERRED'];

  protected $requestedPackageLineItems;

  public function __construct($parsedData)
  {
    $this->shipper = [
      'address' => [
        'streetLines' => ['LowT Franklin', 'Suite 302'],
        'city' => 'Franklin',
        'stateOrProvinceCode' => 'TN',
        'postalCode' => '37067',
        'countryCode' => 'US',
        'residential' => false
      ],
      'contact' => [
        'phoneNumber' => '+18179650267',
        'companyName' => 'ioship'
      ]
    ];

    $this->recipients = [
      [
        'address' =>  [
          'streetLines' => [
            '7033 Bryant Irvin, Suite 200'
          ],
          'city' => 'Fort Worth',
          'stateOrProvinceCode' => 'TX',
          'postalCode' => '76132',
          'countryCode' => 'US',
          'residential' => false
        ],
        'contact' => [
          'personName' => 'Rushi',
          'phoneNumber' => '8174235698'
        ]
      ]
    ];

    $this->labelSpecification = [
      'labelFormatType' => 'COMMON2D',
      'labelOrder' => 'SHIPPING_LABEL_LAST',
      'customerSpecifiedDetail' => [
        'maskedData' => [
          'CUSTOMS_VALUE',
          'TOTAL_WEIGHT'
        ],
        'regulatoryLabels' => [
          [
            'generationOptions' => 'CONTENT_ON_SHIPPING_LABEL_ONLY',
            'type' => 'ALCOHOL_SHIPMENT_LABEL'
          ]
        ],
        'additionalLabels' => [
          [
            'type' => 'CONSIGNEE',
            'count' => 1
          ]
        ],
        'docTabContent' => [
          'docTabContentType' => 'BARCODED',
          'zone001' => [
            'docTabZoneSpecifications' => [
              [
                'zoneNumber' => 0,
                'header' => 'string',
                'dataField' => 'string',
                'literalValue' => 'string',
                'justification' => 'RIGHT'
              ]
            ]
          ],
          'barcoded' => [
            'symbology' => 'UCC128',
            'specification' => [
              'zoneNumber' => 0,
              'header' => 'string',
              'dataField' => 'string',
              'literalValue' => 'string',
              'justification' => 'RIGHT'
            ]
          ]
        ]
      ],
      'labelStockType' => 'PAPER_4X675',
      'labelRotation' => 'NONE',
      'imageType' => 'PNG',
      'labelPrintingOrientation' => 'TOP_EDGE_OF_TEXT_FIRST',
      'returnedDispositionDetail' => true
    ];

    // $this->requestedPackageLineItems = [
    //     [
    //         'weight' => [
    //             'units' => 'LB',
    //             'value' => 10
    //         ],
    //     ]
    // ];

    $this->requestedShipment = [
      'shipDatestamp' => date('Y') . '-' . date('m') . '-' . date('d'),
      'shipper' => $this->shipper,
      'recipients' => $this->recipients,
      'pickupType' => $this->pickupType,
      'serviceType' => $this->serviceType,
      'packagingType' => $this->packagingType,
      'shippingChargesPayment' => $this->shippingChargesPayment,
      'labelSpecification' => $this->labelSpecification,
      'rateRequestType' => $this->rateRequestType,
      'requestedPackageLineItems' => $this->requestedPackageLineItems
    ];

    if(!empty($parsedData['account_number'])) {
      $this->accountNumber = [
        'value' => $parsedData['account_number']
      ];
    } else {
      $this->accountNumber = [
        'value' => env('FEDEX_ACCOUNT_NO')
      ];
    }

    $this->parsedData = $parsedData;
  }

  public function generateShipment()
  {
    //$this->shipmentRequest = [];
    $this->shipmentRequest = [
      'mergeLableDocOption' => $this->mergeLabelDocOption,
      'requestedShipment' => $this->requestedShipment,
      'labelResponseOptions' => $this->labelResponseOptions,
      'accountNumber' => $this->accountNumber,
      'shipAction' => $this->shipAction,
      'processingOptionType' => $this->processingOptionType
    ];

    if (!empty($this->parsedData['shipdate'])) {
      $this->shipmentRequest['requestedShipment']['shipDatestamp'] = $this->parsedData['shipdate'];
    } else {
      unset($this->shipmentRequest['requestedShipment']['shipDatestamp']);
    }

    if (!empty($this->parsedData['address'])) {
      $this->shipmentRequest['requestedShipment']['recipients'] = [
        [
          'address' =>  [
            'streetLines' => $this->parsedData['streetLines'],
            'city' => $this->parsedData['city'],
            'stateOrProvinceCode' => $this->parsedData['stateCode'],
            'postalCode' => $this->parsedData['postalCode'],
            'countryCode' => $this->parsedData['countryCode'],
            'residential' => $this->parsedData['residential'] == '1' ? true : false
          ],
          'contact' => [
            'personName' => $this->parsedData['contactName'],
            'phoneNumber' => $this->parsedData['contactNumber']
          ]
        ]
      ];
    }

    if(!empty($this->parsedData['shipperAddress'])) {
      $this->shipmentRequest['requestedShipment']['shipper'] = [
          'address' =>  [
            'streetLines' => $this->parsedData['shipper_streetLines'],
            'city' => $this->parsedData['shipper_city'],
            'stateOrProvinceCode' => $this->parsedData['shipper_stateCode'],
            'postalCode' => $this->parsedData['shipper_postalCode'],
            'countryCode' => $this->parsedData['shipper_countryCode'],
            'residential' => false
          ],
          'contact' => [
            'personName' => $this->parsedData['shipper_contactName'],
            'phoneNumber' => $this->parsedData['shipper_contactNumber']
          ]
      ];
    }

    if (!empty($this->parsedData['packages'])) {
      for ($i = 0; $i < number_format($this->parsedData['packages']); $i++) {
        if (!empty($this->parsedData['package'][$i])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['weight']['units'] = 'LB';
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['weight']['value'] = (float)$this->parsedData['package'][$i];
        }

        if(!empty($this->parsedData['signature_option_type'])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['packageSpecialServices']['signatureOptionType'] = $this->parsedData['signature_option_type'];
        }

        if (!empty($this->parsedData['pricepackage'][$i])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['declaredValue']['currency'] = 'USD';
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['declaredValue']['amount'] = (float)$this->parsedData['pricepackage'][$i];
        }
      }
    }

    if (!empty($this->parsedData['servicetype'])) {
      $this->shipmentRequest['requestedShipment']['serviceType'] = $this->parsedData['servicetype'];
    }

    if (!empty($this->parsedData['packagingtype'])) {
      $this->shipmentRequest['requestedShipment']['packagingType'] = $this->parsedData['packagingtype'];
    }


    if(!empty($this->parsedData['pricingOption'])) {
      if($this->parsedData['pricingOption'] == '1') {
        $this->shipmentRequest['requestedShipment']['shipmentSpecialServices'] = ['specialServiceTypes'=>['FEDEX_ONE_RATE']];
      }
    }
    return json_encode($this->shipmentRequest);
  }

  public function generateValidateShipment()
  {
    $this->shipmentRequest = [
      'requestedShipment' => $this->requestedShipment,
      'accountNumber' => $this->accountNumber
    ];
    if (!empty($this->parsedData['shipdate'])) {
      $this->shipmentRequest['requestedShipment']['shipDatestamp'] = $this->parsedData['shipdate'];
    } else {
      unset($this->shipmentRequest['requestedShipment']['shipDatestamp']);
    }

    if (!empty($this->parsedData['packages'])) {
      for ($i = 0; $i < number_format($this->parsedData['packages']); $i++) {
        if (!empty($this->parsedData['package'][$i])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['weight']['units'] = 'LB';
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['weight']['value'] = (float)$this->parsedData['package'][$i];
        }

        if (!empty($this->parsedData['pricepackage'][$i])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['declaredValue']['currency'] = 'USD';
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['declaredValue']['amount'] = (float)$this->parsedData['pricepackage'][$i];
        }
      }
    }

    if (!empty($this->parsedData['servicetype'])) {
      $this->shipmentRequest['requestedShipment']['serviceType'] = $this->parsedData['servicetype'];
    }

    if (!empty($this->parsedData['packagingtype'])) {
      $this->shipmentRequest['requestedShipment']['packagingType'] = $this->parsedData['packagingtype'];
    }

    return json_encode($this->shipmentRequest);
  }

  public function rateTransistRequest()
  {
    $this->shipmentRequest = [
      'rateRequestControlParameters' => [
        'returnTransitTimes' => true
      ],
      'requestedShipment' => $this->requestedShipment,
      'accountNumber' => $this->accountNumber
    ];

    if (!empty($this->parsedData['address'])) {
      $this->shipmentRequest['requestedShipment']['recipient'] = [
        'address' =>  [
          'streetLines' => $this->parsedData['streetLines'],
          'city' => $this->parsedData['city'],
          'stateOrProvinceCode' => $this->parsedData['stateCode'],
          'postalCode' => $this->parsedData['postalCode'],
          'countryCode' => $this->parsedData['countryCode'],
          'residential' => $this->parsedData['residential'] == '1' ? true : false
        ],
        'contact' => [
          'personName' => $this->parsedData['contactName'],
          'phoneNumber' => $this->parsedData['contactNumber']
        ]
      ];
    }

    if(!empty($this->parsedData['shipperAddress'])) {
      $this->shipmentRequest['requestedShipment']['shipper'] = [
          'address' =>  [
            'streetLines' => $this->parsedData['shipper_streetLines'],
            'city' => $this->parsedData['shipper_city'],
            'stateOrProvinceCode' => $this->parsedData['shipper_stateCode'],
            'postalCode' => $this->parsedData['shipper_postalCode'],
            'countryCode' => $this->parsedData['shipper_countryCode'],
            'residential' => false
          ],
          'contact' => [
            'personName' => $this->parsedData['shipper_contactName'],
            'phoneNumber' => $this->parsedData['shipper_contactNumber']
          ]
      ];
    }

    if (!empty($this->parsedData['shipdate'])) {
      $this->shipmentRequest['requestedShipment']['shipDatestamp'] = $this->parsedData['shipdate'];
    } else {
      unset($this->shipmentRequest['requestedShipment']['shipDatestamp']);
    }

    if (!empty($this->parsedData['packages'])) {
      for ($i = 0; $i < number_format($this->parsedData['packages']); $i++) {
        if (!empty($this->parsedData['package'][$i])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['weight']['units'] = 'LB';
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['weight']['value'] = (float)$this->parsedData['package'][$i];
        }

        if(!empty($this->parsedData['signature_option_type'])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['packageSpecialServices']['signatureOptionType'] = $this->parsedData['signature_option_type'];
        }

        if (!empty($this->parsedData['pricepackage'][$i])) {
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['declaredValue']['currency'] = 'USD';
          $this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['declaredValue']['amount'] = (float)$this->parsedData['pricepackage'][$i];
        }
      }
    }

    if (!empty($this->parsedData['servicetype'])) {
      $this->shipmentRequest['requestedShipment']['serviceType'] = $this->parsedData['servicetype'];
    }else {
      unset($this->shipmentRequest['requestedShipment']['serviceType']);
    }

    if (!empty($this->parsedData['packagingtype'])) {
      $this->shipmentRequest['requestedShipment']['packagingType'] = $this->parsedData['packagingtype'];
    }

    if(!empty($this->parsedData['pricingOption'])) {
      if($this->parsedData['pricingOption'] == '1') {
        $this->shipmentRequest['requestedShipment']['shipmentSpecialServices'] = ['specialServiceTypes'=>['FEDEX_ONE_RATE']];
      }
    }
    return json_encode($this->shipmentRequest);
  }

  public function addressValidation()
  {
    $recipients = [
      'addressesToValidate' =>
      [
        [
          'address' =>  [
            'streetLines' => [
              '7033 Bryant Irvin, Suite 200'
            ],
            'city' => 'Fort test',
            'stateOrProvinceCode' => 'SX',
            'postalCode' => '76132',
            'countryCode' => 'US'
          ]
        ]
      ]
    ];
    return json_encode($recipients);
  }

  public function postalValidation()
  {
    $recipients = [
      'carrierCode' => 'FDXG',
      'countryCode' => 'US',
      'stateOrProvinceCode' => 'TX',
      'postalCode' => '76132',
      'shipDate' => '2023-05-30',
    ];
    return json_encode($recipients);
  }
}
