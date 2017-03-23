<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\WaterSinglertustation */

$this->title = 'Update Water Singlertustation: ' . ' ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Water Singlertustations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<script src="../../../../../vendor/yiisoft/yii2/web/test.js"></script>

<div class="water-singlertustation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
