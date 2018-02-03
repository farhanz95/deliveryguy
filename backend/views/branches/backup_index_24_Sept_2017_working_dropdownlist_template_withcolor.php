<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;

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

    <!-- ?=Html::beginForm(['branches/bulk-process'],'post',['id'=>'formBulkEngine']);?>
    ?=Html::dropDownList('action','',[''=>'Mark selected to: ','delete'=>'DELETE','suspend'=>'SUSPEND (Deactive)'],['class'=>'form-control','style'=>'width:300px;display:inline;'])?>
    ?=Html::submitButton('EXECUTE', ['class' => 'btn btn-info button_formBulkEngine',]);?> -->

    <?php $form = ActiveForm::begin() ?>

    <?php 

    $listData=['delete'=>'DELETE', 'suspend'=>'SUSPEND (Deactivate)'];

    $options=
    [
    'delete' => ['style' => 'color:#a94442;font-weight:bold'],
    'suspend' => ['style' => 'color:#f0ad4e;font-weight:bold'],
    ];

    ?>

    <?= $form->field($bulkSelection,'selection',['template'=>'<div class="input-group">{input}<button class="button_formBulkEngine btn btn-primary ml10">Execute!</button> </div>'])->dropDownList($listData,['style'=>'width:300px;display:inline;','prompt'=>'Mark selected to : ','options'=>$options])->label(false) ?> 

    <?php ActiveForm::end() ?>

    </div>

    <?php Pjax::begin(['id'=>'branchesGrid']) ?>
    <?= GridView::widget([
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


JS;
$this->registerJs($js);

?>

<?php 

$script = <<< JS
$('body').on('click', '.button_formBulkEngine', function (e) {
    e.preventDefault();
    var url = $(this).attr('value');
    bootbox.confirm('<b>Confirm Execute Process</b>', function(result){
        if(result){
            $('form#formBulkEngine').submit();           
        }
    });
});
$('body').on('beforeSubmit', 'form#formBulkEngine', function () {
     var form = $(this);
     // return false if form still have some validation errors
     if (form.find('.has-error').length) {
          return false;
     }
     // submit form
     $.ajax({
          url: form.attr('action'),
          type: 'post',
          data: form.serialize(),
          success: function (response) {
               alert(response)
          }
     });
     return false;
});

JS;

$this->registerJs($script);
?>