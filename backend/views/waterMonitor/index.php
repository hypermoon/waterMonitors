<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel res\waterMonitor\common\models\search\WaterMonitor */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Water Monitors';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="water-monitor-index">

    <script type="html/x-tamplate" id="adv_search_container_water-monitor">
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>
    </script>


<!--    // 可以删掉这里的yii风格的gridView-->
    <?php  GridView::widget([
        'id'=>'grid_water-monitor',
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'site',
            'individual_monitoring',
            'phone',
            'current_site',
            // 'current_level',
            // 'current-temp',
            // 'rainfall',
            // 'img1',
            // 'img2',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>

</div>

<div style="margin:2px 0;"></div>
<!--data-options 出现的属性对 也可以作为tabl标签的属性出现！-->
<table id="dg_water-monitor" class="easyui-datagrid" style="width:100%;height:auto"
       idField="id"
       pageSize="25"
       pageList="[25,50,80]"
       toolbar="#tb_water-monitor"
       data-options="
            fit:true,
            striped:true,
            rownumbers:true,
            singleSelect: true,
            iconCls: 'icon-save',
            url: '<?= Url::to(['grid-data']) ?>',
            pagination:true,
            method: 'get',
            onLoadSuccess:water-monitorAdmin.onDataGridLoadSuccess,
            remoteSort:true,
            multiSort:true,
            rowStyler: function(index,row){
                    if (row.listprice < 30){
                            return 'background-color:#6293BB;color:#fff;font-weight:bold;';
                    }
            }
    ">
    <thead>
    <tr>
        <th field="ck" checkbox="true"></th>
             <th data-options="field:'id',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('id' ) ?></th>
      <th data-options="field:'site',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('site' ) ?></th>
      <th data-options="field:'individual_monitoring',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('individual_monitoring' ) ?></th>
      <th data-options="field:'phone',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('phone' ) ?></th>
      <th data-options="field:'current_site',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('current_site' ) ?></th>
      <th data-options="field:'current_level',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('current_level' ) ?></th>
      <th data-options="field:'current-temp',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('current-temp' ) ?></th>
      <th data-options="field:'rainfall',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('rainfall' ) ?></th>
      <th data-options="field:'img1',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('img1' ) ?></th>
      <th data-options="field:'img2',width:80,align:'center'" sortable="true" ><?= $searchModel->getAttributeLabel('img2' ) ?></th>

    </tr>
    </thead>
</table>

<div id="tb_water-monitor" style="height:auto">

    <?= Html::beginForm('','get', ['class' => 'easy-search', ]) ?>

    <a href="javascript:void(0)" class="easyui-linkbutton"
       data-options="iconCls:'icon-add',plain:true" onclick="water-monitorAdmin.append()">
        新增
    </a>
    <a href="javascript:void(0)" class="easyui-linkbutton"
       data-options="iconCls:'icon-edit',plain:true" onclick="water-monitorAdmin.edit()">
        修改
    </a>
    <a href="javascript:void(0)" class="easyui-linkbutton"
       data-options="iconCls:'icon-remove',plain:true" onclick="water-monitorAdmin.remove()">
        删除
    </a>
<!--
    这部分用的是 EDataGrid  功能 才需要的按钮
    <a href="javascript:void(0)" class="easyui-linkbutton"
       data-options="iconCls:'icon-save',plain:true" onclick="accept()">
        Accept
    </a>
    <a href="javascript:void(0)" class="easyui-linkbutton"
       data-options="iconCls:'icon-undo',plain:true" onclick="reject()">
        Reject
    </a>
 -->

    <a href="javascript:void(0);" class="easyui-menubutton" menu="#mm_export_water-monitor" iconCls="icon-export">导出为Excel</a>


<!--  可用style布局输入框 ：   Html::activeTextInput($searchModel,'attribute',['style'=>'line-height:26px;border:1px solid #ccc' ]) ?>-->
        <span><?= $searchModel->getAttributeLabel('id') ?>:</span>
    <?= Html::activeTextInput($searchModel,'id',[]) ?>
            <span><?= $searchModel->getAttributeLabel('site') ?>:</span>
    <?= Html::activeTextInput($searchModel,'site',[]) ?>
            <span><?= $searchModel->getAttributeLabel('individual_monitoring') ?>:</span>
    <?= Html::activeTextInput($searchModel,'individual_monitoring',[]) ?>
         <?php /* ?>     <span><?= $searchModel->getAttributeLabel('phone') ?>:</span>
    <?= Html::activeTextInput($searchModel,'phone',[]) ?>
            <span><?= $searchModel->getAttributeLabel('current_site') ?>:</span>
    <?= Html::activeTextInput($searchModel,'current_site',[]) ?>
            <span><?= $searchModel->getAttributeLabel('current_level') ?>:</span>
    <?= Html::activeTextInput($searchModel,'current_level',[]) ?>
            <span><?= $searchModel->getAttributeLabel('current-temp') ?>:</span>
    <?= Html::activeTextInput($searchModel,'current-temp',[]) ?>
            <span><?= $searchModel->getAttributeLabel('rainfall') ?>:</span>
    <?= Html::activeTextInput($searchModel,'rainfall',[]) ?>
            <span><?= $searchModel->getAttributeLabel('img1') ?>:</span>
    <?= Html::activeTextInput($searchModel,'img1',[]) ?>
            <span><?= $searchModel->getAttributeLabel('img2') ?>:</span>
    <?= Html::activeTextInput($searchModel,'img2',[]) ?>
        <?php */ ?>
    <a href="javascript:void(0);" class="easyui-linkbutton"data-options="iconCls:'icon-search',plain:true" onclick="water-monitorAdmin.doSearch(this)"></a>


    <a href="javascript:void(0)" class="easyui-linkbutton" onclick="water-monitorAdmin.advSearch()">
        高级搜索
    </a>

    <?= Html::endForm() ?>

</div>
<!--下拉菜单-->
<div id="mm_export_water-monitor" style="width:100px;">
    <div>
        <a id="water-monitorExportCurrentPage" href="" class="export-grid-action"
           onclick="formPost($(this).attr('href'),{exportMode:'paging'}); return false ;">
            当前页
        </a>

    </div>
    <div>
        <a id="water-monitorExportFull" href="" class="export-grid-action"
           onclick="formPost($(this).attr('href'),{exportMode:'full'}); return false ;">
            全部
        </a>
    </div>
</div>

<?php  \year\widgets\JsBlock::begin() ?>
    <script>
        /**
         * TODO 有空了实现成原型方式实现
         * TODO 仍旧有很大的提升空间  可以提取成js文件 然后每个模型的index.php 页面只需要配置变参部分！
         *
         * 每个实体对应一个相应的管理名空间 防止相互冲突！
         * */
       function WaterMonitorAdmin(){

                this.dataGridSelector = '#dg_water-monitor'; // 数据表格的jquery选择器
                // 重新加载数据表格
                this.reloadDataGrid  = function(){

                    /**
                     * 重新加载数据表格中的内容 注意与load方法的区别：reload 是刷新当前页 load是重新加载--又跑第一页去了
                     */
                    $(this.dataGridSelector).datagrid('reload'); // reload the grid data
                };
            
                // 增
                this.appendUrl = '<?=  Url::to(['create']) ?>';
                this.append = function(){
                    var url = this.appendUrl;
                    var that = this ;
                    top.easyCrud.create( url,{title:"创建",width:450} , function(){ that.reloadDataGrid(); } );
                };

                // 改
                this.getEditUrl = function(row){
                        // 如有必要 做出修正
                        var url = '<?=  Url::to(['update','id'=>'__id']) ?>';
                        // 替换变量占位符
                        return   url.replace('__id', row.id);
                };
                this.edit = function(){
                        var row = $(this.dataGridSelector).datagrid('getSelected');
                        if (row) {
                            var url = this.getEditUrl(row);
                            
                            var that = this ;
                            // 调用父对象的update方法
                            top.easyCrud.update(url,{title:"修改",width:450} , function(){ that.reloadDataGrid(); });

                        } else {
                            msgAlert('请先选择一条记录!');
                        }
                };

                    // 删
                this.getDeleteUrl = function(row){
                        var url = '<?=   Url::to(['delete','id'=>'__id']) ?>';
                        // 替换变量占位符
                        return  url.replace('__id', row.id);

                    };
                this.remove = function(){
                    var that = this ;
                    var row = $(this.dataGridSelector).datagrid('getSelected');
                    if (row) {
                        var jq = parent ? parent.$ : $;
                        jq.messager.confirm('Confirm', ' 您确定要删除此项数据 ?', function (r) {
                            var url = that.getDeleteUrl(row) ;
                            if (r) {
                                top.easyCrud.del(url,{id:row.id} ,function(){ that.reloadDataGrid(); } ) ;
                            }
                        });
                    } else {
                        msgAlert('请先选择一条记录!');
                    }
                };

                // 搜索
                this.doSearch = function(searchButton){
                    var $form = $(searchButton).closest('form');
                    // var formData = form.serialize();
                    var fields = $form.serializeArray();
                    var params = {};
                    $.each( fields, function(i, field){
                        // 同时存在才保存！
                        if(field.name && field.value){
                            params[field.name] = field.value
                        }
                    });
                    $(this.dataGridSelector).datagrid('load',params);
                };
                // 高级搜索
                this.advSearchContainerSelector = '#adv_search_container_water-monitor';
                this.advSearch = function(){
                    var advSearchFormContent = $(this.advSearchContainerSelector).html();
                        // var advSearchFormContent = $(advSearchFormContent+' form').clone();

                        // 声明变量 供下面的回调函数使用
                    var dataGridSelector = this.dataGridSelector ;

                    top.easyCrud.advanceSearch(advSearchFormContent,{title:"高级搜索",width:450} , function(params){
                        console.log(params);
                        $(dataGridSelector).datagrid('load',params);
                    });
            };

                    /**
                     * 主要是记录本次请求的url 可用来做数据导出用
                     *
                     * @param data
                     */
                    this.onDataGridLoadSuccess = function(data){
                        // console.log(data);
                        if(data.url){
                            // console.log(data.url);
                            // 导出动作选择符 如果必要 可以作为对象变量被传递
                            var exportGridActionContextSelector = '#mm_export_water-monitor';
                            // 设定到导出当前页的链接上
                            $(exportGridActionContextSelector).find("a.export-grid-action").attr("href", data.url);
                        }
                    };

                };


        var water-monitorAdmin = new WaterMonitorAdmin();
        // 对象初始化：
        water-monitorAdmin.dataGridSelector = '#dg_water-monitor';
        water-monitorAdmin.appendUrl = '<?=  Url::to(['create']) ?>';
        water-monitorAdmin.dataGridSelector.advSearchContainerSelector = '#adv_search_container_water-monitor';


        /**
         *
         * @param msg
         */
        function msgAlert(msg) {
            // 如果存在父窗体 那么调用父窗体的改方法
            var jq = parent ? parent.$ : $;
            jq.messager.alert('提示!', msg, 'info');

        }

    </script>
<?php  \year\widgets\JsBlock::end()?>
