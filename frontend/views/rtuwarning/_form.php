<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitors\common\models\Rtuwarning */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="rtuwarning-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'rtuno')->textInput() ?>

    <?= $form->field($model, 'date')->textInput() ?>

    <?= $form->field($model, 'waterlv')->textInput() ?>

    <?= $form->field($model, 'rainfall')->textInput() ?>

    <?= $form->field($model, 'volte')->textInput() ?>

    <?= $form->field($model, 'originstr')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'bakup')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
