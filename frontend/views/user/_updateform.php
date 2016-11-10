<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\User */
/* @var $form yii\widgets\ActiveForm */

$this->title = '修改用户: ' . ' ' . $model->nickname;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nickname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
	
	<?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

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
