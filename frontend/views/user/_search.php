<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\search\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'account') ?>

    <?= $form->field($model, 'password') ?>

    <?= $form->field($model, 'auth_key') ?>

    <?= $form->field($model, 'password_hash') ?>

    <?php // echo $form->field($model, 'password_reset_token') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'updated_at') ?>

    <?php // echo $form->field($model, 'role') ?>

    <?php // echo $form->field($model, 'username') ?>

    <?php // echo $form->field($model, 'jpin') ?>

    <?php // echo $form->field($model, 'qpin') ?>

    <?php // echo $form->field($model, 'nickname') ?>

    <?php // echo $form->field($model, 'sex') ?>

    <?php // echo $form->field($model, 'idcard') ?>

    <?php // echo $form->field($model, 'stdid') ?>

    <?php // echo $form->field($model, 'nation') ?>

    <?php // echo $form->field($model, 'birthday') ?>

    <?php // echo $form->field($model, 'mobile') ?>

    <?php // echo $form->field($model, 'qqnum') ?>

    <?php // echo $form->field($model, 'wechat') ?>

    <?php // echo $form->field($model, 'homephone') ?>

    <?php // echo $form->field($model, 'email') ?>

    <?php // echo $form->field($model, 'image') ?>

    <?php // echo $form->field($model, 'sorting') ?>

    <?php // echo $form->field($model, 'address') ?>

    <?php // echo $form->field($model, 'orgin') ?>

    <?php // echo $form->field($model, 'father') ?>

    <?php // echo $form->field($model, 'fathphone') ?>

    <?php // echo $form->field($model, 'guardian') ?>

    <?php // echo $form->field($model, 'gdphone') ?>

    <?php // echo $form->field($model, 'midschool') ?>

    <?php // echo $form->field($model, 'status') ?>

    <?php // echo $form->field($model, 'mather') ?>

    <?php // echo $form->field($model, 'mathphone') ?>

    <?php // echo $form->field($model, 'university') ?>

    <?php // echo $form->field($model, 'specialty') ?>

    <?php // echo $form->field($model, 'level') ?>

    <?php // echo $form->field($model, 'workunit') ?>

    <?php // echo $form->field($model, 'quarters') ?>

    <?php // echo $form->field($model, 'position') ?>

    <?php // echo $form->field($model, 'nexus') ?>

    <?php // echo $form->field($model, 'workphone') ?>

    <?php // echo $form->field($model, 'remark') ?>

    <?php // echo $form->field($model, 'grow') ?>

    <?php // echo $form->field($model, 'schfrom') ?>

    <?php // echo $form->field($model, 'midtid') ?>

    <?php // echo $form->field($model, 'trasaction_id') ?>

    <?php // echo $form->field($model, 'studentpay') ?>

    <?php // echo $form->field($model, 'alternativephone') ?>

    <?php // echo $form->field($model, 'signature') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
