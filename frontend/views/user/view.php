<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\User */

$this->title = $model->nickname;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [

		'username',
		'nickname',
		'jpin',
		'workunit',
		'qpin',
		'mobile',
		'qqnum',
		
        ],
    ]) ?>

</div>
