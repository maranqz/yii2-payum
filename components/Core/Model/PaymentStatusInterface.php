<?php


namespace yii\payum\components\Core\Model;


use Payum\Core\Request\BaseGetStatus;

interface PaymentStatusInterface
{
    public function setStatus(BaseGetStatus $status);

    /**
     * @return BaseGetStatus
     */
    public function getStatus();
}