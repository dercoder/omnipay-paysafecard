<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;

class PurchaseResponseTest extends TestCase
{

    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new PurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT',
            'clientIp' => '127.0.0.1',
            'returnUrl' => 'https://www.foodstore.com/success',
            'cancelUrl' => 'https://www.foodstore.com/failure',
            'notifyUrl' => 'https://www.foodstore.com/notify',
            'subId' => 'shop1',
            'shopId' => '2568-B415rh_785',
            'shopLabel' => 'www.foodstore.com',
            'countryRestrictions' => array('FR', 'ES'),
            'minAgeRestriction' => 18,
            'minKycLevelRestriction' => 'SIMPLE',
            'language' => 'de',
            'locale' => 'de_de',
            'clientMerchantId' => 'client123',
            'transactionId' => 'TX9997888',
            'amount' => '14.65',
            'currency' => 'EUR'
        ));
    }

    public function testException()
    {
        try {
            $httpResponse = $this->getMockHttpResponse('InvalidResponse.txt');
            new PurchaseResponse($this->request, $httpResponse->xml());
        } catch (\Exception $e) {
            $this->assertEquals('Omnipay\Common\Exception\InvalidResponseException', get_class($e));
        }
    }

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new PurchaseResponse($this->request, $httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame(2, $response->getResultCode());
        $this->assertSame(4005, $response->getErrorCode());
        $this->assertSame(4005, $response->getCode());
        $this->assertSame('Technical problem', $response->getMessage());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('https://customer.cc.at.paysafecard.com/psccustomer/GetCustomerPanelServlet?amount=14.65&currency=EUR&mid=0&mtid=TX9997888&language=de&locale=de_de', $response->getRedirectUrl());
        $this->assertSame('shop1', $response->getSubId());
        $this->assertSame(0, $response->getMid());
        $this->assertSame('TX9997888', $response->getTransactionId());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new PurchaseResponse($this->request, $httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame(0, $response->getResultCode());
        $this->assertSame(0, $response->getErrorCode());
        $this->assertSame(0, $response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('GET', $response->getRedirectMethod());
        $this->assertNull($response->getRedirectData());
        $this->assertSame('https://customer.cc.at.paysafecard.com/psccustomer/GetCustomerPanelServlet?amount=14.65&currency=EUR&mid=1000001234&mtid=TX9997889&language=de&locale=de_de', $response->getRedirectUrl());
        $this->assertSame('shop1', $response->getSubId());
        $this->assertSame(1000001234, $response->getMid());
        $this->assertSame('TX9997889', $response->getTransactionId());
    }

}
