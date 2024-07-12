<!-- views/site/car-create.php -->

<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Update Car';
$this->params['breadcrumbs'][] = ['label' => 'Cars', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="car-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'model')->textInput(['maxlength' => true, 'value' => isset($model['model']) ? $model['model'] : null]) ?>

    <?= $form->field($model, 'brand')->textInput(['maxlength' => true, 'value' => isset($model['brand']) ? $model['brand'] : null]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6, 'value' => isset($model['description']) ? $model['description'] : null]) ?>

    <?= $form->field($model, 'color')->textInput(['maxlength' => true, 'value' => isset($model['color']) ? $model['color'] : null]) ?>

    <?= $form->field($model, 'year')->textInput(['type' => 'date', 'value' => isset($model['year']) ? $model['year'] : null]) ?>

    <?= $form->field($model, 'price')->textInput(['value' => isset($model['price']) ? $model['price'] : null]) ?>

    <div class="form-group">
        <?= Html::submitButton('Update', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
