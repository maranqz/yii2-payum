<?php
/**
 *
 */

namespace yii\payum\components\OmnipayBridge\Action;


use Payum\Core\Action\ActionInterface;
use Payum\Core\ApiAwareInterface;
use Payum\Core\Exception\UnsupportedApiException;
use Payum\Core\GatewayAwareInterface;
use LogicException;

trait DecoratedTrait
{
    /**
     * @var ActionInterface
     */
    private $decorated;

    /**
     * {@inheritdoc}
     */
    public function setDecorated(ActionInterface $decorated)
    {
        $this->decorated = $decorated;

        return $this;
    }

    /**
     * @return ActionInterface
     */
    protected function getDecorated()
    {
        $this->prepareAction($this->decorated);

        return $this->decorated;
    }

    protected function prepareAction(ActionInterface $action)
    {
        if ($action instanceof GatewayAwareInterface) {
            $action->setGateway($this->gateway);
        }

        if ($action instanceof ApiAwareInterface) {
            $apiSet = false;
            $unsupportedException = null;
            try {
                $action->setApi($this->omnipayGateway);
                $apiSet = true;
            } catch (UnsupportedApiException $e) {
                $unsupportedException = $e;
            }

            if (false == $apiSet) {
                throw new LogicException(sprintf('Cannot find right api for the action %s', get_class($action)), null, $unsupportedException);
            }
        }
    }
}