<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;

class ValidatePayoutResponseTest extends TestCase
{

    private $request;

    public function setUp()
    {
        parent::setUp();

        $this->request = new ValidatePayoutRequest($this->getHttpClient(), $this->getHttpRequest());
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
            'transactionId' => 'TX9997889',
            'amount' => '14.65',
            'currency' => 'EUR'
        ));
    }

    public function testException()
    {
        try {
            $httpResponse = $this->getMockHttpResponse('InvalidResponse.txt');
            new PayoutResponse($this->request, $httpResponse->xml());
        } catch (\Exception $e) {
            $this->assertEquals('Omnipay\Common\Exception\InvalidResponseException', get_class($e));
        }
    }

    public function testFailure()
    {
        $httpResponse = $this->getMockHttpResponse('ValidatePayoutFailure.txt');
        $response = new ValidatePayoutResponse($this->request, $httpResponse->xml());

        $this->assertFalse($response->isSuccessful());
        $this->assertSame(1, $response->getResultCode());
        $this->assertSame(3150, $response->getErrorCode());
        $this->assertSame(3150, $response->getCode());
        $this->assertSame('Missing mandatory parameter CustomerDetailsBasic', $response->getMessage());
        $this->assertSame('TX9997889', $response->getTransactionId());
        $this->assertSame('14.65', $response->getAmount());
        $this->assertSame('EUR', $response->getCurrency());
        $this->assertTrue($response->getValidationOnly());
    }

    public function testSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('ValidatePayoutSuccess.txt');
        $response = new ValidatePayoutResponse($this->request, $httpResponse->xml());

        $this->assertTrue($response->isSuccessful());
        $this->assertSame(0, $response->getResultCode());
        $this->assertSame(0, $response->getErrorCode());
        $this->assertSame(0, $response->getCode());
        $this->assertNull($response->getMessage());
        $this->assertSame('TX9997889', $response->getTransactionId());
        $this->assertSame('14.65', $response->getAmount());
        $this->assertSame('EUR', $response->getCurrency());
        $this->assertTrue($response->getValidationOnly());
    }

}
