<?php

use yii\helpers\Html;
use yii\grid\GridView;
use dosamigos\datepicker\DatePicker;
use yii\widgets\Pjax;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\CompaniesSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Companies';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="companies-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php  echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('Create Companies', ['create'], ['class' => 'btn btn-success']) ?>
    </p> 
    <?php Pjax::begin() ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'rowOptions' => function($model) {
            if ($model->company_status) {
                return ['class'=>'success'];
            }else{
                return ['class'=>'danger'];
            }
        },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            'company_name',
            'company_email:email',
            [
                'attribute'=>'company_address',
                'format'=>'raw',
                'contentOptions' => [
                    'style' => 'width:50%;white-space: normal;'
                ],
            ],
            [
                'attribute' => 'company_start_date',
                'value' => 'company_start_date',
                'format' => 'raw',
                'filter' => DatePicker::widget([
                        'model' => $searchModel,
                        'attribute' => 'company_start_date',
                        // 'template' => '{addon}{input}',
                            'clientOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-m-d'
                            ]
                    ])
            ],
            // 'company_created_date',
            [
                'attribute'=>'logo',
                'format' => ['raw'],
                'value' =>  function($model){
                    return Html::a(Html::img(Url::base().'/'.$model->logo,['class' => 'pull-left img-responsive']),Url::base().'/'.$model->logo,['target'=>'_blank']);
                }
            ],
            [
                'attribute'=>'company_status',
                'format'=>'raw',
                'value' => function($model){
                    return $model->company_status ? '<span class="btn btn-success">Active</span>' : '<span class="btn btn-danger">Inactive</span>';
                },
                'filter' => Html::activeDropDownlist($searchModel,'company_status',[1=>'Active',0=>'Inactive'],['class'=>'form-control','prompt'=>'-'])
            ],
            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?> 
    <?php Pjax::end(); ?>
</div>
