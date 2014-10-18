<?php
/**
 * UnixTimestampBehavior.php
 *
 * @author Christian Renner <info@christian-renner.eu>
 */

namespace ch12r\crii\behaviors;

use yii\base\Behavior;
use yii\db\ActiveRecord;

class UnixTimestampBehavior extends Behavior
{

    const YII_DATETIME_INTERNAL_FORMAT = 'yyyy-MM-dd hh:mm:ss';

    public $datetimeAttributes = array();

    public function events()
    {
        return [
            ActiveRecord::EVENT_BEFORE_INSERT => 'beforeSave',
            ActiveRecord::EVENT_BEFORE_UPDATE => 'beforeSave',
            ActiveRecord::EVENT_AFTER_FIND => 'afterFind',
        ];
    }

    public function beforeSave($event)
    {
        foreach ($this->datetimeAttributes as $datetimeAttribute) {
            if (isset($this->owner->$datetimeAttribute) && !empty($this->owner->$datetimeAttribute)) {
                if (!is_int($this->owner->$datetimeAttribute)) {
                    if (strlen($this->owner->$datetimeAttribute) == 16) {
                        $this->owner->$datetimeAttribute .= ':00';
                    }
                    $this->owner->$datetimeAttribute = strtotime($this->owner->$datetimeAttribute);
                }
                if (isset($this->owner->$datetimeAttribute) && is_int($this->owner->$datetimeAttribute) && $this->owner->$datetimeAttribute > 0) {
                    $this->owner->$datetimeAttribute = date('YmdHis', $this->owner->$datetimeAttribute);
                }
            } else {
                $this->owner->$datetimeAttribute = null;
            }
        }
    }

    public function afterFind($event)
    {
        foreach ($this->datetimeAttributes as $datetimeAttribute) {
            if (isset($this->owner->$datetimeAttribute)) {
                $this->owner->$datetimeAttribute = DateTimeParser::parse(
                    $this->owner->$datetimeAttribute,
                    self::YII_DATETIME_INTERNAL_FORMAT
                );
            }
        }
    }
}