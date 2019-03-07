<?php

use maranqz\promotion\migrations\Migration;
use maranqz\promotion\models\property\UsageLimitInterface;

/**
 * Class m180904_152219_coupon
 */
class m180904_152219_promotion_coupon extends Migration
{
	const FK_PROMOTION = 'fk-coupon-promotion_id';
	const INDEX_CODE_LENGTH = 'idx-promotion_coupon-code_length';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable($this->table_coupon, [
			'id' => $this->primaryKey(),
			'code' => $this->string(7)->notNull()->unique(),
			'code_length' => $this->tinyInteger()->append(' AS (LENGTH(code)) PERSISTENT'),
			'usage_limit' => $this->integer()
				->defaultValue(UsageLimitInterface::USAGE_LIMIT_DEFAULT)
				->unsigned(),
			'used' => $this->integer()->notNull()->unsigned()->defaultValue(0),
			'promotion_id' => $this->integer()->notNull(),
			'expires_at' => $this->date(),
			'created_at' => $this->date()->notNull(),
			'updated_at' => $this->date()->notNull(),
		]);

		$this->addForeignKey(
			self::FK_PROMOTION,
			$this->table_coupon,
			'promotion_id',
			$this->table_promotion,
			'id',
			'CASCADE'
		);

		$this->createIndex(
			$this::INDEX_CODE_LENGTH,
			$this->table_coupon,
			'code_length'
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex($this::INDEX_CODE_LENGTH, $this->table_coupon);
		$this->dropTable($this->table_coupon);
	}
}
