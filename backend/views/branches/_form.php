<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use backend\models\Companies;
use kartik\select2\Select2;


/* @var $this yii\web\View */
/* @var $model backend\models\Branches */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="branches-form">

    <?php $form = ActiveForm::begin(['id'=>'Branches']); ?>

    <?= $form->field($model,'companies_company_id')->widget(Select2::classname(),[
    	'data' => ArrayHelper::map(Companies::find()->asArray()->all(),'company_id','company_name'),
    	'language' => 'en',
    	'options' => ['placeholder'=>'Select Companies...'],
    	'pluginOptions'=> [
    		'allowClear'=>true
    	],
    ]); ?>

    <?= $form->field($model, 'branch_name')->textInput(['maxlength' => true]) ?>

    <!-- ?= $form->field($model,'branch_name',['template'=>'<div class="input-group"><span class="input-group-btn"><button class="btn btn-default">Go!</button></span>{input}</div>']) ?> -->

    <?= $form->field($model, 'branch_address')->textArea(['maxlength' => true]) ?>

    <?= $form->field($model, 'branch_status')->dropDownList([1=>'Active',0=>'Inactive'],['prompt'=>'-Status-']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

$script = <<< JS

$('form#{$model->formName()}').on('beforeSubmit', function(e)
{
    var \$form = $(this);

    $.post(
        \$form.attr("action"),
        \$form.serialize()
    )

        .done(function(result){
        if(result){
            // $(document).find('#modal').modal('hide');

            $.notify("Saved", {
              className:'info',
              clickToHide: false,
              autoHide: false,
              globalPosition: 'top right'
            });

            $(\$form).trigger("reset");
            $.pjax.reload({container:'#branchesGrid'});
        }else{
            $("#message").html(result);
        }
        }).fail(function(){
            console.log("server error");
        });
    return false;
});

JS;

$this->registerJs($script);
?>
