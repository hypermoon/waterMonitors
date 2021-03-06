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

   //print_r($wtrows);
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
//echo Highmaps::widget([
//'options'=>[
//'chart'=>[],
//'mapNavigation'=>['enabled'=>'ture'],
//'title'=>['text'=>'地图'],
//'subtitle'=>['text'=>'临时数据'],
//'series'=>[[
//'name'=>['省份'],
//'mapData'=>['Hihtcharts.maps'=>'countries/cn/custom/cn-all-sar-taiwan'],
//'joinBy'=>['hc-key'],
//'data'=>['hc-key'=>'cn-z','value'=>'22'],
//'dataLabels'=>['enabled'=>'true','crop'=>'false','overflow'=>'none'],
//],
//],
//'credits'=>['text'=>'地图','href'=>'http://daxueba.net'],
//],
//]),

 echo Highcharts::widget([
   'options' => [
      'chart'=>['type'=>'bar'],
      'title' => ['text' => '数据分析'],
      'xAxis' => [
       'categories' => ['Apples', 'Bananas', 'Oranges'], 
       'crosshair'=>true,
    // datetime 
         'datetime' => $daterows,
        ['Apples', 'Bananas', 'Oranges'], //datetime
        'datetime' =>['02:49:3','03:49:3','04:39:3','05:39:3','06:39:3','07:39:3','09:39:3'], //       
      //'dateTimeLabelFormats' => ['day':%e of %b], 
         'type'=>'datetime',
         'categories' =>$daterows, 
        'datetime'=> ['02:49:3','03:49:3','04:39:3','05:39:3','06:39:3','07:39:3','09:39:3'],
       //'dateTimeLabelFormats'=>[ day=>[%m-%d]],
       ],
      'yAxis' => [
         'title' => ['text' => '数值范围'],
         'text'=>'Waterlv',  'labels'=>['formatter'=>function(){  return this.value.toFixed(2);  }  ],  'allowDecimals'=>'true'
      ],
       'plotOptions'=>['column'=>['dataLabels' =>[ 'enabled'=>true ]]],
       'series' => [
       ['type'=>'bar'],
       ['name' => '水位(m)', 'data' =>$wtrows],      // $john]             
           ['name' => '最近5分钟雨量(mm)', 'data' =>$rfrows],      // $john]
           ['name' => '水温(C)', 'data' =>$wtmprows],      // $john]
]]]);

/*echo Highcharts::widget([
'options'=>[
//'chart'=>['name'=>'中国'],
'title'=>['text'=>'中国地图'],
'subtitle'=>['text'=>'中国地图','floating'=>'true','align'=>'right','y'=>50,'style'=>['fontSize'=>'16px']],
'mapNavigation'=>['enabled'=>true,'buttonOptions'=>['verticalAlign'=>'bottom']],
//'plotOptions'=>['map'=>['states'=>['hover'=>'color'=>['#EEDD6']]]],
'series'=>['data'=>'data','name'=>'中国','dataLables'=>['enabled'=>true,'format'=>'point.properties.cn-name']],
'drilldown'=>['activeDataLabelStyle'=>['color'=>'#ffffff','textDecoration'=>'none','textShadow'=>'0 0 3px #000000'],'drillUpButton'=>['relativeTo'=>'spacingBox'],['position'=>['x'=>'0'],['y'=>'60']]],
//'data'=['hc-key'=>'cn-zj','value'=>'22'],
]]) */
?>

<head>
<meta http-equiv='refresh'>
</head> 
<div class="contactform wow slideInRight" data-wow-duration="0.8">
<!--<div class="map" id="dituContent" style="width:100%;height:500px;border:#ccc solid 1px;float:right;"></div>
<script type="text/javascript" src="http://api.baidu.com/api?key=&v=1.1&services=true"></script>-->
<!--<script>
window.onload=function(){
function initMap(){
createMap();//创建地图
setMapEvent();//设置地图事件 
addMapControl();//向地图添加控件
addMarker();//向地图添加marker
}
//创建地图函数
function createMap(){
var map=new BMap.Map("dituContent");//在百度地图创建一个函数、
var point=new BMap.Point(106.233378,30.009277);
map.centerAndZoom(point,18);
window.map=map;//将map变量存储在全局

}
//地图事件设置函数
function setMapEvent(){
map.enableDragging();
map.enableScrollWheelZoom();
map.enableDoubleClickZoom();
map.enableKeyboard();

}
//地图空间添加函数
function addMapControl(){}
//标注点数组
var markerArr=[{
title:"重庆",
content:"重庆",
point:160.233378|30.009277,
isOpen:1,
icon:{
w:23,
h:25,
l；46；
t:21,
x:9,
lb:12
}
}];
//创建marker
function addMarker(){
for(var i=0;i<markerArr.length;i++){
var json=markerArr[i];
var p0=json.point.split("|")[0];
var p1=json.point.split("|")[1];
var point=new BMap.Point(p0,p1);
var iconImg=createIcon(json.icon);
var marker=new BMap.Marker(point,{
icon:iconImg
});
var iw=createInfoWindow(i);
var lable=new BMap.Lable(json.title,{
"offset":new BMap.Size(json.icon.lb - json.icon.x+10,-20)
});
marker.setLable(lable);
map.addOverlay(marker);
lable.setStyle({
borderColor:"#808080",
color:"#333",
cursor:"pointer"
});
(function(){
var index=i;
var _iw=createInfoWindow(i);
var _marker=marker;
_marker.addEventListener("click",function(){
this.openInfoWindow(_iw);
});
_iw.addEventListener("open",function(){
_marker.getLable().hide();
})
_iw.addEventListener("close",function(){
_marker.getLable().show();
})
lable.addEventListrner("click",function(){
_marker.openInfoWindow(_iw);
})
if(!!json.isOpen){
lable.hide();
_marker.openInfoWindow(_iw);
}
})()
}
}
//创建infowindow
function createInfoWindow(i){
var json=markerArr[i];
var iw=new BMap.InfoWindow("<b class='iw_poi_title' title='"+json.title+"'>"+json.title+"</b><div class='iw+poi_content'>"+json.content+"<div>");
return iw;
}
function createIcon(json){
var icon=new BMap.Icon("http://map.baidu.com/image/us_cursor.gif",new BMap.Size(json.w,json.h),{
imageOffset:new BMap.Size(-json.1,-json.t),
infoWindowOffset:new BMap.Size(json.lb+5,1),
offset:new BMap.Size(json.x,json.h)
})
return icon;
}
initMap();//创建初始化地图
}
</script>-->
</div>
