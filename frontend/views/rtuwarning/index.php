<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Rtuwarnings';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtuwarning-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Rtuwarning', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'rtuno',
            'date',
            'waterlv',
            'rainfall',
            // 'volte',
            // 'originstr:ntext',
            // 'bakup',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
