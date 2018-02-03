<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;

use yii\helpers\ArrayHelper;
use backend\models\Branches;
use backend\models\Companies;

/* @var $this yii\web\View */
/* @var $model backend\models\Departments */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="departments-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model,'companies_company_id')->widget(Select2::classname(),[
    	'data' => ArrayHelper::map(Companies::find()->asArray()->all(),'company_id','company_name'),
    	'language' => 'en',
    	'options' => [
    		'placeholder'=>'-Select Company-',
    		'onchange'=>'$.post("../branches/list?id='.'"+$(this).val(),function(data) { $("select#departments-branches_branch_id").html(data) });'
    	],
    	'pluginOptions'=> [
    		'allowClear'=>true
    	],
    ]); ?>

     <?= $form->field($model,'branches_branch_id')->widget(Select2::classname(),[
    	'data' => ArrayHelper::map(Branches::find()->asArray()->all(),'branch_id','branch_name'),
    	'language' => 'en',
    	'options' => ['placeholder'=>'-Please Select Company First-'],
    	'pluginOptions'=> [
    		'allowClear'=>true
    	],
    ]); ?>

    <?= $form->field($model, 'department_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'department_status')->dropDownList([1=>'Active',0=>'Inactive'],['prompt'=>'-Status-']) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<?php 

$this->registerCss("

// span.select2-selection__rendered {color:#a94442 !important}

");

?>
