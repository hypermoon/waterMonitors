<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\WaterMonitor */

$this->title = 'Update Water Monitor: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Water Monitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="water-monitor-update">

    <div class="easyui-panel" title="<?= Html::encode($this->title) ?>" style="width:100%;padding:10px;">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    </div>

</div>
