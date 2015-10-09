<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Fetch Transaction Response.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
class FetchTransactionResponse extends AbstractResponse
{
    private $messages = array(
        'O' => 'Consumed',
        'R' => 'Created',
        'S' => 'Disposed',
        'L' => 'Cancelled',
        'X' => 'Expired',
        'D' => 'Debited',
    );

    public function __construct(RequestInterface $request, \SimpleXMLElement $data)
    {
        $this->request = $request;
        $this->data = $data
            ->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->children('urn:pscservice')
            ->getSerialNumbersResponse
            ->getSerialNumbersReturn;

        if (!$this->data) {
            throw new InvalidResponseException('Missing element in XML response');
        }
    }

    public function isSuccessful()
    {
        return $this->getErrorCode() === 0 && $this->getResultCode() === 0 && $this->getDispositionState() === 'O';
    }

    public function getCode()
    {
        return $this->getErrorCode();
    }

    public function getMessage()
    {
        if ($message = parent::getMessage()) {
            return $message;
        }

        $state = $this->getDispositionState();

        if ($state && isset($this->messages[$state])) {
            return $this->messages[$state];
        }

        return;
    }

    public function getTransactionId()
    {
        return (string) $this->data->mtid;
    }

    public function getAmount()
    {
        return (string) $this->data->amount;
    }

    public function getCurrency()
    {
        return (string) $this->data->currency;
    }

    public function getSubId()
    {
        return (string) $this->data->subId;
    }

    public function getDispositionState()
    {
        return (string) $this->data->dispositionState;
    }

    public function getSerialNumbers()
    {
        return (string) $this->data->serialNumbers;
    }
}
