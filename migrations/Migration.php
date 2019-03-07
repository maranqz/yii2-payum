<?php


namespace yii\payum\migrations;


use yii\payum\models\PayumPayment;
use yii\payum\models\PayumToken;
use yii\db\Migration as BaseMigration;

class Migration extends BaseMigration
{
    private $table_payment;
    private $table_token;

    public function __construct(array $config = [])
    {
        parent::__construct($config);

        $this->table_payment = PayumPayment::tableName();
        $this->table_token = PayumToken::tableName();
    }

    public function getPaymentTable()
    {
        return $this->table_payment;
    }

    public function getTokenTable()
    {
        return $this->table_token;
    }
}
