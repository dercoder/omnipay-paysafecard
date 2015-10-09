<?php

namespace Omnipay\Paysafecard\Message;

/**
 * Paysafecard Purchase Response.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.4 Paysafecard Payout API Specification
 */
class ValidatePayoutResponse extends PayoutResponse
{
    public function isSuccessful()
    {
        return $this->getErrorCode() === 0 && $this->getResultCode() === 0 && $this->getValidationOnly() === true;
    }
}
