<?php

namespace Site\MainBundle\Entity;

interface PaymentInterface
{
    // Payment discount.
    const DISCOUNT_NONE    = 0;
    const DISCOUNT_25      = 1;
    const DISCOUNT_50      = 2;

    /**
     * Get amount
     *
     * @return integer
     */
    public function getAmount();

    /**
     * Set amount
     *
     * @param $amount
     * @return PaymentInterface
     */
    public function setAmount($amount);
}
