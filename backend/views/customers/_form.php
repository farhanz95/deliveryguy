<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use backend\models\Locations;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model backend\models\Customers */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="customers-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'customer_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model,'zip_code')->widget(Select2::classname(),[
    	'data' => ArrayHelper::map(Locations::find()->asArray()->all(),'zip_code','zip_code'),
    	'language' => 'en',
    	'options' => ['placeholder'=>'-Select Zip Code-','id'=>'zip_code_customers'],
    	'pluginOptions'=> [
    		'allowClear'=>true
    	],
    ]); ?>

    <?= $form->field($model, 'city')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'province')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php 

$js = "

$('#customers-city').attr('readOnly',true);
$('#customers-province').attr('readOnly',true);

$('body').on('change','#zip_code_customers',function(){
	var zipCode = $(this).val();
	$.get('../locations/get-city-province',{ zipCode : zipCode },function(data){
		var data = $.parseJSON(data);
		$('#customers-city').attr('value',data.city)
		$('#customers-province').attr('value',data.province)
	});
})
";
$this->registerJs($js);

?>