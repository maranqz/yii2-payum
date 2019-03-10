<?php

namespace yii\payum\storage;

use Throwable;
use LogicException;
use Payum\Core\Storage\AbstractStorage;
use Payum\Core\Model\Identity;
use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;

/**
 * @property BaseActiveRecord $modelClass
 */
class ActiveRecordStorage extends AbstractStorage
{
    /**
     * {@inheritDoc}
     *
     * @param ActiveRecord $model
     */
    public function doUpdateModel($model)
    {
        $model->save();
    }

    /**
     * {@inheritDoc}
     *
     * @param ActiveRecord $model
     *
     * @throws Throwable
     */
    public function doDeleteModel($model)
    {
        $model->delete();
    }

    /**
     * {@inheritDoc}
     *
     * @param ActiveRecord $model
     */
    public function doGetIdentity($model)
    {
        if ($model->isNewRecord) {
            throw new LogicException('The model must be persisted before usage of this method');
        }
        return new Identity($model->getPrimaryKey(), $model);
    }

    /**
     * {@inheritDoc}
     */
    public function doFind($id)
    {
        return ($this->modelClass)::findOne($id);
    }

    /**
     * {@inheritDoc}
     */
    public function findBy(array $criteria)
    {
        return ($this->modelClass)::findOne($criteria);
    }

    public function support($model)
    {
        return $model instanceof BaseActiveRecord && parent::support($model);
    }
}
