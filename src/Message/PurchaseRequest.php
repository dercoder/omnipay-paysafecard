<?php

namespace Omnipay\Paysafecard\Message;

use League\Url\Url;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Purchase Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
class PurchaseRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function getMethod()
    {
        return 'createDisposition';
    }

    /**
     * {@inheritdoc}
     */
    public function getReturnUrl()
    {
        return $this->modifyUrl($this->getParameter('returnUrl'));
    }

    /**
     * {@inheritdoc}
     */
    public function getNotifyUrl()
    {
        return $this->modifyUrl($this->getParameter('notifyUrl'));
    }

    /**
     * Get the shop ID.
     *
     * Identification of the shop which is the originator of the request. This is most likely used by payment
     * service providers who act as a proxy for other payment methods as well.
     * max. length: 60 characters
     * recommended value: up to 20 characters
     * provided by business partner
     * only the following is allowed A-Z, a-z, 0-9 as well as – (hyphen) and (underline)
     * example: 2568-B415rh_785
     *
     * @return string shop id
     */
    public function getShopId()
    {
        return $this->getParameter('shopId');
    }

    /**
     * Set the shop ID.
     *
     * Identification of the shop which is the originator of the request. This is most likely used by payment
     * service providers who act as a proxy for other payment methods as well.
     * max. length: 60 characters
     * recommended value: up to 20 characters
     * provided by business partner
     * only the following is allowed A-Z, a-z, 0-9 as well as – (hyphen) and (underline)
     * example: 2568-B415rh_785
     *
     * @param string $value shop id
     *
     * @return self
     */
    public function setShopId($value)
    {
        return $this->setParameter('shopId', $value);
    }

    /**
     * Get the shop label.
     *
     * Label or URL of the shop which is the originator of the request, related to the ‘shopId’. This is most
     * likely used by payment service providers which act as a proxy for other payment methods as well.
     * max. length: 60 characters
     * example: www.foodstore.com
     *
     * @return string shop label
     */
    public function getShopLabel()
    {
        return $this->getParameter('shopLabel');
    }

    /**
     * Set the shop label.
     *
     * Label or URL of the shop which is the originator of the request, related to the ‘shopId’. This is most
     * likely used by payment service providers which act as a proxy for other payment methods as well.
     * max. length: 60 characters
     * example: www.foodstore.com
     *
     * @param string $value shop label
     *
     * @return self
     */
    public function setShopLabel($value)
    {
        return $this->setParameter('shopLabel', $value);
    }

    /**
     * Get the client merchant ID.
     *
     * A unique end customer identifier (the unique ID of the end customer as registered within
     * the business partner database, for example). If using e-mail address, please encrypt the values.
     * For promotional activities, paysafecard checks the clientId and avoids multiple redemptions.
     * NOTE: for security reasons do not use the customer’s registered username
     * max. length: 50 characters
     * example: client123
     *
     * @return string client merchant id
     */
    public function getClientMerchantId()
    {
        return $this->getParameter('clientMerchantId');
    }

    /**
     * Set the client merchant ID.
     *
     * A unique end customer identifier (the unique ID of the end customer as registered within
     * the business partner database, for example). If using e-mail address, please encrypt the values.
     * For promotional activities, paysafecard checks the clientId and avoids multiple redemptions.
     * NOTE: for security reasons do not use the customer’s registered username
     * max. length: 50 characters
     * example: client123
     *
     * @param string $value client merchant id
     *
     * @return self
     */
    public function setClientMerchantId($value)
    {
        return $this->setParameter('clientMerchantId', $value);
    }

    /**
     * Get the country restrictions.
     *
     * Disposition restrictions can be set by the business partner in order to restrict a
     * payment transaction, according to their individual needs. See chapter 6.3.2 for details.
     * multiple repeats possible
     * each restriction is a pair of key and value:
     * key – the name of the restriction
     * value – the value of the restriction
     * - COUNTRY DE
     * all countries where paysafecard is distributed (example: FR, ES,…).
     * Restricts the payment to be processed exclusively from country Germany.
     * The value accepts ISO 3166-1 country codes.
     *
     * @return array country restrictions
     */
    public function getCountryRestrictions()
    {
        return $this->getParameter('countryRestrictions');
    }

    /**
     * Set the country restrictions.
     *
     * Disposition restrictions can be set by the business partner in order to restrict a
     * payment transaction, according to their individual needs. See chapter 6.3.2 for details.
     * multiple repeats possible
     * each restriction is a pair of key and value:
     * key – the name of the restriction
     * value – the value of the restriction
     * - COUNTRY DE
     * all countries where paysafecard is distributed (example: FR, ES,…).
     * Restricts the payment to be processed exclusively from country Germany.
     * The value accepts ISO 3166-1 country codes.
     *
     * @param array $value country restrictions
     *
     * @return self
     */
    public function setCountryRestrictions(array $value)
    {
        return $this->setParameter('countryRestrictions', array_map('strtoupper', $value));
    }

    /**
     * Get the minimum age restrictions.
     *
     * Disposition restrictions can be set by the business partner in order to restrict a
     * payment transaction, according to their individual needs. See chapter 6.3.2 for details.
     * multiple repeats possible
     * each restriction is a pair of key and value:
     * key – the name of the restriction
     * value – the value of the restriction
     * - MIN_AGE 18
     * must be a positive number value
     * Restricts my paysafecard user account holder to be at least 18 years old.
     *
     * @return string min age restriction
     */
    public function getMinAgeRestriction()
    {
        return $this->getParameter('minAgeRestriction');
    }

    /**
     * Set the minimum age restrictions.
     *
     * Disposition restrictions can be set by the business partner in order to restrict a
     * payment transaction, according to their individual needs. See chapter 6.3.2 for details.
     * multiple repeats possible
     * each restriction is a pair of key and value:
     * key – the name of the restriction
     * value – the value of the restriction
     * - MIN_AGE 18
     * must be a positive number value
     * Restricts my paysafecard user account holder to be at least 18 years old.
     *
     * @param string $value min age restriction
     *
     * @return self
     */
    public function setMinAgeRestriction($value)
    {
        return $this->setParameter('minAgeRestriction', $value);
    }

    /**
     * Get the minimum KYC level restriction.
     *
     * Disposition restrictions can be set by the business partner in order to restrict a
     * payment transaction, according to their individual needs. See chapter 6.3.2 for details.
     * multiple repeats possible
     * each restriction is a pair of key and value:
     * key – the name of the restriction
     * value – the value of the restriction
     * - MIN_KYC_LEVEL FULL
     * SIMPLE or FULL
     * Restricts my paysafecard user account holder to be at least in the state, i.e. simple.
     *
     * @return string min KYC level restriction
     */
    public function getMinKycLevelRestriction()
    {
        return $this->getParameter('minKycLevelRestriction');
    }

    /**
     * Set the minimum KYC level restriction.
     *
     * Disposition restrictions can be set by the business partner in order to restrict a
     * payment transaction, according to their individual needs. See chapter 6.3.2 for details.
     * multiple repeats possible
     * each restriction is a pair of key and value:
     * key – the name of the restriction
     * value – the value of the restriction
     * - MIN_KYC_LEVEL FULL
     * SIMPLE or FULL
     * Restricts my paysafecard user account holder to be at least in the state, i.e. simple.
     *
     * @param string $value min KYC level restriction
     *
     * @return self
     */
    public function setMinKycLevelRestriction($value)
    {
        return $this->setParameter('minKycLevelRestriction', strtoupper($value));
    }

    /**
     * Get the language.
     *
     * For backward compatibility, all existing language parameters still yield the same result as in former versions of
     * the API, but every language will be automatically transformed into a locale.
     * Basically, the language and locale of the payment panel are determined by following rule:
     * 1. Has the customer already visited the payment panel? Take the locale from the set cookie.
     * 2. Take the locale from the IP address of the customer1.
     * 3. Take the value from the locale parameter.
     * 4. Take the value from the language parameter.
     * 5. Take the locale from the browser header.
     * 6. Take the fallback locale (de_de).
     * Therefore, it is not obligatory to set a locale parameter.
     *
     * @return string language
     */
    public function getLanguage()
    {
        return $this->getParameter('language');
    }

    /**
     * Set the language.
     *
     * For backward compatibility, all existing language parameters still yield the same result as in former versions of
     * the API, but every language will be automatically transformed into a locale.
     * Basically, the language and locale of the payment panel are determined by following rule:
     * 1. Has the customer already visited the payment panel? Take the locale from the set cookie.
     * 2. Take the locale from the IP address of the customer1.
     * 3. Take the value from the locale parameter.
     * 4. Take the value from the language parameter.
     * 5. Take the locale from the browser header.
     * 6. Take the fallback locale (de_de).
     * Therefore, it is not obligatory to set a locale parameter.
     *
     * @param string $value language
     *
     * @return self
     */
    public function setLanguage($value)
    {
        return $this->setParameter('language', strtolower($value));
    }

    /**
     * Get the locale.
     *
     * For backward compatibility, all existing language parameters still yield the same result as in former versions of
     * the API, but every language will be automatically transformed into a locale.
     * Basically, the language and locale of the payment panel are determined by following rule:
     * 1. Has the customer already visited the payment panel? Take the locale from the set cookie.
     * 2. Take the locale from the IP address of the customer1.
     * 3. Take the value from the locale parameter.
     * 4. Take the value from the language parameter.
     * 5. Take the locale from the browser header.
     * 6. Take the fallback locale (de_de).
     * Therefore, it is not obligatory to set a locale parameter.
     *
     * @return string locale
     */
    public function getLocale()
    {
        return $this->getParameter('locale');
    }

    /**
     * Set the locale.
     *
     * For backward compatibility, all existing language parameters still yield the same result as in former versions of
     * the API, but every language will be automatically transformed into a locale.
     * Basically, the language and locale of the payment panel are determined by following rule:
     * 1. Has the customer already visited the payment panel? Take the locale from the set cookie.
     * 2. Take the locale from the IP address of the customer1.
     * 3. Take the value from the locale parameter.
     * 4. Take the value from the language parameter.
     * 5. Take the locale from the browser header.
     * 6. Take the fallback locale (de_de).
     * Therefore, it is not obligatory to set a locale parameter.
     *
     * @param string $value locale
     *
     * @return self
     */
    public function setLocale($value)
    {
        return $this->setParameter('locale', strtolower($value));
    }

    /**
     * Get the data for this request.
     *
     * @return array request data
     */
    public function getData()
    {
        $this->validate(
            'username',
            'password',
            'transactionId',
            'amount',
            'currency',
            'clientIp',
            'returnUrl',
            'cancelUrl',
            'notifyUrl',
            'clientMerchantId'
        );

        $document = new \DOMDocument('1.0', 'utf-8');
        $document->formatOutput = false;
        $document->createElement('soapenv:Header');

        $envelope = $document->appendChild(
            $document->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'soapenv:Envelope')
        );
        $envelope->setAttribute('xmlns:urn', 'urn:pscservice');

        $body = $envelope->appendChild(
            $document->createElement('soapenv:Body')
        );

        $disposition = $body->appendChild(
            $document->createElement('urn:createDisposition')
        );

        $disposition->appendChild(
            $document->createElement('urn:username', $this->getUsername())
        );

        $disposition->appendChild(
            $document->createElement('urn:password', $this->getPassword())
        );

        $disposition->appendChild(
            $document->createElement('urn:mtid', $this->getTransactionId())
        );

        $disposition->appendChild(
            $document->createElement('urn:subId', $this->getSubId())
        );

        $disposition->appendChild(
            $document->createElement('urn:amount', $this->getAmount())
        );

        $disposition->appendChild(
            $document->createElement('urn:currency', $this->getCurrency())
        );

        $disposition->appendChild(
            $document->createElement('urn:okUrl', rawurlencode($this->getReturnUrl()))
        );

        $disposition->appendChild(
            $document->createElement('urn:nokUrl', rawurlencode($this->getCancelUrl()))
        );

        $disposition->appendChild(
            $document->createElement('urn:pnUrl', rawurlencode($this->getNotifyUrl()))
        );

        $disposition->appendChild(
            $document->createElement('urn:shopId', $this->getShopId())
        );

        $disposition->appendChild(
            $document->createElement('urn:shopLabel', $this->getShopLabel())
        );

        $disposition->appendChild(
            $document->createElement('urn:merchantclientid', $this->getClientMerchantId())
        );

        if ($countryRestrictionValues = $this->getCountryRestrictions()) {
            $countryRestriction = $disposition->appendChild(
                $document->createElement('urn:dispositionRestrictions')
            );

            $countryRestriction->appendChild(
                $document->createElement('urn:key', 'COUNTRY')
            );

            $countryRestriction->appendChild(
                $document->createElement('urn:value', implode(',', $countryRestrictionValues))
            );
        }

        if ($minAgeRestrictionValue = $this->getMinAgeRestriction()) {
            $minAgeRestriction = $disposition->appendChild(
                $document->createElement('urn:dispositionRestrictions')
            );

            $minAgeRestriction->appendChild(
                $document->createElement('urn:key', 'MIN_AGE')
            );

            $minAgeRestriction->appendChild(
                $document->createElement('urn:value', $minAgeRestrictionValue)
            );
        }

        if ($minKycLevelRestrictionValue = $this->getMinKycLevelRestriction()) {
            $minKycLevelRestriction = $disposition->appendChild(
                $document->createElement('urn:dispositionRestrictions')
            );

            $minKycLevelRestriction->appendChild(
                $document->createElement('urn:key', 'MIN_KYC_LEVEL')
            );

            $minKycLevelRestriction->appendChild(
                $document->createElement('urn:value', $minKycLevelRestrictionValue)
            );
        }

        return $document->saveXML();
    }

    /**
     * {@inheritdoc}
     */
    protected function modifyUrl($url)
    {
        $url = Url::createFromUrl($url);
        $url->getQuery()->modify(array(
            'mtid' => $this->getTransactionId(),
            'subid' => $this->getSubId(),
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
        ));

        return (string) $url;
    }

    /**
     * Create a proper response based on the request.
     *
     * @param \SimpleXMLElement $xml
     *
     * @return PurchaseResponse
     *
     * @throws InvalidResponseException
     */
    protected function createResponse(\SimpleXMLElement $xml)
    {
        return $this->response = new PurchaseResponse($this, $xml);
    }
}
