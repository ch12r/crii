<?php
/**
 * Similar to yiisoft/yii2/yii
 */

defined('YII_DEBUG') or define('YII_DEBUG', true);

$yiiDir = dirname(__DIR__).'../../yiisoft/yii2';
require($yiiDir . '/Yii.php');

Yii::setAlias('@crii', dirname(__DIR__));

$application = new yii\console\Application([
        'id' => 'yii-console',
        'basePath' => $yiiDir . '/console',
    ]);
$exitCode = $application->run();
exit($exitCode);