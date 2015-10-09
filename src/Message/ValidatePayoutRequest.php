<?php

namespace Omnipay\Paysafecard\Message;

use Omnipay\Common\Exception\InvalidResponseException;

/**
 * Paysafecard Payout Request.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 *
 * @version   1.4 Paysafecard Payout API Specification
 */
class ValidatePayoutRequest extends PayoutRequest
{
    public function getValidationOnly()
    {
        return 'true';
    }

    /**
     * Create a proper response based on the request.
     *
     * @param \SimpleXMLElement $xml
     *
     * @return ValidatePayoutResponse
     *
     * @throws InvalidResponseException
     */
    protected function createResponse(\SimpleXMLElement $xml)
    {
        return $this->response = new ValidatePayoutResponse($this, $xml);
    }
}
