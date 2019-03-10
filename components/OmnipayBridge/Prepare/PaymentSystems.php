<?php


namespace yii\payum\components\OmnipayBridge\Prepare;

use InvalidArgumentException;
use yii\payum\components\OmnipayBridge\Prepare\Systems\PreparePaymentInterface;

class PaymentSystems implements PaymentSystemsInterface
{
    const GATEWAY_NAME = 'gatewayName';
    const REQUEST = 'request';

    /**
     * @var array
     */
    private $byGatewayNames = [];
    private $byRequest = [];

    /**
     * PaymentSystems constructor.
     * @param PreparePaymentInterface[] $preparePayments
     */
    public function __construct($preparePayments)
    {
        $this->prepareData($preparePayments);
    }

    /**
     * {@inheritdoc}
     */
    public function getByGatewayName($gatewayName)
    {
        if (empty($this->byGatewayNames[$gatewayName])) {
            throw new InvalidArgumentException(sprintf('Key "%s" does not exist', $gatewayName));
        }

        return $this->byGatewayNames[$gatewayName];
    }

    /**
     * {@inheritdoc}
     */
    public function getByRequestKeys(array $request)
    {
        $key = $this->prepareRequestKeys($request);
        if (empty($this->byRequest[$key])) {
            throw new InvalidArgumentException(sprintf('Key "%s" does not exist', $key));
        }

        return $this->byRequest[$key];
    }

    private function prepareData($preparePayments)
    {
        foreach ($preparePayments as $preparePayment) {
            list($gatewayName, $requestKeys) = $this->getKeys($preparePayment);

            $this->byGatewayNames[$gatewayName] = $preparePayment;
            $this->byRequest[$requestKeys] = $preparePayment;
        }
    }

    private function getKeys(PreparePaymentInterface $preparePayment)
    {
        return [
            $preparePayment->getGatewayName(),
            $this->prepareRequestKeys($preparePayment->getRequestKeys())
        ];
    }

    private function prepareRequestKeys($requestKeys)
    {
        $request = array_keys($requestKeys);
        sort($request);

        return implode(',', $requestKeys);
    }
}