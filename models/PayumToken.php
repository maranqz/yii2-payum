<?php

namespace yii\payum\models;

use Yii;
use yii\db\ActiveRecord;

class PayumToken extends ActiveRecord implements PayumTokenInterface
{
    use PayumTokenTrait;

    public function rules()
    {
        $rules = parent::rules();

        $rules['details_id'] = [
            ['details_id'],
            'exist',
            'targetClass' => PayumPayment::class,
            'targetAttribute' => ['details_id' => 'number']
        ];

        return $rules;
    }
}
