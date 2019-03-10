<?php
/**
 *
 */

namespace yii\payum\components\OmnipayBridge\Prepare;


use yii\payum\components\OmnipayBridge\Prepare\Systems\PreparePaymentInterface;

interface PaymentSystemsInterface
{
    /**
     * @param string $gatewayName
     * @return PreparePaymentInterface
     */
    public function getByGatewayName($gatewayName);

    /**
     * @param array $request
     * @return PreparePaymentInterface
     */
    public function getByRequestKeys(array $request);
}