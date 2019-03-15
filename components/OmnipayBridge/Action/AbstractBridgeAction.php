<?php
/**
 *
 */

namespace yii\payum\components\OmnipayBridge\Action;


use Payum\Core\Action\ActionInterface;
use Payum\Core\GatewayAwareInterface;
use Payum\Core\GatewayAwareTrait;
use Payum\Core\Request\Generic;
use Payum\OmnipayBridge\Action\BaseApiAwareAction;

/**
 * @method execute(Generic $request);
 */
abstract class AbstractBridgeAction extends BaseApiAwareAction implements ActionInterface, GatewayAwareInterface
{
    use GatewayAwareTrait;
}