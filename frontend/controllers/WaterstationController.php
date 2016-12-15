<?php

//namespace app\controllers;

namespace res\waterMonitors\frontend\controllers;

//use Yii;
//use app\models\WaterSinglertustation;
//use res\waterMonitor\common\models\WaterSinglertustation;

use Yii;
use res\waterMonitors\common\models\Waterstation;
//use app\models\Waterstation;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * WaterstationController implements the CRUD actions for Waterstation model.
 */
class WaterstationController extends Controller
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
     * Lists all Waterstation models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Waterstation::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Waterstation model.
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
     * Creates a new Waterstation model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Waterstation();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->sitenumber]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
      

       echo "create ok";
    }

    /**
     * Updates an existing Waterstation model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
              //  echo "<script language=\"javascript\">alert(\"123\"); </script>";     
         return $this->redirect(['view', 'id' => $model->sitenumber]);
        } else {
              //  echo "<script language=\"javascript\">alert(\"456\"); </script>";     
       
       $nameid =$model->sitenumber;  // time();
       $namestr = strval($nameid); 
       $tbname = 'rtu_'.$namestr;
 
                               //  )
                               //  ")->execute();
                   // select count(*) from pg_class where relname = 'rtu_8765432a'             
      
       $alreadyexist = Yii::$app->db->createCommand("
                    select count(*) from pg_class where relname = '$tbname'             
               ")->queryScalar();
                  //->query()->rowCount;//  ->count(); //->execute();
                      echo "strval($alreadyexist)";           

             if($alreadyexist)    //exists this table
                {
              
                          echo "<script language=\"javascript\">alert(\"this rtu table had exists!\"); </script>";    
                }
             else         //no exists this table
                          //  integer primary key not null
                {
                  Yii::$app->db->createCommand("
                  CREATE TABLE  $tbname (id serial,
                                         state  character(32),
                                         statno integer,
                                         waterlv real,
                                         rainfall integer,
                                         watertemp real,
                                         date   timestamp(0) without time zone,
                                         bakup1 real,
                                         bakup2 character(256),
                                         rainfallmulti character(24),
                                         waterlvmulti character(48),
                                         waterflow character(48),
                                         volte real,
                                         originstr text,
                                         dgtype integer)
                                        ")->execute(); 
                    
                  Yii::$app->db->createCommand("
                  INSERT INTO water_monitor (site,current_site) VALUES ('重庆','$namestr');
                                               ")->execute(); 
                  //echo "<script language=\"javascript\">alert(\"321\"); </script>";     
                }
        
                  //return $this->render('update', ['model' => $model,]);
                  return $this->redirect(['index']);
        }

    }

     /**

                                   statno integer,
                                   waterlv real,
                                   rainfall integer,
                                   watertemp real,
                                   date   timestamp(0) without time zone,
                                   bakup1 real,
                                   bakup2 character(256)
        */





    /**
     * Deletes an existing Waterstation model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
 //         echo "<script language=\"javascript\">alert(\"aaa\"); </script>";     
   
        $model = $this->findModel($id);
       $nameid =$model->sitenumber;  // time();
       $namestr = strval($nameid); 
       $tbname = 'rtu_'.$namestr;
       
       $alreadyexist = Yii::$app->db->createCommand("
                    select count(*) from pg_class where relname = '$tbname'             
               ")->queryScalar();

       if($alreadyexist)
       {
               //echo "strval($alreadyexist)";           
            
   //                echo "<script language=\"javascript\">alert(\"123\"); </script>";     
                  Yii::$app->db->createCommand("
                    DROP TABLE $tbname
                                 ")->execute();  
       }
       else
        {
            //   echo "strval($alreadyexist)";           
                    echo "<script language=\"javascript\">alert(\"this table is not exists!\"); </script>";     
        }

   

         $this->findModel($id)->delete();
        return $this->redirect(['index']);
      
    }

    /**
     * Finds the Waterstation model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Waterstation the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Waterstation::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
