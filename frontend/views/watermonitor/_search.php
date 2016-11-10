<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\search\WaterMonitor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="water-monitor-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'site') ?>

    <?= $form->field($model, 'individual_monitoring') ?>

    <?= $form->field($model, 'phone') ?>

    <?= $form->field($model, 'current_site') ?>

    <?php // echo $form->field($model, 'current_level') ?>

    <?php // echo $form->field($model, 'current_temp') ?>

    <?php // echo $form->field($model, 'rainfall') ?>

    <?php // echo $form->field($model, 'img1') ?>

    <?php // echo $form->field($model, 'img2') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
