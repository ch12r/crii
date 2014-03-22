<?php

namespace ch12r\crii\web;

use ch12r\crii\behaviors\HttpsFilter;
use yii\web\AccessControl;

/**
 * Controller.php
 *
 * @author Christian Renner <info@christian-renner.eu>
 */ 
class SecureController extends Controller
{

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'https' => [
                'class' => HttpsFilter::className(),
                'actions' => [
                    '*' => HttpsFilter::FILTER_ACTION_REDIRECT
                ],
            ],
        ];
    }

}