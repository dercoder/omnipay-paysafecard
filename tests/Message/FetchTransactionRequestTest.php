<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionRequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        parent::setUp();

        $httpResponse = $this->getMockHttpResponse('FetchTransactionSuccess.txt');

        $mockPlugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $mockPlugin->addResponse($httpResponse);

        $httpClient = $this->getHttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $this->request = new FetchTransactionRequest($httpClient, $this->getHttpRequest());
        $this->request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT',
            'subId' => 'shop1',
            'transactionId' => 'TX9997888',
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
            ->getSerialNumbers;

        $this->assertSame('SOAP_USERNAME', (string) $request->username);
        $this->assertSame('oJ2rHLBVSbD5iGfT', (string) $request->password);
        $this->assertSame('TX9997888', (string) $request->mtid);
        $this->assertSame('shop1', (string) $request->subId);
        $this->assertSame('EUR', (string) $request->currency);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Paysafecard\Message\FetchTransactionResponse', get_class($response));
    }
}
