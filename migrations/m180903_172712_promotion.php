<?php

use maranqz\promotion\migrations\Migration;

use maranqz\promotion\models\active\Promotion;
use maranqz\promotion\models\search\PromotionSearch;
use maranqz\promotion\models\property\UsageLimitInterface;

/**
 * Class m180903_172712_promotion
 */
class m180903_172712_promotion extends Migration
{
	const IDX_PROMOTION_NAME = 'idx-promotion-name';

	/**
	 * {@inheritdoc}
	 */
	public function safeUp()
	{
		$this->createTable($this->table_promotion, [
			'id' => $this->primaryKey(),
			'code' => $this->string(255)->notNull(),
			'name' => $this->string(255)->notNull(),
			'description' => $this->string(1024)->null(),
			'priority' => $this->integer(4)->notNull(),
			'exclusive' => $this->boolean()->notNull()
				->defaultValue(Promotion::EXCLUSIVE_DEFAULT),
			'usage_limit' => $this->integer()
				->defaultValue(UsageLimitInterface::USAGE_LIMIT_DEFAULT)
				->unsigned(),
			'used' => $this->integer()->notNull()->unsigned()->defaultValue(0),
			'starts_at' => $this->datetime()->notNull(),
			'ends_at' => $this->datetime()->null(),
			'coupon_based' => $this->boolean()->notNull()
				->defaultValue(Promotion::COUPON_BASED_DEFAULT),
			'created_at' => $this->datetime()->notNull(),
			'updated_at' => $this->datetime()->notNull(),
		]);

		$this->createIndex(
			$this::IDX_PROMOTION_NAME,
			$this->table_promotion,
			'name(' . PromotionSearch::MAX_NAME_LENGTH . ')'
		);
	}

	/**
	 * {@inheritdoc}
	 */
	public function safeDown()
	{
		$this->dropIndex($this::IDX_PROMOTION_NAME, $this->table_promotion);
		
		$this->dropTable($this->table_promotion);
	}
}
