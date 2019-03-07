<?php

use yii\payum\migrations\Migration;

class m190306_231444_token extends Migration
{
    const PRIMARY_KEY = 'id';
    const DETAILS_ID = 'details_id';

    const FK_TOKEN_PAYMENT = 'fk-token-payment';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->getTokenTable(), [
            self::PRIMARY_KEY => $this->primaryKey(),
            'hash' => $this->string()->notNull(),
            'after_url' => $this->string()->null(),
            'target_url' => $this->string()->notNull(),
            'gateway_name' => $this->string()->notNull(),
            self::DETAILS_ID => $this->integer()->notNull(),
        ]);

        $this->addForeignKey(
            self::FK_TOKEN_PAYMENT,
            $this->getTokenTable(),
            self::DETAILS_ID,
            $this->getPaymentTable(),
            static::PRIMARY_KEY,
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey(self::FK_TOKEN_PAYMENT, $this->getTokenTable());
        $this->dropTable($this->getTokenTable());
    }
}
