<?php

namespace yii\payum\models;

use Payum\Core\Model\CreditCardInterface;
use Yii;
use yii\db\ActiveQuery;

/**
 * @property integer $id
 * @property string $number
 * @property string $description
 * @property string $client_email
 * @property string $client_id
 * @property float $total_amount
 * @property string $currency_code
 * @property array $details
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
            [
                [
                    'id',
                    'number'
                ], 'required'
            ],

            [
                [
                    'number'
                ], 'required'
            ],
            [
                [
                    'number',
                ], 'length', 'max' => 255
            ],

            [
                [
                    'total_amount'
                ], 'numerical', 'integerOnly' => true
            ],
            [
                [
                    'client_email',
                    'client_id',
                    'currency_code'
                ], 'length', 'max' => 255
            ],
            'description_string' => [['description'], 'string'],
            'description_length' => [['description'], 'length' => 255],
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'description' => 'Description',
            'client_email' => 'Client Email',
            'client_id' => 'Client ID',
            'currency_code' => 'Currency Code',
            'total_amount' => 'Total Amount',
            'currency_digits_after_decimal_point' => 'Currency Digits After Decimal Point',
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function getId()
    {
        return $this->getPrimaryKey();
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
     */
    public function getCreditCard()
    {
        $this->credit_card;
    }

    /**
     * {@inheritDoc}
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * {@inheritDoc}
     *
     * @param array|\Traversable $details
     */
    public function setDetails($details)
    {
        if ($details instanceof \Traversable) {
            $details = iterator_to_array($details);
        }

        $this->details = serialize($details);
    }

    /**
     * @return ActiveQuery
     */
    public function getPayumTokens()
    {
        return $this->hasMany(PayumToken::className(), ['details_id' => 'id']);
    }
}
