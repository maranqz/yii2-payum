<?php

namespace yii\payum\components\OmnipayBridge\Request;


class GetTokenHash
{
    /**
     * @var string
     */
    private $hash;

    /**
     * @var string
     */
    private $requestClass;

    public function __construct($requestClass)
    {
        $this->requestClass = $requestClass;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    public function getRequestClass()
    {
        return $this->requestClass;
    }
}
