<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = '水文监测';
?>
<html>
<head>
<title><?= Html::encode($this->title) ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
	function test(type,code){
		if(code==1){
			var imgObj1 = document.getElementById("img1");
			var imgObj2 = document.getElementById("img2");
			var imgObj3 = document.getElementById("test");
			imgObj1.style.display="block";
			imgObj2.style.display="none";
                         imgObj3.innerHTML = "555995";
		}else{
			var imgObj1 = document.getElementById("img1");
			var imgObj2 = document.getElementById("img2");
			var imgObj3 = document.getElementById("test");
			imgObj1.style.display="none";
			imgObj2.style.display="block";
                         imgObj3.innerHTML = "<?=trim($model['img2'])?>";
		}
	}
</script>
</head>

<body id="page1">
	 <table border="1" cellspacing="0" bordercolor="#3498db" cellpadding="5" width="90%" bgcolor="#666666" align="center">
		<tbody>
			<tr bgcolor="#f5fafa">
				<td style="font-size: 22px" bgcolor="#a0d3e0" height="40" colspan="8" align="center"><strong>水位监测</strong></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap">
					<?=$model['site']?>&nbsp;&nbsp;<a href='#' onclick="javascript:test(1,1);"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img1.png"></a>&nbsp;&nbsp;<a href='#' onclick="javascript:test(1,2);"><img src="<?php echo Yii::getAlias('@web');?>/public/images/img2.png"></a>
				</td>
				<td nowrap="2" colspan="2" ><p id="test"> <?=trim($model['img1'])?> </p> </td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td rowspan="9" nowrap="nowrap" width="30%">
					<div id="img1"><img src="<?php echo Yii::getAlias('@web');?>/public/rtuimg/<?=trim($model['current_site'])?>/<?=trim($model['img1'])?>" class="img-indent" style="width:100%;height:100%;"/></div>
					<div id="img2" style="display:none;"><img src="<?php echo Yii::getAlias('@web');?>/public/rtuimg/<?=trim($model['current_site'])?>/<?=trim($model['img2'])?>" class="img-indent" style="width:100%;height:100%;"/></div>
				</td>
				<td nowrap="nowrap" width="10%"><p >时间</p></td>
				<td nowrap="nowrap" width="10%"><?=$model['datetime']?></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p > 当前站点.test(1,1) </p></td>
				<td nowrap="nowrap"><?=$model['current_site']?></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p >蓄电池电压</p></td>
				<td nowrap="nowrap"><?=$model['accumulator']?></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p >水位</p></td>
				<td nowrap="nowrap"><?=$model['current_level']?></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p >水温</p></td>
				<td nowrap="nowrap"><?=$model['current_temp']?></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p >堰水计</p></td>
				<td nowrap="nowrap"><?=$model['sluice']?></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p >雨量</p></td>
				<td nowrap="nowrap"><?=$model['rainfall']?></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p >远程校时（“校准”按钮）</p></td>
				<td nowrap="nowrap"><a href='#'>校准</a></td>
			</tr>
			<tr bgcolor="#f5fafa">
				<td nowrap="nowrap"><p >远程修改起报参数（“确定“按钮）</p></td>
				<td nowrap="nowrap"><a href='#'>确定</a></td>
			</tr>
		</tbody>
	</table>
</body>
</html>
