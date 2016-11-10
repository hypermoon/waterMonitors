<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nickname')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'jpin')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'workunit')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'qpin')->textInput() ?>
	
	<?= $form->field($model, 'mobile')->textInput(['maxlength' => true]) ?>
	
	<?= $form->field($model, 'qqnum')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? '新建' : '修改', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
