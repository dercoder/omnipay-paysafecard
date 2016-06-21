<?php
namespace Omnipay\Paysafecard\Message;

use Omnipay\Tests\TestCase;

class PurchaseRequestTest extends TestCase
{
    private $request;

    public function setUp()
    {
        parent::setUp();

        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');

        $mockPlugin = new \Guzzle\Plugin\Mock\MockPlugin();
        $mockPlugin->addResponse($httpResponse);

        $httpClient = $this->getHttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $this->request = new PurchaseRequest($httpClient, $this->getHttpRequest());
        $this->request->initialize(array(
            'username' => 'SOAP_USERNAME',
            'password' => 'oJ2rHLBVSbD5iGfT',
            'clientIp' => '127.0.0.1',
            'returnUrl' => 'https://www.foodstore.com/success.html?parameter=somedata',
            'cancelUrl' => 'https://www.foodstore.com/failure.html?parameter=somedata',
            'notifyUrl' => 'https://www.foodstore.com/notify.html?parameter=somedata',
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

    public function testGetData()
    {
        $data = $this->request->getData();
        $xml = new \SimpleXMLElement($data);

        $request = $xml
            ->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->children('urn:pscservice')
            ->createDisposition;

        $this->assertSame('SOAP_USERNAME', (string) $request->username);
        $this->assertSame('oJ2rHLBVSbD5iGfT', (string) $request->password);
        $this->assertSame('TX9997888', (string) $request->mtid);
        $this->assertSame('shop1', (string) $request->subId);
        $this->assertSame('14.65', (string) $request->amount);
        $this->assertSame('EUR', (string) $request->currency);
        $this->assertSame('https%3A%2F%2Fwww.foodstore.com%2Fsuccess.html%3Fparameter%3Dsomedata%26mtid%3DTX9997888%26subid%3Dshop1%26amount%3D14.65%26currency%3DEUR', (string) $request->okUrl);
        $this->assertSame('https%3A%2F%2Fwww.foodstore.com%2Ffailure.html%3Fparameter%3Dsomedata', (string) $request->nokUrl);
        $this->assertSame('https%3A%2F%2Fwww.foodstore.com%2Fnotify.html%3Fparameter%3Dsomedata%26mtid%3DTX9997888%26subid%3Dshop1%26amount%3D14.65%26currency%3DEUR', (string) $request->pnUrl);
        $this->assertSame('2568-B415rh_785', (string) $request->shopId);
        $this->assertSame('www.foodstore.com', (string) $request->shopLabel);
        $this->assertSame('client123', (string) $request->merchantclientid);

        $this->assertSame('COUNTRY', (string) $request->dispositionRestrictions[0]->key);
        $this->assertSame('FR,ES', (string) $request->dispositionRestrictions[0]->value);

        $this->assertSame('MIN_AGE', (string) $request->dispositionRestrictions[1]->key);
        $this->assertSame('18', (string) $request->dispositionRestrictions[1]->value);

        $this->assertSame('MIN_KYC_LEVEL', (string) $request->dispositionRestrictions[2]->key);
        $this->assertSame('SIMPLE', (string) $request->dispositionRestrictions[2]->value);
    }

    public function testSendData()
    {
        $data = $this->request->getData();
        $response = $this->request->sendData($data);
        $this->assertSame('Omnipay\Paysafecard\Message\PurchaseResponse', get_class($response));
    }

}
