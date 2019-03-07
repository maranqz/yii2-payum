<?php
/**
 *
 */

namespace yii\payum\models;


use Payum\Core\Model\PaymentInterface;

interface PayumPaymentInterface extends PaymentInterface
{
    /**
     * @return mixed
     */
    public function getId();
}