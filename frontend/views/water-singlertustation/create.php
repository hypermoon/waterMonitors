<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\WaterSinglertustation */

$this->title = 'Create Water Singlertustation';
$this->params['breadcrumbs'][] = ['label' => 'Water Singlertustations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-singlertustation-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
