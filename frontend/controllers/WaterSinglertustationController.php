<?php

//namespace app\controllers;

namespace res\waterMonitors\frontend\controllers;

use Yii;
//use app\models\WaterSinglertustation;
use res\waterMonitors\common\models\WaterSinglertustation;
use res\waterMonitors\common\models\Waterstation;
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
   //echo "<script language=\"javascript\">alert(\"ajaxtest\"); </script>";     
   if(!isset($rtutablename))
            $rtutablename = '';
        
       //$query = WaterMonitorModel::find();
        //$dataProvider = new ActiveDataProvider([
        //    'query' => $query,
       // ]);

       // if (!($this->load($params) && $this->validate())) {
       //      return $dataProvider;
       // }
        $model = new Waterstation();

       //  echo "<script language=\"javascript\">alert(\"chaomang\"); </script>";     
   //   if ($model->load(Yii::$app->request->post())){          //&& $model->save()) {
      
  //       echo "<script language=\"javascript\">alert(\"caomang\"); </script>";     
  //    }
  //     else
   //   {
    //     echo "<script language=\"javascript\">alert(\"chaomang\"); </script>";     
   //  }
 //    echo var_dump((Yii::$app->request->post())); 
//      echo $rtuname; 

    //  if($id == '默认表')
           $val =   Yii::$app->request->post('rtuname');
      //   $val =   Yii::$app->request->get('rtuname');
    //   else
      //      $val = $id; 
     
         echo $val;    
      //   $val = 555555;

       //        $val = $id;
       //     $model = new Waterstation();
        $data =  Waterstation::find()->all();
       
 //       $datastationProvider = new ActiveDataProvider([
 //           'query' => Waterstation::find(),
 //       ]);

        $dataProvider = new ActiveDataProvider([
          //  'query' => WaterSinglertustation::findx(''),
           'query' => WaterSinglertustation::findx('water_singlertustation'),
        //    'query' => WaterSinglertustation::findx('rtu_'.$val),
        ]);

        $rtutablename = $val;
       
        // return $this->redirect('index', ['dataProvider' =>$dataProvider);
        // return $this->redirect(['selectrtu', 'id' =>555555]);
        // return $this->redirect($this->render('selectrtu',['dataProvider'=>$dataProvider ] ));
      
      //   $this->redirect(['index','id'=> $val]);
      //echo    Yii::$app->urlManager->createUrl('index.php'); 
   
     // return  $this->render('selectrtu', [

         return $this->render('index', [
            'dataProvider' => $dataProvider,
            'data' =>$data,
            'rtutablename' =>$rtutablename,
         //   'id' =>$rtutablename,
        ]);
        
    //  return $this->redirect(['selectrtu','id'=> $val]);
       
       //      'model' =>$model,
       //      'datastationProvider' =>$datastationProvider,
       //      return $val;
}


    /**
     * Lists all WaterSinglertustation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new Waterstation();
        $data =  Waterstation::find()->all();
  
    // $model->attributes=$_POST[];
    // $mod=$_POST[];

    //  print_r($mod);
      // echo var_dump($model->attributes) ;      
 //       $model = $this->findModel($id);
		    //  $val =  Yii::$app->request->post();

               //   $model->load(Yii::$app->request->post());
               // ($val);     
              //echo var_dump($model->sitenumber) ;      
            

 //echo ($model->sitenumber);

     // if ($model->load(Yii::$app->request->post()) ) {
        //  print_r "<script language=\"javascript\">alert($val); </script>";     
  // }
         // print_r($.post());  
         //echo $.post;
    // echo var_dump(Yii::$app);
    
    $dataProvider = new ActiveDataProvider([
          // return 'water_singlertustation';
           'query' => WaterSinglertustation::findx('water_singlertustation'),
           //'query' => WaterSinglertustation::find(),
//        'query' => WaterSinglertustation::findx('rtu_555555'),
        ]);

   //     print_r($dataProvider->query);

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
//<<<<<<< HEAD
           if(!isset($rtutablename))
               $rtutablename = '';
           $data = Waterstation::find()->all();
          if($id == '默认表')
             $rtuname = 'water_singlertustation';
          else
             $rtuname = 'rtu_'.$id;

           $page =  Yii::$app->request->get('page');
           $sort = Yii::$app->request->get('sort');
    //       echo $page;          

            $dataProvider = new ActiveDataProvider([
            'query' =>WaterSinglertustation::findx($rtuname)->where(['<>','dgtype','5']), 
       //     'pagination' =>[
       //         'page' =>$page
       //       ] 
           ]);
            //echo $currentPage;
       //$mypage = Yii::$app->request->url;
           
          return $this->render('selectrtu',[
                        'dataProvider' => $dataProvider,
                        'data' =>$data,
                        'rtutablename' =>$id,
                        'page'=>$page,
                        'sort'=>$sort,
                  ]);
           
/*=======
   
   if(!isset($rtutablename))
            $rtutablename = '';


        $data =  Waterstation::find()->all();
        if($id == '默认表')
            $rtuname = 'water_singlertustation';
        else
            $rtuname = 'rtu_'.$id; 
        
         $dataProvider = new ActiveDataProvider([
           // 'query' => WaterSinglertustation::find(),
            'query' => WaterSinglertustation::findx($rtuname),
        ]);

      
      return  $this->render('selectrtu', [
       //  return $this->render('index', [
            'dataProvider' => $dataProvider,
            'data' =>$data,
            'rtutablename' =>$id,              //$rtutablename,
         ]);
>>>>>>> 9c4aeb56d7c2ba56f7e0b2a557161843ec46d9db
*/   }

 
    public function actionAlldataanalysis($id)
    {
//	    if(!isset($rtutablename))
//		      $rtutablename = '555555';
   //  if(!isset($rtutablename))
    //        $rtutablename =  Yii::$app->request->post('rtuname');
       // var $rtuname;
        if($id == '默认表')
            $rtuname = 'water_singlertustation';
        else
           $rtuname = 'rtu_'.$id;

        //$mypage = $_GET['page'];
       //$mypage = Yii::$app->request->url;
       //echo $mypage;  

        $dataProvider = new ActiveDataProvider([
          //  'query' => WaterSinglertustation::find(),
            'query' => WaterSinglertustation::findx($rtuname)->where(['<>','dgtype','5']),
        ]);

    //   $page =$dataProvider->pagination;
    //   echo var_dump($page);
        //  echo var_dump($dataProvider->sort);   

         // $page1 = Yii::$app->request->getUrl();
         // echo var_dump($page1);      
 //   $searchModel = new WaterMonitorSearch();
 //   $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

 //   $dataProvider->pagination->pageParam = 'page';
 //   $dataProvider->pagination->pageSizeParam = 'rows';
            
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
     //   if (($model = WaterSinglertustation::findOnex(rtu_555555,$id)) !== null) {
        if (($model = WaterSinglertustation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
