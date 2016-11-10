<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\search\WaterMonitor */
/* @var $form yii\bootstrap\ActiveForm */
?>

<div id="searchPanelWater-monitor" class="easyui-panel" title="search"
     style="width:100%;height:auto;padding:10px;background:#fafafa;"
     data-options="iconCls:'icon-search',closable:false,
    collapsible:true,maximizable:true">

    <div class="water-monitor-search" style="padding:10px 60px 20px 60px">

        <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options'=>[
             'id'=>'yiiAF_SearchWater-monitor',
             'class'=>'search-form'  // 不能加 easyui-form类 不然js受影响！
             ],
        'fieldConfig' => [
                    'template' => "<td>{label}</td>\n<td>{input}</td>\n<td>{error}</td>",
                    'options' => ['tag' => 'tr'],
            ],
        ]); ?>

        <table cellpadding="5">
            <tbody>

                <?= $form->field($model, 'id',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?= $form->field($model, 'site',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?= $form->field($model, 'individual_monitoring',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?= $form->field($model, 'phone',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?= $form->field($model, 'current_site',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?php // echo $form->field($model, 'current_level',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?php // echo $form->field($model, 'current-temp',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?php // echo $form->field($model, 'rainfall',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?php // echo $form->field($model, 'img1',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>

    <?php // echo $form->field($model, 'img2',['inputOptions'=>[ 'class' => 'form-control easyui-textbox ']]) ?>


            </tbody>
        </table>


        <div class="form-group" style="text-align:center;padding:5px">
            <?= Html::submitButton('Search', ['class' => 'easyui-linkbutton']) ?>
            <?= Html::resetButton('Reset', ['class' => 'easyui-linkbutton' , 'onclick'=>'clearForm(this)']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>


<?php  \year\widgets\JsBlock::begin() ?>
<script>
    $(function(){
        $('form.search-form').on('submit', function(e){
            var $form = $(this);

            // var formData = form.serialize();
            var fields = $form.serializeArray();
            var params = {};
            $.each( fields, function(i, field){
                // 同时存在才保存！
                if(field.name && field.value){
                    params[field.name] = field.value
                }
            });

            $('#dg').datagrid('load',params);

            // 阻止表单提交！
            e.preventDefault();
        });
    });
    function clearForm(resetBtn){
        $(resetBtn).closest('form').form('clear');
    }
</script>
<?php  \year\widgets\JsBlock::end()?>
