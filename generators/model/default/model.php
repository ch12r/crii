<?php
/**
 * This is the template for generating the model class of a specified table.
 *
 * Adapted from yiisoft/yii2-gii
 *
 * @var yii\web\View $this
 * @var yii\gii\generators\model\Generator $generator
 * @var string $tableName full table name
 * @var string $className class name
 * @var yii\db\TableSchema $tableSchema
 * @var string[] $labels list of attribute labels (name => label)
 * @var string[] $rules list of validation rules
 * @var array $relations list of relations (name => relation declaration)
 */

echo "<?php\n";
?>

namespace <?= $generator->ns ?>;
<?php
$baseClass = ltrim($generator->baseClass, '\\');
$isCriiAR = ($baseClass == 'ch12r\\crii\\db\\ActiveRecord');
if ($isCriiAR) {
    echo "\n";
    echo 'use ch12r\\crii\\db\\ActiveRecord;'."\n";
}
?>

/**
 * This is the model class for table "<?= $tableName ?>".
 *
<?php foreach ($tableSchema->columns as $column): ?>
 * @property <?= "{$column->phpType} \${$column->name}\n" ?>
<?php endforeach; ?>
<?php if (!empty($relations)): ?>
 *
<?php foreach ($relations as $name => $relation): ?>
 * @property <?= $relation[1] . ($relation[2] ? '[]' : '') . ' $' . lcfirst($name) . "\n" ?>
<?php endforeach; ?>
<?php endif; ?>
<?php
if ($isCriiAR) {
    echo ' * '."\n";
    echo ' * @static '.$className.'|null find()'."\n";
    echo ' * @static '.$className.'[]|null findAll()'."\n";
    echo ' * '."\n";
}
?>
 */

class <?= $className ?> extends <?php echo (($isCriiAR)? 'ActiveRecord' : '\\' . $baseClass) . "\n"; ?>
{

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '<?= $tableName ?>';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [<?= "\n            " . implode(",\n            ", $rules) . "\n        " ?>];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
<?php foreach ($labels as $name => $label): ?>
            <?= "'$name' => " . $generator->generateString($label) . ",\n" ?>
<?php endforeach; ?>
        ];
    }
<?php foreach ($relations as $name => $relation): ?>

    /**
     * @return \yii\db\ActiveQuery
     */
    public function get<?= $name ?>()
    {
        <?= $relation[0] . "\n" ?>
    }
<?php endforeach; ?>
}
