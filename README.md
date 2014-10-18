yii2-crii
====

yii2-crii includes little helpers for improving performance during developing with yii2.

Add Alias to /common/config/bootstrap.php:

 Yii::setAlias('@crii', dirname(dirname(__DIR__)) . '/vendor/ch12r/yii2-crii');

For using crii gii model template, add the following config to /console/config/main.php:
 'modules' => [
     'gii' => [
         'class'      => 'yii\gii\Module',
         'generators' => [
             'model'   => [
                 'class'     => 'yii\gii\generators\model\Generator',
                 'templates' => [
                     'crii-model' => '@crii/generators/model/default'
                 ]
             ]
         ]
     ],
 ],
