<?php


namespace yii\payum\components\OmnipayBridge\Action;


use yii\payum\components\OmnipayBridge\Prepare\PaymentSystemsInterface;

interface PaymentSystemsAwareInterface
{
    public function setPaymentSystems(PaymentSystemsInterface $paymentSystems);
}