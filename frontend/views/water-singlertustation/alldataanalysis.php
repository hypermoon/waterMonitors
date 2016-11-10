<?php

use yii\helpers\ArrayHelper;
use miloschuman\highcharts\Highcharts;
//use miloschuman\highcharts\Highcharts\modules\data;

           // 'id',
           // 'state',
           // 'statno',
           // 'waterlv',
           // 'rainfall',
           // 'watertemp',
           // 'date',
           // 'bakup1',
           // 'bakup2',
// echo "column\n";

 $jans = array(3,0,8);
 $john = array(4,7,9);
   
    $idrows = [];
    $wtrows = [];
    $rfrows = [];
    $wtmprows = [];
    $daterows = [];
   
    foreach($dataProvider->getModels() as $model){
          $idrows[] = ArrayHelper::toArray($model->id);
          $wtrows[] = ArrayHelper::toArray($model->waterlv);
          $rfrows[] = ArrayHelper::toArray($model->rainfall);
          $wtmprows[] = ArrayHelper::toArray($model->watertemp);
          $daterows[] = (ArrayHelper::toArray(substr($model->date,10,8)));
    }
     /*    echo "id:<br/>";
         print_r($idrows);
         echo "waterlv:<br/>";
         print_r($wtrows);
         echo "rainfall:<br/>";
         print_r($rfrows);
         echo "tempory:<br/>";
         print_r($wtmprows);
         echo "date:<br/>";
         print_r($daterows);
         // echo $rows[1];
          echo "1111111";
          echo var_dump($wtrows);
          // echo var_dump($rfrows);
        */

 echo Highcharts::widget([
   'options' => [
      'title' => ['text' => '数据分析'],
      'xAxis' => [
       //  'categories' => ['Apples', 'Bananas', 'Oranges']  //datetime
       //   'datetime' => $daterows   //['Apples', 'Bananas', 'Oranges']  //datetime
       //   'datetime' =>['02:49:3','03:49:3','04:39:3','05:39:3','06:39:3','07:39:3','09:39:3'],
         // dateTimeLabelFormats => [
          //      day:'%e of %b'
         // ]
       //  'type'=>'datetime',
         'categories' =>$daterows,  //['02:49:3','03:49:3','04:39:3','05:39:3','06:39:3','07:39:3','09:39:3'],
         
         'dateTimeLabelFormats'=>[
               'day'=>'%m-%d'
          ]
       ],
      'yAxis' => [
         'title' => ['text' => '数值范围'],
         // 'text'=>'Waterlv',
         // 'labels'=>['formatter'=>function(){
         //                  return this.value.toFixed(2);
         //          }
        // ],
        // 'allowDecimals'=>'true'
      ],
       'plotOptions'=>[
           'line'=>[
                'dataLabels' =>[
                     'enabled'=>true
                ]
          ]
       ],
      'series' => [
   //      ['name' => 'Jane', 'data' => [1.2, 0.3, 4.4,2.4,5.7,5.3,7.4,8.5]],
    //     ['name' => 'John', 'data' => [5, 7, 3]]
  
  //        ['name' => 'isd', 'data' =>$idrows],      // $jans],
           ['name' => '水位(m)', 'data' =>$wtrows],      // $john]
             
           ['name' => '24小时雨量(mm)', 'data' =>$rfrows],      // $john]
           ['name' => '水温(C)', 'data' =>$wtmprows],      // $john]
      ]
   ]
]);
 
?>
