<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;
use Guzzle\Http\Client as HttpClient;
use Symfony\Component\HttpFoundation\Request as HttpRequest;

class CompletePurchaseRequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        parent::setUp();

        $httpCompletePurchaseResponse = $this->getMockHttpResponse('CompletePurchaseSuccess.txt');
        $httpFetchTransactionResponse = $this->getMockHttpResponse('FetchTransactionPending.txt');

        $mockPlugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $mockPlugin->addResponse($httpFetchTransactionResponse);
        $mockPlugin->addResponse($httpCompletePurchaseResponse);

        $httpClient = new HttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $httpRequest = new HttpRequest(array(
            'mtid' => 'TX9997888',
            'subid' => 'shop1',
            'amount' => '1.00',
            'currency' => 'EUR'
        ));

        $this->request = new CompletePurchaseRequest($httpClient, $httpRequest);
        $this->request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT'
        ));
    }

    public function testExceptions()
    {
        try {
            $request = new CompletePurchaseRequest(new HttpClient(), new HttpRequest());
            $request->initialize(array(
                'username' => 'SOAP_USERNAME',
                'password' => 'oJ2rHLBVSbD5iGfT'
            ))->getData();
        } catch (\Exception $e) {
            $this->assertEquals('Omnipay\Common\Exception\InvalidRequestException', get_class($e));
        }

        try {
            $httpResponse = $this->getMockHttpResponse('FetchTransactionSuccess.txt');

            $mockPlugin = new \Guzzle\Plugin\Mock\MockPlugin();
            $mockPlugin->addResponse($httpResponse);

            $httpClient = new HttpClient();
            $httpClient->addSubscriber($mockPlugin);

            $request = new CompletePurchaseRequest($httpClient, new HttpRequest());
            $request->initialize(array(
                'username' => 'SOAP_USERNAME',
                'password' => 'oJ2rHLBVSbD5iGfT',
                'transactionId' => 'TX9997888',
                'subId' => 'shop1',
                'amount' => '1.00',
                'currency' => 'EUR'
            ))->getData();
        } catch (\Exception $e) {
            $this->assertEquals('Omnipay\Common\Exception\InvalidRequestException', get_class($e));
        }
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $xml = new \SimpleXMLElement($data);

        $request = $xml
            ->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->children('urn:pscservice')
            ->executeDebit;

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
        $this->assertSame('Omnipay\Paysafecard\Message\CompletePurchaseResponse', get_class($response));
    }

    public function testSendInvalidData()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionSuccess.txt');

        $mockPlugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $mockPlugin->addResponse($httpResponse);

        $httpClient = new HttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $request = new CompletePurchaseRequest($httpClient, new HttpRequest());
        $response = $request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT',
            'transactionId' => 'TX9997888',
            'subId' => 'shop1',
            'amount' => '1.00',
            'currency' => 'EUR'
        ))->send();

        $this->assertSame('Omnipay\Paysafecard\Message\FetchTransactionResponse', get_class($response));
        $this->assertTrue($response->isSuccessful());
        $this->assertSame(0, $response->getCode());
        $this->assertSame('Consumed', $response->getMessage());
        $this->assertSame('9922921184073520;1.00', $response->getSerialNumbers());
    }
}
