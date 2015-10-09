<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;

class PayoutRequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        parent::setUp();

        $httpResponse = $this->getMockHttpResponse('PayoutSuccess.txt');

        $mockPlugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $mockPlugin->addResponse($httpResponse);

        $httpClient = $this->getHttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $this->request = new PayoutRequest($httpClient, $this->getHttpRequest());
        $this->request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT',
            'subId' => 'shop1',
            'email' => 'user@example.com',
            'firstName' => 'John',
            'lastName' => 'Doe',
            'birthday' => '30.12.1976',
            'utcOffset' => '+02:00',
            'clientMerchantId' => 'client123',
            'transactionId' => 'TX9997888',
            'amount' => '14.65',
            'currency' => 'EUR'
        ));
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $xml = new \SimpleXMLElement($data);

        $request = $xml
            ->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->children('urn:pscservice')
            ->payout;

        $this->assertSame('SOAP_USERNAME', (string) $request->username);
        $this->assertSame('oJ2rHLBVSbD5iGfT', (string) $request->password);
        $this->assertSame('TX9997888', (string) $request->ptid);
        $this->assertSame('shop1', (string) $request->subId);
        $this->assertSame('14.65', (string) $request->amount);
        $this->assertSame('EUR', (string) $request->currency);
        $this->assertSame('client123', (string) $request->merchantClientId);
        $this->assertSame('EMAIL', (string) $request->customerIdType);
        $this->assertSame('user@example.com', (string) $request->customerId);
        $this->assertSame('false', (string) $request->validationOnly);
        $this->assertSame('+02:00', (string) $request->utcOffset);
        $this->assertSame('John', (string) $request->customerDetailsBasic->firstName);
        $this->assertSame('Doe', (string) $request->customerDetailsBasic->lastName);
        $this->assertSame('1976-12-30', (string) $request->customerDetailsBasic->dateOfBirth);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Paysafecard\Message\PayoutResponse', get_class($response));
    }

}
