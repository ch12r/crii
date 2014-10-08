<?php

namespace ch12r\crii\db;

use yii\db\ActiveQuery;

/**
 * Extends yii\db\ActiveRecord.
 *
 * @author Christian Renner <info@christian-renner.eu>
 */
class ActiveRecord extends \yii\db\ActiveRecord
{

    /**
     * @param mixed|ActiveQuery $query
     *
     * @return ActiveRecord[]
     */
    public static function findAll($query)
    {
        if ($query instanceof ActiveQuery) {
            return $query->all();
        }
        return parent::findAll($query);
    }
}
 