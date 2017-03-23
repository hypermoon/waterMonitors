<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '站点管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="../../../../../vendor/yiisoft/yii2/web/test.js"></script>

<div class="waterstation-index" style="position:relative;z-index:1; ">
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
<div style="width:100%;"><img src="<?php echo Yii::getAlias('@web');?>/public/backgroundimg/sk_02.gif" style="width:105%;margin-left:-30px;">
</div>
