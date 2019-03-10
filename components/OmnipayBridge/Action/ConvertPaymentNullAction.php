<?php

namespace yii\payum\components\OmnipayBridge\Action;

use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetHttpRequest;

class ConvertPaymentNullAction extends AbstractBridgeAction implements DecoratedAwareInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;
    use DecoratedTrait;

    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->getDecorated()->execute($request);

        /** @var PaymentInterface $payment */
        $payment = $request->getSource();

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        $details = $request->getResult();

        if (false === empty($httpRequest->query['redirect'])) {
            $details['_completeCaptureRequired'] = 0;
        }
        $payment->setDetails($details);

        $this->getPaymentSystems()->getByGatewayName($request->getToken()->getGatewayName())->convert($request);
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Convert &&
            'array' == $request->getTo() &&
            $request->getSource() instanceof PaymentInterface;
    }
}
