<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\WaterMonitor */

$this->title = '水文监测: ' . ' ' . $model->site;
$this->params['breadcrumbs'][] = ['label' => '水文监测', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->site, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<script src="../../../../../vendor/yiisoft/yii2/web/test.js"></script>

<div class="water-monitor-update">
    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
