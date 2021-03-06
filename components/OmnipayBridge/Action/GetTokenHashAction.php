<?php

namespace yii\payum\components\OmnipayBridge\Action;

use Payum\Core\Exception\RequestNotSupportedException;
use Payum\Core\Request\GetHttpRequest;
use yii\payum\components\OmnipayBridge\Prepare\PaymentSystemsInterface;
use yii\payum\components\OmnipayBridge\Request\GetTokenHash;

class GetTokenHashAction extends AbstractBridgeAction implements PaymentSystemsAwareInterface
{
    use PaymentSystemsTrait;

    public function __construct(PaymentSystemsInterface $paymentSystems)
    {
        $this->setPaymentSystems($paymentSystems);
    }

    /**
     * {@inheritDoc}
     *
     * @param GetTokenHash $request
     */
    public function execute($request)
    {
        RequestNotSupportedException::assertSupports($this, $request);

        $this->gateway->execute($httpRequest = new GetHttpRequest());

        $hash = $this->getPaymentSystems()
            ->getByRequestKeys(array_keys($httpRequest->request))
            ->getTokenHash($httpRequest->request, $request->getRequestClass());

        $request->setHash($hash);

        return $hash;
    }

    /**
     * {@inheritDoc}
     */
    public function supports($request)
    {
        return
            $request instanceof GetTokenHash &&
            null === $request->getHash();
    }
}
