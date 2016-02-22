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

class DateTimeBehavior extends Behavior
{

    /**
     * @var array attributes names which should be handled by this behavior
     */
    public $dateTimeAttributes = array();

    /**
     * @var string the basic timezone which is used to normalize dates
     */
    public $timezone;

    private $_dateTimeZone;

    public function init()
    {
        parent::init();
        if (!isset($this->timezone)) {
            $this->timezone = Yii::$app->timeZone;
        }
        $this->_dateTimeZone = new DateTimeZone($this->timezone);
    }

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
        foreach ($this->dateTimeAttributes as $dateTimeAttribute) {
            $value = $this->owner->$dateTimeAttribute;
            if (isset($value) && !empty($value)) {
                if (!($value instanceof \DateTime)) {
                    if (strlen($value) == 16) {
                        $value .= ':00';
                    }
                    $value = new DateTime($value, $this->_dateTimeZone);
                }
                if ($value instanceof \DateTime) {
                    $value = $value->setTimezone($this->_dateTimeZone)->format('Y-m-d H:i:s');
                } else {
                    $value = null;
                }
            } else {
                $value = null;
            }
            $this->owner->$dateTimeAttribute = $value;
        }
    }

    public function afterFind($event)
    {
        foreach ($this->dateTimeAttributes as $dateTimeAttribute) {
            $value = $this->owner->$dateTimeAttribute;
            if (isset($value)) {
                $value = $this->parseDatetime($value);
            }
            $this->owner->$dateTimeAttribute = $value;
        }
    }

    /**
     * @param        $value
     * @param string $format
     *
     * @return null|DateTime
     */
    private function parseDatetime($value, $format = 'Y-m-d H:i:s')
    {
        $date = DateTime::createFromFormat($format, $value, $this->_dateTimeZone);
        $errors = DateTime::getLastErrors();
        if ($date === false || $errors['error_count'] || $errors['warning_count']) {
            return null;
        }
        // if no time was provided in the format string set time to 0 to get a simple date timestamp
        if (strpbrk($format, 'HhGgis') === false) {
            $date->setTime(0, 0, 0);
        }
        return $date;
    }
}
