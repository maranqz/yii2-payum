<?php

namespace yii\payum\components\Core\Action;


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

    private $api;

    /**
     * {@inheritdoc}
     */
    public function setDecorated(ActionInterface $decorated)
    {
        $this->decorated = $decorated;

        return $this;
    }

    public function setApi($api)
    {
        $decorated = $this->getDecorated();
        if ($decorated instanceof ApiAwareInterface) {
            $decorated->setApi($api);
        }
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
    }
}