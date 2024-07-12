<?php

/* @var $this yii\web\View */
/* @var $carData array */

use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Car Detail';
?>

<div class="car-detail">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $carData,
        'attributes' => [
            'id',
            'model',
            'brand',
            'description',
            'color',
            'year',
            [
                'attribute' => 'price',
                'format' => ['currency', '$'],
            ],
        ],
        'options' => ['class' => 'table table-striped detail-view'],
    ]) ?>

    <p>
        <?= Html::a('Delete Car', ['site/car-delete', 'id' => $carData['id']], ['class' => 'btn btn-danger']) ?>
        <?= Html::a('Update Car', ['site/car-update', 'id' => $carData['id']], ['class' => 'btn btn-primary']) ?>
    </p>
    <?= Html::a('Back to Cars List', ['site/index'], ['class' => 'btn btn-primary']) ?>
</div>
