<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Waterstation */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="waterstation-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'sitenumber')->textInput() ?>

    <?= $form->field($model, 'stationame')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fatherpoint')->textInput() ?>

    <?= $form->field($model, 'desciber')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'bakup')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
