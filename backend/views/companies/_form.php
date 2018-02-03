<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Companies */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="companies-form">

    <?php $form = ActiveForm::begin([
        'enableAjaxValidation'=>true,
        'options' => ['enctype'=>'multipart/form-data'],
        'fieldConfig' => ['errorOptions' => ['encode'=>false,'class'=>'help-block']]
        ]); ?>

    <?= $form->field($model, 'company_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'company_address')->textArea(['maxlength' => true]) ?>

    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?php if(!$model->isNewRecord): ?>
        <?= Html::activeHiddenInput($model, 'imageFile'); ?>
    <?php endif; ?>
    

    <?= $form->field($model, 'company_start_date')->widget(
    DatePicker::className(), [
        // inline too, not bad
         'inline' => false, 
         // modify template for custom rendering
        //'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            'autoclose' => true,
            'format' => 'yyyy-mm-dd'
        ]
    ]);?>

    <?= $form->field($model, 'company_status')->dropDownList([1=>'Active',0=>'Inactive'],['prompt'=>'-Status-']) ?>

    <?= $form->field($branch, 'branch_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($branch, 'branch_address')->textArea(['maxlength' => true]) ?>

    <?= $form->field($branch, 'branch_status')->dropDownList([1=>'Active',0=>'Inactive'],['prompt'=>'-Status-']) ?>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
