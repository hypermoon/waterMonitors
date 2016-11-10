<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\WaterMonitor */
/* @var $form yii\bootstrap\ActiveForm */
?>
<style>
    .help-block {
        color: red;
    };           
</style>
<div class="water-monitor-form" >

    <?php $form = ActiveForm::begin(
            [
                'options' => [
                    'class' => 'ui-form',
                     'id'=>'yiiAF_water-monitor',
                    // 此处比较重要！yii默认会为没有id的widget生成id的 如果是ajax加载 会导致id冲突的 这样js中有些代码就失效了
                    // 这个最容易根search表单的id冲突  参考yiiActiveForm.js 源码就可以看出问题所在了！
                    // 'id'=>'form-'.time(),
                 ],
            'fieldConfig' => [
                'template' => "<td>{label}</td>\n<td>{input}</td>\n<td>{error}</td>",
                //  'template' => "{input} \n {error}",
                // 'labelOptions' => ['class' => 'ui-label'],
                // 'inputOptions' => ['class' => 'ui-input'],
                'options' => ['tag' => 'tr'],
                 ],
            ]
    ); ?>

    <?php $form->errorSummary($model); ?>

    <table>

    <?php echo $form->field($model, 'site')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'individual_monitoring')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'phone')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'current_site')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'current_level')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'current-temp')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'rainfall')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'img1')->textInput(['maxlength' => 30]) ?>

    <?php echo $form->field($model, 'img2')->textInput(['maxlength' => 30]) ?>

    </table>

    <div class="form-group" style="text-align:center;padding:5px">
        <?php echo Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success easyui-linkbutton' : 'btn btn-primary easyui-linkbutton']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
