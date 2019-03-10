<?php

namespace yii\payum\controllers;

use Throwable;
use Payum\Core\Payum;
use Payum\Core\Reply\HttpRedirect;
use Payum\Core\Reply\HttpResponse;
use Payum\Core\Reply\ReplyInterface;
use Payum\Core\Request\Authorize;
use Payum\Core\Request\Capture;
use Payum\Core\Request\Notify;
use Payum\Core\Request\Refund;
use Payum\Core\Security\TokenInterface;
use Yii;
use yii\web\Controller;

class PaymentController extends Controller
{
    const DISABLE_CSRF = [
        'capture' => true,
        'notify' => true
    ];

    public function runAction($id, $params = [])
    {
        if (isset(static::DISABLE_CSRF[$id])) {
            $this->enableCsrfValidation = false;
        }

        try {
            return parent::runAction($id, $params);
        } catch (\Throwable $exception) {
            return $this->handleException($exception);
        }
    }

    public function actionCapture($token = null)
    {
        $token = $this->getToken($token);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());
        $gateway->execute($capture = new Capture($token));
        $this->getPayum()->getHttpRequestVerifier()->invalidate($token);
        $this->redirect($token->getAfterUrl());
    }

    public function actionAuthorize()
    {
        $token = $this->getToken($token = null);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());
        $gateway->execute($capture = new Authorize($token));
        $this->getPayum()->getHttpRequestVerifier()->invalidate($token);
        $this->redirect($token->getAfterUrl());
    }

    public function actionNotify()
    {
        $token = $this->getToken($token = null);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());
        $gateway->execute($capture = new Notify($token));
    }

    public function actionRefund()
    {
        $token = $this->getToken($token = null);
        $this->getPayum()->getHttpRequestVerifier()->invalidate($token);
        $gateway = $this->getPayum()->getGateway($token->getGatewayName());
        $gateway->execute($capture = new Refund($token));
        $this->redirect($token->getAfterUrl());
    }

    public function handleException(Throwable $reply)
    {
        if (false == $reply instanceof ReplyInterface) {
            throw $reply;
        }

        if ($reply instanceof HttpRedirect) {
            return $this->redirect($reply->getUrl());
        } elseif ($reply instanceof HttpResponse) {
            $response = Yii::$app->response;
            foreach ($reply->getHeaders() as $name => $value) {
                $response->headers->set($name, $value);
            }

            $response->setStatusCode($reply->getStatusCode());
            echo $reply->getContent();

            return $response->send();
        }
        $ro = new \ReflectionObject($reply);
        throw new \LogicException(
            sprintf('Cannot convert reply %s to Yii response.', $ro->getShortName()),
            null,
            $reply
        );
    }

    protected function getToken(TokenInterface $token = null)
    {
        return $token ?? $this->getPayum()->getHttpRequestVerifier()->verify($_REQUEST);
    }

    /**
     * @return Payum
     */
    protected function getPayum()
    {
        return Yii::$app->payumModule()->getPayum();
    }
}