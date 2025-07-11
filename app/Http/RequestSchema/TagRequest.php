<?php

namespace App\Http\RequestSchema;

class TagRequest
{

  protected $requestedShipment;

  protected $shipper;

  protected $recipients;

  protected $accountNumber;

  protected $shipmentRequest;

  protected $parsedData;

  protected $pickupType = 'CONTACT_FEDEX_TO_SCHEDULE';

  protected $serviceType = 'PRIORITY_OVERNIGHT';

  protected $packagingType = 'FEDEX_BOX';

  protected $shippingChargesPayment = ['paymentType' => 'SENDER'];

  protected $shipmentSpecialService;

  protected $labelSpecification;

  protected $requestedPackageLineItems;

  protected $pickupDetail;

  public function __construct($parsedData)
  {
    $this->shipper = [
      'address' => [
        'streetLines' => ['LowT Franklin', 'Suite 302'],
        'city' => 'Franklin',
        'stateOrProvinceCode' => 'TN',
        'postalCode' => '37067',
        'countryCode' => 'US'
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
            '10 FedEx Parkway',
            'Suite 302'
          ],
          'city' => 'Beverly Hills',
          'stateOrProvinceCode' => 'CA',
          'postalCode' => '90210',
          'countryCode' => 'US'
        ],
        'contact' => [
          'personName' => 'test person',
          'phoneNumber' => '9182563189'
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

    $this->shipmentSpecialService = [
      'specialServiceTypes' => ['RETURN_SHIPMENT'],
      'returnShipmentDetail' => [
        'returnType' => 'FEDEX_TAG'
      ]
    ];

    $a = strtotime("+1 day");

    $this->requestedShipment = [
      'shipper' => $this->shipper,
      'recipients' => $this->recipients,
      'shipDatestamp' => date('Y-m-d', $a),
      'pickupType' => $this->pickupType,
      'serviceType' => $this->serviceType,
      'packagingType' => $this->packagingType,
      'shippingChargesPayment' => $this->shippingChargesPayment,
      //'labelSpecification' => $this->labelSpecification,
      'shipmentSpecialServices' => $this->shipmentSpecialService,
      'blockInsightVisibility' => false,
      'pickupDetail' => $this->pickupDetail,
      'requestedPackageLineItems' => $this->requestedPackageLineItems
    ];

    $this->accountNumber = [
      'value' => env('FEDEX_ACCOUNT_NO')
    ];

    $this->parsedData = $parsedData;
  }

  public function generateCreateTag()
  {
    $a = strtotime($this->parsedData['shipdate']);
    $b = strtotime($this->parsedData['shipdate']);
    $this->requestedShipment['pickupDetail'] = [
      //'readyPickupDateTime' => date("Y-m-d", $a) . 'TT09:00:00Z' . date("h:i:s") . 'Z',
      'readyPickupDateTime' => date("Y-m-d", $a) . 'T09:00:00Z',
      'latestPickupDateTime' => date("Y-m-d", $b) . 'T14:00:00Z',
    ];

    $this->requestedShipment['shippingChargesPayment']['payor'] = [
        'responsibleParty' => [
          'accountNumber' => [
            'value' => env('FEDEX_ACCOUNT_NO')
          ]
        ]
    ];

    $this->shipmentRequest = [
      'requestedShipment' => $this->requestedShipment,
      'accountNumber' => $this->accountNumber
    ];

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
          //$this->shipmentRequest['requestedShipment']['requestedPackageLineItems'][$i]['itemDescription'] = 'test item';
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
}
