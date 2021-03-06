<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\WaterMonitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Water Monitors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-monitor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Water Monitor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'site',
            'individual_monitoring',
            'phone',
            'current_site',
            // 'current_level',
            // 'current-temp',
            // 'rainfall',
            // 'img1',
            // 'img2',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
