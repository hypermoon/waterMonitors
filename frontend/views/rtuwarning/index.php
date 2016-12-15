<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

<<<<<<< HEAD
$this->title = 'Rtu报警信息';
=======
$this->title = 'Rtuwarnings';
>>>>>>> 9c4aeb56d7c2ba56f7e0b2a557161843ec46d9db
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="rtuwarning-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
<<<<<<< HEAD
        <!-- = Html::a('Create Rtuwarning', ['create'], ['class' => 'btn btn-success']) -->
=======
        <?= Html::a('Create Rtuwarning', ['create'], ['class' => 'btn btn-success']) ?>
>>>>>>> 9c4aeb56d7c2ba56f7e0b2a557161843ec46d9db
    </p>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

<<<<<<< HEAD
            //'id',
=======
            'id',
>>>>>>> 9c4aeb56d7c2ba56f7e0b2a557161843ec46d9db
            'rtuno',
            'date',
            'waterlv',
            'rainfall',
<<<<<<< HEAD
             'volte',
            // 'originstr:ntext',
             'bakup',
=======
            // 'volte',
            // 'originstr:ntext',
            // 'bakup',
>>>>>>> 9c4aeb56d7c2ba56f7e0b2a557161843ec46d9db

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
</div>
