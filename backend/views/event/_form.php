<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datetimepicker\DateTimePicker;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model backend\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'created_date')->widget(
    DatePicker::className(), [
        // inline too, not bad
         // 'inline' => false, 
         // modify template for custom rendering
        // 'template' => '<div class="well well-sm" style="background-color: #fff; width:250px">{input}</div>',
        'clientOptions' => [
            // 'autoclose' => true,
            'format' => 'yyyy-mm-dd',
        ],
        'options' => [
        	'readOnly'=>true
        ]
    ]);?>

	<div class="row col-lg-12" style="margin-bottom:10px !important">

		<?= '<label>Start Time</label>'; ?>
		<?= TimePicker::widget([
			'model' => $model,
			'attribute' => 'start_time', 
			'value' => date('h:i:s A'),
			'pluginOptions' => [
				'showSeconds' => false
			]
		]); ?>

	</div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
