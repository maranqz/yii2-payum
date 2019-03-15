<?php

namespace yii\payum\components\Core\Action;


use Payum\Core\Action\ActionInterface;

interface DecoratedAwareInterface
{
    /**
     * @param ActionInterface $decorated
     * @return mixed
     */
    public function setDecorated(ActionInterface $decorated);
}