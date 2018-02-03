<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\BranchesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Branches';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="branches-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::button('Create Branches', ['value' => Url::to(['/branches/create']), 'class' => 'btn btn-success','id'=>'modalButton']) ?>
    </p>

    <?php 
        Modal::begin([
                'header'=>'<h4>Branches</h4>',
                'id'=>'modal',
                'size'=>'modal-lg',
                'options'=>['tabindex'=>false] // to make select2 widget functional inside modal
        ]);

        echo "<div id='modalContent'></div>";

        Modal::end();
    ?>

    <div class="row col-lg-12 bulkEngine" style="display:none">
 
    <?=Html::submitButton('<span class="glyphicon glyphicon-remove mr10"></span>Delete Selected Application', ['class' => 'btn btn-danger button_formBulkEngine',]);?>

    </div>

    <?php Pjax::begin(['id'=>'branchesGrid']) ?>
    <?= GridView::widget([
        'options' => ['id' => 'grid'],
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model){
            if ($model->branch_status) {
               return ['class'=>'success'];
            }else{
                return ['class'=>'danger'];
            }
        },
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn'
            ],
            [
                'attribute' => 'companies_company_id',
                'value' => 'companiesCompany.company_name'
            ],
            'branch_name',
            'branch_address',
            'branch_created_date',
            // 'branch_status',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
</div>

<?php 

$js = <<< JS

$('body').on('click','input:checkbox[name="selection[]"]',function(){ 

    if($('input[name="selection[]"]:checked').length > 0){
        $('.bulkEngine').show();
    }else{
        $('.bulkEngine').hide();
    }

});

$('body').on('click','input:checkbox[name="selection_all"]',function(){

    if($('input[name="selection_all"]:checked').length > 0){
        $('.bulkEngine').show();
    }else{
        $('.bulkEngine').hide();
    }

});

if($('input[name="selection[]"]:checked').length > 0){
    $('.bulkEngine').show();
}else{
    $('.bulkEngine').hide();
}

if($('input[name="selection_all"]:checked').length > 0){
    $('.bulkEngine').show();
}else{
    $('.bulkEngine').hide();
}

JS;
$this->registerJs($js);

?>

<?php 

$script = "

// $.notify('You are currently logged in from : ".$_SERVER['REMOTE_ADDR']." <br> ".Yii::$app->getUser()->identity['username']."', {
//   className:'info',
//   clickToHide: false,
//   autoHide: false,
//   globalPosition: 'top right',
//   color: '#a94442'
// });

$('body').on('click', '.button_formBulkEngine', function (e) {
    e.preventDefault();
    var url = $(this).attr('value');
    bootbox.confirm('<b>Confirm Delete Application</b>', function(result){
        if(result){
            var keys = $('#grid').yiiGridView('getSelectedRows');

            if(keys.length>0){
                jQuery.post('".Url::to(['bulk-process'])."',{ids:keys.join()},function(){

                    $.notify(\"Deleted Succesfuly\", {
                      className:'info',
                      clickToHide: false,
                      autoHide: false,
                      globalPosition: 'top right'
                    });

                    $.pjax.reload({container:'#branchesGrid'});
                });
            }else{
                alert('Fail');
            }     
            $('.bulkEngine').hide();     
        }
    });
});
";
$this->registerJs($script);
?>