<?php

namespace yii\payum\models;

use Payum\Core\Model\PaymentInterface;
use thamtech\uuid\helpers\UuidHelper;
use thamtech\uuid\validators\UuidValidator;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property string $hash
 * @property string $after_url
 * @property string $target_url
 * @property string $gateway_name
 * @property integer $details_id
 * @property PaymentInterface $detailsQuery
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

    public static function primaryKey()
    {
        return ['hash'];
    }

    /**
     * {@inheritDoc}
     */
    public function init()
    {
        $this->hash = UuidHelper::uuid();
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'hash' => [['hash'], UuidValidator::class],

            'after_url' => [['after_url'], 'string', 'max' => 255],
            'target_url' => [['target_url'], 'string', 'max' => 255],

            'gateway_name' => [['gateway_name'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'hash' => Yii::t('yii/payum', 'Hash'),
            'after_url' => Yii::t('yii/payum', 'After url'),
            'target_url' => Yii::t('yii/payum', 'Target url'),
            'gateway_name' => Yii::t('yii/payum', 'Gateway name'),
            'details' => Yii::t('yii/payum', 'Details'),
        ];
    }

    public function transactionId()
    {
        return $this->getPrimaryKey();
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
     * {@inheritDoc}
     */
    public function getDetails()
    {
        return $this->detailsQuery;
    }

    /**
     * @return ActiveQuery
     */
    public function getDetailsQuery()
    {
        return $this->hasOne(PayumPayment::className(), ['number' => 'details_id']);
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
