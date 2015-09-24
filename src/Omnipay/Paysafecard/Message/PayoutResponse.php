<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Purchase Response.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.4 Paysafecard Payout API Specification
 */
class PayoutResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, \SimpleXMLElement $data)
    {
        $this->request = $request;
        $this->data = $data
            ->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->children('urn:pscservice')
            ->payoutResponse
            ->PayoutReturn;

        if (!$this->data) {
            throw new InvalidResponseException('Missing element in XML response');
        }
    }

    public function isSuccessful()
    {
        return $this->getErrorCode() === 0 && $this->getResultCode() === 0 && $this->getValidationOnly() === false;
    }

    public function getCode()
    {
        return $this->getErrorCode();
    }

    public function getMessage()
    {
        $message = (string) $this->data->errorCodeDescription;

        return $message ? $message : parent::getMessage();
    }

    public function getTransactionId()
    {
        return (string) $this->data->ptid;
    }

    public function getCurrency()
    {
        return (string) $this->data->requestedCurrency;
    }

    public function getAmount()
    {
        return (string) $this->data->requestedAmount;
    }

    public function getValidationOnly()
    {
        $validationOnly = (string) $this->data->validationOnly;

        return $validationOnly === 'false' ? false : true;
    }
}
