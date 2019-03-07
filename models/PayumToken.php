<?php

namespace yii\payum\models;

use Yii;
use yii\db\ActiveRecord;

class PayumToken extends ActiveRecord implements PayumTokenInterface
{
    use PayumTokenTrait;
}
