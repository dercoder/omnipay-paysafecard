<?php

namespace Omnipay\Paysafecard\Message;

/**
 * Paysafecard Abstract Response.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.1 Paysafecard API Specification
 */
abstract class AbstractResponse extends \Omnipay\Common\Message\AbstractResponse
{
    public function getResultCode()
    {
        return (int) $this->data->resultCode;
    }

    public function getErrorCode()
    {
        return (int) $this->data->errorCode;
    }

    public function getCode()
    {
        return $this->getErrorCode();
    }

    public function getMessage()
    {
        switch ($this->getResultCode()) {
            case 0:
                return;
            case 1:
                return 'Logical problem';
            case 2:
                return 'Technical problem';
            default:
                return 'Unknown problem';
        }
    }
}
