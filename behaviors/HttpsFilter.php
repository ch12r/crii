<?php

namespace ch12r\crii\behaviors;

use Yii;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\VerbFilter;

/**
 * HttpsFilter.php
 *
 * @author Christian Renner <info@christian-renner.eu>
 */
class HttpsFilter extends VerbFilter
{

    const FILTER_ACTION_QUIT_ON_ERROR = '!';

    const FILTER_ACTION_REDIRECT = '+';

    /**
     * @inheritdoc
     */
    public function beforeAction($event)
    {
        $filterAction = null;
        $action = $event->action;
        $controller = $event->action->controller;
        if (!($controller instanceof Controller)) {
            throw new HttpException(500, 'Filter is only supported in web controller context.');
        }
        if (isset($this->actions[$action->id])) {
            $filterAction = $this->actions[$action->id];
        } else if(isset($this->actions['*'])) {
            $filterAction = $this->actions['*'];
        } else {
            return $event->isValid;
        }

        if (!in_array($filterAction, array(self::FILTER_ACTION_QUIT_ON_ERROR, self::FILTER_ACTION_REDIRECT), true)) {
            throw new HttpException(500, 'Misconfigured filter. Unknown value given.');
        }

        /**
         * @var $request \yii\web\Request
         */
        $request = Yii::$app->getRequest();
        if (!$request->isSecureConnection) {
            if ($filterAction == self::FILTER_ACTION_QUIT_ON_ERROR) {
                throw new HttpException(500, 'Page is only available over SSL. Please use https.');
            }
            if ($filterAction == self::FILTER_ACTION_REDIRECT) {
                $controller->redirect(
                    Url::toRoute($controller->getRoute(), 'https')
                );
            }
        }
        return $event->isValid;
    }

}