<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '站点管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waterstation-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('创建站点', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'sitenumber',
            'stationame',
            'fatherpoint',
            'desciber',
            'bakup',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
