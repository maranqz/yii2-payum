<?php

namespace yii\payum\components\OmnipayBridge\Prepare\Systems;


use Payum\Core\Request\Convert;
use Payum\Core\Security\GenericTokenFactoryAwareInterface;
use Payum\Core\Security\TokenInterface;
use Payum\Core\Storage\StorageInterface;

interface PreparePaymentInterface extends GenericTokenFactoryAwareInterface
{
    /**
     * @param StorageInterface $paymentStorage
     * @return $this
     */
    public function setPaymentStorage(StorageInterface $paymentStorage);

    /**
     * @param Convert $request
     * @return Convert $request
     */
    public function convert(Convert $request);

    /**
     * @param array $request
     * @param string $classToken
     * @return TokenInterface
     */
    public function getTokenHash(array $request, $classToken);

    public function getGatewayName();

    public function getRequestKeys();
}