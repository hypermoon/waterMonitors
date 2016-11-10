<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\User */
// $model->id

$this->title = '修改用户: ' . ' ' . $model->nickname;
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->nickname, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';
?>
<div class="user-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
