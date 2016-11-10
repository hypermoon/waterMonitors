<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\WaterSinglertustation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="water-singlertustation-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'state')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'statno')->textInput() ?>

    <?= $form->field($model, 'waterlv')->textInput() ?>

    <?= $form->field($model, 'rainfall')->textInput() ?>

    <?= $form->field($model, 'watertemp')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'bakup1')->textInput() ?>

    <?= $form->field($model, 'bakup2')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
