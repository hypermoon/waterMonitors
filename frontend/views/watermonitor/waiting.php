<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\WaterMonitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '召测中,请稍候...';
$this->params['breadcrumbs'][] = $this->title;
?>
<script type="text/javascript">
    function delay(a,id){
         a--;
        // alert(id);
         document.getElementById("timer").value = a;
         var jz = document.getElementById("jz");
               //   jz.innerHTML = "<img src='/public/delay.gif'>";
            //       jz.innerHTML="id";
               jz.innerHTML="<img src='<?php echo Yii::getAlias('@web');?>/public/gif/delay.gif'>";      
         if(a<=0)
          {
            document.getElementById("timer").value=window.location.href= "remotecallrtu?id="+id;      //"next.html";
          }
         else
          {
           setTimeout("delay(" +a+","+id+")" ,1000);
          }
    }

</script>
<div class="water-monitor-index">

    <h1><?= Html::encode($this->title) ?></h1>

     <script >
       // <?php echo $id // echo $this->render('_search', ['model' => $searchModel]); ?>
     // <?php echo "javascript:delay(16);";?>
      //  <img src='<?php echo Yii::getAlias('@web');?>/public/gif/delay.gif'>;      
     // <button name="open_date" id="y_open"  type="text" onclick="javascript:delay(7)"  > 
     </script>
         <body name="wait" id="wait1" onload="javascript:delay(18, <?php echo$id ?> )"  >
               <div id="timer">
                    <div id="jz">
                                <br/> 
                                <br/> 
                           
                            <div>
                                <br/> 
                            </div>
                    </div>
               </div> 

        </body>
 <!--   = GridView::widget([
//        'dataProvider' => $dataProvider,
 //       'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            'site',
            'current_site',
            //'individual_monitoring',
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
