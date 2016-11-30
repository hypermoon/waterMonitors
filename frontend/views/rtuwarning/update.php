<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model res\waterMonitors\common\models\Rtuwarning */

$this->title = 'Update Rtuwarning: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Rtuwarnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="rtuwarning-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
