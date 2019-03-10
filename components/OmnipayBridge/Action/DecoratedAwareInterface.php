<?php
/**
 *
 */

namespace yii\payum\components\OmnipayBridge\Action;


use Payum\Core\Action\ActionInterface;

interface DecoratedAwareInterface
{
    /**
     * @param ActionInterface $decorated
     * @return mixed
     */
    public function setDecorated(ActionInterface $decorated);
}