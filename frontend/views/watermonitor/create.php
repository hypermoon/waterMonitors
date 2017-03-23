<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\WaterMonitor */

$this->title = 'Create Water Monitor';
$this->params['breadcrumbs'][] = ['label' => 'Water Monitor', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script src="../../../../../vendor/yiisoft/yii2/web/test.js"></script>

<div class="water-monitor-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
