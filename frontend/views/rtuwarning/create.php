<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model res\waterMonitors\common\models\Rtuwarning */

$this->title = 'Create Rtuwarning';
$this->params['breadcrumbs'][] = ['label' => 'Rtuwarnings', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtuwarning-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
