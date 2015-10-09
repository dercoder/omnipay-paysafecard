<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Exception\InvalidResponseException;
use Omnipay\Common\Message\AbstractResponse;

/**
 * Paysafecard Abstract Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
    protected $liveEndpoint = 'https://soa.paysafecard.com/psc/services/PscService';
    protected $testEndpoint = 'https://soatest.paysafecard.com/psc/services/PscService';

    /**
     * Get the method for this request.
     *
     * @return string method
     */
    abstract protected function getMethod();

    /**
     * Get the username.
     *
     * Custom account username provided by paysafecard for the authentication.
     *
     * @return string username
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the username.
     *
     * Custom account username provided by paysafecard for the authentication.
     *
     * @param string $value username
     *
     * @return self
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the password.
     *
     * Custom account password provided by paysafecard for the authentication.
     *
     * @return string password
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the password.
     *
     * Custom account password provided by paysafecard for the authentication.
     *
     * @param string $value password
     *
     * @return self
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * Get the sub ID.
     *
     * mandatory parameter, value must be left empty if nothing else is agreed.
     * so-called ‘reporting criteria’, offers the possibility to classify transactions.
     * max. length: 8 characters (case sensitive)
     * agreement with paysafecard needed
     * example: shop1
     *
     * @return string sub id
     */
    public function getSubId()
    {
        return $this->getParameter('subId');
    }

    /**
     * Set the sub ID.
     *
     * mandatory parameter, value must be left empty if nothing else is agreed.
     * so-called ‘reporting criteria’, offers the possibility to classify transactions.
     * max. length: 8 characters (case sensitive)
     * agreement with paysafecard needed
     * example: shop1
     *
     * @param string $value sub id
     *
     * @return self
     */
    public function setSubId($value)
    {
        return $this->setParameter('subId', $value);
    }

    /**
     * Get the endpoint for this request.
     *
     * @return string endpoint
     */
    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
    }

    /**
     * Send the request with specified data.
     *
     * @param mixed $data The data to send
     *
     * @throws InvalidResponseException
     *
     * @return AbstractResponse
     */
    public function sendData($data)
    {
        $endpoint = $this->getEndpoint();
        $headers = array(
            'Content-Type' => 'text/xml; charset=utf-8',
            'SOAPAction' => $endpoint.'/'.$this->getMethod(),
        );

        $httpResponse = $this->httpClient->post($endpoint, $headers, $data)->send();

        return $this->createResponse($httpResponse->xml());
    }

    /**
     * Get the response from request.
     *
     * @param \SimpleXMLElement $xml
     *
     * @return AbstractResponse
     */
    abstract protected function createResponse(\SimpleXMLElement $xml);
}
