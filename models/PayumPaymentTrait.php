<?php

namespace yii\payum\models;

use Yii;
use Payum\Core\Model\CreditCardInterface;
use yii\db\ActiveQuery;

/**
 * @property string $number
 * @property string $description
 * @property string $client_email
 * @property string $client_id
 * @property float $total_amount
 * @property string $currency_code
 * @property string $details
 * @property CreditCardInterface $credit_card
 */
trait PayumPaymentTrait
{
    /**
     * {@inheritDoc}
     */
    public static function tableName()
    {
        return '{{%payum_payment}}';
    }

    /**
     * {@inheritDoc}
     */
    public function rules()
    {
        return [
            'description' => [['description'], 'string', 'max' => 255],

            'total_amount_number' => [['total_amount'], 'number', 'min' => 0.1],
            'total_amount_required' => [['total_amount'], 'required'],

            'client_email_string' => [['client_email'], 'string', 'max' => 255],
            'client_email' => [['client_email'], 'email'],

            'client_id' => [['client_id'], 'string', 'max' => 255],

            'currency_code' => [['currency_code'], 'string', 'max' => 255],

            'details' => [['details'], 'string'],
            'details_default' => [['details'], 'default', 'value' => '{}'],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'number' => Yii::t('yii/payum', 'Number'),
            'description' => Yii::t('yii/payum', 'Description'),
            'client_email' => Yii::t('yii/payum', 'Client email'),
            'client_id' => Yii::t('yii/payum', 'Client ID'),
            'currency_code' => Yii::t('yii/payum', 'Currency code'),
            'total_amount' => Yii::t('yii/payum', 'Total amount'),
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->getNumber();
    }

    /**
     * Use for exchange currency
     *
     * {@inheritDoc}
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * {@inheritDoc}
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * {@inheritDoc}
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    public function getClientEmail()
    {
        return $this->client_email;
    }

    public function setClientEmail($clientEmail)
    {
        $this->client_email = $clientEmail;
    }

    /**
     * {@inheritDoc}
     */
    public function getClientId()
    {
        return $this->client_id;
    }

    /**
     * @param string $clientId
     */
    public function setClientId($clientId)
    {
        $this->client_id = $clientId;
    }

    /**
     * {@inheritDoc}
     */
    public function getTotalAmount()
    {
        return $this->total_amount;
    }

    /**
     * {@inheritDoc}
     */
    public function setTotalAmount(int $total_amount)
    {
        $this->total_amount = $total_amount;
    }

    /**
     * {@inheritDoc}
     */
    public function getCurrencyCode()
    {
        return $this->currency_code;
    }

    /**
     * @param string $currencyCode
     */
    public function setCurrencyCode($currencyCode)
    {
        $this->currency_code = $currencyCode;
    }

    /**
     * {@inheritDoc}
     *
     * Method not realized
     */
    public function getCreditCard()
    {
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getDetails()
    {
        return json_decode($this->details, true);
    }

    /**
     * {@inheritDoc}
     *
     * @param array|\Traversable $details
     */
    public function setDetails($details)
    {
        $details = $details ?? [];

        if ($details instanceof \Traversable) {
            $details = iterator_to_array($details);
        }

        $this->details = json_encode($details);
    }

    /**
     * @return ActiveQuery
     */
    public function getPayumTokens()
    {
        return $this->hasMany(PayumToken::className(), ['details_id' => 'id']);
    }
}
