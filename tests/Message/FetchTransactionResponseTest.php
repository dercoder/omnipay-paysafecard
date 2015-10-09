<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;

class FetchTransactionResponseTest extends TestCase
{

    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new FetchTransactionRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT',
            'subId' => 'shop1',
            'transactionId' => 'TX9997888',
            'currency' => 'EUR'
        ));
    }

    public function testException()
    {
        try {
            $httpResponse = $this->getMockHttpResponse('InvalidResponse.txt');
            new FetchTransactionResponse($this->request, $httpResponse->xml());
        } catch (\Exception $e) {
            $this->assertEquals('Omnipay\Common\Exception\InvalidResponseException', get_class($e));
        }
    }

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionFailure.txt');
        $response = new FetchTransactionResponse($this->request, $httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(1, $response->getResultCode());
        $this->assertSame(2002, $response->getErrorCode());
        $this->assertSame(2002, $response->getCode());
        $this->assertSame('Logical problem', $response->getMessage());
        $this->assertSame('shop1', $response->getSubId());
        $this->assertSame('', $response->getDispositionState());
        $this->assertSame('', $response->getSerialNumbers());
        $this->assertSame('TX9997888', $response->getTransactionId());
        $this->assertSame('0.0', $response->getAmount());
        $this->assertSame('', $response->getCurrency());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionSuccess.txt');
        $response = new FetchTransactionResponse($this->request, $httpResponse->xml());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(0, $response->getResultCode());
        $this->assertSame(0, $response->getErrorCode());
        $this->assertSame(0, $response->getCode());
        $this->assertSame('Consumed', $response->getMessage());
        $this->assertSame('shop1', $response->getSubId());
        $this->assertSame('O', $response->getDispositionState());
        $this->assertSame('9922921184073520;1.00', $response->getSerialNumbers());
        $this->assertSame('TX9997889', $response->getTransactionId());
        $this->assertSame('1.0', $response->getAmount());
        $this->assertSame('EUR', $response->getCurrency());
    }

    public function testState()
    {
        $httpResponse = $this->getMockHttpResponse('FetchTransactionState.txt');
        $response = new FetchTransactionResponse($this->request, $httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(0, $response->getResultCode());
        $this->assertSame(0, $response->getErrorCode());
        $this->assertSame(0, $response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('Y', $response->getDispositionState());
    }

}
