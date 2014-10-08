<?php

namespace ch12r\crii\web;

use ch12r\crii\behaviors\HttpsFilter;
use yii\web\AccessControl;

/**
 * Controller.php
 *
 * @property boolean $_overSSL Enables/Disables SSL Filter
 *
 * @author Christian Renner <info@christian-renner.eu>
 */
class SecureController extends Controller
{

    protected $_overSSL = true;

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        $behaviors = [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
        if ($this->_overSSL) {
            $behaviors['https'] = [
                'class' => HttpsFilter::className(),
                'actions' => [
                    '*' => HttpsFilter::FILTER_ACTION_REDIRECT
                ],
            ];
        }
        return $behaviors;
    }
}
