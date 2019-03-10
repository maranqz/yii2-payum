<?php

namespace yii\payum\components\OmnipayBridge\Prepare;


interface PaymentSystemsAwareInterface
{
    /**
     * @param PaymentSystemsInterface $paymentSystems
     * @return $this
     */
    public function setPaymentSystems(PaymentSystemsInterface $paymentSystems);
}