<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\WaterMonitor */

$this->title = 'Create Water Monitor';
$this->params['breadcrumbs'][] = ['label' => 'Water Monitors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-monitor-create">

    <div class="easyui-panel" title="<?= Html::encode($this->title) ?>" style="width:100%;padding:10px;">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

    </div>
</div>
