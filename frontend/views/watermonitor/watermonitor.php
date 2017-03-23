<?php
use yii\widgets\LinkPager;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = '水文监测';
?>
<html>
<head>
<title><?= Html::encode($this->title) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

<script type="text/javascript">
	//图片切换
	function imageSwitcher(id,url){
		var imgObj = $("#dimg"+id);
		imgObj.html("<img src='"+url+"' class='img-indent' style='width:100%;height:100%;'/>");
	}
	
	
	function detail(ids){
		var url = "/WlMonitor/frontend/web/index.php/waterMonitor/watermonitor/watermonitordetail?ids="+ids;
		window.open(url);
	}
</script>
</head>
<body id="page1">
<script src="../../../../../vendor/yiisoft/yii2/web/test.js"></script>

<?php
$datas = "";
foreach ($models as $lists) {
	$datas.= $lists['id']."*".$lists['site']."*".$lists['individual_monitoring']."*".$lists['phone']."*".$lists['current_site']."*".$lists['current_level']."*".$lists['current_temp']."*".$lists['rainfall']."*".$lists['img1']."*".$lists['img2']."*".$lists['datetime']."[then]";
}

$dataArray = explode("[then]",$datas);
for($n=0;$n<count($dataArray)-1;$n++){
	$dagaList = explode("*",$dataArray[$n]);
	$dagaList2 = explode("*",$dataArray[$n+1]);
	if($dagaList2[0]==""){
		$dagaList2 = array("","","","","","","","","","","","","",);
	}
	//echo $dagaList2[1];exit;
	$n = $n + 1;
?>	
	
<table border="1" cellspacing="0" bordercolor="#3498db" cellpadding="5" width="90%" bgcolor="#666666" align="center">
	<tbody>
		<tr bgcolor="#f5fafa">
			<td nowrap="nowrap">
				<?=$dagaList[1]?>&nbsp;&nbsp;<a href='#' onclick="javascript:imageSwitcher('<?=$dagaList[0]?>','<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dagaList[4])?>/<?=trim($dagaList[8])?>');"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img1.png"></a>&nbsp;&nbsp;
				<a href='#' onclick="javascript:imageSwitcher('<?=$dagaList[0]?>','<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dagaList[4])?>/<?=trim($dagaList[9])?>');"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img2.png"></a>
			</td>
			<td nowrap="nowrap" colspan="2"><?=$dagaList[2]?>&nbsp;&nbsp;<?=$dagaList[3]?></td>
			<td rowspan="6" nowrap="nowrap">&nbsp;</td>
			<td nowrap="nowrap">
				<?=$dagaList2[1]?>&nbsp;&nbsp;<a href='#' onclick="javascript:imageSwitcher('<?=$dagaList2[0]?>','<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dagaList2[4])?>/<?=trim($dagaList2[8])?>');");"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img1.png"></a>&nbsp;&nbsp;
				<a href='#' onclick="javascript:imageSwitcher('<?=$dagaList2[0]?>','<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dagaList2[4])?>/<?=trim($dagaList2[9])?>');");"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img2.png"></a>
			</td>
			<td nowrap="nowrap" colspan="2"><?=$dagaList2[2]?>&nbsp;&nbsp;<?=$dagaList2[3]?></td>
		</tr>
		<tr bgcolor="#f5fafa">
			<td rowspan="5" nowrap="nowrap" width="30%">
			<?php
				if($dagaList[8]!=""){
			?>
				<div  style="position:relative;"id="dimg<?=$dagaList[0]?>"><img src="<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dagaList[4])?>/<?=trim($dagaList[8])?>" alt="" class="img-indent" style="width:100%;height:100%;"/>
<div style= "position:absolute;bottom:0px;right:0px;color:red;"><?=trim($dagaList[10])?></div>
</div>
			<?php
				}else{
			?>
				<div style="position:relative;" id="dimg<?=$dagaList[2]?>"><img src="<?php echo Yii::getAlias('@web');?>/public/images/null.jpg" alt="" class="img-indent" style="width:100%;height:100%;"/>
<div style="position:absolute;bottom:0px;right:0px;color:red;"><?=trim($dagaList[10])?></div>
</div>
			<?php			
				}
			?>
			</td>
			<td nowrap="nowrap" width="10%"><p >当前站号</p></td>
			<td nowrap="nowrap" width="10%"><?=$dagaList[4]?></td>
			<td rowspan="5" nowrap="nowrap" width="30%">
				<?php
					if($dagaList2[8]!=""){
				?>
					<div style="position:relative;" id="dimg<?=$dagaList2[0]?>"><img src="<?php echo Yii::getAlias('@web');?>/public/rtuimgs/<?=trim($dagaList2[4])?>/<?=trim($dagaList2[8])?>" alt="" class="img-indent" style="width:100%;height:100%;"/>
<div style="position:absolute;right:0px;bottom:0px;color:red;"><?=trim($dagaList[10])?></div>
</div>
				<?php
					}else{
				?>
					<div style="position:relative;" id="dimg<?=$dagaList2[0]?>"><img src="<?php echo Yii::getAlias('@web');?>/public/images/null.jpg" alt="" class="img-indent" style="width:100%;height:100%;"/>
<div style="position:absolute;bottom:0px;right:0px;color:red;"><?=trim($dagaList[10])?></div>
</div>
				<?php
					}
				?>
			</td>
			<td nowrap="nowrap" width="10%"><p >当前站号</p></td>
			<td nowrap="nowrap" width="10%"><?=$dagaList2[4]?></td>
		</tr>
		<tr bgcolor="#f5fafa">
			<td nowrap="nowrap"><p >当前水位</p></td>
			<td nowrap="nowrap"><?=$dagaList[5]?>&nbsp;米</td>
			<td nowrap="nowrap"><p >当前水位</p></td>
			<td nowrap="nowrap"><?=$dagaList2[5]?>&nbsp;米</td>
		</tr>
		<tr bgcolor="#f5fafa">
			<td nowrap="nowrap"><p >当前水温</p></td>
			<td nowrap="nowrap"><?=$dagaList[6]?>&nbsp;度</td>
			<td nowrap="nowrap"><p >当前水温</p></td>
			<td nowrap="nowrap"><?=$dagaList2[6]?>&nbsp;度</td>
		</tr>
		<tr bgcolor="#f5fafa">
			<td nowrap="nowrap"><p >24小时雨量</p></td>
			<td nowrap="nowrap"><?=$dagaList[7]?>&nbsp;毫米</td>
			<td nowrap="nowrap"><p >24小时雨量</p></td>
			<td nowrap="nowrap"><?=$dagaList2[7]?>&nbsp;毫米</td>
		</tr>
		<tr bgcolor="#f5fafa">
			<td nowrap="nowrap"><p >详细情况</p></td>
			<td nowrap="nowrap">
			<?php 
				if($dagaList[0]!=""){
			?>
				<a href="/WlMonitor/frontend/web/index.php/waterMonitor/watermonitor/watermonitordetail?ids=<?=$dagaList[0]?>" target="
			_block">查看</a>
			<?php 	
				}
			?>
			</td>
			<td nowrap="nowrap"><p >详细情况</p></td>
			<td nowrap="nowrap">
			<?php 
				if($dagaList2[0]!=""){
			?>
				<a href="/WlMonitor/frontend/web/index.php/waterMonitor/watermonitor/watermonitordetail?ids=<?=$dagaList2[0]?>" target="
			_block">查看</a>
			<?php 	
				}
			?>
			</td>
		</tr>
	</tbody>
</table>
	
<?php
}

// 显示分页
echo LinkPager::widget([
    'pagination' => $pagination,
    'firstPageLabel'=>"首页",
    'prevPageLabel'=>'上一页',
    'nextPageLabel'=>'下一页',
    'lastPageLabel'=>'末页',
]);
?>
</body>
</html>
