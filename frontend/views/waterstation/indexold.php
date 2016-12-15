<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '站点管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="waterstation-index" style="position:relative;z-index:1;">
<img src="<?php echo Yii::getAlias('@web');?>/public/backgroundimg/sk_02.gif" style="position:absolute;right:0px;bottom:0px;z-index:0;">
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
            'bakup',
            'fatherpoint',
            'desciber',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
