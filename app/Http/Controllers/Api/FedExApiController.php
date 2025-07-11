<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\RequestSchema\ShipmentRequest;
use App\Http\RequestSchema\TagRequest;
use Illuminate\Http\Request;
use \GuzzleHttp\Client;

class FedExApiController extends Controller
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

    /**
     * common token generation function
     * generates token based on client id and client secret
     */
    public function authenticate($clientDetails = [])
    {
        $request = new Client();
        $endpoint = $this->fedexapiUrl . '/oauth/token';
        $body = [
            'grant_type' => 'client_credentials',
            'client_id' => config('services.fedex.client_id'),
            'client_secret' => config('services.fedex.client_secret')
        ];

        //json_encode($body);
        try {
            $response = $request->request(
                'POST',
                $endpoint,
                [
                    'headers' => ['Content-Type' => 'application/x-www-form-urlencoded'],
                    'form_params' => $body,
                    'verify' => 'C:\wamp64\bin\php\php8.4.0\extras\ssl\cacert.pem'
                ]
            );
            $statusCode = $response->getStatusCode();
            $content = $response->getBody();
            return $content;
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $response = $e->getResponse();
            $responseBodyAsString = $response->getBody()->getContents();
            return $responseBodyAsString;
        }
    }

    public function rateTransistTimeFunction($requestData)
    {
        // Authenticate and get token
        $data = json_decode($this->authenticate($requestData));
        $token = '';
        if (isset($data->errors)) {
            $response = [
                'success' => false,
                'error' => ['errors' => [['message' => 'Your Fedex Credentials are not valid for this user.']]]
            ];
            return json_encode($response);
        } else {
            $token = $data->access_token;
        }

        // Build the shipment request directly from the passed data
        $shipmentRequest = [
            'rateRequestControlParameters' => [
                'returnTransitTimes' => true
            ],
            'requestedShipment' => $requestData['requestedShipment'],
            'accountNumber' => $requestData['accountNumber']
        ];

        logger('Final Shipment Request:', $shipmentRequest);

        $request = new Client();
        $endpoint = $this->fedexapiUrl . '/rate/v1/rates/quotes';
        $header = ['content-type' => 'application/json', 'Authorization' => "Bearer " . $token];

        try {
            logger("entering try and catch");
            $response = $request->request(
                'POST',
                $endpoint,
                [
                    'headers' => $header,
                    'json' => $shipmentRequest,
                    'verify' => 'C:\wamp64\bin\php\php8.4.0\extras\ssl\cacert.pem'
                ]
            );
            logger("API call made");
            $statusCode = $response->getStatusCode();
            $content = json_decode($response->getBody());
            $content = json_decode(json_encode($content), true);
            $responseArr = array();
            $rateArray = $content['output']['rateReplyDetails'];
            for ($i = 0; $i < count($rateArray); $i++) {
                $responseArr[] = [
                    "serviceName" => $rateArray[$i]['serviceName'],
                    "day" => !empty($rateArray[$i]['commit']['dateDetail']) ? $rateArray[$i]['commit']['dateDetail']['dayOfWeek'] : 'Not available',
                    "timeMain" => !empty($rateArray[$i]['commit']['dateDetail']) ? $rateArray[$i]['commit']['dateDetail']['dayFormat'] : 'Not available',
                    "time" => !empty($rateArray[$i]['commit']['dateDetail']) ? date('h:m', strtotime($rateArray[$i]['commit']['dateDetail']['dayFormat'])) . ' ' . $rateArray[$i]['commit']['dateDetail']['dayOfWeek'] . ' ' . date('d-m-Y', strtotime($rateArray[$i]['commit']['dateDetail']['dayFormat'])) : 'Not available',
                    "totalDiscounts" => $rateArray[$i]['ratedShipmentDetails'][0]['totalDiscounts'],
                    "totalBaseCharge" => $rateArray[$i]['ratedShipmentDetails'][0]['totalBaseCharge'],
                    "totalNetCharge" => $rateArray[$i]['ratedShipmentDetails'][0]['totalNetCharge'],
                    "totalNetFedExCharge" => $rateArray[$i]['ratedShipmentDetails'][0]['totalNetFedExCharge']
                ];
            }

            $response = [
                'success' => true,
                'data' => $responseArr
            ];
            return json_encode($response);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            $responseBody = $e->getResponse()->getBody()->getContents();
            $decoded = json_decode($responseBody, true);
            $errorCode = $decoded['errors'][0]['code'] ?? 'GENERIC.ERROR';

            $friendlyMessage = $this->fedexErrorMessages[$errorCode] ?? $decoded['errors'][0]['message'] ?? 'Something went wrong. Please try again.';

            return response()->json([
                'success' => false,
                'error_code' => $errorCode,
                'message' => $friendlyMessage
            ]);
        } catch (\GuzzleHttp\Exception\RequestException $e) {
            $response = $e->getMessage();
            $responseBodyAsString = $response;
            $error = '';
            if ($e->hasResponse()) {
                $error = $e->getResponse();
            }
            $response = [
                'success' => false,
                'error' => $responseBodyAsString,
                'msg' => $error
            ];
            return json_encode($response);
        }
    }


}
