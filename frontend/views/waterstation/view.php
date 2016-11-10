<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Waterstation */

$this->title = $model->sitenumber;
$this->params['breadcrumbs'][] = ['label' => 'Waterstations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waterstation-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->sitenumber], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->sitenumber], [
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
            'sitenumber',
            'stationame',
            'fatherpoint',
            'desciber',
            'bakup',
        ],
    ]) ?>

</div>
