<?php

//namespace app\controllers;

namespace res\waterMonitor\frontend\controllers;

use Yii;
//use app\models\WaterSinglertustation;
use res\waterMonitor\common\models\WaterSinglertustation;
use res\waterMonitor\common\models\Waterstation;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WaterSinglertustationController implements the CRUD actions for WaterSinglertustation model.
 */
class WaterSinglertustationController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

public function actionSelectrtu($id)
{
   if(!isset($rtutablename))
             $rtutablename = '';
   $model = new Waterstation(); 

   $val = Yii::$app->request->post('rtuname');
   //echo $val;
   $data = Waterstation::find()->all();
   $dataProvider = new ActiveDataProvider([
      //  'query' => WaterSinglertustation::findx('rtu_'.$val),
        'query' => WaterSinglertustation::findx('water_singlertustation'),
  ]);
   $rtutablename = $val;
   return $this->render('index',[
          'dataProvider' =>$dataProvider,
          'data' =>$data,
          'rtutablename' =>$rtutablename,
  ]);
}




    /**
     * Lists all WaterSinglertustation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Waterstation();
        $data = Waterstation::find()->all();
        $dataProvider = new ActiveDataProvider([
           // 'query' => WaterSinglertustation::find(),
            'query' => WaterSinglertustation::findx('water_singlertustation'),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'data' =>$data,
        ]);
    }

    /**
     * Displays a single WaterSinglertustation model.
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
     * Creates a new WaterSinglertustation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WaterSinglertustation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
         
    }

    /**
     * Updates an existing WaterSinglertustation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }
    
    public function actionUpdatertu($id)
   {
           if(!isset($rtutablename))
               $rtutablename = '';
           $data = Waterstation::find()->all();
          if($id == '默认表')
             $rtuname = 'water_singlertustation';
          else
             $rtuname = 'rtu_'.$id;

           $dataProvider = new ActiveDataProvider([
            'query' =>WaterSinglertustation::findx($rtuname), 
           ]);
          return $this->render('selectrtu',[
                        'dataProvider' => $dataProvider,
                        'data' =>$data,
                        'rtutablename' =>$id,
                  ]);
           
   }
    public function actionAlldataanalysis($id)
    {
        if($id =='默认表')
           $rtuname = 'water_singlertustation';
        else
           $rtuname = 'rtu_'.$id;


        $dataProvider = new ActiveDataProvider([
          //  'query' => WaterSinglertustation::find(),
            'query' => WaterSinglertustation::findx($rtuname),
        ]);
         // echo "aadfeeew";
     //   $id =2;
     //   $model = $this->findModel($id);
             // return $this->redirect(['view','id' =>$model->id]);
             // return $this->redirect(['info']);
             // return $this->redirect(['index']);
             // return $this->redirect(['alldataanalysishtml.html']);
       //return $this->render('alldataanalysis'); //, ['model' => $model,]);
       return $this->render('alldataanalysis', [
                    'dataProvider' => $dataProvider,]);
                     // exit ;
       // $model = new WaterSinglertustation();
       // return $this->render('view', [
       //     'model' => $this->findModel($id),
       // ]);

        /*   if ($model->load(Yii::$app->request->post()) && $model->save()) {
                  return $this->redirect(['view', 'id' => $model->id]);
              } else {
                  return $this->render('create', [
                     'model' => $model,
            ]);
           }
        */
         
  
    }

    /**
     * Deletes an existing WaterSinglertustation model.
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
     * Finds the WaterSinglertustation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WaterSinglertustation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
   //     if (($model = WaterSinglertustation::findOnex(rtu_555555,$id)) !== null) {
        if (($model = WaterSinglertustation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
