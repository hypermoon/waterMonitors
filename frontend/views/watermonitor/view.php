<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\WaterMonitor */

$this->title = $model->site;
$this->params['breadcrumbs'][] = ['label' => '水文监测', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-monitor-view">

    <h1><?= Html::encode($this->title) ?></h1>
<!--
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
-->
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'site',
            'individual_monitoring',
            'phone',
            'current_site',
            'current_level',
            'current_temp',
            'rainfall',
            'img1',
            'img2',
        ],
    ]) ?>

</div>
