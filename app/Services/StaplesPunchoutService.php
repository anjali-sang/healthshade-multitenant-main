<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Client\Response;

class StaplesPunchoutService
{
    protected $endpoint = 'https://bciprod.stapleslink.com/invoke/StaplescXML/receive11';
    protected $username = 'asthmalleh';
    protected $password = 'staples';

    public function send(array $orderData)
    {
        $cxml = $this->buildCXML($orderData);

        if (config('services.staples.production', true)) {
            $response = Http::withBasicAuth($this->username, $this->password)
                ->withHeaders([
                    'Content-Type' => 'text/xml',
                ])
                ->post($this->endpoint, $cxml);
            Log::info('Staples Punchout Response', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            return $response;
        } else {
            logger('Staples Punchout Service: Production mode disabled');
            return new Response(
                new \GuzzleHttp\Psr7\Response(200, [], 'Simulated success in test mode')
            );
        }
    }

    protected function buildCXML(array $data)
    {

        $itemsXml = '';
        foreach ($data['items'] as $index => $item) {
            $lineNumber = $index + 1;
            $itemsXml .= "
<ItemOut quantity=\"{$item['quantity']}\" lineNumber=\"{$item['lineNumber']}\">
    <ItemID>
        <SupplierPartID>{$item['code']}</SupplierPartID>
    </ItemID>
    <ItemDetail>
        <UnitPrice>
            <Money currency=\"USD\">{$item['price']}</Money>
        </UnitPrice>
        <Description>{$item['description']}</Description>
        <UnitOfMeasure>{$item['uom']}</UnitOfMeasure>
    </ItemDetail>
</ItemOut>";
        }

        return <<<XML
            <?xml version="1.0" encoding="UTF-8"?>
                <cXML payloadID="{$data['payLoadID']}" timestamp="{$data['timestamp']}" xml:lang="en-US">
                <Header>
                    <From>
                        <Credential domain="NetworkId">
                            <Identity>{$data['customerIdentity']}</Identity>
                        </Credential>
                    </From>
                    <To>
                        <Credential domain="DUNS">
                            <Identity>{$data['toIdentity']}</Identity>
                        </Credential>
                    </To>
                    <Sender>
                        <Credential domain="NetworkId">
                            <Identity>{$data['senderIdentity']}</Identity>
                            <SharedSecret>{$data['sharedSecret']}</SharedSecret>
                        </Credential>
                        <UserAgent>{$data['userAgent']}</UserAgent>
                    </Sender>
                </Header>
                <Request deploymentMode="{$data['production']}" type="new">
                <OrderRequest>
                    <OrderRequestHeader orderID="{$data['orderId']}" orderDate="{$data['orderDate']}" type="new">
                    <Total>
                        <Money currency="USD">{$data['total']}</Money>
                    </Total>
                    <ShipTo>
                        <Address isoCountryCode="US" addressID="{$data['shiptoAddressId']}">
                        <Name>{$data['shiptoName']}</Name>
                        <PostalAddress>
                            <DeliverTo>{$data['deliverTo']}</DeliverTo>
                            <Street></Street>
                            <City>{$data['shiptoCity']}</City>
                            <State>{$data['shiptoState']}</State>
                            <PostalCode>{$data['shiptoPostalCode']}</PostalCode>
                            <Country>{$data["shiptoCountry"]}</Country>
                        </PostalAddress>
                        <Email>{$data['shiptoEmail']}</Email>
                        <Phone>
                            <TelephoneNumber>
                                <Number>{$data['shiptoPhoneNumber']}</Number>
                            </TelephoneNumber>
                        </Phone>
                    </Address>
                </ShipTo>
                <BillTo>
                    <Address isoCountryCode="US" addressID="{$data['billToAddressId']}">
                        <Name>{$data['billToName']}</Name>
                        <PostalAddress>
                            <DeliverTo>{$data['billToAddress']}</DeliverTo>
                            <Street></Street>
                            <City>{$data['billToCity']}</City>
                            <State>{$data['billToState']}</State>
                            <PostalCode>{$data['billToPostalCode']}</PostalCode>
                            <Country>{$data['billToCountry']}</Country>
                        </PostalAddress>
                        <Email>{$data['billToEmail']}</Email>
                        <Phone>
                            <TelephoneNumber>
                                <Number>{$data['billToPhoneNumber']}</Number>
                            </TelephoneNumber>
                        </Phone>
                    </Address>
                </BillTo>
                <Tax>
                    <Money>0.00</Money>
                    <Description/>
                </Tax>
                <Payment>
                    <PCard />
                </Payment>
                <Contact role="Order Contact">
                    <Name>{$data['contactName']}</Name>
                    <Email name="Buyer Email">{$data['contactEmail']}</Email>
                </Contact>
            </OrderRequestHeader>
            {$itemsXml}
        </OrderRequest>
    </Request>
</cXML>
XML;
    }
}
