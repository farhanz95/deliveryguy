<?php

namespace backend\controllers;

use Yii;
use backend\models\Companies;
use backend\models\CompaniesSearch;
use backend\models\Branches;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CompaniesController implements the CRUD actions for Companies model.
 */
class CompaniesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Companies models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CompaniesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Companies model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Companies model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {   
        if (Yii::$app->user->can('create-company')) {
            $model = new Companies();

            $branch = new Branches();

            if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
                Yii::$app->response->format = 'json';
                return \yii\widgets\ActiveForm::validate($model);
            }

            if ($model->load(Yii::$app->request->post()) && $branch->load(Yii::$app->request->post())) {

                $imageName = $model->company_name;
                // get the instance of the uploded file
                $model->imageFile = UploadedFile::getInstance($model,'imageFile');
                // save path to image in db
                if ($model->imageFile) {
                    $model->logo = 'uploads/'.$model->imageFile->baseName.'.'.$model->imageFile->extension;
                }else{
                     Yii::$app->session->setFlash('danger', 
                    "Product unable to be uploaded");
                }
                $model->company_created_date = date('Y-m-d');

                // FIRST : save changes in db
                // SECOND : upload

                /// If Success Save Company Then Save Branches
                if ($model->save()) {

                    $branch->companies_company_id = $model->company_id;
                    $branch->branch_created_date = date('Y-m-d');
                    if ($branch->save()) {
                    }else{
                        var_dump($branch->errors);
                    }
                }else{
                    var_dump($model->errors);
                }

                if ($model->imageFile) {
                    $model->upload();
                }else{
                     Yii::$app->session->setFlash('danger', 
                    "Product unable to be uploaded");
                }

                return $this->redirect(['view', 'id' => $model->company_id]);
            } else {
                return $this->render('create', [
                    'model' => $model,
                    'branch' => $branch
                ]);
            }
        }else{
            throw new ForbiddenHttpException;
        }
    }

    /**
     * Updates an existing Companies model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $branch = Branches::find()->where(['companies_company_id'=>$id])->one();

        if (!$branch) {
            $branch = new Branches;
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = 'json';
            return \yii\widgets\ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post())) {

            //check if new logo has been browsed or not
            if (UploadedFile::getInstance($model,'imageFile'))
            {
                //get the instance of the uploaded file
                $model->imageFile =  UploadedFile::getInstance($model,'imageFile');
                //save the path to the DB
                if ($model->imageFile) {
                    $model->logo = 'uploads/'.$model->imageFile->baseName.'.'.$model->imageFile->extension;
                }
            }        

            $model->save();

            if ($model->imageFile) {
                    $model->upload();
            }

            return $this->redirect(['view', 'id' => $model->company_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'branch' => $branch
            ]);
        }
    }

    /**
     * Deletes an existing Companies model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Companies model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Companies the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Companies::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
