<?php

namespace yii\payum\components\OmnipayBridge\Action;

use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\Convert;
use Payum\Core\Request\GetHttpRequest;
use Payum\Core\Request\GetToken;
use yii\payum\components\OmnipayBridge\Prepare\PaymentSystemsInterface;
use yii\payum\components\OmnipayBridge\Request\GetTokenHash;
use Payum\Core\Request\Notify;

class NotifyNullAction extends AbstractBridgeAction implements PaymentSystemsAwareInterface
{
    use PaymentSystemsTrait;

    public function __construct(PaymentSystemsInterface $paymentSystems)
    {
        $this->setPaymentSystems($paymentSystems);
    }

    /**
     * {@inheritDoc}
     *
     * @param Convert $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        $hash = $this->gateway->execute($getToken = new GetTokenHash(Notify::class));
        $this->gateway->execute($getToken = new GetToken($hash));
        $this->gateway->execute(new Notify($getToken->getToken()));
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof Notify &&
            null === $request->getModel();
    }
}
