<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\WaterMonitor */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="water-monitor-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'individual_monitoring')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_site')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_level')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'current_temp')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'rainfall')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'img1')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'img2')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
