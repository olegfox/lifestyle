<?php

namespace Site\MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

/**
 * PaymentCancel
 *
 * @ORM\Table(name="payments_cancel")
 * @ORM\Entity
 */
class PaymentCancel
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Payment")
     * @ORM\JoinColumn(name="payment_id", referencedColumnName="id")
     **/
    protected $payment;

    /**
     * TerminalKey
     *
     * Идентификатор терминала, выдается Продавцу Банком.
     *
     * @var string
     *
     * @ORM\Column(name="terminal_key", type="string", length=20, nullable=true)
     */
    protected $terminalKey;

    /**
     * Success
     *
     * Успешность операции
     *
     * @var boolean
     *
     * @ORM\Column(name="success", type="boolean", nullable=true)
     */
    protected $success = false;

    /**
     * Status
     *
     * Статус транзакции.
     *
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=20, nullable=true)
     */
    protected $status;

    /**
     * PaymentId
     *
     * Уникальный идентификатор транзакции в системе Банка.
     *
     * @var integer
     *
     * @ORM\Column(name="payment_id", type="integer", nullable=true)
     */
    protected $paymentId;

    /**
     * ErrorCode
     *
     * Код ошибки, «0» если успешно.
     *
     * @var string
     *
     * @ORM\Column(name="error_code", type="string", length=20, nullable=true)
     */
    protected $errorCode;

    /**
     * OrderId
     *
     * Номер заказа в системе Продавца.
     *
     * @var string
     *
     * @ORM\Column(name="order_id", type="string", length=20, nullable=true)
     */
    protected $orderId;

    /**
     * OriginalAmount
     *
     * Сумма в копейках до операции отмены.
     *
     * @var integer
     *
     * @ORM\Column(name="original_amount", type="integer", nullable=true)
     */
    protected $originalAmount;

    /**
     * NewAmount
     *
     * Сумма в копейках после операции отмены.
     *
     * @var integer
     *
     * @ORM\Column(name="new_amount", type="integer", nullable=true)
     */
    protected $newAmount;

    /**
     * Message
     *
     * Краткое описание ошибки
     *
     * @var string
     *
     * @ORM\Column(name="message", type="string", nullable=true)
     */
    protected $message;

    /**
     * Details
     *
     * Подробное описание ошибки
     *
     * @var string
     *
     * @ORM\Column(name="details", type="string", nullable=true)
     */
    protected $details;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set terminalKey
     *
     * @param string $terminalKey
     * @return PaymentCancel
     */
    public function setTerminalKey($terminalKey)
    {
        $this->terminalKey = $terminalKey;

        return $this;
    }

    /**
     * Get terminalKey
     *
     * @return string 
     */
    public function getTerminalKey()
    {
        return $this->terminalKey;
    }

    /**
     * Set success
     *
     * @param boolean $success
     * @return PaymentCancel
     */
    public function setSuccess($success)
    {
        $this->success = $success;

        return $this;
    }

    /**
     * Get success
     *
     * @return boolean 
     */
    public function getSuccess()
    {
        return $this->success;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return PaymentCancel
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set paymentId
     *
     * @param integer $paymentId
     * @return PaymentCancel
     */
    public function setPaymentId($paymentId)
    {
        $this->paymentId = $paymentId;

        return $this;
    }

    /**
     * Get paymentId
     *
     * @return integer 
     */
    public function getPaymentId()
    {
        return $this->paymentId;
    }

    /**
     * Set errorCode
     *
     * @param string $errorCode
     * @return PaymentCancel
     */
    public function setErrorCode($errorCode)
    {
        $this->errorCode = $errorCode;

        return $this;
    }

    /**
     * Get errorCode
     *
     * @return string 
     */
    public function getErrorCode()
    {
        return $this->errorCode;
    }

    /**
     * Set orderId
     *
     * @param string $orderId
     * @return PaymentCancel
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get orderId
     *
     * @return string 
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set originalAmount
     *
     * @param integer $originalAmount
     * @return PaymentCancel
     */
    public function setOriginalAmount($originalAmount)
    {
        $this->originalAmount = $originalAmount;

        return $this;
    }

    /**
     * Get originalAmount
     *
     * @return integer 
     */
    public function getOriginalAmount()
    {
        return $this->originalAmount;
    }

    /**
     * Set newAmount
     *
     * @param integer $newAmount
     * @return PaymentCancel
     */
    public function setNewAmount($newAmount)
    {
        $this->newAmount = $newAmount;

        return $this;
    }

    /**
     * Get newAmount
     *
     * @return integer 
     */
    public function getNewAmount()
    {
        return $this->newAmount;
    }

    /**
     * Set message
     *
     * @param string $message
     * @return PaymentCancel
     */
    public function setMessage($message)
    {
        $this->message = $message;

        return $this;
    }

    /**
     * Get message
     *
     * @return string 
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * Set details
     *
     * @param string $details
     * @return PaymentCancel
     */
    public function setDetails($details)
    {
        $this->details = $details;

        return $this;
    }

    /**
     * Get details
     *
     * @return string 
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set payment
     *
     * @param \Site\MainBundle\Entity\Payment $payment
     * @return PaymentCancel
     */
    public function setPayment(\Site\MainBundle\Entity\Payment $payment = null)
    {
        $this->payment = $payment;

        return $this;
    }

    /**
     * Get payment
     *
     * @return \Site\MainBundle\Entity\Payment 
     */
    public function getPayment()
    {
        return $this->payment;
    }
}
