<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\FedExApiController;
use App\Models\Customer;
use App\Models\Location;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public $fedexapiUrl;
    private $fedexErrorMessages = [
        'PACKAGINGTYPE.MISSING.OR.INVALID' => 'Packaging type is missing or not valid. Please select correct packaging.',
        'SERVICE.UNAVAILABLE.ERROR' => 'FedEx service is temporarily unavailable. Please try again in a few minutes.',
        'SERVICE_TYPE.INVALID' => 'The selected service type is not supported for this shipment.',
        'ACCOUNT_NUMBER.INVALID' => 'Your FedEx account number is invalid or unauthorized.',
        'INVALID.INPUT.EXCEEDED' => 'Input limits exceeded. Check your address or weight values.',
        'GENERIC.ERROR' => 'There was a general error with your shipment request.',
    ];

    public function __construct()
    {
        if (env('APP_ENV') == 'production') {
            $this->fedexapiUrl = 'https://apis.fedex.com';
        } else {
            $this->fedexapiUrl = 'https://apis-sandbox.fedex.com';
        }
    }
    public function index()
    {
        $servicetype = [
            ["name" => "FedEx First Overnight®", "code" => "FIRST_OVERNIGHT"],
            ["name" => "FedEx Priority Overnight®", "code" => "PRIORITY_OVERNIGHT"],
            ["name" => "FedEx 2Day® AM", "code" => "FEDEX_2_DAY_AM"],
            ["name" => "FedEx 2Day®", "code" => "FEDEX_2_DAY"],
            ["name" => "FedEx Ground", "code" => "FEDEX_GROUND"],
            ["name" => "FedEx First Overnight® Freight", "code" => "FIRST_OVERNIGHT_FREIGHT"],
            ["name" => "FedEx 1Day® Freight", "code" => "FEDEX_1_DAY_FREIGHT"]
        ];
        $oneRateService = [
            ["name" => "FedEx First Overnight®", "code" => "FIRST_OVERNIGHT"],
            ["name" => "FedEx Priority Overnight®", "code" => "PRIORITY_OVERNIGHT"],
            ["name" => "FedEx 2Day® AM", "code" => "FEDEX_2_DAY_AM"],
            ["name" => "FedEx 2Day®", "code" => "FEDEX_2_DAY"]
        ];
        return view('organization.shipping.index', compact('servicetype'));
    }

    public function createShipment(Request $request)
    {


        $countryNameToCode = [
            'United States' => 'US',
            'USA' => 'US',
            'India' => 'IN',
        ];
        $stateCode = [
            "US" => [
                ['name' => 'Alabama', 'code' => 'AL'],
                ['name' => 'Alaska', 'code' => 'AK'],
                ['name' => 'Arizona', 'code' => 'AZ'],
                ['name' => 'Arkansas', 'code' => 'AR'],
                ['name' => 'California', 'code' => 'CA'],
                ['name' => 'Colorado', 'code' => 'CO'],
                ['name' => 'Connecticut', 'code' => 'CT'],
                ['name' => 'Delaware', 'code' => 'DE'],
                ['name' => 'District of Columbia', 'code' => 'DC'],
                ['name' => 'Florida', 'code' => 'FL'],
                ['name' => 'Georgia', 'code' => 'GA'],
                ['name' => 'Hawaii', 'code' => 'HI'],
                ['name' => 'Idaho', 'code' => 'ID'],
                ['name' => 'Illinois', 'code' => 'IL'],
                ['name' => 'Indiana', 'code' => 'IN'],
                ['name' => 'Iowa', 'code' => 'IA'],
                ['name' => 'Kansas', 'code' => 'KS'],
                ['name' => 'Kentucky', 'code' => 'KY'],
                ['name' => 'Louisiana', 'code' => 'LA'],
                ['name' => 'Maine', 'code' => 'ME'],
                ['name' => 'Maryland', 'code' => 'MD'],
                ['name' => 'Massachusetts', 'code' => 'MA'],
                ['name' => 'Michigan', 'code' => 'MI'],
                ['name' => 'Minnesota', 'code' => 'MN'],
                ['name' => 'Mississippi', 'code' => 'MS'],
                ['name' => 'Missouri', 'code' => 'MO'],
                ['name' => 'Montana', 'code' => 'MT'],
                ['name' => 'Nebraska', 'code' => 'NE'],
                ['name' => 'Nevada', 'code' => 'NV'],
                ['name' => 'New Hampshire', 'code' => 'NH'],
                ['name' => 'New Jersey', 'code' => 'NJ'],
                ['name' => 'New Mexico', 'code' => 'NM'],
                ['name' => 'New York', 'code' => 'NY'],
                ['name' => 'North Carolina', 'code' => 'NC'],
                ['name' => 'North Dakota', 'code' => 'ND'],
                ['name' => 'Ohio', 'code' => 'OH'],
                ['name' => 'Oklahoma', 'code' => 'OK'],
                ['name' => 'Oregon', 'code' => 'OR'],
                ['name' => 'Pennsylvania', 'code' => 'PA'],
                ['name' => 'Rhode Island', 'code' => 'RI'],
                ['name' => 'South Carolina', 'code' => 'SC'],
                ['name' => 'South Dakota', 'code' => 'SD'],
                ['name' => 'Tennessee', 'code' => 'TN'],
                ['name' => 'Texas', 'code' => 'TX'],
                ['name' => 'Utah', 'code' => 'UT'],
                ['name' => 'Vermont', 'code' => 'VT'],
                ['name' => 'Virginia', 'code' => 'VA'],
                ['name' => 'Washington State', 'code' => 'WA'],
                ['name' => 'West Virginia', 'code' => 'WV'],
                ['name' => 'Wisconsin', 'code' => 'WI'],
                ['name' => 'Wyoming', 'code' => 'WY'],
                ['name' => 'Puerto Rico', 'code' => 'PR']
            ],
            "IN" => [
                ['name' => "Andaman & Nicobar (U.T)", 'code' => "AN"],
                ['name' => "Andhra Pradesh", 'code' => "AP"],
                ['name' => "Arunachal Pradesh", 'code' => "AR"],
                ['name' => "Assam", 'code' => "AS"],
                ['name' => "Bihar", 'code' => "BR"],
                ['name' => "Chattisgarh", 'code' => "CG"],
                ['name' => "Chandigarh (U.T.)", 'code' => "CH"],
                ['name' => "Daman & Diu (U.T.)", 'code' => "DD"],
                ['name' => "Delhi (U.T.)", 'code' => "DL"],
                ['name' => "Dadra and Nagar Haveli (U.T.)", 'code' => "DN"],
                ['name' => "Goa", 'code' => "GA"],
                ['name' => "Gujarat", 'code' => "GJ"],
                ['name' => "Haryana", 'code' => "HR"],
                ['name' => "Himachal Pradesh", 'code' => "HP"],
                ['name' => "Jammu & Kashmir", 'code' => "JK"],
                ['name' => "Jharkhand", 'code' => "JH"],
                ['name' => "Karnataka", 'code' => "KA"],
                ['name' => "Kerala", 'code' => "KL"],
                ['name' => "Lakshadweep (U.T)", 'code' => "LD"],
                ['name' => "Madhya Pradesh", 'code' => "MP"],
                ['name' => "Maharashtra", 'code' => "MH"],
                ['name' => "Manipur", 'code' => "MN"],
                ['name' => "Meghalaya", 'code' => "ML"],
                ['name' => "Mizoram", 'code' => "MZ"],
                ['name' => "Nagaland", 'code' => "NL"],
                ['name' => "Orissa", 'code' => "OR"],
                ['name' => "Punjab", 'code' => "PB"],
                ['name' => "Puducherry (U.T.)", 'code' => "PY"],
                ['name' => "Rajasthan", 'code' => "RJ"],
                ['name' => "Sikkim", 'code' => "SK"],
                ['name' => "Tamil Nadu", 'code' => "TN"],
                ['name' => "Tripura", 'code' => "TR"],
                ['name' => "Uttaranchal", 'code' => "UA"],
                ['name' => "Uttar Pradesh", 'code' => "UP"],
                ['name' => "West Bengal", 'code' => "WB"]
            ]
        ];
        $servicetype = [
            ["name" => "FedEx First Overnight®", "code" => "FIRST_OVERNIGHT"],
            ["name" => "FedEx Priority Overnight®", "code" => "PRIORITY_OVERNIGHT"],
            ["name" => "FedEx 2Day® AM", "code" => "FEDEX_2_DAY_AM"],
            ["name" => "FedEx 2Day®", "code" => "FEDEX_2_DAY"],
            ["name" => "FedEx Ground", "code" => "FEDEX_GROUND"],
            ["name" => "FedEx First Overnight® Freight", "code" => "FIRST_OVERNIGHT_FREIGHT"],
            ["name" => "FedEx 1Day® Freight", "code" => "FEDEX_1_DAY_FREIGHT"]
        ];
        $oneRateService = [
            ["name" => "FedEx First Overnight®", "code" => "FIRST_OVERNIGHT"],
            ["name" => "FedEx Priority Overnight®", "code" => "PRIORITY_OVERNIGHT"],
            ["name" => "FedEx 2Day® AM", "code" => "FEDEX_2_DAY_AM"],
            ["name" => "FedEx 2Day®", "code" => "FEDEX_2_DAY"]
        ];
        $users = User::where('is_active', true)->where('organization_id', auth()->user()->organization_id)->get();
        $shipmentId = $request->query('shipment_id');
        $shipment = Shipment::with(['shipmentProducts.product', 'shipmentProducts.batch'])
            ->findOrFail($shipmentId);
        $location = Location::where('id', $shipment->location_id)->first();
        $customer = Customer::where('id', $shipment->customer_id)->first();
        $shipmentProducts = $shipment->shipmentProducts->map(function ($product) {
            return [
                'id' => $product->id,
                'product_search' => $product->product->product_name,
                'product_id' => $product->product_id,
                'batch_id' => $product->batch_id,
                'batch_number' => $product->batch?->batch_number,
                'quantity' => $product->quantity,
                'shipment_unit_id' => $product->shipment_unit_id,
                'shipment_unit' => $product->unit->unit_name,
                'net_unit_price' => $product->net_unit_price,
                'total_price' => $product->total_price
            ];
        })->toArray();
        return view(
            'organization.shipping.fedex-shipping',
            compact(
                'users',
                'shipment',
                'shipmentProducts',
                'location',
                'customer',
                'servicetype'
            )
        );
    }
    public function calculateShipment($id, Request $request)
    {
        $countryNameToCode = [
            'United States' => 'US',
            'USA' => 'US',
            'India' => 'IN',
            'india' => 'IN'
        ];

        $stateCode = [
            "US" => [
                ['name' => 'Alabama', 'code' => 'AL'],
                ['name' => 'Alaska', 'code' => 'AK'],
                ['name' => 'Arizona', 'code' => 'AZ'],
                ['name' => 'Arkansas', 'code' => 'AR'],
                ['name' => 'California', 'code' => 'CA'],
                ['name' => 'Colorado', 'code' => 'CO'],
                ['name' => 'Connecticut', 'code' => 'CT'],
                ['name' => 'Delaware', 'code' => 'DE'],
                ['name' => 'District of Columbia', 'code' => 'DC'],
                ['name' => 'Florida', 'code' => 'FL'],
                ['name' => 'Georgia', 'code' => 'GA'],
                ['name' => 'Hawaii', 'code' => 'HI'],
                ['name' => 'Idaho', 'code' => 'ID'],
                ['name' => 'Illinois', 'code' => 'IL'],
                ['name' => 'Indiana', 'code' => 'IN'],
                ['name' => 'Iowa', 'code' => 'IA'],
                ['name' => 'Kansas', 'code' => 'KS'],
                ['name' => 'Kentucky', 'code' => 'KY'],
                ['name' => 'Louisiana', 'code' => 'LA'],
                ['name' => 'Maine', 'code' => 'ME'],
                ['name' => 'Maryland', 'code' => 'MD'],
                ['name' => 'Massachusetts', 'code' => 'MA'],
                ['name' => 'Michigan', 'code' => 'MI'],
                ['name' => 'Minnesota', 'code' => 'MN'],
                ['name' => 'Mississippi', 'code' => 'MS'],
                ['name' => 'Missouri', 'code' => 'MO'],
                ['name' => 'Montana', 'code' => 'MT'],
                ['name' => 'Nebraska', 'code' => 'NE'],
                ['name' => 'Nevada', 'code' => 'NV'],
                ['name' => 'New Hampshire', 'code' => 'NH'],
                ['name' => 'New Jersey', 'code' => 'NJ'],
                ['name' => 'New Mexico', 'code' => 'NM'],
                ['name' => 'New York', 'code' => 'NY'],
                ['name' => 'North Carolina', 'code' => 'NC'],
                ['name' => 'North Dakota', 'code' => 'ND'],
                ['name' => 'Ohio', 'code' => 'OH'],
                ['name' => 'Oklahoma', 'code' => 'OK'],
                ['name' => 'Oregon', 'code' => 'OR'],
                ['name' => 'Pennsylvania', 'code' => 'PA'],
                ['name' => 'Rhode Island', 'code' => 'RI'],
                ['name' => 'South Carolina', 'code' => 'SC'],
                ['name' => 'South Dakota', 'code' => 'SD'],
                ['name' => 'Tennessee', 'code' => 'TN'],
                ['name' => 'Texas', 'code' => 'TX'],
                ['name' => 'Utah', 'code' => 'UT'],
                ['name' => 'Vermont', 'code' => 'VT'],
                ['name' => 'Virginia', 'code' => 'VA'],
                ['name' => 'Washington State', 'code' => 'WA'],
                ['name' => 'West Virginia', 'code' => 'WV'],
                ['name' => 'Wisconsin', 'code' => 'WI'],
                ['name' => 'Wyoming', 'code' => 'WY'],
                ['name' => 'Puerto Rico', 'code' => 'PR']
            ],
            "IN" => [
                ['name' => "Andaman & Nicobar (U.T)", 'code' => "AN"],
                ['name' => "Andhra Pradesh", 'code' => "AP"],
                ['name' => "Arunachal Pradesh", 'code' => "AR"],
                ['name' => "Assam", 'code' => "AS"],
                ['name' => "Bihar", 'code' => "BR"],
                ['name' => "Chattisgarh", 'code' => "CG"],
                ['name' => "Chandigarh (U.T.)", 'code' => "CH"],
                ['name' => "Daman & Diu (U.T.)", 'code' => "DD"],
                ['name' => "Delhi (U.T.)", 'code' => "DL"],
                ['name' => "Dadra and Nagar Haveli (U.T.)", 'code' => "DN"],
                ['name' => "Goa", 'code' => "GA"],
                ['name' => "Gujarat", 'code' => "GJ"],
                ['name' => "Haryana", 'code' => "HR"],
                ['name' => "Himachal Pradesh", 'code' => "HP"],
                ['name' => "Jammu & Kashmir", 'code' => "JK"],
                ['name' => "Jharkhand", 'code' => "JH"],
                ['name' => "Karnataka", 'code' => "KA"],
                ['name' => "Kerala", 'code' => "KL"],
                ['name' => "Lakshadweep (U.T)", 'code' => "LD"],
                ['name' => "Madhya Pradesh", 'code' => "MP"],
                ['name' => "Maharashtra", 'code' => "MH"],
                ['name' => "Manipur", 'code' => "MN"],
                ['name' => "Meghalaya", 'code' => "ML"],
                ['name' => "Mizoram", 'code' => "MZ"],
                ['name' => "Nagaland", 'code' => "NL"],
                ['name' => "Orissa", 'code' => "OR"],
                ['name' => "Punjab", 'code' => "PB"],
                ['name' => "Puducherry (U.T.)", 'code' => "PY"],
                ['name' => "Rajasthan", 'code' => "RJ"],
                ['name' => "Sikkim", 'code' => "SK"],
                ['name' => "Tamil Nadu", 'code' => "TN"],
                ['name' => "Tripura", 'code' => "TR"],
                ['name' => "Uttaranchal", 'code' => "UA"],
                ['name' => "Uttar Pradesh", 'code' => "UP"],
                ['name' => "West Bengal", 'code' => "WB"]
            ]
        ];

        try {
            // 1. Fetch and validate data
            $shipment = Shipment::with(['shipmentProducts.product', 'shipmentProducts.batch'])->findOrFail($id);
            $customer = Customer::findOrFail($shipment->customer_id);
            $warehouse = Location::findOrFail($shipment->location_id);

            // 2. Validate request input
            $data = $request->all();

            if (empty($data['service_type']) || empty($data['package_type'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Service type and package type are required'
                ]);
            }

            // 3. Process input data
            $packages = intval($data['package_number'] ?? 1);
            $packageWeights = array_fill(0, $packages, floatval($data['weight'] ?? 0));
            $declaredValues = array_fill(0, $packages, floatval($data['declared_Value'] ?? 0));
            $shippingDate = $data['shipping_date'] ?? date('Y-m-d');
            $shipDate = date('Y-m-d', strtotime($shippingDate));
            $serviceType = $data['service_type'];
            $packagingType = $data['package_type'];
            $signatureOptionType = $data['signature_type'] ?? '';
            $isResidential = !empty($data['residential_address']);

            // 4. Validate and normalize countries
            $shipCountryRaw = $warehouse->country ?? '';

            if (empty($shipCountryRaw)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Country information is required for both sender and recipient'
                ]);
            }
            $shipCountry = strtoupper($countryNameToCode[$shipCountryRaw] ?? $shipCountryRaw);

            // 5. Process state codes
            $shipStateName = $warehouse->state ?? '';
            $shipStateCode = $shipStateName;

            if (isset($stateCode[$shipCountry]) && !empty($shipStateName)) {
                foreach ($stateCode[$shipCountry] as $s) {
                    if (strcasecmp($s['name'], $shipStateName) === 0) {
                        $shipStateCode = $s['code'];
                        break;
                    }
                }
            }

            // 6. Validate address information
            if (empty($warehouse->address) || empty($warehouse->city) || empty($warehouse->pin)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Warehouse address information is incomplete'
                ]);
            }

            if (empty($customer->customer_address) || empty($data['to_city']) || empty($data['to_zip'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Customer address information is incomplete'
                ]);
            }

            // 7. Build package line items
            $requestedPackageLineItems = [];
            for ($i = 0; $i < $packages; $i++) {
                $packageItem = [
                    'weight' => [
                        'units' => 'LB',
                        'value' => $packageWeights[$i]
                    ]
                ];

                if (!empty($signatureOptionType)) {
                    $packageItem['packageSpecialServices'] = [
                        'signatureOptionType' => $signatureOptionType
                    ];
                }

                if ($declaredValues[$i] > 0) {
                    $packageItem['declaredValue'] = [
                        'currency' => 'USD',
                        'amount' => $declaredValues[$i]
                    ];
                }

                $requestedPackageLineItems[] = $packageItem;
            }

            // 8. Build complete FedEx API request
            $fedexRequest = [
                'rateRequestControlParameters' => [
                    'returnTransitTimes' => true
                ],
                'requestedShipment' => [
                    'shipDatestamp' => $shipDate,
                    'shipper' => [
                        'address' => [
                            'streetLines' => [$warehouse->address],
                            'city' => $warehouse->city,
                            'stateOrProvinceCode' => $shipStateCode,
                            'postalCode' => $warehouse->pin,
                            'countryCode' => $shipCountry,
                            'residential' => false
                        ],
                        'contact' => [
                            'personName' => $warehouse->name ?? 'Warehouse',
                            'phoneNumber' => $warehouse->phone ?? '0000000000'
                        ]
                    ],
                    'recipient' => [
                        'address' => [
                            'streetLines' => [$customer->customer_address],
                            'city' => $data['to_city'],
                            'stateOrProvinceCode' => $data['to_state'],
                            'postalCode' => $data['to_zip'],
                            'countryCode' => $data['to_country'],
                            'residential' => $isResidential
                        ],
                        'contact' => [
                            'personName' => $customer->customer_name ?? 'Customer',
                            'phoneNumber' => $customer->customer_phone ?? '0000000000'
                        ]
                    ],
                    'pickupType' => 'USE_SCHEDULED_PICKUP',
                    'serviceType' => $serviceType,
                    'packagingType' => $packagingType,
                    'shippingChargesPayment' => [
                        'paymentType' => 'SENDER'
                    ],
                    'rateRequestType' => ['LIST', 'ACCOUNT'],
                    'requestedPackageLineItems' => $requestedPackageLineItems
                ],
                'accountNumber' => [
                    'value' => config('services.fedex.account_number')
                ]
            ];

            logger('FedEx API Request:', $fedexRequest);

            // 9. FedEx Authentication - integrated directly
            $authData = json_decode($this->authenticateWithFedEx(), true);

            if (isset($authData['errors'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'FedEx authentication failed: Invalid credentials'
                ]);
            }

            $token = $authData['access_token'] ?? '';
            if (empty($token)) {
                return response()->json([
                    'success' => false,
                    'error' => 'Failed to get FedEx access token'
                ]);
            }
            logger(" REached FedEx API call");

            // 10. Make FedEx API call directly
            $client = new \GuzzleHttp\Client();
            $endpoint = $this->fedexapiUrl . '/rate/v1/rates/quotes';
            $headers = [
                'content-type' => 'application/json',
                'Authorization' => 'Bearer ' . $token
            ];

            logger("Making FedEx API call");

            $response = $client->request('POST', $endpoint, [
                'headers' => $headers,
                'json' => $fedexRequest,
                'verify' => 'C:\wamp64\bin\php\php8.4.0\extras\ssl\cacert.pem'
            ]);

            logger("FedEx API call completed");

            $statusCode = $response->getStatusCode();
            $responseBody = json_decode($response->getBody(), true);

            // 11. Process and validate FedEx response
            if (!isset($responseBody['output']) || !isset($responseBody['output']['rateReplyDetails'])) {
                return response()->json([
                    'success' => false,
                    'error' => 'Invalid response from FedEx API',
                    'debug' => $responseBody
                ]);
            }

            $rateArray = $responseBody['output']['rateReplyDetails'];
            $processedRates = [];

            foreach ($rateArray as $rate) {
                $serviceName = $rate['serviceName'] ?? 'Unknown Service';
                $day = 'Not available';
                $timeMain = 'Not available';
                $formattedTime = 'Not available';

                // Process delivery time information
                if (isset($rate['commit']['dateDetail'])) {
                    $dateDetail = $rate['commit']['dateDetail'];
                    $day = $dateDetail['dayOfWeek'] ?? 'Not available';
                    $timeMain = $dateDetail['dayFormat'] ?? 'Not available';

                    if ($timeMain !== 'Not available') {
                        $formattedTime = date('h:i', strtotime($timeMain)) . ' ' . $day . ' ' . date('d-m-Y', strtotime($timeMain));
                    }
                }

                // Process pricing information
                $ratedDetails = $rate['ratedShipmentDetails'][0] ?? [];

                $processedRates[] = [
                    'serviceName' => $serviceName,
                    'day' => $day,
                    'timeMain' => $timeMain,
                    'time' => $formattedTime,
                    'totalDiscounts' => $ratedDetails['totalDiscounts'] ?? 0,
                    'totalBaseCharge' => $ratedDetails['totalBaseCharge'] ?? 0,
                    'totalNetCharge' => $ratedDetails['totalNetCharge'] ?? 0,
                    'totalNetFedExCharge' => $ratedDetails['totalNetFedExCharge'] ?? 0
                ];
            }

            return response()->json([
                'success' => true,
                'data' => $processedRates
            ]);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $statusCode = $e->getResponse()->getStatusCode();
            $responseBody = $e->getResponse()->getBody()->getContents();

            logger()->error('FedEx ClientException:', [
                'status_code' => $statusCode,
                'body' => $responseBody,
                'exception' => $e->getMessage(),
            ]);

            // Try to decode the JSON response
            $decoded = json_decode($responseBody, true);

            // Define FedEx error code to user-friendly message map
            $fedexErrorMessages = [
                'CURRENCYTYPE.CADORUSD.ONLYAVAILABLEERROR' => 'Only USD or CAD currencies are supported for declared value. Please check the currency setting.',
                'SERVICE.PACKAGECOMBINATION.INVALID' => 'The selected service and package type combination is not valid. Try changing one of them.',
                'DESTINATION.POSTALCODE.MISSING.ORINVALID' => 'The postal code for the destination is either missing or invalid.',
                'ACCOUNT.NOT.FOUND' => 'The provided FedEx account number is invalid or not authorized.',
                'SHIPPER.CONTACT.PHONENUMBER.INVALID' => 'The phone number format for the shipper is invalid.',
                'SERVICETYPE.NOT.ALLOWED' => 'The selected FedEx service type is not allowed for the provided origin/destination or account.',
                'AUTHENTICATION.FAILED' => 'Authentication failed. Please check your FedEx API credentials.',
                'RATE.LIMIT.EXCEEDED' => 'Too many requests. Please wait a moment before trying again.',
                'INTERNAL.SERVER.ERROR' => 'FedEx service is temporarily unavailable. Please try again later.',
                'WEIGHT.BELOWMINIMUMLIMIT.ERROR' => 'Weight added is below required limit.' 
            ];

            // Handle JSON decode failure or unexpected response format
            if (json_last_error() !== JSON_ERROR_NONE || !is_array($decoded)) {
                return response()->json([
                    'success' => false,
                    'error' => [
                        'type' => 'FedExError',
                        'message' => 'FedEx service returned an invalid response. Please try again later.',
                        'friendly_messages' => [
                            'There was a communication error with FedEx. Please verify your shipment details and try again.'
                        ],
                        'debug' => config('app.debug') ? $responseBody : null
                    ]
                ], 422);
            }

            // Handle different FedEx response formats
            $errors = [];

            if (isset($decoded['errors']) && is_array($decoded['errors'])) {
                // Standard FedEx error format
                foreach ($decoded['errors'] as $err) {
                    $code = $err['code'] ?? 'GENERIC.ERROR';
                    $message = $fedexErrorMessages[$code] ?? ($err['message'] ?? 'Unknown FedEx error');
                    $errors[] = [
                        'code' => $code,
                        'message' => $message,
                        'original_message' => $err['message'] ?? null
                    ];
                }
            } elseif (isset($decoded['error'])) {
                // Alternative error format
                $code = $decoded['error']['code'] ?? 'GENERIC.ERROR';
                $message = $fedexErrorMessages[$code] ?? ($decoded['error']['message'] ?? 'Unknown FedEx error');
                $errors[] = [
                    'code' => $code,
                    'message' => $message,
                    'original_message' => $decoded['error']['message'] ?? null
                ];
            } else {
                // Fallback for unexpected formats
                $errors[] = [
                    'code' => 'GENERIC.ERROR',
                    'message' => 'An error occurred while processing your shipment. Please verify the shipment details.',
                    'original_message' => null
                ];
            }

            // Extract just the user-friendly messages
            $friendlyMessages = array_column($errors, 'message');

            return response()->json([
                'success' => false,
                'error' => [
                    'type' => 'FedExError',
                    'message' => count($friendlyMessages) === 1 ? $friendlyMessages[0] : 'Multiple errors occurred with your shipment.',
                    'friendly_messages' => $friendlyMessages,
                    'details' => $errors,
                    'raw' => config('app.debug') ? $decoded : null
                ]
            ], 422);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            logger('FedEx RequestException: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'Network error occurred while connecting to FedEx',
                'message' => $e->getMessage()
            ]);

        } catch (\Exception $e) {
            logger('General Exception in calculateShipment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'error' => 'An unexpected error occurred: ' . $e->getMessage()
            ]);
        }
    }

    // Helper method for FedEx authentication
    private function authenticateWithFedEx()
    {
        try {
            $client = new \GuzzleHttp\Client();
            $authUrl = $this->fedexapiUrl . '/oauth/token';

            $response = $client->request('POST', $authUrl, [
                'form_params' => [
                    'grant_type' => 'client_credentials',
                    'client_id' => config('services.fedex.client_id'),
                    'client_secret' => config('services.fedex.client_secret')
                ],
                'verify' => 'C:\wamp64\bin\php\php8.4.0\extras\ssl\cacert.pem'
            ]);

            return $response->getBody();

        } catch (\Exception $e) {
            logger('FedEx Authentication Error: ' . $e->getMessage());
            return json_encode(['errors' => [['message' => 'Authentication failed']]]);
        }
    }
}
