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
    protected $fetchTransaction;

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
        if ($transactionId = $this->httpRequest->query->get('mtid')) {
            $this->setTransactionId($transactionId);
        }

        if ($currency = $this->httpRequest->query->get('currency')) {
            $this->setCurrency($currency);
        }

        if ($amount = $this->httpRequest->query->get('amount')) {
            $this->setAmount($amount);
        }

        if ($subId = $this->httpRequest->query->get('subid')) {
            $this->setSubId($subId);
        }

        $this->validate(
            'username',
            'password',
            'transactionId',
            'currency',
            'amount'
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
            $document->createElement('urn:mtid', $this->getTransactionId())
        );

        $debit->appendChild(
            $document->createElement('urn:subId', $this->getSubId())
        );

        $debit->appendChild(
            $document->createElement('urn:amount', $this->getAmount())
        );

        $debit->appendChild(
            $document->createElement('urn:currency', $this->getCurrency())
        );

        $debit->appendChild(
            $document->createElement('urn:close', '1')
        );

        return $document->saveXML();
    }

    /**
     * {@inheritdoc}
     */
    public function sendData($data)
    {
        $fetchTransaction = new FetchTransactionRequest($this->httpClient, $this->httpRequest);

        $response = $fetchTransaction->initialize(array(
            'testMode' => $this->getTestMode(),
            'username' => $this->getUsername(),
            'password' => $this->getPassword(),
            'subId' => $this->getSubId(),
            'transactionId' => $this->getTransactionId(),
            'currency' => $this->getCurrency(),
            'amount' => $this->getAmount(),
        ))->send();

        if ($response->getDispositionState() !== 'S') {
            return $response;
        }

        return parent::sendData($data);
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
