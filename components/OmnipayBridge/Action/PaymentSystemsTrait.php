<?php


namespace yii\payum\components\OmnipayBridge\Action;


use yii\payum\components\OmnipayBridge\Prepare\PaymentSystemsInterface;

trait PaymentSystemsTrait
{
    /**
     * @var PaymentSystemsInterface
     */
    private $paymentSystems;

    /**
     * {@inheritDoc}
     */
    public function setPaymentSystems(PaymentSystemsInterface $paymentSystems)
    {
        $this->paymentSystems = $paymentSystems;

        return $this;
    }

    protected function getPaymentSystems()
    {
        return $this->paymentSystems;
    }
}