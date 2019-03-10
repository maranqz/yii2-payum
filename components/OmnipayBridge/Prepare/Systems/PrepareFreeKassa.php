<?php
/**
 *
 */

namespace yii\payum\components\OmnipayBridge\Prepare\Systems;


use Payum\Core\Model\PaymentInterface;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Convert;
use Payum\Core\Request\Notify;
use Payum\Core\Security\GenericTokenFactoryInterface;
use Payum\Core\Security\TokenInterface;
use Payum\Core\Storage\StorageInterface;

class PrepareFreeKassa implements PreparePaymentInterface
{
    const CLASS_TO_NAME_TOKEN = [
        Capture::class => 'return',
        Notify::class => 'notify',
    ];

    /**
     * @var StorageInterface
     */
    private $paymentStorage;

    /**
     * @var GenericTokenFactoryInterface
     */
    private $tokenFactory;

    /**
     * @param GenericTokenFactoryInterface $genericTokenFactory
     *
     * @return void
     */
    public function setGenericTokenFactory(GenericTokenFactoryInterface $genericTokenFactory = null)
    {
        $this->tokenFactory = $genericTokenFactory;
    }

    public function setPaymentStorage(StorageInterface $paymentStorage)
    {
        $this->paymentStorage = $paymentStorage;

        return $this->paymentStorage;
    }

    public function getGatewayName()
    {
        return 'omnipay_freeKassa';
    }

    public function getRequestKeys()
    {
        return [
            'MERCHANT_ID',
            'AMOUNT',
            'intid',
            'MERCHANT_ORDER_ID',
            'P_EMAIL',
            'CUR_ID',
            'SIGN'
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function convert(Convert $request)
    {
        $payment = $request->getSource();
        $details = $payment->getDetails();

        $details = $this->setTokens($details, $this->generateTokens($request->getToken()));
        $details['transactionId'] = $payment->getNumber();

        $request->setResult((array)$details);
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenHash(array $request, $classToken)
    {
        /** @var PaymentInterface $payment */
        $payment = $this->paymentStorage->find($request['MERCHANT_ORDER_ID']);
        $details = $payment->getDetails();

        $tokenName = $this->getTokenName($classToken);

        return $details[$tokenName . 'Hash'];
    }

    /**
     * @param $details
     * @param TokenInterface[] $tokens
     * @return array
     */
    protected function setTokens($details, $tokens)
    {
        list($returnToken, $cancelToken, $notifyToken) = $tokens;

        $details['returnUrl'] = $returnToken->getTargetUrl();
        $details['returnHash'] = $returnToken->getHash();
        $details['cancelUrl'] = $cancelToken->getTargetUrl();
        $details['cancelHash'] = $cancelToken->getHash();
        $details['notifyUrl'] = $notifyToken->getTargetUrl();
        $details['notifyHash'] = $notifyToken->getHash();
        $details['doneUrl'] = $returnToken->getAfterUrl();
        $details['doneHash'] = $returnToken->getHash();

        return $details;
    }

    private function getTokenName($classToken)
    {
        if (isset(static::CLASS_TO_NAME_TOKEN[$classToken])) {
            $result = static::CLASS_TO_NAME_TOKEN[$classToken];
        } else {
            $result = $classToken;
        }

        return $result;
    }

    private function generateTokens(TokenInterface $captureToken)
    {
        return [
            // return
            $captureToken,
            // cancel
            $captureToken,
            // notify
            $this->tokenFactory->createNotifyToken(
                $captureToken->getGatewayName(),
                $captureToken->getDetails()
            )
        ];
    }
}