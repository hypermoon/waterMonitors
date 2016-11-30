<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\WaterMonitor */
/* @var $dataProvider yii\data\ActiveDataProvider */
date_default_timezone_set('prc');
$this->title = '远程维护';
$this->params['breadcrumbs'][] = $this->title;
 
$vals = '0';
  
   if(!isset($rtutablename))
            $rtutablename = ' ';
?>
<script type="text/javascript">
        // echo "<script language=\"javascript\">alert(\"caomang\");  
         // echo "alert(\'caomang\');";     
        // alert('233'); 
      $vvv= showTime();     
   function showTime() {
        var myArray = new Array(7);
        var TD = new Date();
        myArray[0] ="星期日";
        myArray[1] ="星期一";
        myArray[2] ="星期二";
        myArray[3] ="星期三";
        myArray[4] ="星期四";
       myArray[5] ="星期五";
        myArray[6] ="星期六";
       weekday = TD.getDay();
       var h = TD.getHours();
       var m = TD.getMinutes();
       var s = TD.getSeconds();
       var hstr = h;
       var mstr = m;
       var istr = s;
       if (h < 10) { hstr ="0"+ h };
       if (m < 10) { mstr ="0"+ m };
       if (s < 10) { istr ="0"+ s };
       // alert('cwoja3va'); 
        var imgObj1 = document.getElementById("y_open");
         	imgObj1.style.display="block";
      // $("#clock").innerHTML('当前时间：' + new Date().toLocaleDateString() +""+ myArray[weekday] +""+ hstr +":"+ mstr +":"+ istr);
      // $("#cloc1k").innerHTML('当前时间：'+ mstr +":"+ istr);
       
     //   imgObj1.value = "2007"; 
       imgObj1.value =(new Date().toLocaleDateString() +" "+ hstr +":"+ mstr +":"+ istr+" " + myArray[weekday]);
       $vals = imgObj1.value;
    //  $("#cloc1k").innerHTML("ff");
   //     alert($vals);
 
     //  $("#clock").innerHTML('当前时间：' + new Date().toLocaleDateString() +""+ myArray[weekday] +""+ hstr +":"+ mstr +":"+ istr);
 //         setTimeout(showTime, 1000);
      return $vals;  
 }
</script>
<div class="water-monitor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <p>
         <?=Html::beginForm(['/waterMonitor/watermonitor/remote','id'=>$rtutablename]);?>
         <?=Html::label(' RTU编号:'.$rtutablename) ; ?>
         <?= Html::dropDownList('rtuname', $rtutablename,ArrayHelper::map($data,'sitenumber',
               'sitenumber'),['onchange'=>'this.form.submit()' ,'prompt'=>'请选择:rtu表','style'=>'width:120px']); ?>
         
         <?= Html::endForm();  ?>
      <!--  = Html::a('Create Water Monitor', ['create'], ['class' => 'btn btn-success']) -->
    </p>
           <!-- <div style="text-align:center">  -->
    <hr> <hr/>

       <div>  
           <div id="tt1">  
           <?php  echo '系统时间:';  // echo $this->render('_search', ['model' => $searchModel]); ?>
           <?php echo date('y-m-d H:i:s',time())  ?>
           <br/>
  <!--         <input name="open_date" id="y_open" style="width:190px;" type="text" onclick="javascript:showTime()" value="点击更新时间" readonly="true" >
      -->
             <br/>
            
             <?= Html::a('设置RTU时钟', ['setime','id' => $rtutablename], ['class' => 'btn btn-success']) ?>
 
             <script>
         //    <?php echo "javascript:showTime();";  ?> 
            </script>
           </div>
      </div>

    <!--  <p >远程修改起报参数（“确定“按钮）</p>
            <-=Html::input('input','myinput','20',['onclick' =>'javascript:showTime()']) ?>             
             php echo "$vvv=javascript:showTime();";  ?> 
                  "Obj1 = document.getElementById('y_open');
                 php echo   "$vvv = Obj1.value();";  ?>
               <!-=Html::encode(Obj1 = document.getElementById('y_open')) ?>
               <! =Html::encode($vvv=javascript:showTime()) ?>
            //php  $vals = javascript:showTime();  ?> 
      <a href='#'>确定</a> -->
         <!--=Html::label(' RTU编号:'.$rtutablename,['onchange'=> 'javascript:showTime()']) ;-->


 
<!--
    = GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'site',
            'current_site',
            // 'individual_monitoring',
            //'phone',
            'current_level',
            'current_temp',
             'rainfall',
            // 'img1',
            // 'img2',
             'datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
 --> 
</div>
