<?php

namespace App\Services;

use App\Models\MedicalRepSales;
use App\Models\Shipment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Exception;

class UPSShippingService
{
    private $accessKey;
    private $username;
    private $password;
    private $accountNumber;
    private $baseUrl;
    private $isProduction;

    public function __construct()
    {
        $this->accessKey = config('ups.access_key');
        $this->username = config('ups.username');
        $this->password = config('ups.password');
        $this->accountNumber = config('ups.account_number');
        $this->isProduction = config('ups.production', false);

        // UPS API URLs
        $this->baseUrl = $this->isProduction
            ? 'https://onlinetools.ups.com/ups.app/xml'
            : 'https://wwwcie.ups.com/ups.app/xml';
    }

    /**
     * Create UPS shipment for a sale
     */
    public function createShipment($saleId)
    {
        try {
            $sale = MedicalRepSales::with([
                'organization',
                'receiverOrganization',
                'location',
                'saleItems.product'
            ])->findOrFail($saleId);

            // Prepare shipment data
            $shipmentData = $this->prepareShipmentData($sale);

            logger('Loading sale in UPS services with ID: ' . $saleId);
            logger('shipment data for : ' . json_encode(['shipment' => $shipmentData]));


            // Create shipment with UPS
            $response = $this->sendShipmentRequest($shipmentData);

            logger('got response while send shipment request: ' . json_encode(['res' => $response]));

            if ($response['success']) {
                // Save shipment to database
                $shipment = $this->saveShipment($sale, $response['data']);

                return [
                    'success' => true,
                    'shipment' => $shipment,
                    'tracking_number' => $response['data']['tracking_number'],
                    'label_url' => $response['data']['label_url']
                ];
            }

            return $response;

        } catch (Exception $e) {
            Log::error('UPS Shipment Creation Failed', [
                'sale_id' => $saleId,
                'error' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'message' => 'Failed to create shipment: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Prepare shipment data from sale
     */
    private function prepareShipmentData($sale)
    {
        $shipperAddress = $this->getShipperAddress($sale->organization);
        $recipientAddress = $this->getRecipientAddress($sale->receiverOrganization);
        $packages = $this->preparePackages($sale->saleItems);

        return [
            'shipper' => $shipperAddress,
            'recipient' => $recipientAddress,
            'packages' => $packages,
            'service_code' => '03', // UPS Ground
            'payment_info' => [
                'type' => 'BillShipper',
                'account_number' => $this->accountNumber
            ]
        ];
    }

    /**
     * Get shipper address from organization
     */
    private function getShipperAddress($organization)
    {
        return [
            'name' => $organization->name,
            'company' => $organization->name,
            'address_line_1' => $organization->address ?? '123 Main St',
            'city' => $organization->city ?? 'New York',
            'state' => $organization->state ?? 'NY',
            'postal_code' => $organization->zip_code ?? '10001',
            'country' => $organization->country ?? 'US',
            'phone' => $organization->phone ?? '555-123-4567'
        ];
    }

    /**
     * Get recipient address from receiver organization
     */
    private function getRecipientAddress($organization)
    {
        return [
            'name' => $organization->name,
            'company' => $organization->name,
            'address_line_1' => $organization->address ?? '456 Oak Ave',
            'city' => $organization->city ?? 'Los Angeles',
            'state' => $organization->state ?? 'CA',
            'postal_code' => $organization->zip_code ?? '90001',
            'country' => $organization->country ?? 'US',
            'phone' => $organization->phone ?? '555-987-6543'
        ];
    }

    /**
     * Prepare packages from sale items
     */
    private function preparePackages($saleItems)
    {
        $packages = [];
        $totalWeight = 0;
        $totalValue = 0;

        foreach ($saleItems as $item) {
            $weight = $item->product->weight ?? 1.0; // Default 1 lb per item
            $totalWeight += $weight * $item->quantity;
            $totalValue += $item->total;
        }

        // For simplicity, create one package
        $packages[] = [
            'packaging_type' => '02', // Customer Supplied Package
            'dimensions' => [
                'length' => '12',
                'width' => '9',
                'height' => '6',
                'unit' => 'IN'
            ],
            'weight' => [
                'value' => max($totalWeight, 1), // Minimum 1 lb
                'unit' => 'LBS'
            ],
            'declared_value' => $totalValue
        ];

        return $packages;
    }

    /**
     * Send shipment request to UPS API
     */
    private function sendShipmentRequest($shipmentData)
    {
        try {
            $xmlRequest = $this->buildShipmentXML($shipmentData);

            logger('Sending shipment request to UPS API in XML format ' . $xmlRequest);

            $response = Http::withOptions([
                'verify' => false, // Disable SSL check (TEMP for local testing)
            ])->withHeaders([
                        'Content-Type' => 'application/x-www-form-urlencoded',
                    ])->post($this->baseUrl . '/ShipConfirm', $xmlRequest);


            if ($response->successful()) {
                $xmlResponse = simplexml_load_string($response->body());

                if ((string) $xmlResponse->Response->ResponseStatusCode === '1') {
                    // Shipment confirmed, now accept it
                    $acceptResponse = $this->acceptShipment($xmlResponse->ShipmentDigest);

                    if ($acceptResponse['success']) {
                        return [
                            'success' => true,
                            'data' => $acceptResponse['data']
                        ];
                    }

                    return $acceptResponse;
                } else {
                    $errorMsg = (string) $xmlResponse->Response->Error->ErrorDescription;
                    return [
                        'success' => false,
                        'message' => 'UPS Error: ' . $errorMsg
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'UPS API request failed'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'API Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Accept UPS shipment
     */
    private function acceptShipment($shipmentDigest)
    {
        try {
            $xmlRequest = $this->buildAcceptXML($shipmentDigest);

            $response = Http::withHeaders([
                'Content-Type' => 'application/x-www-form-urlencoded'
            ])->post($this->baseUrl . '/ShipAccept', $xmlRequest);

            if ($response->successful()) {
                $xmlResponse = simplexml_load_string($response->body());

                if ((string) $xmlResponse->Response->ResponseStatusCode === '1') {
                    $trackingNumber = (string) $xmlResponse->ShipmentResults->PackageResults->TrackingNumber;
                    $labelImage = (string) $xmlResponse->ShipmentResults->PackageResults->LabelImage->GraphicImage;

                    // Save label as file
                    $labelPath = $this->saveLabelImage($labelImage, $trackingNumber);

                    return [
                        'success' => true,
                        'data' => [
                            'tracking_number' => $trackingNumber,
                            'label_url' => $labelPath,
                            'shipment_cost' => (string) $xmlResponse->ShipmentResults->ShipmentCharges->TotalCharges->MonetaryValue ?? '0.00'
                        ]
                    ];
                } else {
                    $errorMsg = (string) $xmlResponse->Response->Error->ErrorDescription;
                    return [
                        'success' => false,
                        'message' => 'UPS Accept Error: ' . $errorMsg
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'UPS Accept API request failed'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Accept API Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Build XML for shipment confirmation
     */
    private function buildShipmentXML($data)
    {
        $xml = '<?xml version="1.0"?>
        <AccessRequest xml:lang="en-US">
            <AccessLicenseNumber>' . $this->accessKey . '</AccessLicenseNumber>
            <UserId>' . $this->username . '</UserId>
            <Password>' . $this->password . '</Password>
        </AccessRequest>
        <?xml version="1.0"?>
        <ShipmentConfirmRequest xml:lang="en-US">
            <Request>
                <TransactionReference>
                    <CustomerContext>Medical Rep Sale Shipment</CustomerContext>
                    <XpciVersion>1.0001</XpciVersion>
                </TransactionReference>
                <RequestAction>ShipConfirm</RequestAction>
                <RequestOption>validate</RequestOption>
            </Request>
            <Shipment>
                <Shipper>
                    <Name>' . htmlspecialchars($data['shipper']['name']) . '</Name>
                    <AttentionName>' . htmlspecialchars($data['shipper']['name']) . '</AttentionName>
                    <CompanyDisplayableName>' . htmlspecialchars($data['shipper']['company']) . '</CompanyDisplayableName>
                    <Address>
                        <AddressLine1>' . htmlspecialchars($data['shipper']['address_line_1']) . '</AddressLine1>
                        <City>' . htmlspecialchars($data['shipper']['city']) . '</City>
                        <StateProvinceCode>' . htmlspecialchars($data['shipper']['state']) . '</StateProvinceCode>
                        <PostalCode>' . htmlspecialchars($data['shipper']['postal_code']) . '</PostalCode>
                        <CountryCode>' . htmlspecialchars($data['shipper']['country']) . '</CountryCode>
                    </Address>
                    <ShipperNumber>' . $this->accountNumber . '</ShipperNumber>
                </Shipper>
                <ShipTo>
                    <CompanyName>' . htmlspecialchars($data['recipient']['company']) . '</CompanyName>
                    <Address>
                        <AddressLine1>' . htmlspecialchars($data['recipient']['address_line_1']) . '</AddressLine1>
                        <City>' . htmlspecialchars($data['recipient']['city']) . '</City>
                        <StateProvinceCode>' . htmlspecialchars($data['recipient']['state']) . '</StateProvinceCode>
                        <PostalCode>' . htmlspecialchars($data['recipient']['postal_code']) . '</PostalCode>
                        <CountryCode>' . htmlspecialchars($data['recipient']['country']) . '</CountryCode>
                    </Address>
                </ShipTo>
                <ShipFrom>
                    <CompanyName>' . htmlspecialchars($data['shipper']['company']) . '</CompanyName>
                    <Address>
                        <AddressLine1>' . htmlspecialchars($data['shipper']['address_line_1']) . '</AddressLine1>
                        <City>' . htmlspecialchars($data['shipper']['city']) . '</City>
                        <StateProvinceCode>' . htmlspecialchars($data['shipper']['state']) . '</StateProvinceCode>
                        <PostalCode>' . htmlspecialchars($data['shipper']['postal_code']) . '</PostalCode>
                        <CountryCode>' . htmlspecialchars($data['shipper']['country']) . '</CountryCode>
                    </Address>
                </ShipFrom>
                <PaymentInformation>
                    <Prepaid>
                        <BillShipper>
                            <AccountNumber>' . $this->accountNumber . '</AccountNumber>
                        </BillShipper>
                    </Prepaid>
                </PaymentInformation>
                <Service>
                    <Code>' . $data['service_code'] . '</Code>
                </Service>';

        foreach ($data['packages'] as $package) {
            $xml .= '
                <Package>
                    <PackagingType>
                        <Code>' . $package['packaging_type'] . '</Code>
                    </PackagingType>
                    <Dimensions>
                        <UnitOfMeasurement>
                            <Code>' . $package['dimensions']['unit'] . '</Code>
                        </UnitOfMeasurement>
                        <Length>' . $package['dimensions']['length'] . '</Length>
                        <Width>' . $package['dimensions']['width'] . '</Width>
                        <Height>' . $package['dimensions']['height'] . '</Height>
                    </Dimensions>
                    <PackageWeight>
                        <UnitOfMeasurement>
                            <Code>' . $package['weight']['unit'] . '</Code>
                        </UnitOfMeasurement>
                        <Weight>' . $package['weight']['value'] . '</Weight>
                    </PackageWeight>
                    <PackageServiceOptions>
                        <InsuredValue>
                            <CurrencyCode>USD</CurrencyCode>
                            <MonetaryValue>' . $package['declared_value'] . '</MonetaryValue>
                        </InsuredValue>
                    </PackageServiceOptions>
                </Package>';
        }

        $xml .= '
            </Shipment>
        </ShipmentConfirmRequest>';

        return $xml;
    }

    /**
     * Build XML for shipment acceptance
     */
    private function buildAcceptXML($shipmentDigest)
    {
        return '<?xml version="1.0"?>
        <AccessRequest xml:lang="en-US">
            <AccessLicenseNumber>' . $this->accessKey . '</AccessLicenseNumber>
            <UserId>' . $this->username . '</UserId>
            <Password>' . $this->password . '</Password>
        </AccessRequest>
        <?xml version="1.0"?>
        <ShipmentAcceptRequest>
            <Request>
                <TransactionReference>
                    <CustomerContext>Medical Rep Sale Shipment Accept</CustomerContext>
                    <XpciVersion>1.0001</XpciVersion>
                </TransactionReference>
                <RequestAction>ShipAccept</RequestAction>
            </Request>
            <ShipmentDigest>' . $shipmentDigest . '</ShipmentDigest>
        </ShipmentAcceptRequest>';
    }

    /**
     * Save shipping label image
     */
    private function saveLabelImage($base64Image, $trackingNumber)
    {
        $imageData = base64_decode($base64Image);
        $fileName = 'shipping_label_' . $trackingNumber . '.gif';
        $path = storage_path('app/public/shipping_labels/' . $fileName);

        // Create directory if it doesn't exist
        if (!file_exists(dirname($path))) {
            mkdir(dirname($path), 0755, true);
        }

        file_put_contents($path, $imageData);

        return '/storage/shipping_labels/' . $fileName;
    }

    /**
     * Save shipment to database
     */
    private function saveShipment($sale, $shipmentData)
    {
        return Shipment::create([
            'sale_id' => $sale->id,
            'tracking_number' => $shipmentData['tracking_number'],
            'carrier' => 'UPS',
            'service_type' => 'Ground',
            'label_url' => $shipmentData['label_url'],
            'cost' => $shipmentData['shipment_cost'] ?? 0,
            'status' => 'shipped',
            'shipped_at' => now()
        ]);
    }

    /**
     * Track shipment
     */
    public function trackShipment($trackingNumber)
    {
        try {
            $xmlRequest = $this->buildTrackingXML($trackingNumber);

            $response = Http::withHeaders([
                'Content-Type' => 'text/xml',
            ])->post($this->baseUrl . '/Track', $xmlRequest);

            if ($response->successful()) {
                $xmlResponse = simplexml_load_string($response->body());

                if ((string) $xmlResponse->Response->ResponseStatusCode === '1') {
                    $trackingInfo = [];

                    if (isset($xmlResponse->Shipment)) {
                        $activities = $xmlResponse->Shipment->Package->Activity ?? [];

                        foreach ($activities as $activity) {
                            $trackingInfo[] = [
                                'status' => (string) $activity->Status->StatusType->Description,
                                'description' => (string) $activity->Status->StatusType->Description,
                                'location' => (string) $activity->ActivityLocation->Address->City . ', ' .
                                    (string) $activity->ActivityLocation->Address->StateProvinceCode,
                                'date' => (string) $activity->Date,
                                'time' => (string) $activity->Time
                            ];
                        }
                    }

                    return [
                        'success' => true,
                        'tracking_info' => $trackingInfo
                    ];
                } else {
                    return [
                        'success' => false,
                        'message' => 'Tracking information not available'
                    ];
                }
            }

            return [
                'success' => false,
                'message' => 'Failed to retrieve tracking information'
            ];

        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Tracking Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Build XML for tracking request
     */
    private function buildTrackingXML($trackingNumber)
    {
        return '<?xml version="1.0"?>
        <AccessRequest xml:lang="en-US">
            <AccessLicenseNumber>' . $this->accessKey . '</AccessLicenseNumber>
            <UserId>' . $this->username . '</UserId>
            <Password>' . $this->password . '</Password>
        </AccessRequest>
        <?xml version="1.0"?>
        <TrackRequest xml:lang="en-US">
            <Request>
                <TransactionReference>
                    <CustomerContext>Track Medical Rep Shipment</CustomerContext>
                    <XpciVersion>1.0001</XpciVersion>
                </TransactionReference>
                <RequestAction>Track</RequestAction>
                <RequestOption>1</RequestOption>
            </Request>
            <TrackingNumber>' . $trackingNumber . '</TrackingNumber>
        </TrackRequest>';
    }
}