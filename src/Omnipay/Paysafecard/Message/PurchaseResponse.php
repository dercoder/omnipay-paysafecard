<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Message\RequestInterface;
use Omnipay\Common\Message\RedirectResponseInterface;
use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Purchase Response.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
class PurchaseResponse extends AbstractResponse implements RedirectResponseInterface
{
    protected $liveRedirect = 'https://customer.cc.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';
    protected $testRedirect = 'https://customer.test.at.paysafecard.com/psccustomer/GetCustomerPanelServlet';

    public function __construct(RequestInterface $request, \SimpleXMLElement $data)
    {
        $this->request = $request;
        $this->data = $data
            ->children('http://schemas.xmlsoap.org/soap/envelope/')
            ->children('urn:pscservice')
            ->createDispositionResponse
            ->createDispositionReturn;

        if (!$this->data) {
            throw new InvalidResponseException('Missing element in XML response');
        }
    }

    public function isSuccessful()
    {
        return false;
    }

    public function isRedirect()
    {
        return $this->getResultCode() === 0 && $this->getErrorCode() === 0;
    }

    public function getRedirectUrl()
    {
        $url = $this->request->getTestMode() ? $this->testRedirect : $this->liveRedirect;
        $data = array(
            'amount' => $this->request->getAmount(),
            'currency' => $this->request->getCurrency(),
            'mid' => $this->getMid(),
            'mtid' => $this->getTransactionId(),
        );

        if ($language = $this->request->getLanguage()) {
            $data['language'] = $language;
        }

        if ($locale = $this->request->getLocale()) {
            $data['locale'] = $locale;
        }

        return $url.'?'.http_build_query($data, '', '&');
    }

    public function getRedirectMethod()
    {
        return 'GET';
    }

    public function getRedirectData()
    {
        return;
    }

    public function getSubId()
    {
        return (string) $this->data->subId;
    }

    public function getMid()
    {
        return (int) $this->data->mid;
    }

    public function getTransactionId()
    {
        return (string) $this->data->mtid;
    }
}
