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
if($state[$model->onlinestat]=='离线'){
return  Html::img("/WlMonitor/frontend/web/public/backgroundimg/11.png");
}
else if($state[$model->onlinestat]=='在线'){
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
<div class="demo_main" style="position:relative;left:0px;top:500px;">
<fieldset class="demo_content">
<div style="min-height:300px;width:100%;" id="map">
</div>
<script type="text/javascript" src="http://api.map.baidu.com/library/AreaRestriction/1.2/src/AreaRestriction_min.js"></script>
<script>
var markerArr=[
//{title:"名称：重庆南岸区",point:"106.564639,29.530529",address:"重庆市南岸区",tel:"123"},
//{title:"名称：重沙坪坝坝",point:"106.464029,29.549634",address:"重庆是沙坪坝区",tel:"123"},
//{title:"名称：重庆江北",point:"106.59051,29.61597",adrress:"重庆江北区",tel:"123"}
];
function map_init(){
var map=new BMap.Map("map",{minZoom:15,maxZoom:19});
//var point=new BMap.Point(106.562339,29.570746);
map.centerAndZoom(new BMap.Point(106.569813,29.531032),15);
map.enableScrollWheelZoom();
map.addControl(new BMap.MapTypeControl());
var b=new BMap.Bounds(new BMap.Point(106.5724,29.521981),new BMap.Point(106.582318,29.538951));
try{
BMapLib.AreaRestriction.setBounds(map,b);
}catch(e){
alert(e);
}
var ctrlNav=new window.BMap.NavigationControl({
anchor:BMAP_ANCHOR_TOP_LEFT,
type:BMAP_NAVIGATION_CONTROL_LARGE
});
map.addControl(ctrlNav);
var ctrlOve=new window.BMap.OverviewMapControl({
anchor:BMAP_ANCHOR_BOTTOM_RIGHT,
isOpen:1
});
map.addControl(ctrlOve);
var ctrlSca=new window.BMap.ScaleControl({
anchor:BMAP_ANCHOR_BOTTOM_LEFT
});
map.addControl(ctrlSca);
var point=new Array();
var marker=new Array();
var info=new Array();
for(var i=0;i<markerArr.length;i++){
var p0=markerArr[i].point.split(",")[0];
var p1=markerArr[i].point.split(",")[1];
point[i]=new window.BMap.Point(p0,p1);
marker[i]=new window.BMap.Marker(point[i]);
map.addOverlay(marker[i]);
marker[i].setAnimation(BMAP_ANIMATION_BOUNCE);
var label=new window.BMap.Label(markerArr[i].title,{offset:new window.BMap.Size(20,-10)});
marker[i].setLabel(label);
info[i]=new window.BMap.InfoWindow("<p style='font-size:12px;line-height:25px;'>"+markerArr[i].title+"</br>地址:"+markerArr[i].address+"</br>电话:"+markerArr[i].tel+"</br></p>");
};
marker[0].addEventListener("mouseover",function(){
this.openInfoWindow(info[0]);
});
marker[1].addEventListener("mouseover",function(){
this.openInfoWindow(info[1]);
});
marker[2].addEventListener("mouseover",function(){
this.openInfoWindow(info[2]);
});
}
//异步调用百度js
function map_load(){
var load=document.createElement("script");
load.src="http://api.map.baidu.com/api?v=1.4&callback=map_init";
document.body.appendChild(load);
};
window.onload=map_load;
//window.onload=function(){
  //   map_load();
//}
</script>
</fieldset>
</div>

<!--<script>
window.onload=function(){
//创建和初始化地图
function initMap(){
createMap();
setMapEvent();
addMapControl();
addMarker();
}
//创建地图函数
function createMap(){
var mamp=new BMap.Map("dituContent");
var point=new BMap.Point(106.233378,30.009277);
map.centerAndZoom(point,18);
window.map=mmap;
}
//地图设置函数
function setMapEvent(){
map.enableDragging();
map.enableScrollWheelZoom();
map.enableKeyboard();
}
//地图空间添加函数
function addMapContrl(){
var markerArr=[{
title:"重庆市",
content:"重庆书",
point:"106.233378|30.009277",
isOpen:1,
icon:{
w:23,
h:25,
l:46,
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
var label=new BMap.Label(json.title,{
"offset":new BMap.Size(json.icon.lb - json.icon.x + 10,-20)
});
marker.setLabel(label);
map.addOverlay(marker);
label.setStyle({
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
_iw.addEventListener("open"，function(){
_marker.getLabel().hide();
})
_iw.addEventListener("close",function(){
_marker.getLabel().show();
})
label.addEventListener("click",function(){
_marker.openInfoWindow(_iw);
})
if(!!json.isOpen){
label.hide();
_marker.openInfoWindow(_iw);
}
})()
}
}
//创建infowindow
function createInfoWindow(i){
var json=markerArr[i];
var iw=new BMap..InfoWindow("<b class='iw_poi_title' title='"+json.title+"'>" +json.title+"'>"+json.title+"</b><div class='iw_poi_content'>"+json.con+"</div>");
return iw;
}
//创建一个icon
function createIcon(json){
var icon=new BMap.Icon("http://app.baidu.com/map/images/us_mk_icon.png", new BMap.Size((json.w,json.h),{
imageOffset:new BMap.Size(-json.l,-json.t),
infoWindowOffset:new BMap.Size(json.lb+5,1),
offset:new BMap.Size(json.x,json.h)
})
return icon;
}
initMap();
}
}
</script>-->

<img src="<?php echo Yii::getAlias('@web');?>/public/backgroundimg/sk-03.gif" style="width:150%;margin-top:400px;margin-left:-50px;">
