<?php


namespace yii\payum\components\Core\Model;


use Payum\Core\Request\BaseGetStatus;

interface PaymentStatusAwareInterface
{
    public function setStatus(BaseGetStatus $status);
}