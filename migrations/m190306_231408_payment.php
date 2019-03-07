<?php

use yii\payum\migrations\Migration;
use yii\payum\models\PayumTokenInterface;

class m190306_231408_payment extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->getPaymentTable(), [
            'id' => $this->primaryKey(),
            'number' => $this->string()->null(),
            'description' => $this->string()->null(),
            'client_email' => $this->string()->null(),
            'client_id' => $this->string()->null(),
            'total_amount' => $this->decimal(
                PayumTokenInterface::TOTAL_AMOUNT_PRECISION,
                PayumTokenInterface::TOTAL_AMOUNT_SCALE
            )->null(),
            'currency_code' => $this->string()->null(),
            'details' => $this->text()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable($this->getPaymentTable());
    }
}
