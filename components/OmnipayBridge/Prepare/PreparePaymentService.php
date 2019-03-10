<?php

namespace yii\payum\components\OmnipayBridge\Prepare;


use Payum\Core\Request\Convert;

class PreparePaymentService
{
    /**
     * @var PaymentSystems
     */
    private $payments;

    public function convert(Convert $request)
    {
        return $this->payments
            ->getByGatewayName($request->getToken()->getGatewayName())
            ->convert($request);
    }

    public function getToken($parameters, $classToken)
    {
        return $this->payments
            ->getByRequestKeys($parameters)
            ->getToken($parameters, $classToken);
    }
}