<?php

namespace yii\payum\models;

use Yii;
use yii\db\ActiveRecord;

class PayumPayment extends ActiveRecord implements PayumPaymentInterface
{
    use PayumPaymentTrait;
}
