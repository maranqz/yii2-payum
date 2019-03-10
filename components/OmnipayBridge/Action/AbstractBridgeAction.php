<?php
/**
 *
 */

namespace yii\payum\components\OmnipayBridge\Action;


use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayInterface;
use Payum\Core\Request\Generic;
use Payum\OmnipayBridge\Action\BaseApiAwareAction;
use yii\payum\components\OmnipayBridge\Prepare\PaymentSystemsInterface;

/**
 * @method execute(Generic $request);
 */
abstract class AbstractBridgeAction extends BaseApiAwareAction implements ActionInterface, GatewayAwareInterface
{
    public function __construct(PaymentSystemsInterface $paymentSystems)
    {
        $this->setPaymentSystems($paymentSystems);
    }

    /**
     * @var GatewayInterface
     */
    private $gateway;

    /**
     * @var PaymentSystemsInterface
     */
    private $paymentSystems;

    /**
     * {@inheritDoc}
     */
    public function setGateway(GatewayInterface $gateway)
    {
        $this->gateway = $gateway;

        return $this;
    }

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