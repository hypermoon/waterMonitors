<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model res\waterMonitor\common\models\User */

$this->title = '新建用户';
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
