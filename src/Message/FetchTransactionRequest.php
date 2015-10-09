<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Fetch Transaction Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
class FetchTransactionRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function getMethod()
    {
        return 'getSerialNumbers';
    }

    /**
     * Get the data for this request.
     *
     * @throws InvalidRequestException
     *
     * @return string request data
     */
    public function getData()
    {
        $this->validate(
            'username',
            'password',
            'transactionId',
            'currency'
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

        $serialNumbers = $body->appendChild(
            $document->createElement('urn:getSerialNumbers')
        );

        $serialNumbers->appendChild(
            $document->createElement('urn:username', $this->getUsername())
        );

        $serialNumbers->appendChild(
            $document->createElement('urn:password', $this->getPassword())
        );

        $serialNumbers->appendChild(
            $document->createElement('urn:mtid', $this->getTransactionId())
        );

        $serialNumbers->appendChild(
            $document->createElement('urn:subId', $this->getSubId())
        );

        $serialNumbers->appendChild(
            $document->createElement('urn:currency', $this->getCurrency())
        );

        return $document->saveXML();
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
        return new FetchTransactionResponse($this, $xml);
    }
}
