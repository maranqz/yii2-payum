<?php


namespace yii\payum\components\Core\Action;

use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\BaseGetStatus;
use Payum\Core\Request\GetHumanStatus;
use yii\payum\components\Core\Model\PaymentStatusInterface;

class UpdateStatusDecorator implements ActionInterface, DecoratedAwareInterface, GatewayAwareInterface
{
    const BASE_STATUS_CLASS = BaseGetStatus::class;

    use GatewayAwareTrait;
    use DecoratedTrait;

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

    public function execute($request)
    {
        try {
            $this->getDecorated()->execute($request);
        } finally {
            $model = $request->getModel();
            if ($model instanceof PaymentStatusInterface) {
                $this->gateway->execute($status = new ($this->statusClass)($model));
                $model->setStatus($status);
            }
        }
    }

    public function supports($request)
    {
        return $request->getModel() instanceof PaymentStatusInterface &&
            $this->decorated->supports($request);
    }
}