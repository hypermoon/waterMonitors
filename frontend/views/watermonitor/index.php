<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\WaterMonitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '水文监测';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="water-monitor-index" style="position:absolute;z-index:1;">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

<!--
    <p>
        <?= Html::a('Create Water Monitor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
--> 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'site',
            'current_site',
            // 'individual_monitoring',
            //'phone',
            'current_level',
            'current_temp',
             'rainfall',
            // 'img1',
            // 'img2',
             'datetime',
            ['class' => 'yii\grid\ActionColumn'],
            ['class' => 'yii\grid\CheckboxColumn'],
           //  ['class' => 'yii\grid\DataColumn'],
           // ['class' => 'yii\grid\GridView'],
        ],
    ]); ?>
 
</div>
<img src="<?php echo Yii::getAlias('@web');?>/public/backgroundimg/sk-03.gif" style="width:150%;margin-top:400px;margin-left:-50px;">

