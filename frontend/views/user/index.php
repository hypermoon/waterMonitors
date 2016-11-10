<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\User */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title ='用户管理';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('新建用户', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'username',
			'nickname',
            'jpin',
			'workunit',
			'qpin',
			//'mobile',
			//'qqnum',
			//'created_at',
			//'updated_at',
            //'id',
            //'account',
            //'password',
            //'auth_key',
            //'password_hash',
            // 'password_reset_token',
            // 'created_at',
            // 'updated_at',
            // 'role',
            // 'username',
            // 'jpin',
            // 'qpin',
            // 'nickname',
            // 'sex',
            // 'idcard',
            // 'stdid',
            // 'nation',
            // 'birthday',
            // 'mobile',
            // 'qqnum',
            // 'wechat',
            // 'homephone',
            // 'email:email',
            // 'image',
            // 'sorting',
            // 'address',
            // 'orgin',
            // 'father',
            // 'fathphone',
            // 'guardian',
            // 'gdphone',
            // 'midschool',
            // 'status',
            // 'mather',
            // 'mathphone',
            // 'university',
            // 'specialty',
            // 'level',
            // 'workunit',
            // 'quarters',
            // 'position',
            // 'nexus',
            // 'workphone',
            // 'remark',
            // 'grow',
            // 'schfrom',
            // 'midtid',
            // 'trasaction_id',
            // 'studentpay',
            // 'alternativephone',
            // 'signature:ntext',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>
