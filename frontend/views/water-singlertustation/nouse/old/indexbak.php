<?php

use yii\helpers\Html;
use yii\grid\GridView;
//namespace yii\Base\Widget;
//namespace yii\web\Widget;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RTU遥测站历史数据';
$this->params['breadcrumbs'][] = $this->title;

       // <?= Html::a('创建记录', ['create'], ['class' => 'btn btn-success']) 
         //>
?>
<div class="water-singlertustation-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?= Html::a('数据分析', ['alldataanalysis'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'state',
            'statno',
            'waterlv',
            'rainfall',
            'waterflow',
            //'watertemp',
            'volte',
            'date',
            // 'bakup1',
            // 'bakup2',
            // 'rainfallmulti',
            //  'waterlvmulti',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
