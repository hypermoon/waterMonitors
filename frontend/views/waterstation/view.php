<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Waterstation */

$this->title = $model->sitenumber;
$this->params['breadcrumbs'][] = ['label' => 'Waterstations', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<script>
window.onload=function(){
var imgid=$("#imgid").text();
var myimg=document.getElementById("myimg");
if(imgid==654321){
var img=document.getElementById("myimg").innerHTML='<img src="../../../public/backgroundimg/zd.gif" style="width:110%;margin-left:-20px;" >'; 
}else if(imgid==555555){
var img=document.getElementById("myimg").innerHTML='<img src="../../../public/backgroundimg/zd.gif" style="width:110%;margin-left:-20px;">';
}else if(imgid==332255){
var img=document.getElementById("myimg").innerHTML='<img src="../../../public/backgoundimg/zd.gif" style="width:110%;margin-left:-20px;">';
}else if(imgid==100100){
var img=document.getElementById("myimg").innerHTML='<img src="../../../public/backgroundimg/zd.gif" style="width:110%;margin-left:-20px;">';
}
}
</script>
<div class="waterstation-view">

   <h1 id="imgid">
<?=Html::encode($this->title)?>
</h1>
    <p>
        <?= Html::a('Update', ['update', 'id' => $model->sitenumber], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->sitenumber], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'sitenumber',
            'stationame',
            'fatherpoint',
            'desciber',
            'bakup',
        ],
    ]) ?>
<div id="myimg"></div>
</div>
