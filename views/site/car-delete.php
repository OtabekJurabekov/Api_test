<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

// Assuming $model is your active record model or any model containing car data
?>

<?php $form = ActiveForm::begin(['action' => ['site/car-delete']]); ?>

    <?= $form->field($model, 'id')->textInput(['type' => 'number']) ?>

    <div class="form-group">
        <?= Html::submitButton('Delete Car', ['class' => 'btn btn-danger']) ?>
    </div>

<?php ActiveForm::end(); ?>