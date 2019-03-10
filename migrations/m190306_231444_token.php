<?php

use yii\payum\migrations\Migration;

class m190306_231444_token extends Migration
{
    const PRIMARY_KEY = 'number';
    const DETAILS_ID = 'details_id';

    const IDX_HASH = 'idx-token-hash';
    const FK_TOKEN_PAYMENT = 'fk-token-payment';

    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable($this->getTokenTable(), [
            'hash' => $this->char(36)->notNull(),
            'after_url' => $this->string()->null(),
            'target_url' => $this->string()->notNull(),
            'gateway_name' => $this->string()->notNull(),
            self::DETAILS_ID => $this->integer()->notNull(),
        ]);

        $this->createIndex(
            self::IDX_HASH,
            $this->getTokenTable(),
            self::PRIMARY_KEY
        );

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
        $this->dropIndex(self::IDX_HASH, $this->getTokenTable());
        $this->dropForeignKey(self::FK_TOKEN_PAYMENT, $this->getTokenTable());
        $this->dropTable($this->getTokenTable());
    }
}
