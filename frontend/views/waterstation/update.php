<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Waterstation */

$this->title = 'Update Waterstation: ' . $model->sitenumber;
$this->params['breadcrumbs'][] = ['label' => 'Waterstations', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->sitenumber, 'url' => ['view', 'id' => $model->sitenumber]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="waterstation-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
