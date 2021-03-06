<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\WaterSinglertustation */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Water Singlertustations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="../../../../../vendor/yiisoft/yii2/web/test.js"></script>

<div class="water-singlertustation-view">

   <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'state',
            'statno',
            'waterlv',
            'rainfall',
            'watertemp',
            'date',
            'bakup1',
            'bakup2',
        ],
    ]) ?>

</div>
