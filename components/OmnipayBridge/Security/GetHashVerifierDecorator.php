<?php

namespace yii\payum\components\OmnipayBridge\Security;

use Payum\Core\Request\Capture;
use Payum\Core\Security\HttpRequestVerifierInterface;
use Payum\Core\Security\TokenInterface;
use InvalidArgumentException;
use yii\payum\components\OmnipayBridge\Prepare\PaymentSystemsInterface;

class GetHashVerifierDecorator implements HttpRequestVerifierInterface
{
    /**
     * @var HttpRequestVerifierInterface
     */
    private $decorated;

    /**
     * @var string
     */
    private $tokenParameter;

    /**
     * @var PaymentSystemsInterface
     */
    private $paymentSystems;

    /**
     * @param HttpRequestVerifierInterface $decorated
     * @param PaymentSystemsInterface $paymentSystems
     * @param string $tokenParameter
     */
    public function __construct(HttpRequestVerifierInterface $decorated, PaymentSystemsInterface $paymentSystems, $tokenParameter = 'payum_token')
    {
        $this->decorated = $decorated;
        $this->paymentSystems = $paymentSystems;
        $this->tokenParameter = $tokenParameter;
    }

    /**
     * {@inheritDoc}
     */
    public function verify($httpRequest, $classToken = Capture::class)
    {
        if (!empty($httpRequest)) {
            try {
                $preparePayment = $this->paymentSystems->getByRequestKeys(array_keys($httpRequest));
                if (isset($preparePayment)) {
                    $httpRequest[$this->tokenParameter] = $preparePayment->getTokenHash(
                        $httpRequest, $classToken
                    );
                }
            } catch (InvalidArgumentException  $e) {
            }
        }

        $token = $this->decorated->verify($httpRequest);

        return $token;
    }

    /**
     * {@inheritDoc}
     */
    public function invalidate(TokenInterface $token)
    {
        $this->decorated->invalidate($token);
    }
}
