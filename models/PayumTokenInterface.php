<?php

namespace yii\payum\models;

use Yii;
use Payum\Core\Security\TokenInterface;

interface PayumTokenInterface extends TokenInterface
{
    const TOTAL_AMOUNT_PRECISION = 11;
    const TOTAL_AMOUNT_SCALE = 3;
}
