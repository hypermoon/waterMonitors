<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\grid\GridView;
//namespace yii\Base\Widget;
//namespace yii\web\Widget;
/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'RTU遥测站历史数据';
$this->params['breadcrumbs'][] = $this->title;

//  if(!isset($rtutablename))
  //          $rtutablename = '默认表';

       // <?= Html::a('创建记录', ['create'], ['class' => 'btn btn-success']) 
         //>
?>
<div class="water-singlertustation-index">

    <h2><?= Html::encode($this->title) ?></h2>

    <p>
        <?=Html::beginForm(['/waterMonitor/water-singlertustation/selectrtu','id'=>$rtutablename]);?>
         <?= Html::dropDownList('rtuname',$rtutablename,ArrayHelper::map($data,'sitenumber','sitenumber'),['onchange'=>'this.form.submit()','prompt'=>'请选择:rtu表','style'=>'width:120px']); ?>
     
        <?=Html::label(' RTU编号:'.$rtutablename); ?>
        <?= Html::endForm();  ?> 
        <?=Html::a('更新RTU数据',['updatertu','id'=>$rtutablename],['class' =>'btn btn-success']) ?>
        <div style="text-align:center">
        <?= Html::a('数据分析', ['alldataanalysis','id' =>$rtutablename,'page' =>$page], ['class' => 'btn btn-success']) ?>
        </div> 
   </p>
  
      
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

</div>
