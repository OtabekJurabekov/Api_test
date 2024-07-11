<?php
use yii\helpers\Html;

$this->title = 'Cars List';
$this->params['breadcrumbs'][] = $this->title;
?>

<h1><?= Html::encode($this->title) ?></h1>
<?= Html::a('Create a Car', ['site/car-create'], ['class' => 'btn btn-success']) ?>
<div class="cars-list">
    <ul>
        <?php foreach ($carsData as $car): ?>
            <li>
                <div class="p-2">
                    <?= Html::encode($car['model']) ?> - <?= Html::encode($car['brand']) ?>
                    <?= Html::a('View Car', ['site/car-detail', 'id' => $car['id']], ['class' => 'btn btn-primary']) ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
