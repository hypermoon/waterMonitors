<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Zootst */

$this->title = 'Create Zootst';
$this->params['breadcrumbs'][] = ['label' => 'Zootsts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="zootst-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
