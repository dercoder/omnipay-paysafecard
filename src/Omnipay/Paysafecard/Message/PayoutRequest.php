<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Payout Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.4 Paysafecard Payout API Specification
 */
class PayoutRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function getMethod()
    {
        return 'payout';
    }

    /**
     * {@inheritdoc}
     */
    protected function getValidationOnly()
    {
        return 'false';
    }

    /**
     * Get the customer's email address.
     *
     * Related value to the customerIdType
     * Max. length: 90 characters
     * The e-mail address of the customer
     *
     * @return string email
     */
    public function getEmail()
    {
        return $this->getParameter('email');
    }

    /**
     * Set the customer's email address.
     *
     * Related value to the customerIdType
     * Max. length: 90 characters
     * The e-mail address of the customer
     *
     * @param string $value email
     *
     * @return self
     */
    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    /**
     * Get the client merchant ID.
     *
     * A unique end customer identifier (the unique ID of the end customer as registered at the
     * merchant’s database)
     * NOTE: for security reasons do not use the customer‘s registered username, unless encrypted.
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
     * A unique end customer identifier (the unique ID of the end customer as registered at the
     * merchant’s database)
     * NOTE: for security reasons do not use the customer‘s registered username, unless encrypted.
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
     * Get UTC offset.
     *
     * The difference in hours and minutes from Coordinated Universal Time (UTC)
     * example: -03:00
     *
     * @return string UTC offset
     */
    public function getUtcOffset()
    {
        $value = $this->getParameter('utcOffset');

        return $value ? $value : '+00:00';
    }

    /**
     * Set UTC offset.
     *
     * The difference in hours and minutes from Coordinated Universal Time (UTC)
     * example: -03:00
     *
     * @param string $value UTC offset
     *
     * @return self
     */
    public function setUtcOffset($value)
    {
        return $this->setParameter('utcOffset', $value);
    }

    /**
     * Get the customer's first name.
     *
     * The first name of the payout customer
     * example: John
     * max. length. 40 characters
     *
     * @return string first name
     */
    public function getFirstName()
    {
        return $this->getParameter('firstName');
    }

    /**
     * Set the customer's first name.
     *
     * The first name of the payout customer
     * example: John
     * max. length. 40 characters
     *
     * @param string $value first name
     *
     * @return self
     */
    public function setFirstName($value)
    {
        return $this->setParameter('firstName', $value);
    }

    /**
     * Get the customer's last name.
     *
     * The last name of the payout customer
     * example: Do
     * max. length. 40 characters
     *
     * @return string last name
     */
    public function getLastName()
    {
        return $this->getParameter('lastName');
    }

    /**
     * Set the customer's last name.
     *
     * The last name of the payout customer
     * example: Do
     * max. length. 40 characters
     *
     * @param string $value last name
     *
     * @return self
     */
    public function setLastName($value)
    {
        return $this->setParameter('lastName', $value);
    }

    /**
     * Get the customers's birthday.
     *
     * The date of birth of the payout customer in YYYY-MM-DD format
     * example: 1979-12-20
     *
     * @param string $format
     *
     * @return string birthday
     */
    public function getBirthday($format = 'Y-m-d')
    {
        $value = $this->getParameter('birthday');

        return $value ? $value->format($format) : null;
    }

    /**
     * Set the customers's birthday.
     *
     * The date of birth of the payout customer in YYYY-MM-DD format
     * example: 1979-12-20
     *
     * @param string $value
     *
     * @return self
     */
    public function setBirthday($value)
    {
        $value = new \DateTime($value, new \DateTimeZone('UTC'));

        return $this->setParameter('birthday', $value);
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
            'email',
            'clientMerchantId',
            'firstName',
            'lastName',
            'birthday'
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

        $payout = $body->appendChild(
            $document->createElement('urn:payout')
        );

        $payout->appendChild(
            $document->createElement('urn:username', $this->getUsername())
        );

        $payout->appendChild(
            $document->createElement('urn:password', $this->getPassword())
        );

        $payout->appendChild(
            $document->createElement('urn:ptid', $this->getTransactionId())
        );

        $payout->appendChild(
            $document->createElement('urn:subId', $this->getSubId())
        );

        $payout->appendChild(
            $document->createElement('urn:amount', $this->getAmount())
        );

        $payout->appendChild(
            $document->createElement('urn:currency', $this->getCurrency())
        );

        $payout->appendChild(
            $document->createElement('urn:customerIdType', 'EMAIL')
        );

        $payout->appendChild(
            $document->createElement('urn:customerId', $this->getEmail())
        );

        $payout->appendChild(
            $document->createElement('urn:merchantClientId', $this->getClientMerchantId())
        );

        $payout->appendChild(
            $document->createElement('urn:validationOnly', $this->getValidationOnly())
        );

        $payout->appendChild(
            $document->createElement('urn:utcOffset', $this->getUtcOffset())
        );

        $customer = $payout->appendChild(
            $document->createElement('urn:customerDetailsBasic')
        );

        $customer->appendChild(
            $document->createElement('urn:firstName', $this->getFirstName())
        );

        $customer->appendChild(
            $document->createElement('urn:lastName', $this->getLastName())
        );

        $customer->appendChild(
            $document->createElement('urn:dateOfBirth', $this->getBirthday())
        );

        return $document->saveXML();
    }

    /**
     * Create a proper response based on the request.
     *
     * @param \SimpleXMLElement $xml
     *
     * @return PayoutResponse
     *
     * @throws InvalidResponseException
     */
    protected function createResponse(\SimpleXMLElement $xml)
    {
        return $this->response = new PayoutResponse($this, $xml);
    }
}
