<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\web\JsExpression;
use yii\grid\GridView;
//namespace yii\Base\Widget;
//namespace yii\web\Widget;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RTU遥测站历史数据';
$this->params['breadcrumbs'][] = $this->title;


  if(!isset($rtutablename))
            $rtutablename = '默认表';
  
         //<?=Html::beginForm(['/waterMonitor/water-singlertustation/selectrtu','post','id'=>$rtutablename]);
     // <?= Html::a('创建记录', ['create'], ['class' => 'btn btn-success']) 
         //>
?>

<div class="water-singlertustation-index">
    <h2><?= Html::encode($this->title) ?></h2>
<input type="button" value="打印" onclick="printout()" style="float:right;"/>
<script>
function printout(){
$("mytable").css("size","landscape");
var bdhtml;
$("#mytable thead tr th:nth-child(2)").html("ID");
$("#mytable thead tr th:nth-child(3)").html("站点");
$("#mytable thead tr th:nth-child(4)").html("RTU编号");
$("#mytable thead tr th:nth-child(5)").html("水位");
$("#mytable thead tr th:nth-child(6)").html("最近5分钟雨量");
$("#mytable thead tr th:nth-child(7)").html("24小时雨量");
$("#mytable thead tr th:nth-child(8)").html("流量");
$("#mytable thead tr th:nth-child(9)").html("水温");
$("#mytable thead tr th:nth-child(10)").html("电压");
$("#mytable thead tr th:nth-child(11)").html("记录时间");
$("#mytable tbody tr td:nth-child(12)").remove();
$("#mytable thead tr th:nth-child(12)").remove();
$(".mytitle").html("数据分析");
$(".mytitle").css({"text-align":"center","font-size":"24pt"})
//var hr=$("#mytabl thead tr th").find("a").attr("href","");
var hr=$("a").remove();
bdhtml=window.document.body.innerHTML;
sprnstr="<!--startprint-->";
eprnstr="<!--endprint-->";
prnhtml=bdhtml.substr(bdhtml.indexOf(sprnstr)+999);
prnhtml=prnhtml.substring(0,prnhtml.indexOf(eprnstr));
window.document.body.innerHTML=prnhtml;
window.print();
}
</script>
<script>
//window.onload=function(){
//var id=document.getElementsByTagName("select")[0];

//var value=0;
//id.onchange=function(){
//value=id.value;
//var url=window.location.href;
//var urls=url.split("?")[0]+"?id="+value;
//window.location.href=urls;


//}

//}


//ajax

</script>
    <p>
<!--<object classid="clsid:8856f961-340a-11d0-a96b-00c04fd705a2" height=0 id=webbrowser width=0></object>-->
         <?=Html::beginForm(['/waterMonitor/water-singlertustation/selectrtu','id'=>$rtutablename]);?>
         <?= Html::dropDownList('rtuname', $rtutablename,ArrayHelper::map($data,'sitenumber',
               'sitenumber'),['onchange'=>'this.form.submit()' ,'prompt'=>'请选择:rtu表','style'=>'width:120px']); ?>
          
         <?=Html::label(' RTU编号:'.$rtutablename);?>
         <?= Html::endForm();  ?>
        <?= Html::a('更新rtu数据', ['updatertu','id' =>$rtutablename], ['class' => 'btn btn-success']) ?>

      <div style="text-align:center">  
          <?= Html::a('数据分析', ['alldataanalysis','id' =>$rtutablename], ['class' => 'btn btn-success']) ?>
      </div>
    </p>


<div id="mytable">
<!--startprint-->  
     
        <!--= Html::a('数据分析', ['alldataanalysis','id' =>$rtutablename], ['class' => 'btn btn-success'])-->
      <!-- php $form=ActiveForm::begin(); 
      = $form->field($model,'emal') 
      = $form->field($model,'sitenumber')->dropDownList(ArrayHelper::map($data,'sitenumber','sitenumber'),['prompt'=>'select rtu']) ?>
      php ActiveForm::end(); 
       =Html::submitButton('testbtn'.$rtutablename);?>
           =Html::beginForm(['/waterMonitor/water-singlertustation/selectrtu','post']);-->
       <!-- = Html::dropDownList('rtuname', $rtutablename,ArrayHelper::map($data,'sitenumber',
               'sitenumber'),['onchange'=>new JsExpression("function(event,ui){console.log(ui);if($('#rtuname').val()=='6787'){page.reload();}"),'prompt'=>'请选择:rtu表','style'=>'width:120px']);-->
       <!--= Html::dropDownList('rtuname', $rtutablename,ArrayHelper::map($data,'sitenumber',
               'sitenumber'),['onchange'=>'this.form.submit()','prompt'=>'请选择:rtu表','style'=>'width:120px']); ?>
         --> 


<div class="mytitle"></div>
<?=GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'state',
            'statno',
            'waterlv',
            'rainfall',
            'rainfallmulti',
            'waterflow',
            'watertemp',
            'volte',
            'date',
            // 'bakup1',
            // 'bakup2',
            // 'rainfallmulti',
            //  'waterlvmulti',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
<!--endprint-->
</div>
</div>
