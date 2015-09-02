<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;

class CompletePurchaseResponseTest extends TestCase
{

    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new CompletePurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT',
            'subId' => 'shop1',
            'transactionId' => 'TX9997888',
            'amount' => '1.00',
            'currency' => 'EUR'
        ));
    }

    public function testException()
    {
        try {
            $httpResponse = $this->getMockHttpResponse('InvalidResponse.txt');
            new CompletePurchaseResponse($this->request, $httpResponse->xml());
        } catch (\Exception $e) {
            $this->assertEquals('Omnipay\Common\Exception\InvalidResponseException', get_class($e));
        }
    }

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('CompletePurchaseFailure.txt');
        $response = new CompletePurchaseResponse($this->request, $httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(999, $response->getResultCode());
        $this->assertSame(2017, $response->getErrorCode());
        $this->assertSame(2017, $response->getCode());
        $this->assertSame('Unknown problem', $response->getMessage());
        $this->assertSame('shop1', $response->getSubId());
        $this->assertSame('TX9997888', $response->getTransactionId());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('CompletePurchaseSuccess.txt');
        $response = new CompletePurchaseResponse($this->request, $httpResponse->xml());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(0, $response->getResultCode());
        $this->assertSame(0, $response->getErrorCode());
        $this->assertSame(0, $response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('shop1', $response->getSubId());
        $this->assertSame('TX9997889', $response->getTransactionId());
    }

}
