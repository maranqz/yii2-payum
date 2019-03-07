<?php

namespace yii\payum\models;

use Yii;
use Payum\Core\Security\Util\Random;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property string $hash
 * @property string $after_url
 * @property string $target_url
 * @property string $gateway_name
 * @property integer $details_id
 */
trait PayumTokenTrait
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%payum_token}}';
    }

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->hash = Random::generateToken();
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'details_id'], 'integer'],
            [['hash', 'after_url', 'target_url'], 'string', 'max' => 255],
            [['payment_name'], 'string', 'max' => 64],
            ['after_url, target_url', 'safe'],

        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'hash' => 'Hash',
            'payment_name' => 'Payment Name',
            'after_url' => 'After Url',
            'target_url' => 'Target Url',
            'details_id' => 'Details ID',
        ];
    }

    /**
     * {@inheritDoc}
     *
     * @param PayumPaymentInterface $details
     */
    public function setDetails($details)
    {
        $this->details_id = $details->getId();
    }

    /**
     * @return ActiveQuery
     */
    public function getDetails()
    {
        return $this->hasOne(PayumPayment::className(), ['id' => 'details_id']);
    }

    /**
     * {@inheritDoc}
     */
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * {@inheritDoc}
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * {@inheritDoc}
     */
    public function getTargetUrl()
    {
        return $this->target_url;
    }

    /**
     * {@inheritDoc}
     */
    public function setTargetUrl($targetUrl)
    {
        $this->target_url = $targetUrl;
    }

    /**
     * {@inheritDoc}
     */
    public function getAfterUrl()
    {
        return $this->after_url;
    }

    /**
     * {@inheritDoc}
     */
    public function setAfterUrl($afterUrl)
    {
        return $this->after_url = $afterUrl;
    }

    /**
     * @return string
     */
    public function getGatewayName()
    {
        return $this->gateway_name;
    }

    /**
     * {@inheritDoc}
     */
    public function setGatewayName($paymentName)
    {
        $this->gateway_name = $paymentName;
    }
}
