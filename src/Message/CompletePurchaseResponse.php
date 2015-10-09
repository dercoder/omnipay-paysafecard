<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Complete Purchase Response.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
class CompletePurchaseResponse extends AbstractResponse
{
    public function __construct(RequestInterface $request, \SimpleXMLElement $data)
    {
        $this->request = $request;
        $this->data = $data
            ->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->children('urn:pscservice')
            ->executeDebitResponse
            ->executeDebitReturn;

        if (!$this->data) {
            throw new InvalidResponseException('Missing element in XML response');
        }
    }

    public function isSuccessful()
    {
        return $this->getErrorCode() === 0 && $this->getResultCode() === 0;
    }

    public function getCode()
    {
        return $this->getErrorCode();
    }

    public function getTransactionId()
    {
        return (string) $this->data->mtid;
    }

    public function getSubId()
    {
        return (string) $this->data->subId;
    }
}
