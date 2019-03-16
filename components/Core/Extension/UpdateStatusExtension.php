<?php


namespace yii\payum\components\Core\Extension;

use Payum\Core\Extension\Context;
use Payum\Core\Extension\ExtensionInterface;
use Payum\Core\Request\BaseGetStatus;
use Payum\Core\Request\Generic;
use Payum\Core\Request\GetHumanStatus;
use Payum\Core\Request\GetStatusInterface;
use Payum\Core\Request\Notify;
use yii\payum\components\Core\Model\PaymentStatusAwareInterface;

class UpdateStatusExtension implements ExtensionInterface
{
    const BASE_STATUS_CLASS = BaseGetStatus::class;

    private $statusClass = GetHumanStatus::class;

    public function __construct($statusClass = null)
    {
        if (isset($statusClass)) {
            if (!is_a($statusClass, self::BASE_STATUS_CLASS, true)) {
                throw new \InvalidArgumentException(sprintf(
                    'Class "%s" is not instance of "%s"',
                    $statusClass,
                    self::BASE_STATUS_CLASS
                ));
            }

            $this->statusClass = $statusClass;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function onPreExecute(Context $context)
    {
    }

    /**
     * {@inheritDoc}
     */
    public function onExecute(Context $context)
    {
        // TODO: Implement onExecute() method.
    }

    /**
     * {@inheritDoc}
     */
    public function onPostExecute(Context $context)
    {
        $previousStack = $context->getPrevious();
        $previousStackSize = count($previousStack);
        if ($previousStackSize > 1) {
            return;
        }
        if ($previousStackSize === 1) {
            $previousActionClassName = get_class($previousStack[0]->getAction());
            if (false === stripos($previousActionClassName, 'NotifyNullAction')) {
                return;
            }
        }
        /** @var Generic $request */
        $request = $context->getRequest();
        if (false === $request instanceof Generic) {
            return;
        }
        if (false === $request instanceof GetStatusInterface && false === $request instanceof Notify) {
            return;
        }
        /** @var PaymentStatusAwareInterface $payment */
        $payment = $request->getFirstModel();
        if (false === $payment instanceof PaymentStatusAwareInterface) {
            return;
        }

        $context->getGateway()->execute($status = new $this->statusClass($payment));
        $payment->setStatus($status);
    }
}