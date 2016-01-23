<?php
/**
 * UnixTimestampBehavior.php
 *
 * @author Christian Renner <info@christian-renner.eu>
 */

namespace ch12r\crii\behaviors;

use DateTime;
use DateTimeZone;
use Yii;
use yii\base\Behavior;
use yii\db\ActiveRecord;

class UnixTimestampBehavior extends Behavior
{

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
                $this->owner->$datetimeAttribute = $this->parseDatetime($this->owner->$datetimeAttribute);
            }
        }
    }

    /**
     * @param $value
     *
     * @return bool|int
     */
    private function parseDatetime($value, $format = 'Y-m-d H:i:s')
    {
        $date = DateTime::createFromFormat($format, $value, new DateTimeZone(Yii::$app->timeZone));
        $errors = DateTime::getLastErrors();
        if ($date === false || $errors['error_count'] || $errors['warning_count']) {
            return false;
        } else {
            // if no time was provided in the format string set time to 0 to get a simple date timestamp
            if (strpbrk($format, 'HhGgis') === false) {
                $date->setTime(0, 0, 0);
            }
            return $date->getTimestamp();
        }
    }
}
