<?php

namespace Omnipay\Paysafecard;

use Omnipay\Common\AbstractGateway;

/**
 * Paysafecard Gateway.
 *
 * @author    Alexander Fedra <contact@dercoder.at>
 * @copyright 2015 DerCoder
 * @license   http://opensource.org/licenses/mit-license.php MIT
 */
class Gateway extends AbstractGateway
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'Paysafecard';
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultParameters()
    {
        return array(
            'username' => '',
            'password' => '',
            'testMode' => false,
        );
    }

    /**
     * Get the username.
     *
     * Custom account username provided by paysafecard for the authentication.
     *
     * @return string username
     */
    public function getUsername()
    {
        return $this->getParameter('username');
    }

    /**
     * Set the username.
     *
     * Custom account username provided by paysafecard for the authentication.
     *
     * @param string $value username
     *
     * @return self
     */
    public function setUsername($value)
    {
        return $this->setParameter('username', $value);
    }

    /**
     * Get the password.
     *
     * Custom account password provided by paysafecard for the authentication.
     *
     * @return string password
     */
    public function getPassword()
    {
        return $this->getParameter('password');
    }

    /**
     * Set the password.
     *
     * Custom account password provided by paysafecard for the authentication.
     *
     * @param string $value password
     *
     * @return self
     */
    public function setPassword($value)
    {
        return $this->setParameter('password', $value);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Paysafecard\Message\PurchaseRequest
     */
    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paysafecard\Message\PurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Paysafecard\Message\CompletePurchaseRequest
     */
    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paysafecard\Message\CompletePurchaseRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Paysafecard\Message\PayoutRequest
     */
    public function payout(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paysafecard\Message\PayoutRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Paysafecard\Message\ValidatePayoutRequest
     */
    public function validatePayout(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paysafecard\Message\ValidatePayoutRequest', $parameters);
    }

    /**
     * @param array $parameters
     *
     * @return \Omnipay\Paysafecard\Message\FetchTransactionRequest
     */
    public function fetchTransaction(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Paysafecard\Message\FetchTransactionRequest', $parameters);
    }
}
