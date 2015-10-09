<?php
namespace Omnipay\Paysafecard;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public $gateway;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->gateway->setUsername('SOAP_USERNAME');
        $this->gateway->setPassword('oJ2rHLBVSbD5iGfT');
        $this->gateway->setTestMode(true);
    }

    public function testGateway()
    {
        $this->assertSame('SOAP_USERNAME', $this->gateway->getUsername());
        $this->assertSame('oJ2rHLBVSbD5iGfT', $this->gateway->getPassword());
    }

    public function testPurchase()
    {
        $request = $this->gateway->purchase(array(
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

        $this->assertSame('https://soatest.paysafecard.com/psc/services/PscService', $request->getEndpoint());
        $this->assertSame('shop1', $request->getSubId());
        $this->assertSame('2568-B415rh_785', $request->getShopId());
        $this->assertSame('www.foodstore.com', $request->getShopLabel());
        $this->assertContains('FR', $request->getCountryRestrictions());
        $this->assertContains('ES', $request->getCountryRestrictions());
        $this->assertSame(18, $request->getMinAgeRestriction());
        $this->assertSame('SIMPLE', $request->getMinKycLevelRestriction());
        $this->assertSame('de', $request->getLanguage());
        $this->assertSame('de_de', $request->getLocale());
        $this->assertSame('client123', $request->getClientMerchantId());
        $this->assertSame('TX9997888', $request->getTransactionId());
        $this->assertSame('14.65', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase(array(
            'subId' => 'shop1',
            'transactionId' => 'TX8889777',
            'amount' => '12.43',
            'currency' => 'EUR'
        ));

        $this->assertSame('https://soatest.paysafecard.com/psc/services/PscService', $request->getEndpoint());
        $this->assertSame('shop1', $request->getSubId());
        $this->assertSame('TX8889777', $request->getTransactionId());
        $this->assertSame('12.43', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }

    public function testPayout()
    {
        $request = $this->gateway->payout(array(
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

        $this->assertSame('https://soatest.paysafecard.com/psc/services/PscService', $request->getEndpoint());
        $this->assertSame('shop1', $request->getSubId());
        $this->assertSame('user@example.com', $request->getEmail());
        $this->assertSame('John', $request->getFirstName());
        $this->assertSame('Doe', $request->getLastName());
        $this->assertSame('1976-12-30', $request->getBirthday());
        $this->assertSame('+02:00', $request->getUtcOffset());
        $this->assertSame('client123', $request->getClientMerchantId());
        $this->assertSame('TX9997888', $request->getTransactionId());
        $this->assertSame('14.65', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }

    public function testValidatePayout()
    {
        $request = $this->gateway->validatePayout(array(
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

        $this->assertSame('https://soatest.paysafecard.com/psc/services/PscService', $request->getEndpoint());
        $this->assertSame('shop1', $request->getSubId());
        $this->assertSame('user@example.com', $request->getEmail());
        $this->assertSame('John', $request->getFirstName());
        $this->assertSame('Doe', $request->getLastName());
        $this->assertSame('1976-12-30', $request->getBirthday());
        $this->assertSame('+02:00', $request->getUtcOffset());
        $this->assertSame('client123', $request->getClientMerchantId());
        $this->assertSame('TX9997888', $request->getTransactionId());
        $this->assertSame('14.65', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }

    public function testFetchTransaction()
    {
        $request = $this->gateway->fetchTransaction(array(
            'transactionId' => 'TX8889777',
            'amount' => '12.43',
            'currency' => 'EUR'
        ));

        $this->assertSame('https://soatest.paysafecard.com/psc/services/PscService', $request->getEndpoint());
        $this->assertSame('TX8889777', $request->getTransactionId());
        $this->assertSame('12.43', $request->getAmount());
        $this->assertSame('EUR', $request->getCurrency());
    }
}
