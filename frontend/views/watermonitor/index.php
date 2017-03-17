<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\WaterMonitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '水文监测';
$this->params['breadcrumbs'][] = $this->title;
?>
<head>
 <meta http-equiv='refresh' content ="360">
</head>
<div class="water-monitor-index" style="position:absolute;z-index:1;">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
<!-- test -->
<!--
    <p>
        <?= Html::a('Create Water Monitor', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
--> 
    <?= GridView::widget([
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
           // ['class' => 'yii\grid\CheckboxColumn'],
           // 'onlinestat',
          
       ['label'=>'在线状态',
        'attribute'=>'onlinestat',
        'format'=>'raw', 
        'value'=>function($model){
       $state=['0'=>'离线','1'=>'在线'];   
if(@$state[$model->onlinestat]=='离线')
{
return  Html::img("/WlMonitor/frontend/web/public/backgroundimg/11.png");
}
else if(@$state[$model->onlinestat]=='在线')
{
//return Html::img($model->onlinestat,['width'=>40,'height'=>40,'background-color'=>'red']);
return  Html::img("/WlMonitor/frontend/web/public/backgroundimg/12.png");
}
}
],
//['attribute'=>'background-img',
//'format'=>['background-img',['width'=>'10','height'=>'10']],
//'value'=>function ($data){
//return $data->background-img;
//}],
        //    ['label' =>'在线状态',
          //     'attribute'=>'onlinestat',
            //   'value'=> function($model){
              //           $state=[
                //                   
                  //                '0' =>'离线' 
                    //               ,
                      //            '1' =>    //'['class'=>'yii\grid\CheckboxColumn']',
                        //          '在线',
                          //     ];  
                      //   return $state[$model->onlinestat]
                  // ;
               //  } 
        //     ],
            'datetime',
            ['class' => 'yii\grid\ActionColumn',
                         'template'=>'{view}',
                         'template'=>'{state}',
                         ],
                       //  'Action'=>['print'=>['label'=>'打印 ' ]    ]],
            
           //  ['class' => 'yii\grid\DataColumn'],
           // ['class' => 'yii\grid\GridView'],
        ],
    ]); ?>
</div>


