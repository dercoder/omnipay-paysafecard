<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Complete Purchase Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
class CompletePurchaseRequest extends AbstractRequest
{
    /**
     * {@inheritdoc}
     */
    protected function getMethod()
    {
        return 'executeDebit';
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
            'password'
        );

        $transactionId = $this->httpRequest->query->get('mtid');
        $currency = $this->httpRequest->query->get('currency');
        $amount = $this->httpRequest->query->get('amount');
        $subId = $this->httpRequest->query->get('subid', '');

        if (!$transactionId || !$currency || !$amount) {
            throw new InvalidRequestException('Missing query parameter');
        }

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

        $debit = $body->appendChild(
            $document->createElement('urn:executeDebit')
        );

        $debit->appendChild(
            $document->createElement('urn:username', $this->getUsername())
        );

        $debit->appendChild(
            $document->createElement('urn:password', $this->getPassword())
        );

        $debit->appendChild(
            $document->createElement('urn:mtid', $transactionId)
        );

        $debit->appendChild(
            $document->createElement('urn:subId', $subId)
        );

        $debit->appendChild(
            $document->createElement('urn:amount', $amount)
        );

        $debit->appendChild(
            $document->createElement('urn:currency', $currency)
        );

        $debit->appendChild(
            $document->createElement('urn:close', '1')
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
        return $this->response = new CompletePurchaseResponse($this, $xml);
    }
}
