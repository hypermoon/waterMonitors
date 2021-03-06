<?php

use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\WaterMonitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RTU召测数据';
$this->params['breadcrumbs'][] = $this->title;



          
       //foreach($dataProvider->getModels() as $models){
      // $models = $dataProvider->getModels();

    //echo var_dump($models->current_site);
      
   //       $site = ArrayHelper::toArray($models->current_site);
         // echo $site;
        //  $img1= ArrayHelper::toArray($models->img1);
        //  $img2 = ArrayHelper::toArray($models->img2);
     //}



?>
<script src="../../../../vendor/yiisoft/yii2/web/test.js"></script>

<div class="water-monitor-index">

    <h1><?= Html::encode($this->title) ?></h1>
    


    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>


<!--
    <p>
        <?= Html::a('Create Water Monitor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
--> 
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,i
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
            'accumulator',
            'sluice',
            'datetime',
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

<?php
    $datas = "";

    foreach ($models as $lists) {
        $datas.=$lists['current_site']."*".$lists['img1']."*".$lists['img2']."*".$lists['datetime']."[then]" ; 
         }

         $dataArray =explode("[then]", $datas);
       
        for($n=0; $n<count($dataArray)-1; $n++ )
        {
            $dataList = explode("*", $dataArray[$n]);
        
 ?>
      
 <table border="1" cellspacing="0" bordercolor="#3498db" cellpadding="5" width="90%" bgcolor="#666666" align="center">
		<tbody style="border:2px solid #3498db;">
			<tr bgcolor="#f5fafa" style="border:2px solid #3498db;">
				<td style="font-size: 22px" bgcolor="#a0d3e0" height="40"  style="height:40px;" colspan="8" align="center"><strong>召测图片</strong></td>
			</tr>
	
   		       <tr bgcolor="#f5fafa">
				<td nowrap="nowrap" style="height:50px;border:2px solid #3498db;">
	                        <?=$model['site']?>&nbsp;&nbsp;<a href='#' onclick="javascript:test(1,1);"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img1.png" style="margin-left:25%;"></a>&nbsp;&nbsp;<a href='#' onclick="javascript:test(1,2);"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img2.png" style="margin-left:25%;"></a>
				</td>
				<td style="border:2px solid #3498db;" nowrap="2" colspan="2"><?=trim($model['img1'])?></td>
			</tr>

			<tr bgcolor="#f5fafa">
				<td rowspan="9" nowrap="nowrap" width="15%" heigth = "200%" style="border:2px solid #3498db;">
					<div id="img1"><img src="<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dataList[0])?>/<?=trim($dataList[1])?>"class="img-indent" style="width:100%;height:100%;"/></div>
					<div id="img2" style="display:none;"><img src="<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dataList[0])?>/<?=trim($dataList[1])?>" class="img-indent" style="width:100%;height:100%;"/></div>
				</td>
	<td nowrap="nowrap" width="20%" style="height:50px;border:2px solid #3498db;"><p style="text-indent:6px;" >时间</p></td>
				<td nowrap="nowrap" width="20%" style="border: 2px solid #3498db;"><?=trim($dataList[3])?></td>
			</tr>

                      <tr bgcolor="#f5fafa" style="height:50px;border:2px solid #3498db;">
				<td nowrap="nowrap" style="border:2px solid #3498db;"><p  style="text-indent:6px;" > 当前站点 </p></td>
				<td nowrap="nowrap" style="border:2px solid #3498db;"><?=trim($dataList[0])?></td>
			</tr>

		</tbody>
	</table>

<?php
 }
 ?>

</div>


<img src="<?php echo Yii::getAlias('@web');?>/public/backgroundimg/zd.gif" style="width:110%;margin-left:-20px;">
