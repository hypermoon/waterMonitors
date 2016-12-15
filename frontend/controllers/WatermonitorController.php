<?php

namespace res\waterMonitors\frontend\controllers;

use Yii;
use res\waterMonitors\common\models\WaterSinglertustation;
use res\waterMonitors\common\models\WaterMonitor;
use res\waterMonitors\common\models\Waterstation;
use res\waterMonitors\common\models\search\WaterMonitor as WaterMonitorSearch;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;

date_default_timezone_set('prc');

/**
 * WatermonitorController implements the CRUD actions for WaterMonitor model.
 */
class WatermonitorController extends Controller
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

    /**
     * Lists all WaterMonitor models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new WaterMonitorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
       
        //print_r($dataProvider) ;
       // UPDATE water_monitor SET current_level = '23',current_temp='33',rainfall = '33' where current_site='552233';
      
        $datr = new ActiveDataProvider([
            'query' => Waterstation::find(),
        ]);
        
        $datass =  Waterstation::findBySql('SELECT sitenumber from waterstation')->all();
//         $datab = Yii::$app->getDb()->createCommand('SELECT sitenumber from waterstation')->queryRow();     
  
         foreach($datass as $key=>$val){
          $newdata[$key] = $val->attributes; 
        }

        //$datass = json_decode(CJSON::encode($datass),TRUE);
        //$datas =  Waterstation::find()->where(['sitenumber' => 555555])->one();

        //$tt = Waterstation::find()->batch(6);
        //   $stationmodel = new Waterstation();
        //  echo var_dump($datab);     
        //  print_r($newdata);
        //   echo var_dump($newdata);
            

     //  foreach($newdata as $nn =>$mm)
     //  {
           //           echo $nn."end <br/>";
           //            echo var_dump($mm)."tt<br/>";
           // echo var_dump($datass[$nn]);
          //     echo $nn;
       //       print_r($mm);
       //       echo $mm['sitenumber'];
       //       echo "<br/>";   
           // echo var_dump($mm);
         
               //  echo var_dump($mm);
              //      echo var_dump($datab[$nn]);  
              //      echo  var_dump($nn)."<br/>";
      // }       






         foreach($newdata as $key =>$siteno)
         {
                //echo $siteno['sitenumber'];
                $varname =  $siteno['sitenumber'];
                $rtuname = 'rtu_'.$varname;
        
                Yii::$app->db->createCommand("
        UPDATE water_monitor SET site =(select state from $rtuname  order by id desc LIMIT 1)where current_site='$varname';
                                                 ")->execute();
        Yii::$app->db->createCommand("
        UPDATE water_monitor SET current_level =(select waterlv from $rtuname order by id desc LIMIT 1)where current_site='$varname';
                                     ")->execute();
        Yii::$app->db->createCommand("
        UPDATE water_monitor SET current_temp =(select watertemp from $rtuname order by id desc LIMIT 1)where current_site='$varname'                                     ;")->execute();
        Yii::$app->db->createCommand("
        UPDATE water_monitor SET rainfall =(select rainfall from $rtuname order by id desc LIMIT 1)where current_site= '$varname';
                                     ")->execute();

        Yii::$app->db->createCommand("
        UPDATE water_monitor SET datetime =(select date from $rtuname order by id desc LIMIT 1)where current_site= '$varname';
                                    ")->execute(); 
}  
     
         return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single WaterMonitor model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }
   public function actionWarning()
   {
            echo "预警";
   }
   public function actionAlert()
   {
            echo "报警";
   }
     

   /* public function actionAlldataAnalysis()
  {
         $id = 2;
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);

  }*/
    /**
     * Creates a new WaterMonitor model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new WaterMonitor();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing WaterMonitor model.
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

    /**
     * Deletes an existing WaterMonitor model.
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
     * Finds the WaterMonitor model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return WaterMonitor the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = WaterMonitor::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
	
	//socket服务器端
	public function actionSocketserver(){
		if(extension_loaded('sockets')){
		  echo "socket已开启";
		}else{
		  echo "socket未开启";
		}
		//echo "<script>alert(1111);</script>";
		exit;

		
		error_reporting(E_ALL);
		set_time_limit(0);
		//ob_implicit_flush();

		//$address = '127.0.0.1';
		$address = '183.230.176.58';
		$port = 9000;
		//创建端口
		if( ($sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP)) === false) {
			echo "socket_create() failed :reason:" . socket_strerror(socket_last_error()) . "\n";
		}

		//绑定
		if (socket_bind($sock, $address, $port) === false) {
			echo "socket_bind() failed :reason:" . socket_strerror(socket_last_error($sock)) . "\n";
		}

		//监听
		if (socket_listen($sock, 5) === false) {
			echo "socket_bind() failed :reason:" . socket_strerror(socket_last_error($sock)) . "\n";
		}

		do {
			//得到一个链接
			if (($msgsock = socket_accept($sock)) === false) {
				echo "socket_accepty() failed :reason:".socket_strerror(socket_last_error($sock)) . "\n";
				break;
			}
			
			//获取客户连接信息
			for($n=0;$n<count($clientSockets);$n++){
				if($clientSockets[$n]==$msgsock){
					break;
				}
			}
			if($n>=count($clientSockets)){
				array_push($clientSockets, $msgsock);
			}
			
			// 从客户端获取得的数据
			$buf = socket_read($msgsock, 8192);
			
			$datas = bin2hex($buf);//ascii转16进制
			
			//调试文件
			$myfile = fopen("D:/xampp/htdocs/WlMonitor/data/shuju1.txt", "w") or die("Unable to open file!");
			$txt = "服务端获取数据：".$buf."---".$datas;
			fwrite($myfile, $txt);
			
			//获取的数据包
			if(substr($datas,0,4)=="7B09" || substr($datas,0,4)=="7b09"){//数据包 
				//welcome  发送到客户端
				$msg = hex2bin("7B850000AFAF00000D0A7B");
				socket_write($msgsock, $msg, strlen($msg));
				echo 'read client message\n';
				
				//$buf;
				$talkback = $buf;
				
				 //取得信息给客户端一个反馈 success
				if (false === socket_write($msgsock, $talkback, strlen($talkback))) {
					echo "socket_write() failed reason:" . socket_strerror(socket_last_error($sock)) ."\n";
				} else {
					//写入文件
					$myfile = fopen("D:/xampp/htdocs/WlMonitor/data/server.txt", "w") or die("Unable to open file!");
					$txt = "服务端获取数据：".$talkback;
					fwrite($myfile, $txt);
					
					$this->getAnalysisData($datas);//解析数据存入数据库
				}
			}else{
				//welcome  发送到客户端 error
				$msg = hex2bin("7B840000INVD0000ODOA7B");
				socket_write($msgsock, $msg, strlen($msg));
				echo 'read client message\n';
			}
			
			
			//获取的图片包
			
			
			/*
			//welcome  发送到客户端
			$msg = "<font color='red'>server send:welcome</font><br/>";
			socket_write($msgsock, $msg, strlen($msg));
			echo 'read client message\n';
			
			//$buf;
			$talkback = $buf;
			
			 //取得信息给客户端一个反馈
			if (false === socket_write($msgsock, $talkback, strlen($talkback))) {
				echo "socket_write() failed reason:" . socket_strerror(socket_last_error($sock)) ."\n";
			} else {
				//写入文件
				$myfile = fopen("D:/xampp/htdocs/WlMonitor/data/server.txt", "w") or die("Unable to open file!");
				$txt = "服务端获取数据：".$talkback;
				fwrite($myfile, $txt);
				
				$this->getAnalysisData($datas);//解析数据存入数据库
			}
			*/
			
			//socket_close($msgsock);
			
		} while(true);
		//关闭socket
		//socket_close($sock);

	}
	
	//socket数据客户端
	public function myactionSocketclient($rtuname,$type,$content){    // $time){
		echo "<h2>tcp/ip connection </h2>\n";
		$service_port = 9008;
		$address = '183.230.164.63';

		$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($socket === false) {
			echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		} else {
			echo "OK. \n";
		}

		echo "Attempting to connect to '$address' on port '$service_port'...";
		$result = socket_connect($socket, $address, $service_port);
		if($result === false) {
			echo "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
		} else {
			echo "OK \n";
		}
	
                 switch($type)
                {
                   case 1:                //set rtu time
         	           $in = "PageStart:Rtu:".$rtuname."Type:$type;"."Time:".$content." PageEnd";
		           $out = "";
                         break;
                   case 2:                //remote call     
         	           $in = "PageStart:Rtu:".$rtuname."Type:$type;"."Time:".$content." PageEnd";
                         break;
                   default:
                         break;
                }  
               
		

                 echo "sending http head request ...";
            	 socket_write($socket, $in, strlen($in));
		 echo  "OK\n";
		//socket_close($socket);
		

		//echo "Reading response:\n\n";
	        /*	while ($out = socket_read($socket, 8192)) {
			echo "<br><br><br>服务器数据：".$out."<br><br><br>";
			//写入文件
			$myfile = fopen("D:/xampp/htdocs/WlMonitor/data/client.txt", "w") or die("Unable to open file!");
			$txt = "客户端获取数据：".$out;
			
			fwrite($myfile, $txt);
		}*/
		echo "closeing socket..";
		socket_close($socket);
		//echo "ok .\n\n";

	}

          public function actionSetime($id){
                 $tims = date('ymdHis',time()) ;              
                // $valss =   Yii::$app->request->post('y_open');
                // print_r($valss);           
                 echo "RTU ".$id."设置时钟成功";
	         echo $tims;	
	
            $this->myactionSocketclient($id,1, $tims);

                 //       echo "<script>alert(1111);</script>";

                 //       $sec =3;      
                 //   echo "<script language=\"javascript\">alert(\"caomang\"); </script>";     
                 // return $this->render(['remote'],['sec'=>$sec]);
                 //    return $this->redirect(['remote']);
 
           }




        public function actionRemote(){
 
                   $data =  Waterstation::find()->all();
                   $searchModel = new WaterMonitorSearch();
                   $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      
           if(!isset($rtutablename))
                 $rtutablename = '';

           $val =   Yii::$app->request->post('rtuname');

           echo $val;
        
           $rtutablename = $val;
                   
     
          return $this->render('remote', [
                          'searchModel' => $searchModel,
                          'dataProvider' => $dataProvider,
                          'data' =>$data,
                          'rtutablename' =>$rtutablename,
                          ]);

      }
       
        public function actionCalltest(){
 
                   $data =  Waterstation::find()->all();
                   $searchModel = new WaterMonitorSearch();
                   $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      
           if(!isset($rtutablename))
                 $rtutablename = '';

           $val =   Yii::$app->request->post('rtuname');

           echo $val;
        
           $rtutablename = $val;
                   
     
          return $this->render('remotecall', [
                          'searchModel' => $searchModel,
                          'dataProvider' => $dataProvider,
                          'data' =>$data,
                          'rtutablename' =>$rtutablename,
                          ]);


         }	
	//发送图片信息到客户端
	public function actionPictureclient(){
		//发送到客户端  
                   //echo "TBD";       
                   $data =  Waterstation::find()->all();
                   $searchModel = new WaterMonitorSearch();
                   $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         
                   return $this->render('remote', [
                          'searchModel' => $searchModel,
                          'dataProvider' => $dataProvider,
                          'data' =>$data,
                          ]);
		                  //$msg = hex2bin("7BA1000AIMAG00000000000000000000XXXX\r\n7B");
	            $clientSockets = 2;
                                  // 	$msg = hex2bin("7BA1007B");
	                          //	for($n=0;$n<count($clientSockets);$n++){
	                          //		socket_write($clientSockets[$n], $msg, strlen($msg));
	                          //	}
	}
	
	public function actionRemotecall(){
		//发送到客户端  
                   //echo "TBD";       
                   $data =  Waterstation::find()->all();
                   $searchModel = new WaterMonitorSearch();
                   $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
         
                   return $this->render('remotecall', [
                          'searchModel' => $searchModel,
                          'dataProvider' => $dataProvider,
                          'data' =>$data,
                          ]);
           }          
 
        public function actionRemotedelay($id)
        {
                     
                  $tims = date('ymdHis',time()) ;              
                  $this->myactionSocketclient($id,2,$tims);
               
              return $this->redirect(['watermonitor/waiting','id' =>$id]);
              // return $this->redirect(['water-singlertustation/index']);
               
        }
        public function actionWaiting($id)
        {
                  // sleep(10);
             return $this->render('waiting',['id' =>$id]); 
         }

        public function actionRemotecallrtu($id){
                  $searchModel = new WaterMonitorSearch();
                  //$dataProvider = $searchModel->search(Yii::$app->request->queryParams);
                  //$model = WaterMonitor::find();
            
                //  $tims = date('ymdHis',time()) ;              
            
                 // $this->myactionSocketclient($id,2,$tims);
        
                //  sleep(10);

                  $dataProvider = new ActiveDataProvider([
                  'query' =>WaterMonitor::find()->where(['=', 'current_site',$id]),    // ->where(['<>','dgtype','5']), 
                   ]);              
              
       
                  return $this->render('callindex',[
                          'dataProvider' => $dataProvider,
                          'searchModel' => $searchModel,
                           ]);   
        } 

	//水位监测
	 public function actionWatermonitor()
    {
        //分页读取类别数据
        $model = WaterMonitor::find();
		//$model=WaterMonitor::model()->findAllBySql("select * from water_monitor group by client_ip");

        $pagination = new Pagination([
            'defaultPageSize' => 4,
            'totalCount' => $model->count(),
        ]);

        $model = $model->orderBy('id ASC')
            ->offset($pagination->offset)
            ->limit($pagination->limit)
            ->all();

        return $this->render('watermonitor', [
            'models' => $model,
            'pagination' => $pagination,
        ]);
    }
	
	//大图
	public function actionWatermonitordetail()
    {
		$id = $_GET['ids'];
		$model = $this->findModel($id);
		//echo $id;
              // echo $model['id'];exit;
		$this->layout = false;
        return $this->render('watermonitorydetail',['model' => $model,]);
    }  
	
	//获取数据包内容
	public function getDataAnalysis(){
		$urls = "D:/xampp/htdocs/WlMonitor/data/shuju1.txt";
		$file = file_get_contents($urls);
		$str_data = bin2hex($file);//ascii转16进制

		$datetime = "";//时间
		$stationnumber = "";//站号
		$voltage = "";//太阳能板电压
		$accumulator = "";//蓄电池电压
		$waterlevel = "";//水位
		$watertemperature = "";//水温
		$sluice = "";//堰水计
		$rainfall = "";//雨量
		$capacity = "";//水位存储容量
		$rainfallstorage = "";//雨量存储容量
		$advocate = "";//主信道包数
		$always = "";//总信道包数
		
		$datetime = substr($str_data,16,28);//时间14字节
		$stationnumber = substr($str_data,44,16);//站号8字节
		$voltage = substr($str_data,60,8);//太阳能板电压4字节
		$accumulator = substr($str_data,68,8);//蓄电池电压4字节
		$waterlevel = substr($str_data,76,8);//水位4字节
		$watertemperature = substr($str_data,84,8);//水温4字节
		$sluice = substr($str_data,92,10);//堰水计5字节
		$rainfall = substr($str_data,102,8);//雨量4字节
		$capacity = substr($str_data,110,12);//水位存储容量6字节
		$rainfallstorage = substr($str_data,122,10);//雨量存储容5字节
		$advocate = substr($str_data,132,10);//主信道包数5字节
		$always = substr($str_data,142,10);//总信道包数5字节
		
		$dataKey = array(
			"datetime" => $datetime,
			"stationnumber" => $stationnumber,
			"voltage" => $voltage,
			"accumulator" => $accumulator,
			"waterlevel" => $waterlevel,
			"watertemperature" => $watertemperature,
			"sluice" => $sluice,
			"rainfall" => $rainfall,
			"capacity" => $capacity,
			"rainfallstorage" => $rainfallstorage,
			"advocate" => $advocate,
			"always" => $always,
		);
		return $dataKey;
		exit;

		
		for($n=0;$n<strlen($str_data);$n++){
			if($n>=16 && $n<44){//时间14字节
				$datetime.= $data_array[$n];
			}
			if($n>=44 && $n<60){//站号8字节
				$stationnumber.= $data_array[$n];
			}
			if($n>=60 && $n<68){//太阳能板电压4字节
				$voltage.= $data_array[$n];
			}
			if($n>=68 && $n<76){//蓄电池电压4字节
				$accumulator.= $data_array[$n];
			}
			if($n>=76 && $n<84){//水位4字节
				$waterlevel.= $data_array[$n];
			}
			if($n>=84 && $n<92){//水温4字节
				$watertemperature.= $data_array[$n];
			}
			if($n>=92 && $n<102){//堰水计5字节
				$sluice.= $data_array[$n];
			}
			if($n>=102 && $n<110){//雨量4字节
				$rainfall.= $data_array[$n];
			}
			if($n>=110 && $n<122){//水位存储容量6字节
				$capacity.= $data_array[$n];
			}
			if($n>=122 && $n<132){//雨量存储容5字节
				$rainfallstorage.= $data_array[$n];
			}
			if($n>=132 && $n<142){//主信道包数5字节
				$advocate.= $data_array[$n];
			}
			if($n>=142 && $n<152){//总信道包数5字节
				$always.= $data_array[$n];
			}
		}
		
		$dataKey = array(
			"datetime" => $datetime,
			"stationnumber" => $stationnumber,
			"voltage" => $voltage,
			"accumulator" => $accumulator,
			"waterlevel" => $waterlevel,
			"watertemperature" => $watertemperature,
			"sluice" => $sluice,
			"rainfall" => $rainfall,
			"capacity" => $capacity,
			"rainfallstorage" => $rainfallstorage,
			"advocate" => $advocate,
			"always" => $always,
		);

		return $dataKey;
		
		exit;
		
		$data_array = explode(" ",$file);//分割包内容
		
		$datetime = "";//时间
		$stationnumber = "";//站号
		$voltage = "";//太阳能板电压
		$accumulator = "";//蓄电池电压
		$waterlevel = "";//水位
		$watertemperature = "";//水温
		$sluice = "";//堰水计
		$rainfall = "";//雨量
		$capacity = "";//水位存储容量
		$rainfallstorage = "";//雨量存储容量
		$advocate = "";//主信道包数
		$always = "";//总信道包数
		
		for($n=0;$n<count($data_array);$n++){
			if($n>=8 && $n<22){//时间14字节
				$datetime.= $data_array[$n];
			}
			if($n>=22 && $n<30){//站号8字节
				$stationnumber.= $data_array[$n];
			}
			if($n>=30 && $n<34){//太阳能板电压4字节
				$voltage.= $data_array[$n];
			}
			if($n>=34 && $n<38){//蓄电池电压4字节
				$accumulator.= $data_array[$n];
			}
			if($n>=38 && $n<42){//水位4字节
				$waterlevel.= $data_array[$n];
			}
			if($n>=42 && $n<46){//水温4字节
				$watertemperature.= $data_array[$n];
			}
			if($n>=46 && $n<51){//堰水计5字节
				$sluice.= $data_array[$n];
			}
			if($n>=51 && $n<55){//雨量4字节
				$rainfall.= $data_array[$n];
			}
			if($n>=55 && $n<61){//水位存储容量6字节
				$capacity.= $data_array[$n];
			}
			if($n>=61 && $n<66){//雨量存储容5字节
				$rainfallstorage.= $data_array[$n];
			}
			if($n>=66 && $n<71){//主信道包数5字节
				$advocate.= $data_array[$n];
			}
			if($n>=71 && $n<76){//总信道包数5字节
				$always.= $data_array[$n];
			}
		}
		
		$dataKey = array(
			"datetime" => $datetime,
			"stationnumber" => $stationnumber,
			"voltage" => $voltage,
			"accumulator" => $accumulator,
			"waterlevel" => $waterlevel,
			"watertemperature" => $watertemperature,
			"sluice" => $sluice,
			"rainfall" => $rainfall,
			"capacity" => $capacity,
			"rainfallstorage" => $rainfallstorage,
			"advocate" => $advocate,
			"always" => $always,
		);

		return $dataKey;
	}
	
	//包解析->存储数据库
	public function getAnalysisData($datas){
		//包数据
		$data_array = $this -> getDataPackage($datas);
		
		$datetime = hex2bin($data_array['datetime']);//时间
		$stationnumber = hex2bin($data_array['stationnumber']);//站号
		$voltage = hex2bin($data_array['voltage']);//太阳能板电压
		$accumulator = (float)hex2bin($data_array['accumulator'])/100;//蓄电池电压
		$waterlevel = (float)hex2bin($data_array['waterlevel'])/100;//水位
		$watertemperature = (float)hex2bin($data_array['watertemperature'])/100;//水温
		$sluice = hex2bin($data_array['sluice']);//堰水计
		$rainfall = (float)hex2bin($data_array['rainfall'])/10;//雨量
		$capacity = hex2bin($data_array['capacity']);//水位存储容量
		$rainfallstorage = hex2bin($data_array['rainfallstorage']);//雨量存储容量
		$advocate = hex2bin($data_array['advocate']);//主信道包数
		$always = hex2bin($data_array['always']);//总信道包数
		
		$str_data = "时间：".$datetime."----"."站号：".$stationnumber."----"."太阳能板电压：".$voltage."----"."蓄电池电压：".$accumulator."----"."水位：".$waterlevel."----"."水温：".$watertemperature."----"."堰水计：".$sluice."----"."雨量：".$rainfall."----"."水位存储容量：".$capacity."----"."雨量存储容量：".$rainfallstorage."----"."主信道包数：".$advocate."----"."总信道包数：".$always;
			
		//写入文件
		$myfile = fopen("D:/xampp/htdocs/WlMonitor/data/test_data.txt", "w") or die("Unable to open file!");

		fwrite($myfile, $str_data);
			
	}
	
	//获取数据包内容
	public function getDataPackage($datas){
		//$str_data = bin2hex($datas);//ascii转16进制
		$str_data = $datas;

		$datetime = "";//时间
		$stationnumber = "";//站号
		$voltage = "";//太阳能板电压
		$accumulator = "";//蓄电池电压
		$waterlevel = "";//水位
		$watertemperature = "";//水温
		$sluice = "";//堰水计
		$rainfall = "";//雨量
		$capacity = "";//水位存储容量
		$rainfallstorage = "";//雨量存储容量
		$advocate = "";//主信道包数
		$always = "";//总信道包数
		
		$datetime = substr($str_data,16,28);//时间14字节
		$stationnumber = substr($str_data,44,16);//站号8字节
		$voltage = substr($str_data,60,8);//太阳能板电压4字节
		$accumulator = substr($str_data,68,8);//蓄电池电压4字节
		$waterlevel = substr($str_data,76,8);//水位4字节
		$watertemperature = substr($str_data,84,8);//水温4字节
		$sluice = substr($str_data,92,10);//堰水计5字节
		$rainfall = substr($str_data,102,8);//雨量4字节
		$capacity = substr($str_data,110,12);//水位存储容量6字节
		$rainfallstorage = substr($str_data,122,10);//雨量存储容5字节
		$advocate = substr($str_data,132,10);//主信道包数5字节
		$always = substr($str_data,142,10);//总信道包数5字节
		
		$dataKey = array(
			"datetime" => $datetime,
			"stationnumber" => $stationnumber,
			"voltage" => $voltage,
			"accumulator" => $accumulator,
			"waterlevel" => $waterlevel,
			"watertemperature" => $watertemperature,
			"sluice" => $sluice,
			"rainfall" => $rainfall,
			"capacity" => $capacity,
			"rainfallstorage" => $rainfallstorage,
			"advocate" => $advocate,
			"always" => $always,
		);
		return $dataKey;
	}
	
	//获取图片包内容
	public function getDataPicture(){
		$urls = "D:/xampp/htdocs/WlMonitor/data/tupian1.txt";
		$file = file_get_contents($urls);
		
		return base_convert($file, ASCII, 2);//bindec($file);exit;
		
		$data_array = explode(" ",$file);//分割包内容
		
		$start_tag = "";//起始标识
		$type = "";//数据包类型
		$parameter_length = "";//参数数据长度
		$datas = "";//数据域头
		$picture_name = "";//图片名称
		$lumps = "";//当前块数
		$image_data = "";//图像数据
		$intended_effect = "";//CRC效验
		$trail = "";//数据域尾
		$ending_tag = "";//结束标识
		
		for($n=0;$n<count($data_array)-7;$n++){
			if($n>=0 && $n<1){//起始标识1字节
				$start_tag.= $data_array[$n];
			}
			if($n>=1 && $n<2){//数据包类型1字节
				$type.= $data_array[$n];
			}
			if($n>=2 && $n<4){//参数数据长度2字节
				$parameter_length.= $data_array[$n];
			}
			if($n>=4 && $n<8){//数据域头4字节
				$datas.= $data_array[$n];
			}
			if($n>=8 && $n<15){//图片名称7字节
				$picture_name.= $data_array[$n];
			}
			if($n>=15 && $n<16){//当前块数1字节
				$lumps.= $data_array[$n];
			}
			if($n>=16){//图像数据
				$image_data.= $data_array[$n];
			}
		}
		

		$intended_effect.= $data_array[$n].$data_array[++$n].$data_array[++$n].$data_array[++$n];//CRC效验4字节
		$trail.= $data_array[++$n].$data_array[++$n];//数据域尾2字节
		$ending_tag.= $data_array[++$n];//结束标识1字节

		$dataKey = array(
			"start_tag" => $start_tag,
			"type" => $type,
			"parameter_length" => $parameter_length,
			"datas" => $datas,
			"picture_name" => $picture_name,
			"lumps" => $lumps,
			"image_data" => $image_data,
			"intended_effect" => $intended_effect,
			"trail" => $trail,
			"ending_tag" => $ending_tag,
		);

		return $dataKey;
	}
	
	//包解析
	public function actionAnalysis(){
	
        // 	echo hexdec(substr("7ba1000a494d414707e00713110002438401000000000d0a7b",30,2));
	//	exit;
	//        header("waterMonitor/user/index");
                $id = 1;
               // $model = $this->findModel($id);
               // $model =WaterSinglertustation::findModel($id);
                echo $id;
               
        $dataProvider = new ActiveDataProvider([
            'query' => WaterSinglertustation::find(),
        ]);
               // $model = new WaterSinglertustation();
                     //  $query = watersinglertustation::find();
                        //  $dataProvider = new ActiveDataProvider([
                        //      'query' => $query,
                        //      ]);
              // return  $this->render('../water-singlertustation/index',['dataProvider' =>$dataProvider] ); //['model'=>$model,]);
              // return  $this->render('../water-singlertustation/index',array('dataProvider'=>$dataProvider));
       


         // return $this->render('watermonitorydetail',['model' => $model,]);
               // return  $this->render('watermonitorydetail',['model'=>$model,]);
               // return $this->redirect(['watermonitordetail','ids'=>1]);
               return $this->redirect(['water-singlertustation/index']);
               exit;   
           	// 连接，选择数据库
		$dbconn = pg_connect("host=localhost dbname=WlMonitor user=postgres password=pgsql123 port=5432")
			or die('Could not connect: ' . pg_last_error());
			
		$id_sql = "select max(id) as m_id from water_monitor";
		$result = pg_query($id_sql) or die('Query failed: ' . pg_last_error());
		$val = pg_fetch_array($result);
		echo $val['m_id']+1; 
                echo "aaaaa";
                exit;

                // WlMonitor/frontend/web/index.php/waterMonitor/water-singlertustation/index;

		// 执行 SQL 查询
		$query = 'SELECT * FROM water_monitor';
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		
		$query = "update water_monitor set site='test123321' where id=1";
		$result = pg_query($query) or die('Query failed: ' . pg_last_error());
		
		echo $result; 
                exit;
		
		$datasss = "7ba20208494d41470000000000000001ffd8ffe000104a46494600010100000100010000ffdb004300090606070606090707070909090a0c150d0c0c0c0c1912130f151e191f1e1c191c1c21242e2721222b221c1c2837282b30313434341f27393d39333c2e333431ffdb0043010909090c0a0c180d0d1831211c213131313131313131313131313131313131313131313131313131313131313131313131313131313131313131313131313131ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a7374000000000d0a7b7ba20208494d41470000000000000001ffd8ffe000104a46494600010100000100010000ffdb004300090606070606090707070909090a0c150d0c0c0c0c1912130f151e191f1e1c191c1c21242e2721222b221c1c2837282b30313434341f27393d39333c2e333431ffdb0043010909090c0a0c180d0d1831211c213131313131313131313131313131313131313131313131313131313131313131313131313131313131313131313131313131ffc4001f0000010501010101010100000000000000000102030405060708090a0bffc400b5100002010303020403050504040000017d01020300041105122131410613516107227114328191a1082342b1c11552d1f02433627282090a161718191a25262728292a3435363738393a434445464748494a535455565758595a636465666768696a737475767778797a838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae1e2e3e4e5e6e7e8e9eaf1f2f3f4f5f6f7f8f9faffc4001f0100030101010101010101010000000000000102030405060708090a0bffc400b51100020102040403040705040400010277000102031104052131061241510761711322328108144291a1b1c109233352f0156272d10a162434e125f11718191a262728292a35363738393a434445464748494a535455565758595a636465666768696a7374000000000d0a7b7ba20208494d4147000000000000000275767778797a82838485868788898a92939495969798999aa2a3a4a5a6a7a8a9aab2b3b4b5b6b7b8b9bac2c3c4c5c6c7c8c9cad2d3d4d5d6d7d8d9dae2e3e4e5e6e7e8e9eaf2f3f4f5f6f7f8f9faffc000110801e0028003012100021101031101ffdd00040002ffda000c03010002110311003f00e3ae2d22ba50240772fdc75e190fb1aa65e5b2005d90d181febc0c2ffc087f09fd3dfb534ce72c0f5a5c5508ffd0e0eeec20be8f64cb9c7423aafd2b98d47499f4f21882f11fe30381ec7d2a9994256d0a35d0e8de27780adbdfb178ba0971965fafa8fd7ebc0a93492ba3ffd1e6a3912450f1bab2b0c86539069e3a50623d697d850c0fffd2c2fe214fa0c90e029c071480ffd3ca0296958c850297ad033fffd4cf18a514bcccc70e94a3a71480ffd5a83e94ec5233014a296807ffd68681d2a48f21c29451603fffd768a5c735048a2945007fffd0752802a09485fc697193cd0819ffd19c0c5281deb3448a294628068fffd2b628150ac217e873f4a5c91401ffd3ba39a776acfa083140e6981fffd4d0a5edcd42100e99a514ec07ffd5d3a2a40314b4988fffd6d4e828f7a9b085193de947340791ffd7d7a518a95a6820c027a51b57d052db41743fffd0d8d88474eb4862534ba936e81e50e693caed9a7a0ac7ffd1d6311f5149e511da84409b181e946da3d44cffd2d4db46283261b78a4c7345fb88ffd3e53b50000000000d0a7b7ba20208494d41470000000000000003704118e08c1a6739464b292d72f6837c7de02dd3fdd3fd0f1ee296de78ee158a13f29dac08c1523b11d8d34c19ffd4e338a4644752aea194f0411906ace739fd53c3c63569ecf254758ba9fc3fc2b0c82a4820823a8352d1b465747fffd5f1fd235bb8d25f0bfbc818e5a227009f507b1ff3d8576d617f6da8c226b69038fe253c3213d88edd0fe5c51b1125d4b5da9cb4c83fffd6c21f7aa4e0d06428069f401fffd7cb0297a506414a050c68ffd0cf14a29198a3a53b1401ffd1a801a752331452fe34867fffd28714a054903852e3bd007fffd368a70e0545c917145303ffd47ff3a5078c54122814a280e87fffd5b007a13405a8b8ae3b1401401fffd6b74b8a8043b1cfad18c51aa1791fffd7bd8a5c1e2a108318a70145c765b9ffd0d2c0a31c66a04005380c9a7719ffd1d4c5047cd521b07514b4c47fffd2d5239ed46054ad05b8018a5a607fffd3d614bf9d26c5a5c5a5a101ffd4d91c528a42b052820f6a623fffd5dcc518f4a168405185e9401fffd6dbda0f6a5f2d48f4a7722c86f963e948621d73f4a691363fffd7e4e9734ce701505cd8c770e2607cb9d461655eb8f423b8f63f862901ffd0e15659229560b94dae7eeb8fb8ff0043d8fb1e7ea39ab00fa55ee738b8acdd4f4486f95a48f11cfd9b1c37d7fc68609d99ffd1f13b9b59ad25314e85587ea3d452da5e5c584c27b595a2900c64771e8477140b000000000d0a7b7ba20208494d414700000000000000047476ba37882df550b0be22b9c64a766f753fae3afd719ad95a466d599fffd2c31cb7e1520a3631145387a503b1ffd3cb14a29190b4a0d033ffd4cf14ee94ba998b4a071401ffd5aa3d29471da9320777a5a407ffd6871de9d52c8628a7019e9401ffd73140151b923852d0163fffd078a506a09b0a053b1f4a0763ffd1b03a1a70a8b928052d2b0ec7ffd2b98a5ed59a7a890a052e0556a80fffd3d014a056602f5a00c556807fffd4d3c7a5151601463f2a307f0a1858ffd5d61eb4983f954a15ae2d1f5343407fffd6d7a4fca9200a5eb4847fffd7d7c50074cd210a052e734058ffd0d9edcd281489006814d0ec7fffd1dbcfbd2f4a489173c5266981ffd2dc1471da9936141a3a7342dc93ffd3e4cd2d08c02945303fffd4e5248d26431ca8b22370cac320d536867b4ff55bee21cf2a79913e9fde1fafd69a30248a68a78c4913aba1e841ce69f8aa11ffd5e1ef2ca0bd88c53ae57b11d54fa8ae5752d1e7d38eeff590f6703a7b11daa9a3284acec510482083823a5753a1f8b394b6d4dfb616e0ff00ecdfe3f9f7352692573fffd6c28d95be65656560082a7208ec41ef52506428a78a00ffd7cbe01a514b74642d28a067ffd0a0297149ee663873c5281480ffd1adc0a5a9b1028a5a2cc0ffd2885387153720053852633fffd3052f5a8f4245f6a05007ffd49318a51e95048b8f4a51d6803fffd5b229718c7bd409000000000d0a7b7ba20208494d414700000000000000058a297e9401ffd6bb8e29477acc48503b52f6a633ffd7d1f4fca940a8dc05fad2e3d28d901fffd0d4c678a51f4a8dd00b8f4a00a101ffd1d6a2a4051d68029033ffd2d8a31cf4a91001476a2c33ffd3d8c034a052f415c3d7d68e0679cd007fffd4d902971c8a192140a2c3d8ffd5db1d31da8fc28b12c51d78a29ad02e7fffd6dd1453b22370340a103ee7ffd7e531450602d14c0fffd0e56968302adcd9991da7b76114e7924e7639ff00687f5ebf5e94c8ae097f2668cc53019da7a30f553dc7f2ef8aa4ec163fffd1e3b39a4655652ac0302304119047a559cece7b55f0f15dd3d9027b98bff89ff0ff00f55611054904608e08352d1b45dd1fffd2f24d13c4371a4b88db335b1eb193cafba9edf4e9c9fa8ee6c750b6d46059ed64de87a83f794fa11d8ff91eb410d5b52c83e94fa3724fffd3ca14b48c851cf6a70a067fffd4a03834ea48cc5c5385007fffd5adda9ca2a762050297de84ae07ffd68c74a50315240a297140cfffd700a5e2a5122e2940a480ffd09452e3b54edb922e3e94bed4bc80ffd1b22940152216940a498cffd2bb8ec697eb5170d05c73cd2814901fffd3d2a5038a86002971401fffd4d5032697daa0031d39fad28c9e7b7ad0867fffd5d7028c540c500fb51dbda981ffd6d8c0a5c6338a56b000a3f0a2c07fffd7d9c52d02131ed4b8f5a480ffd0dac514083bf5a5ed45c47fffd1dbc74a51000000000d0a7b7ba20208494d41470000000000000006413b876a3af34c0fffd2dc031fce971c5040629718ed8a7603ffd3e47cd3e94a25f6a7639c5f307a51bc51603fffd4e5378a5dc3d68b1806e151cf045731f972a8650723d54fa83d8fb8a00fffd5e1a4335967ce26587b4a07283fdb1fd47e205488eaea1958329e4107ad5277301c48f6aced4b4782fc175c453ff7f1c1fad0c13b6a7fffd6f13b9b59ad2531cc8548e87b1fa5741e0ab7737335c0675455da40270c4f406825bd0ec54d3d4e6820ffd7ca06969198e14b43f203ffd0a0053b34b746628a70a18ee7ffd1ac053c5490029d8a067fffd2601c528a9245a5e4f5a5b01fffd370a00f6a9d8429fa53852047ffd4986052f4e6a05a8a3e94efe7482c7fffd5b6052e2b3bdc03a5281401ffd6bc39a754300c7a53ba76a02e7fffd7d214a2b301714a3bd3bdc67fffd0d6ed4632318eb50342fd6969e807ffd1d9a4fc2a500a052e29219fffd2dac518149683770c76a403bd1711ffd3dac527e1490c5a00f5e68423ffd4dac76a2816c2f7e940a3d40fffd5dcfca92810514c47ffd6dce452d3640bde8edd2803ffd7e3bde8eb55639c2814303fffd0e3e9d9c5339c01a5046298ee7fffd1e4f233550e9b1a9cdb48d6ff00ecaf287fe027a7e18aa301bf63be078ba8187bc247fecd4af6f7c0fc82ddbeae57fa1a02c7ffd2e02e34ebabc8cc5736f0b2f505252483ea32062ade87a7369969e53e37b3163839fa000000000d0a7b7ba20208494d4147000000000000000753f431f234964614f599bda8b219ffd3c2f3ce7a0a78b8ff0067f5a2c64385c0eea69e2e178c834ac3b9ffd4cb13a1eb9a789e3f5a56331e268c8fbc29e1d3fbc3f3a4c0ffd5ac31f81a70c638a92051c53c0a067fffd66814bf4a9b122e2940ed4819ffd770e2940a8b122818a518a7619fffd09c0c52d40851ee322968b023ffd1b78a502b35a085c1c52814c2e7ffd2d014a076a8f5040403c67f2a7628b08fffd3d3c528fa54240029c3d29033ffd4d6ebd2945468805e28c76a067fffd5d9c51516b0c5e3a514c47fffd6db3eb462a7a00518e314fd00ffd7dbc63f0a0fe1480369cd2e38a1203fffd0dbc702971c9345ae21314b8a019fffd1dce94516243d7f4a28d106e7ffd2dcef4e0299170f4e9cd20c114d20d4ffd3e3f18a5239154738628c5007ffd4e431ed46daa300c514303fffd5e446451fad3301c1b14a1b3405cfffd6e543d481e9988bb87e34a1a901ffd7e703669c0d0643a9453047ffd0c3a3f0a0c85a51d2803fffd1c604f14f0e7b311f8d0643c4b20fe334e13ca06371fc693b31dd9fffd2a22ea41e94e178e072aa6a48b8e17a3fb9fad28bc527054fd68b05cfffd3892ee2ef91f8548b7311fe2a97726e3c4b1b670eb9fad3c32f5c8a5719ffd4b03938c834a0d67d05aec8514a28407fffd5b631d69401dab34038006978a2e163ffd6d014e1d3dab3600314b8a77d067fffd7d4fce97005000000000d0a7b7ba20208494d4147000000000000000842017a52819a03a1ffd0d7a00e95031452f142d40fffd1d90dc6077a5c541428a29ee23fffd2db14bf8525b8c071cf7a2803ffd3dc1d7eb4a052001462803fffd4ddc63b5291c500c4c52e33408fffd5dd23b51cd0841c520031db8e945c47ffd6dda071da8dc80f4a0f1cd303ffd7e4718a3156738b462901ffd0e4c0e38a00aa300c5211c74a00ffd1e4b18ed4b8a6602525303fffd2e44138a5dc45062287cd3c3d3607ffd3e6031a706a6645bb27b35940be59fc93d5e123727bed20ee1ec39f4cf43d345e10b0b98527b6d4a5922704a3aed753cfa803e9f8629a7dd1514a5ea7ffd4b4fe0ae7f77a8e3fdeb7cffecd4cff008432e78db7f01fac6c3fc6aeebb0bd9e9b913f83b5053f24d68fff000361fcd6a36f0aeaca388627ff007655feb8a3dd17248fffd5aede1ed5d0e0d8487fdd746fe4d50c9a5ea30e77d85d8f53e4311f9e3154a2ba323965d881d4c5feb014c750e31fce8560df7483f434b91ae84dcfffd6cced4d3c74a36330196a7006901fffd7cd008a70ce7e94881e3269c3228607ffd0ac198743f95384b20e43b71ef4ac46c3bce940fbe69e2e65c7507f0a9b0ee7ffd15178fdd54d396f31d507e06a2dd84a561eb789c655bfc29e2ee23c9dc3f0a2cd0731ffd2ba2e623fc5d7b53c4b19e03afe751a85c786047068cf15298d1fffd3d51d294567a0c2940e29a03fffd4d8c114a0542d862fb5000000000d0a7b7ba20208494d41470000000000000009250b403fffd5dac74a5ed51d0614014c0fffd6dca5c1fc6921d808cd1401ffd7defad1838e9cd2186297146a07ffd0dfc5046280b8839fa52f6a2dd40fffd1df039a314f6d04260fa51db20e6816acffd2dec51f4a2e48527b5049ffd3e571484555ce70c71462981fffd4e5314a055180628a00ffd5e500a31eb54600466908f7a407ffd6e48f229b4cc04ddf852ef3d2803fffd7e43cd65ed4dfed0449046ca7268d4c6e68ac4de587da4023357749d5aeb4699a4b52192420cb0b1c2c87a67d9b000dc3d06410314ca4ec7fffd0e9f4bd5acb5885a4b57f9d31e6c4dc491e7a647a707919071c1e0d5cdbc7bd52ec5a7743b68c501714867fffd1ed766697183f4a6682e5ff00bc4fb66a192d6de550b2dbc320e4e1e356e7f1142d03c99fffd2ea1f44d29ce4e9f6ebcf3b1767fe838aaafe18d29ce4452a7a6c98ff005cd5f348a708b223e0eb27c94bbb94faec6fe82a0b9f08088068af59940e7745c8fd695fc89705d0ffd395bc3b3a8e278d8fb823fc6a23a2de2768db8ecdfe34f4138b233a65e27581bf020ff234c36d3af0d0ca38cf287eb49a158fffd4ac415383c1a066959902fe340fad3f901fffd587a5033488147d69476a49bb81ffd66d02a6e48a38c638c548b2480603b0fc68682f63ffd7945c4831fbc3e83be69e2ee600f20fd454d84a43d6f5fba83f4e29cb7a3f893eb834acd6c1cc7fffd0d15b000000000d0a7b7ba20208494d4147000000000000000ac88f04383eb8e29eb751138dff0081151a85d0f1346df76453f8d3f83e868f51e8cfffd1dbf4f4a5c63d6a50fa01e94a0719cd0ae07fffd2dd029695c6831d6803145ac07fffd3df039f5a7638a486260d2818a7703fffd4e87146280d808c1fad18a00fffd5e87a51f4a1a1077c5211de8b033fffd6e80a8a4c7a5089020fa5277a6893ffd7e5b18a31cd5180b8a4c5303fffd0e5b1462acc05c526293407ffd1e5b1462a8c44c5260771480fffd2e4cafa534834cc463f009f4ae75fc50e1d80b652b9e0eeebfa52638ab9ffd3f2d1e2a23adaff00e3ff00fd6a8e6d7ad6e4ef9ac18385c065979fa74a08e434349f11da44de54975751a11c2483746a7b639257bf4007af6c74b1bac8a1d18329190ca7208f506826cd1fffd4c4b7b89ed2e12e6da678664fbaebfa823a11ec722bb8f0ff008922d582db4e8b05ee08f2c1f965e39299f6e76f2473d40268b8a12b3b1b58e319a519f4e2a8d4ffd5ee00e29714cd0314841c66981fffd6ee197201c5376e4e0726a8d07018e87a7e35201c7d696c07ffd7ec6e2cc2e5e3e9dc7a55468c0ce69fa16d0c68f8c7f4a89a3a7b08ffd0d964cf071cd44f046720c6873fec8a6914f5d089ad2120feec544d630ff748fa1a2e4b5d8fffd19cd8c63a337e34c6b2ee1f8fa5161584fb1b8e8c0fe9486da41d003f8d2b0accffd20c3203ca9c51b187553f954b44d8403da97a53f5000000000d0a7b7ba20208494d4147000000000000000b03ffd3514a2a490ef4639a067fffd49334a38a915c3b75a519ed4d211fffd5b2b23a8c873efcd3d6ea507ef671ed4ac85763c5e483b29f5e39a916f8e398f3cff7b149243b9fffd6d65bc43d548a78bb889c64faf4a561dd0ff3e2fef8a70643fc408f6ef482e8ffd7e8401eb4628dc62e074a38a4901fffd0e8b1452188296988ffd1e8f8f4a3146e02628c73c5023fffd2e8b149b68d84c31f5a6918f6a7b927ffd3c09ed6481b6bae0d40579cd5985831462901ffd4e67146055980628c5219ffd5e6303d28c553300229319a067fffd6e588a695f4a66254d4d9e2b19de30770438c0ce38ebf875ae4868d7aca19635652320ab839a4545a5b9fffd7f193a4df0ff9776fc08a61d3ef17adb49ff7cd02e6440c8c8db594a91d88c56ae93ad5ce98e029f321ef131c0fa8f43ef403d4ffd0e66c350b7d42112c0f9fef29e190fb8fc0fd6ad6d0c31923041041c1047420f639ef45cc8eab43f171cfd97589140e047767001f693b0ff7ba7ae3196eac0c75a6b4378bba3fffd1eefb7e34631db1eb546a2e3d052630319a407fffd2eef6f1c526cfce9b469d050a71c8a705238c74a3619fffd3f4245fc6a0b9b118324638eacbe9effe7ffd4ec685078c05a85e3f4a2ddc4cffd4e8593d07b544529a2d8c65a8cad1e849ffd5d32b4d238f4a69683131ed4633408fffd6d0dbf95263da8ea00403d464d21890f551f95160000000000d0a7b7ba20208494d4147000000000000000cd8ffd7ba604cfdda3ecc847008fc684b40634db0ecdc527d94f6607f0a2c8563ffd0b06ddc74c1a3ca6eea68b5c4214618257f4a00cd2b08ffd1b181e941e296e4dc514a3e9421b3ffd2b791da81484ae3872281d28f20bd8fffd3beac54614903da9eb3cabd1cd0f7126c7add483d0d49f6b6c7dc1f9d2b0eed1fffd4db176a7f808fc69e2e623d720fd28b05c70923c7de18fad38303ce46292761ee7fffd5e8cf5cd2d002e38ed49818f7a05b9fffd6e936f1cd18cd315c4c71d690ad0908ffd752239d00215d0f23bfe5542e749ead01247f74f5ab32666c909462aca411c10474a8c8a093ffd0e6f0450055b301314b8a101fffd1e6f1462a8c44233462803fffd2e676d262a8c44c7391c115467b164265b4daac492d11e11cfaff00b27e9c7b739a407fffd3e162915cb21564913ef238c11fe23dc706a4db9ed4cc086e2ca0ba4d9346186720f71f435cfdf69335892c01922cf0c0723eb458a4ec7fffd4f1db5b99ace613dbcac8e32011fcbd0fd2baed23c4505fed866022b823a0fbadf4ff000fe7410cd90dc56d683e249b4711dbccaf3d8801420fbd08edb3d47fb3f963a1184656773fffd5edadeea0bdb78ee6da54961933b5d7a1c7041ee0820e41e460e6a6039fad55cd34171eb41f4c0fc2901fffd6ef860d3828381efd69dee6a382e7e94a23cfd6981fffd7f4755039c74a90114cd4a5796630658861000000000d0a7b7ba20208494d4147000000000000000d7f89403c7a9fa7f2fa74a0f18a3d093fffd0ea5a3f6a84a67b74a116c8d933c546529dc4cfffd1d82a29bb29f90c69538239e682b46807ffd2d323349b698c0019c518a40cffd3d2c52e3b5341d431cd016819ffd4d3c52edfafd2a9030c76a0a8f4a4fb81ffd5d331a1fe114d3027a114ec16b89f6753d188a4fb3b76228d02c7ffd6bc607c9c7229be53aff09a397a08500a8e41a39a2c23ffd7bb9a51c0efef4ed710e06969696b81ffd0d2a33814c9141a7678c51603ffd1d75620601c0c7ad384add4b1a09d872dc3e3b1a78b83dd41a1a0b9ffd2df170b8e41a72ce878e473e94da2531c2407ee919fad3be5ce6a5303ffd3c1b7bb9ed4feea4207753c83f856c5a6a70dc7cadfbb6f463c1fa1abd8c532c4d6b15c00254391c67b8acbb9d2a5886e4c3a8ea471fa53766368ffd4e7cc64704526dab310db4639a407ffd5e7b1498ab310c5047a52b01fffd6e7369a36d518898e29a5692407ffd7e4ee2d23b8501b2aca72aebc32fd0ff4e87bd52267b560b720153c0994614f38008c92a7f4f7cf14cc49b3e94ec0c60f7a1580ffd0f37d43420499acd557b98bb1ff0077d3e9d3e98ac52ac1cab0208e1948e47b1f4a66699d0691e23308105fb3320e166c12c3d030ea7ea39faf5ae9a3757556460cac32a41c823d45203fffd1a7a56ad77a3cc65b52ac8e41961727649f9746c7461d3dc715dee93ab5a6af019a000000000d0a7b7ba20208494d4147000000000000000ed58e5302489b1be33ee3d3d0f43fa51a0e0fa177ae0d2ae0f6a7e4687fffd2f41c0f4a5029f91a8f14a393e9406c7fffd3f481927fc2947a53351ea71556eac000648800b8e540e9f41e9fca803fffd4ecda2238239a81e3a11a113479e95132638a7711ffd5de299a615c0a342bc84dbe829bb78a7a5c563fffd6d8dbde9a54f2450acc618ef8a08f6a61a9ffd7d5c518e79a6314018e98a5fc290b53ffd0d70339e28c53189b71c9a5dbc50ec07fffd1d7c66948f6a6360568229dd01fffd2d7033f5a76315453003da93cb53d547d7140b53fffd3d83127f7690dbafa115564027d9bd1a90dbb7622924163ffd4d430b8ed485587f09a6969a8986314a290af6d4fffd5d506941f4a7a5891452f38c8a407ffd6d81ef4b9a08172474a7027d69a5a81ffd7e6813df9a7af3deaf6302e5a6a535ae10fef231c6d6edf43dab62dafa0b9ff0056f86feeb7047f8fe141499fffd04b9d3a2b80597e47fd0d665c58cb01f9d0e3b1ec6b43268ac548ed498a407fffd1c0a5c5518898a314c0ffd2e7f1e946315464211498a00fffd3e7768a69407208ce7a8aa322849652db12d6803a67fd4310028ff60f6fa1e3dc014904c9303b720a9c32b295653e841e45216c7fffd4e487d2aadf6990dfae5b0928181201923d88ee2a8c4e7aeace7b393cb9931e8c3956fa1ab5a5eaf71a63851fbc809cb444e3f107b1fd0f71de958adc000000000d0a7b7ba20208494d4147000000000000000fffd5e7ec6fedefe1f3607dc33820f0ca7d08edfe719abf6b75359dcadcdb4ad14a990aebdc77047707d0d1b99dcee343f11c1ab816f2aac1798e63fe1930324a1f4ff64f239ea066b64727bd1e474269ab9fffd6f425fa0a7283d3b7ad3351df8528a680ffd7f49db918f7c503aff4c53351eb52c67239a00fffd0f47bbb2c969625e0f2ca3b7d3fcfe9d339a3c038a77352178f8e0542cbea28bdc47fffd1e98a546ca3bd058c29cd30ad311fffd2dbdbcd201cd05098e73462981fffd3d9228c64d056a18c52e3da9a607fffd4dadbed4114c604518a680fffd5dac518f5154b61bd031463f5a61b9fffd6dbc518ab0003d29714219fffd7dd028c7bf3560007e3f5a5031ef4219fffd0df200a4c7b55dc18100e7229362e3eef34058fffd1dc3129a0423a826aac8560f248e869044de94ec1667fffd2da11b0ed49823b551029a28407ffd3e771cd262b43014123a8a7a9f43cfafa5007ffd4a369abc910d9701a55cfdefe21fe3f8f3ef5af0cd0ddc67cb65917f8971d3ea0fe35a5ccd32b5ce929265a1c2b7f74f4acb9ed2481b6ba153da8681ab1ffd5c42b498f4ab32d831462803fffd6c2c5047b55190633d0526def401fffd7c1db49b7daa8c84dbcf4aaf71631ce448a4c72a8c2baf5fa1f51ec7d4e3079a480ffd0e304af6eeb15da842485594708e7d07a1f63ef8ce3356453321b3411dc42d14aa1918720ff000000000d0a7b7ba20208494d4147000000000000000fffd5e7ec6fedefe1f3607dc33820f0ca7d08edfe719abf6b75359dcadcdb4ad14a990aebdc77047707d0d1b99dcee343f11c1ab816f2aac1798e63fe1930324a1f4ff64f239ea066b64727bd1e474269ab9fffd6f425fa0a7283d3b7ad3351df8528a680ffd7f49db918f7c503aff4c53351eb52c67239a00fffd0f47bbb2c969625e0f2ca3b7d3fcfe9d339a3c038a77352178f8e0542cbea28bdc47fffd1e98a546ca3bd058c29cd30ad311fffd2dbdbcd201cd05098e73462981fffd3d9228c64d056a18c52e3da9a607fffd4dadbed4114c604518a680fffd5dac518f5154b61bd031463f5a61b9fffd6dbc518ab0003d29714219fffd7dd028c7bf3560007e3f5a5031ef4219fffd0df200a4c7b55dc18100e7229362e3eef34058fffd1dc3129a0423a826aac8560f248e869044de94ec1667fffd2da11b0ed49823b551029a28407ffd3e771cd262b43014123a8a7a9f43cfafa5007ffd4a369abc910d9701a55cfdefe21fe3f8f3ef5af0cd0ddc67cb65917f8971d3ea0fe35a5ccd32b5ce929265a1c2b7f74f4acb9ed2481b6ba153da8681ab1ffd5c42b498f4ab32d831462803fffd6c2c5047b55190633d0526def401fffd7c1db49b7daa8c84dbcf4aaf71631ce448a4c72a8c2baf5fa1f51ec7d4e3079a480ffd0e304af6eeb15da842485594708e7d07a1f63ef8ce3356453321b3411dc42d14aa1918720ff000000000d0a7b7ba20208494d4147000000000000001155d4a3aab2b0c32b0c823d08ef545ac66b53fe8fba587fe79b392c9eb863d47b1e7af27814cc9a08654993721c8c904118208ea08ec47a54a3d7b8a7ea23ffd5e0f50d0e398b4d6a047213964fe16e793ec7f4e3dc9ac32ac8c518323a9e41e0a9ff0038e69989d0e95e232b88afdba9e26ffe2bfc7df9e84d74b1c8aca0e720d22933ffd6b7a0f88e6d276dbcc1a7b21801472d08ff0063d47fb27f0c743dbdacf0dddbc771048b2c520ca3af43ea3d8fa83c8ef47997069ab13751cf14ec74a651ffd7f4cc63a668c654f7cf5a66a48a31d0e38e69df4a047fffd0f5352053d186719e86834b156eac9547991e42f75f4ffeb55078c004f6eb914c67ffd1ed9a2ea7d6a278f8a7b1a1114c76a8ca7347a01fffd2e94a0c7a8c526d03814d9634ae7b5263da8b899fffd3e84ad26d069aee8a176f346da2c1d0ffd4e836fe34629941b71d4f14bb7f1a7b01ffd5e80a8cf4e3b52edabdc626da5db4867fffd6e871d075c9c52e3a127ad5ad8a0c5285a00fffd7e931d68c558f4023814de68bd80fffd0e8b19a08abe830200a0006811fffd1e888ce7b5281cf4abf40140e3a528e3b53607fffd2ea00ed4b8c5682171f851b451b81ffd3e7873de94559ce28a5a067ffd4c1c518ab31b0a1b1d6a686796dd8bc12146c6323fcf3f8d3047fffd52d35c47f92e86c6c7df51f29fa8edfe7a568496f05dc60b056047cae3fc6b4000000000d0a7b7ba20208494d4147000000000000001244a7732aef4792105e3f9d07a751f5ace6423b5264b563ffd6c62bc5155d0c82802819ffd7c6c73460d519863da8c62901ffd0c6e87a51cd51995aeacd6e1bcd46314e0002403391e8c3f887ea3b115544cf148b0dca796e78561928ff00eeb63afb1c1f6c7349099fffd1e66aadee9f05ea01202197eeb8eabedf4f6aab18b39fb9b49ac9c2cc0618e15c7dd6ff0003edf5ebd6ac69faadce9ff2ab178739f2cf6e79dbe9f4e99f4c93480fffd2ccb0b8b5beb6135ade47311c3a805593d010791d0f5ebd47b6ae97aa5e6913996d9815623cc858fc927d7d0e3a30e7ea38a2d7253b6a8ef34ad56d75780cb6cc77263cc89880f19ed91e9d707a1fcc55e141ba773fffd3f4dc71cd3954fa8a66ac7af1d452e38c6297503fffd4f530bed9a7200a719e9c7341a928c11cf3deaa5d59ed2d2a0f94f2c3fbbffd6a7b01ffd5f4092238241e7dc542d18a3a1a90b2039c7d2a368f1da988ffd6eada3f438ef4d298ed41a0d284722936f1d2988fffd7e908e38a5dbed4cb131cd2a8ee450bc83a1fffd0e976fad26dcb0aa45790bb7346dc0c5219ffd1e9767b5215c55228369a0019c719a00fffd2e93140c8e9565585db4a0114580fffd3e9c0a0af18ad1685580a67b526d3e94ac23fffd4e982f3cf6f4a36d68302b4817bfe7480ffd5e9b6f19149b4e78e9deac050a6942f19229dec07ffd6ead40e3fc28db5a08500e39c000000000d0a7b7ba20208494d41470000000000000013521a108fffd7c036ad11e491ed46dc1e9569ea602fd6939a680fffd0c156c8e841f434a3156601806800f6a2c33fffd1c4c8e86ac5a5ecf64d9858609e558654fe1fe156657b1b965abdbdd0547c4521e0ab1e09f63fd3f9d4b73a6417196dbe5bfa8e87ea2834f891ffd2ab77a74b6ec77292b9c061d0d536423ad599b5662629318a047fffd3c8c7ad18aab19a0c518a4c0fffd4c8c518aadccc0ad3248525429222b2b750c323d47eb83480ffd5e5a4b59ed0663df3c207dcfbd22fd3fbc3ff001ee3f889a48a58e750f1baba9ee0e45518ec124492a323aabab7504645625e692f6c0bc04c91019c1e597fc7f9fd7ad1603fffd6f33b2bdb8b1985c5a4a518819eeae3d18771fe460f35d7e93e23b7d4408dd7ca9c0cb479c83eea7b8fd47e448fb9945f436ad6ee4b69d2e6da531cc9c2b8c123d883c11c743c576da1f8920d5192d6e02c1787a2ff0004bfee9ec7fd93cfa67070799b425d0fffd7f4d5f9ba77a7a807a74f5a0d47ae7038e697fce2803fffd0f55c678f4a7a8f7f6a0d470e3b1a70ed9fd6988fffd1f50bcb50bfbd8c657f88771ffd6aa2e8299a90ba0a85928b6a23ffd2ec8a530ad3341a569a453d447fffd3eab149b78a762c4dbcfb5285ef421a3fffd4ea76d0077f6aa2c36e3eb46dc51e407fffd5ea76e3ad0572067b555997a0853d7e94a17d31f4a3503fffd6ea7651b2acab8bb78a02e3b5000000000d0a7b7ba20208494d414700000000000000141603ffd7eab1ef4b8eb56d32c08cd26d19ce39a3511fffd0eaf1463f5abb0c4da39f5a31cd1a81ffd1eaf1d28c0f4ad0036f7a50b9e6901fffd2ebc271460d6801b78a6b267be2815cffd380a0618619aaf259a924a1ebd8d599159e0643822a3da476a3624fffd4c10296aef730019269462819ffd5c33c9a01c74abdcc470208c11d7d6afd96af71684239f3621c6d63ca8f63fd3a7d28f5293b1fffd6d2b4bdb7be5222604e32d1b0f980f71fe19aaf75a4472fcd0908dfdd3d0fe3daafc87a4968644f692c2db6442a7dc5572b8ed4bccccfffd7c9fc2940cd5198018eb4a05007ffd0cbdb4bb734db330c5201ed4c0fffd1ca2bc74aa777a7099dae207315c11cb60957ff00797233f5e0f4e70314cc8a8649a23b67b6950f4dc8a5d0f4e7701c0e7be0fb544351b22c53ed506e0705778cfe54fd05b6e7ffd2e12f74c8a60d35b1547ce4a8fbac7fa1e3a8f7e0e7355b4cb5924d4238e4899190ee208e571ce7f30391ed4ee8c0e874cbf7b79fcbd4a3b92a4024c2a24d9c9e481f363af383f4cd6da32c8bc72a7d692344f43fffd3e8f44f15f97b6db559095c00b74c7247fd74f5ff007bf3eed5d6a8e077c8c83d8fbd22d4ae8907a52e3a0a651fffd4f57c7000fc69ca3bfad1b9a8f1cf4a08e7fa5311ffd5f5b66ebe86b3aea2099651f2e3a7707fc283529b119e950b1f6a1303ffd6ed0914c2723b517341a791000000000d0a7b7ba20208494d4147000000000000001548467b51703fffd7eb71e9498e28bd8d03146303a5311fffd0eb36fe14631dbe94ee58a071405a7a8cffd1eb76d295f4fd2acb136d017dbb52680fffd2eb769a5da327d2b42c02f269428eb8a5e407ffd3ec36fb51b7dab4b5cb1360a5283b52b0b73fffd4ec36fb66936d594010e4018e7b5014639c1cf4347a01ffd5ebf6e319ebed46df415a6c3dc319fa669c05023fffd6ecb1d2976e0fad580639e94d229a11ffd78f14bb7d2b43218ca1861866aa4f6e17953f81a1e80cffd0c361b4e0f069b900d59886452d203fffd1c3a31546226294311ef401ffd2c659304152411c820e08ad6b3d7644c25d0320fef8fbc3ebd8ff003fad5ee445d8d3f36dafa3ca959171e9c8fea3b5675d698ea7742432ff0074f5ff00ebd4dcb6afa9ffd3cd652a704734dc8cd5198a2971c50c0fffd4ce02940aa6661b7bd1b690cfffd5cedbc5214e38a666c42b4c752e30e72318e79e295867ffd6f20d49aee7bfb9bd8619e389a460aca8ca36838fe9cd5296e6e251b659a47038c33138a7a92ac4f0eafa940c0c57f74a463812b60e3db3cd753a178aaf5767db889a227058200ebefc6011ed8cfbf62ac825a1ffd782d2ea1ba8966864592361c32f43fe7d3b56ee87e209f47c44434f664f30e7e68fd4a13d3fdd3c1f6249a3a045db53bbb3bbb7bfb75b9b5956589b80c33c7b107907d8f352e28353ffd0f5851834f5e28b9a0f5e000000000d0a7b7ba20208494d41470000000000000016a690a81f80ef4c0fffd1f597aaee46718cd0cd0a1788b1302a386ea3d2a9b30cf3fca95c67ffd2ec89f422a36c741537351a4907ad34be2aae23ffd3ea77fe54bbf345fa9a06f07bd3830a7711ffd4eb4104fa5000f5ed4cd0514ec0fc69dc0fffd5ec38a315499a00191d2948a34158ffd6ec40e3814bb6acb61b47bd2851d2803fffd7ed36e78e6976d5ad0b00b4bb73c530b1ffd0ed8a034797cd5df42989b38cf349b00f5c5303ffd1ecf69e3bd1b38ad2e31421f4a50945c0ffd2edc2fbd2e39c015a0084014d2bde84163fffd36a8ef4b5a1908c3355a604034303ffd4c59bdeabb023914cc4682452873de9dc0fffd5e77cc3d288e70f9201f94e0d5188f120a70607a53607ffd6c2fa528623bd51912453c90b892272ac3b8eb5a76dad02025ca60f4debdfea3f2e47e5415195b73fffd79648a1bb01ce1b23860735427d3a58fe64f9d7d475a6292b94db729e45287f7a641fffd0cc0e3a0a78607bd55fa99e83b3c528c63da90cffd1a7b476a36d344085290c7401ffd2c3bad3164733c04453f527f864ff00787fecc391c7503159ed12492182ea0559b192ac3218772a71f30e473f98078a6b53368826d074d9810d691ae7ba0dbfcaa8dc68d2590cdb06962ce48eacbfe23f97e66825bd0fffd3f3cd3f52b9d39fceb57055f05d09f96418fd0fb8f6ea38aed349d62df538cb44595940df13e3727d71d47b8e000000000d0a7b7ba20208494d414700000000000000173f1c801945f43774cd4aeb4ab8fb459b282701d1b94907a30fcf07a8c9c7539eef48d6ad757843444c7328ccb039f9939edfde5f71f8e0f14799bc5f43ffd4f5951cf07eb520038028468380c03c10690f1c0a00ffd5f596e7a7e7d6ab3f0d8a19aa33af5b3311e800aa6c32783d3a54b607ffd6eb9873cd46738a846a332c39cd3771029dc47fffd7e9379a4df81de91a06f1eb4e0c3d69dd833fffd0e9b2452866f5a2e68c779871f4a787a6988fffd1eb8494a1c11de9a6cb0c823069d9f4a633ffd2ecc74e28c7e1eb57b1628f4a701ee3f1ef4bc8123fffd3ee052e3d3f1ab2c36f7a5c7b5007ffd4eef6e3b62940ab2c4db49b3d3b53f311ffd5ee767ebcd26de78cfe5568a176f1f4a50a453133ffd6eed541191d3da9db6b46310af7a6b2e0fa53480fffd768a70ad36666846e95049cf1cd211fffd0ca9a3ddd38aa9221070453b988d02823da803fffd1e73155ec184b0b3e08ccae067be188fe9546259dbef49b4d007fffd2e770c28dcc3bd55cc851211d452aca3b8a480fffd3c7b7b992062f0c8467a8ec7ea2b5ad3568a4f967c42deb9f94fe3daa9111762d496305c0ced0a7ae477cd645ddafd96531b30ce323dc7afe8687b0e48fffd4c90b93c1a304555ccc50c40a7890d2433fffd5a025a7ab834d1038107bd2e01a7703ffd6adb41a8ae2ce2ba8bcb95772e770c120a9e9904720f27914c8b1977104000000000d0a7b7ba20208494d41470000000000000018d600b4bba5b703fd76395ff7c0ff00d0871c1cede32a0034264d8fffd7e36fb475998cd6db63909cb293f2bfbfb1fa75e73c9cd64a34f6d3074325bcf1f7e8cbede84703d41f714cc6d63aed0fc4a97acb6d74162b93c0c1f964ff0077d0ff00b3f9679c74b04ad1c893c12bc72a1dc92236194ff9edd0fd2925d0d62ee7ffd0efb41f12c77ce9697c162ba2708c38498fa0feeb7b77ec79c0e800cf1491a5efa8ea423d2981ffd1f599381c55623736075a0d4c895c3b33839dc735037d6a751bec7fffd2eb9873519a8341a7f3a61e94d203ffd3e8c8e7ad347d3f0a9e85a1081da931ed4d303fffd4dfee08e314bb9877ed4aecb143b018c0a72c9ea39ef8a13633ffd5e984a0fa8a70917d4524cd0786e3834a1b8aabdc2c7fffd6eb437eb4f0f8e2a8d072b67a0ef4e0f408ffd7edd4823afe14ec8ee6acb1474e734ec8fa5303ffd0ef00e3814ef539ce79cd68cb1474fe86936fe7401fffd1ef48c7e3460e6b42ec007614eda2988fffd2f410b91c9e6976f7c67f1ad3618853183d7b534a53b81fffd36d737e24d5efacef6de1d3e6dae012cbb376ecf0060fe3d39e9822b58abbb3326ec84b1f1b412809a8dbb5bbf42f182c99ef95fbcbf4f9ab696e22b9884f04b1cb13701e360c0fb645138b88af73ffd4cc6a6150782334332186d437dd38f6a89e09533953f5a2fa88ffd5e5eeaea3b688bc840c9c28cf2c7d000000000d0a7b7ba20208494d4147000000000000001907bd2da7eeede35723781f363d7bd3b98930208ea297140cffd6e7c0e2970299909b47a71485476a407fffd7e7402a720e28f35d4d17322cda6af3d990130501e50f4ffeb7e14ebabd37b33cc5766e3c2eece07614c77d2c7fffd0c0271d0e28f3645fe23556321df687039029cb703bae3e8690ee7fffd1c713c67b914f5743d1c5333d09158f6391ed4f59185033ffd2a8b2fa8a91594f7c522072e33907f2acd9747117cf60153904c3d10faedfee9fd38e8325aa81ab9fffd3e7e290485919592443878dfef21f43fe3d08e464532eac20bd50254cb2fdc71c32ff00f5bdbfa804332302f2c26b16093e195f8575076b7b7b1c76faf5c66b6b43f14bd9ed835190b443859cf2c9e9bfd47fb5d477cf242f4127667fffd4b50cd15c463055d1d782390c0ff306ba7d13c4ef6616df502f341c04979678c7a11d597f51ee3a1b951675e8c92a2cb1b2ba300559482ac08c8208ebda95a828ffd5f5993383c9fc2a9dc36c491bb01d4506a64b7150b54a03ffd6eb58e4605318d4334d98cefd3a8e6986981fffd7e8e93b54962638a3f2a3a033ffd0e83e947bd245b038c50051603fffd1e831ef463d2922dd8763eb4063d8d007ffd2e904ae3b83f85384c7d07bd1a742c789c0ec69e274c77a2fd41b3fffd3ebd6543d1853c119ddf866aae5e83c31a78739eb401fffd4ee44981934fde302acb17783cd2e47d31401ffd5000000000d0a7b7ba20208494d4147000000000000001aeff8f5a3f955fa962819e0528e3393d29dc47fffd6f43039cd39483ed5a14c8aea636f6b34ea85cc5133841d5b03381ee7155348d5edf5ab15bbb738cf0e84e4a37707f420f7041a994946497721bd6c7fffd769c57137f3c8fab497512a3329c2875c8c0e3fc7f3ade9eb239ea3b0d92ead2f4ac77f0794c3f8d573f89efdeaac7637768df6ad32e1d4be0610e0b7d47438f4356f4d3a0933ffd0e22d7c572261351b5604e0f991ae0e0f4ca9f6e723f2adf8668ee2249a26dc8ea195b04641efcd6928f29896235e95612a1948ffd1e5fc717df61d317ca62b348e1558751dff0090ae2e2f12ea71f595241e8c83fa6295884ae8d7d33c556b34823d4435b83ff2d635debf88ce40fa67e95d45b59c97b0fda2c2e6def2123868a4ef8ce0838c1f63cfb53ba5b89c7b1fffd2c192d6f601996da403d76e47e638a884d9c719079e2ab431144aa4f39a5dcbea290cffd3e7f39a8d8734fa19098ed4f0714203ffd4e7b26806ab4311702936d203ffd5e771453311cb914f12baf4634683bb3fffd6c65ba9070483f514f4bb231bd01f5c7145919f3122dda0ea18548b791ff7cfe2281dcfffd7a33c36f7c17ccc8740424884074cf5c1f4f6208e871c0aa2de6da388ee80da480b328c239f423f84fb1f5e09e684fa10f5d49da149e368a4457475c32b0c823d0d60ea1a0cb6b996d034b0f74e4ba7ff00143f51df3c915a12000000000d0a7b7ba20208494d4147000000000000001bd687ffd0e134ed5ae34f0be4cce61ceed81b839e78cf03b9f4e4fad7a1693a8c5a9d925c40a54676905d5b0700e38e4751c100f7c608c96b6a441f43a0d1758b9d25d550192d89cc9067b9ea57d0e79f43df9e47696d7b6fa84226b5937c79c1ec54fa107907ff00d7d08a0d933fffd1f5c90565ea2d88f67f78e7f01fe452d0d0cb66a89db9a433ffd2eac914c26a74346349e87a534f23a5203fffd3e8cfb5211e95268c0e39e293391d293d588fffd4e8783477e94ac8b1060814b81e948353ffd5e848c8a514bd4bb8518a00ffd6e8c514b465875a5038a00fffd7e940f4a5030339a0a448b238ee7f1a7acce382738f5a34b8ee7fffd0ebc4f8c657f2a945c2f704555d163c4e87be39a78653d0834efd40ffd1ef0fb1a5e82acb1c3233c77a703ed408ffd2f4353db14e041ab2850406071df35e5c9a84fe19d7aedac97f72971244d01e15d039c0f620743dbf3ae7c53b414badcc2ab71b33ffd382fa5f26d6461d71815c8fd9f764f39cfad74d25a338eabd5220922272a5430fa557c3c3930cacbce7693c55b1459fffd4f3a334da9c90dbb46818bfde5079edeb5d846811422e76a8c0cfa56d356491844b118a990639ac8b47ffd5f3ef1aca6ff57b7b152c028c9f4e78fd307f3ac7bbf0ddc4596b671328fe13f2b7f85088bd8c9911e2729223230eaac30454d637f77a6ce2e2cae248251fc48d8247a1f51ec7000000000d0a7b7ba20208494d4147000000000000001c83417b9fffd6e2f44f896ca562d660dc3a7da200011eec9d0fe18fa575709d275f8bcfb2b88a41fc4d1755ff00794e08fc714ad615afa3285d68d710e5907989eabdbeb544a953c8c53bdccdab33ffd7e6707b1a30d4190ab9279a7d35b08fffd0e741a2a9988a3da9c0d007ffd1c0c0a5db4cc84c629718a00fffd2c1029714cc802d2e28dc0fffd3c3e714e05bcb64cfc8c0865ec41ed8a0c969b114666b56dd19696224968c9f9867fba4fbf63ebc11802ae5bdc4773109627c83c671820fa107a11e86a931a3ffd4e6f53d0d2e18cf681629492ce9d164ff0006f7e84f5e4ee191677d7da3ddb4b6b29865521658d972ae073b5d7b8e7208c1e7208cd53465b3b9e87e1ed6e1d7eda49228648e481824d1b0242923230d8c30e0fbfa81915b967717165389eda4d8ebd73cab0f461dc7f9183cd49b465a5cffd5f50d3b548b538db6811cea332459e9ee0f71fcbbf6cd1d5240b28427a0e9ef9ffeb527a2d4d4cb793aff008d465f3cd4eb603fffd6e9d8fbd213c549a0d3cf6a4e7d78a2e1a1ffd7e8b2338a2a5dd1620ea451da8bf703ffd0e83b52fe3525b17fc9a4eb4f603fffd1e8b145050b8a292633ffd2e8e971e947a94039e3da9401401fffd3e9bf2a519eb4328763d7f2a28480ffd4eafbd03a7241fa5348a147229c39f6a00fffd5ec0391d0d3d6571c039fad56da95ea48b3b83c85c77a9127f518efc53e000000000d0a7b7ba20208494d4147000000000000001da173ffd6efc4e98cf4a707423218735772ae3c608eb924d7926ae7cdd4ef9948c1ba948c7fd746ae7c57f0be6bf539abf43fffd7ced61ff76b18ee727e959262c2e00aeba5f09e7d67ef95e58b031d2a9c90919dca1b3df356c98b3fffd0e2f46b506f0498e10135d120c915ad4dec73d3d55c9d053ddc244cde83359bd8d11fffd1f37526ff005d9ee586429c0fc38ad3e45096864c8ae2da0ba4d93c4ae3b6474fa7a562de786f196b497df63ff434869d8fffd2f139ede6b5731cd1943eff00d0f7a2daea7b49966b69a48655fbaf1b1561f422816e769a27c4abbb7db1ead00b94ff009eb100920fc3856ffc77ea6bb0b3b8d17c47116b2b88e57037305f96441eea79c7be31ef536b6a87be8cffd3cdbbf0fdcc1f34244ab8e76f51f85654b23c032c8703afad2bdccdab1206de0301c1a5fc2ad127ffd4e769453dcc425fdca867380475a689633d1d7f3a00ffd5e7c1a50d4cc870a08fca901fffd6c314a283217a8e94bb45080fffd7c70b9a5d9c506426da0c0ac77e086e3e60483c7d3afd29a0b1ffd0c8114e0b18ee9864702440c17f2c13f9d57bcb092f1544eb03941c4ca4c6cbf861811ec4fe479a6999ee775e09d11f47f0da437017ceba95aee4039c6e0a107d762a67dc9ad378914f2001d28f336574ac7fffd1eb554c6eb2c6e51d4ee565e0a9a7dd4f3dd4cd348cbb9b19c0e28d19640524239209a66d000000000d0a7b7ba20208494d4147000000000000001e71d454db40b9ffd2e90923a83484d433410d14fa0b53ffd3e868cf7c54db42d899f6a518c74345819fffd4e83bd28a9d0b614a0e4e6803ffd5e8a947bf14ba961cfb51c5007fffd6e8c528e78a2c58bd2940e29dac07ffd7e9f14a01f5ed46fa9771dd09a3f0a77d447fffd0ea80c0039e9de94631d299438669dd28ea367fffd1eb863f0a70e4034ca1c29cbcf1de9899ffd2edb38a064719cd31f90aa48e7d2bce35fb736bacde46790652ea7d437cc3f9e3f0ae7c4afddd8c2ba6d5cfffd3cbbdfdecededc55564c738aed82f751e55497bec8248c9e455678813cae3e945c699ffd4e7b4c88244cf81f31c568c631d6aea3f78e6a7f0a2c20cfa551f105d9b3d2a7910a87dbf2e78c9edfae2a19a9fffd5f3cd12dc456ecffde381f8569639e9419098a4db408fffd6e22581265d9222b2fa119159377e1d81f2d01f2cfa751419276312eac2e2ccfef10e3fbc3a5470cf24322cb148d1c8872aeac4153ea08a0d3747ffd7e0b44f893a9586c8b515fb7c238dec76ca07fbdfc5f88c9f5aed2cb55f0f78b2278e2954ca632cf13feee503073ec71dc827eb52d35aa15efa331551154045daa0700f61e94b8356667fffd0e7be94a8a599540e49c5346225d49db8f4acc9a3073b3e53fa52133fffd1e0c9962e8ec3e869d1ea3749d64c8f7148e7b9622d524fe34523db8ab715fabf5520d172ae7fffd2e705fdb6483205000000000d0a7b7ba20208494d4147000000000000001f2064e41031f5e95323a48034722b83d0ab0345ccae8900e2940a680fffd3c8039a7d3330c714a2958563ffd4ce5a9adeddeeee22b58f869e45881f42cc067f5a66696a7a5ca8a01d830a06147b76159f7430393814cd8fffd5e90311d29e25200079a94d97b0be78a5f357383c669fa81fffd6ea44a8ddc60f340d8fd30682c4f2d0d21b704101b14ac163ffd7e94c0fd88229ad14801f94fd45234b0ddaddd587e147418a42b1ffd0e87e94679a9f22ec18e28fa5303fffd1e8a8ed4932f6147d283fcbbd0d7503ffd2e9318eb4a0e070282d00f714e1d0d1b2047fffd3ea314bd39a11618fc2971de8048fffd4eac0a500fe74fd4a0f4f4a5a00ffd5eb89c9c678ed4a3af14f62870a7034d08fffd6ed32319a5073d6aac3dc70e95cbf8d74e324116a31ae4c7fba931d7693953f81623fe0559d45cd168cea479a2cffd7a622007cd824f538a8e48d1391c1ec3ad6f769d8e2f67192bb127d92aed28bd3822b31e12188a706f664558a5668ffd0c9850246ab8ab318e31552d598c159227415ccf8d6e731456ebc65b2df87ff005f1e9d2a5947ffd1f1f8b5cbdb76db1ca1a3190aac322af41e2a6c8f3edc63b943fd0ff8d04729762f11584806e768cfa32ff855c86fad67ff00553c6df46a2e4dac7fffd2e338c673d6908a0c8ced46458e09188042a9e2b93a4540ffd3f0f048aec3c0f0f950ea17fb32c635b5439fbb000000000d0a7b7ba20208494d41470000000000000020bf2588f70100ff00817a668265a237f34639aa333fffd4e7aa4880059cf4552699894ae251d383551e4507a7eb52c4cfffd5e19a541c9fc88a68b7573c0209f4348e71b66f15c24b22301147279624cfcac7009e7f1feb49657f15d2481244122332f97bbe620771eb48763fffd6e1d05ca82d1332fae0ff004a48d9914ef5e9f778ef491cc35aeee2243e54d22b76f9b3fa1e2a48758bb4f2d6465932704b273fa6290d367fffd7e6ad351799889200a338521bafe9c5686727349192771d8140aa1ee7ffd0cf1c56e783ad7ed3af42e41db6d1bce7f441fab83f8535a111573bb78b70ed593a90f2c2a9fe238a2fa1b5ae7fffd1e98a605211818ea4d496348a61c77e698ac7ffd2dec521c81d4d22c51238e4134e170c073cd1711fffd3e8d6e7d4548b7487d452bdcb2413a1e8dc53f746fd429e3a914c2da1ffd4eae48e2503f76cd9381b6991476f71218e390f9a06767f17e5d7fc9a2c6829b36e8ae0fb118a6b5aca3a60fd0d2b058fffd5e90c6ea4e51b1eb8e29bc671de922ec2d14203ffd6e929682c3f1a70346e07ffd7ea070334bd682c5f6a5c53407fffd0eac52f38a772ac2e071c75a076e734203fffd1eb8d2820d328751f4a607fffd2ecb229c0f34c638631ef58be29d6adf4bb27b778d669ae632ab13671b4f059bdbd3dc536d257264ec9b67fffd3c1b1d65a7ba68674312c50798c64e18918cf1d80000000000d0a7b7ba20208494d4147000000000000002127f51c0abd3b2dd5a2cb6d280cc320f6fa7e7c7d6b4bdb539f7564655b4f732bc9e6ee0d1fca55f83f4ab823dc41f5e6aae93d0ca6b9924cffd4ce0338a9d053f33244cb80335e7be2dbcf36f26c1e235c0fa9ff0022a58cffd5f0ea2800a3a5007fffd6f148af6ea1c795712a01d00738ab917882fa30433249fef2ff0085026931b77abc9770189a30a49e48359f402563ffd7f10893cc9157d4d7a168f69f64d0f4e728545cac972a7b365fcbe3f0897f334af6224ec5ced40fad5127ffd0e7c50ec12063dc9e0d06263cf36c24d539af142e57ef7a5211ffd1f3696ed5a10f9e9924550bbd6f74261858fcdf798641c7a54d8c631bb321dcb0dbb98a824804f73d4d59d3ae6e2c9dee6df665576b0623907dbbf4aa356b43ffd2f37b4d72d6f6486dcdbba3b2969a4de02a6012d81e800ce739ebc1efb096ce63996de667f2e67864403a3231073fa1fa1153b184a362ac9048a7e68cd2471ee382a45049ffd3e5b4e85966524820738c77ed5a80e684631b8bf8d38532ae7fffd4ce15dafc3fb4c5bdf5e9eaf22c0a71d028dc7f3de3f2a6b5262b53aad9c73515c69f0de200f95751f2b83d3ea3bff4fd09d0d4ffd5ede7d35a2255f729ebcf4232791fe7b5577b263caba9cfe145ae590b5a4e3a464fb8c1fd3ad40e8e9cb46c9f518a4163ffd6df3820d21073f5a92d098a29ad501fffd7ddc7bd285e2a762d86de69000000000d0a7b7ba20208494d414700000000000000227951c13ef46b71dee7ffd0e844920e8c686db210d24314846082c8091f4e3ad2d51658176c4e5b93dc9a7c774a793c13d684db19ffd1ec56743fc409a76637e3e538e80d08d01ade16e4a753db8a69b2888c0661fad37a81ffd2ebdb4f6c1d8e0fa03c530d94cb820023a7ca68b1761ad6f28e7cb6c0ea40245331d460e450f403ffd3ea69453b94f41476a00e013c50901fffd4eb3a1a514245873cd2f6eb4f6623ffd5eba978a656e47319c958ed63df2b64818ced001258fd31f9915cfc7e24b813b5b3f96248df6b70003c76358559ca3a2329cecec7ffd6e92d6eaeee20f3a3804e01c30460086c13b71eb804e29b65e20d36f0aa2dd2472300424876939f43d0fe06b1a555bdc88cd752d6a179fd9f6535d6cdc635c852db771ce00cf6c9e2bcc355bf9efeee4b8b8937c8dd4fa0ec07b0ed55565a588acf4b1fffd7a53d9a4d1b232a9de00391fd6b324d3e6b201ad273b41398a525948f63d4107ebfcaadab1cf6b14ef27092c72dc5acaeea368922621b18e01e0838ed91c76ab9a5ea1f6e424c611940dca1b2149ed9c0cfafe2296b60d1b3ffd0a0a326ac20e2999a12e6410db3bfa2d7956b539924624925dcb73e952f705b9fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f17b18dde426352cff00750019258f0057a96be238b561a7db5c47716da4d9dbe990cb190565f2d32ee31c67cc7707dd7f24d5000000000d0a7b7ba20208494d41470000000000000023dafebfadc4f9795df7e8510c453b70aae8647fffd4e7be869b7a36a2af1c0cf1f9d33130ae9ba9acb9dcf6a912dcffd5f2096e1a389f6ae723196e954e4b49628127603649d39a4885a10f5a96d9f64e87de9967ffd6f1391de299ca3b296c8241c641ea3e95a761e23b8b2d427bb64322dc3979230e402c4e491e87afaf5a56ba279533aeb3f114373a7cb7ef6d2a5bc40e4b95e48c7ca39ce4e40e40ea2ae68335bea3a65bdccf0a4accbb64cf1960704fcb8c6482454ec435d19fffd7cfb886c6264fb14324595fde2b485c03ec4f38a8c0f7a51db533d16c28a51c55091fffd0cf03d2bd37c2d6bf64f0ed921fbd247e79f62e4b807e8081f853b0a0ba9aa29d8c7141a1ffd1f543e959faab08206915572a858fcb9ce3a7e39a659c95f6b977a5d8dc5e4937991dbc2f295655f9b6a938e83af4fc6b90b1f8d330da9a968c84e30ef6d31193ecac3a7e35295d12e7667fffd296cbe277862fcaacde65abb71fbe8f81c777e83bf7adeb7bbd2af2149ade7568e41b95d58907e87a7e54b55b94a49937d96373fbb9863a763519b37192195b9c706819ffd3e8fecf28fe027e82998c707f5a5b16831ed4b8e287a01fffd4e8714629162e38eb4b8a2d7633ffd5e8c7eb4e04ae30718a46971eb23f4c9a916e241c134fa88fffd6ec16ec818238ce6a54ba53de9ad8d095675e707da9dbd1c61b0dec79a7a05ae7ffd7ef0c000000000d0a7b7ba20208494d414700000000000000243030e517f0a6358424e14bafe39f4aa34ea37fb3bfbb28e3d4530d8cc3a283f43458563fffd0ec0c12a8e5180fa534a95e1b8aab32c00e940071408fffd1ebc714b8f7aa2882e3503a3c96da9323c96f6f30fb5469d5a27063638efb4b07c77db5c8f8c6c52c75417da6c9bed650a56553df1f2b7e5df8ce07ad72d6f8d5faa31ab6b1ffd2dcf0d6bb14da04b720edbab2c2cd6cae15a78b19dd164e0329f9909e84143b54ab5717e2a863d3b58beb2dbe6595caadc5a6f4c06b7906e5f948c8c36f4c119f94f15c14746d3febfa464ece174ccfb6d52f16d9f4f17970f6a70c2291f70523a633c81ed9c54520c8cfbd692dcc24db3fffd389c85c8e7359d711b96fbcd8fad6cadd4e677163b705769e547ad2c7108f7600058e5b1deb265a3fffd4a518ab082999a32bc5371e4696ebcee71b78ebcf1fd7f4af31bf7dd7047f7462a7a8d6e7ffd5f0eab76da64f75179a8540ce064d026ec24ba65dc3d62247aaf35599590e194a9f422804d33fffd6f0ea2800a2803fffd7f33f06c046a10dce33f67dd758c7741f27fe4429f9d74ecccecceec59998b313d492724fe7424672619ee2973ea29927ffd0e7e24f325445ce588029da9c44331a1f731673d7499cf15972c4776315225b9fffd1f1cd411c2ab67e40318f7a74766d25a04959958366304f1ce3b7bf1537d084ec8ad35acd6efe54b0babe33b4a9047e150aab13000000000d0a7b7ba20208494d4147000000000000002585049f614ee5267fffd2f134065ca6ccb9fe227a531d0c670719a422d69e6f6e5d74eb69242b70e079218ed6391c91d3b0e7dab62cf574d367b4b68e7f312d656699e1e566ce0600e32000704e3939faa68992be87ffd3c5b6b81776f15c00cab2a2ba86ea011915283445190f06940e38a341a3ffd4a76f6ad793c3689f7ae254841c671b982e7f5af5f21572140da380070001d0534105a5c0738a7734167fffd5f55e3d2b2fc492797a68518dd2c8aa3f563ffa08a1977b1c8dd5b417903db5d411cf0c830e8eb956efd3f5ac993c0fe15be2d1ddda1b290a822e6194c4b91eabfead0f7cedda79e9c655376766673573ffd6cfd4be0b491967d2f5361fdd8af221d7be644ffe22b2e28757f08dd0b79a230c8c3798c9cc770a38dcadf97230470187f0d692869a196a99d6e99acdbea29ba1721971bd1b86427a7f2e08eb83e95d7e93e20b27516fad4313ff000addba02403d9cf61fed7e7dc9c5686d167fffd7f569742b0907c892447a82ae4fe8d9e3f0aa92f8750ffabb93d3a3a03fa8c7f2a572ca32f87a74e0243201fdc62a7f5c0fd6aacba3dc45d619571edb87e946807fffd0ea9acdb2c030c8ec78a69b7941e172bea29686830c6cbd548fc2839efd6908ffd1e9714a066a4d05c1e314a053607fffd2ea47cd4e039a2c682f23a528240233d78a2c23ffd3ec048e0f2d520b8719ed4cb2517478c8e29eb763000000000d0a7b7ba20208494d41470000000000000026be29dfa0cfffd4f4017087193de9fb918f407db1557458186093f817f0a61b285beee57e8734ec07ffd5ef4e9dc6564e7d08a8dac2619c0538f4355628867b13345243344cd1baed703b83d7f9d710eb3e9cf2e9774379870b87e44b1f63c8e323a11d0e476ac6babc7d0caaad2e7fffd6aeb65269c8b796127991a9c00f82d1e73fbb917ba9191e847bd54f17dfc7abc36770171243e62fcec4c815883b18ff00180412add70dcf2093e7c55ea2923993b2b1cf5b7cb26decc38a95db6f07bd5bdcccffd7927008e466a84b10cfcb9aabd8c9a10300318a8c8c9a5703ffd0ab18a9d0533348e4bc69720c91c24f084b75f6ff00ebd70123ef7673dce6a56a54773fffd1f0f3d00ad7b4d4ed16d56de40f19030580cd04c95d162231caebf66bd0727ee93fd0d666af2a497842105506dc8f5ef4898ad4ffd2f0ea2800a00c9c0a00ffd3e17c376c21d3ee243826478edd7b602fceff00af955a94d193b8bcd1408fffd4c2b32c921950a06452577aee5cf4191df9e7191d2b32f755d5645637367664b75f2498c0c0c671c8c9ea7181ce06050cc763264bfcff00adb7993fe03b87fe3b9aaf2b43261a29e3624e0a6ec30fc3ad48ac7fffd5f2f7b05ba531b9201fba47ad3b4dd367b7d4e179d3cc05cc792bb8052a70703d4e4545cc53d2c5bbad3e15b78aec06796ea777f31b271181f2a8fcd7af38fa7149edd15c88c7cc000000000d0a7b7ba20208494d414700000000000000270761d07bd66deb616a7fffd6f1bba87c8ccac48676f940f4ef55dd5d944a791ed52992864723c4dba362a482b91e84608fc4122aee97646e278959253e63851b47504e334d8de87fffd7c8550a0281800600f4a774a68c90a3eb4e068b8cffd0bde07b4377e2381f6e56d6392e0f19e4008a3f3707f0af46c63b531c1590bce3068c0c60814147ffd1f55c739c75ae7fc4d217b8b7801fb91973cff78e07fe827f3a1bd0b310c74d2ac3a74f7ac447ffd2e8ac3569b4e51132996dd54048c637263a6d27b638c1e3818dbce65d5a2d375db3f2a78927809dc3208656c7041eaac01fae091d09cd42a5d59ee268f3bd4b47bef0f5c2dc45348f083b52e1060ae78db20e9cf033f749c743815a7a46bf35e5cadbcf1c684a121909e58760bcf18c9ebc0073eb512ee4ad19ffd3ea742f11c9a59114fba5b53fc03968cfaa7b7aaf4ee31ce7b5b7b886ee149ede459229065597a7a7e878c75eb509969dd0e23ad55b8e33fa50c67fffd4f4a90678233f5ed54e7450c30a064738acf635d0808c74a6b046fbca33eb8a6a4267ffd5ecbc9b763ca95fa1a4fb2c646125fcea5346806c9fa86523b533ecb283f7777a77aa4c1687ffd6eb0c6e879461f8520e79a6682e3bd3874e9485b1ffd7ebd734a0608fa74a7a22c763d281d31401ffd0ecc673d29e320704e29ab163c3b0e869e2e1c7534f6067ffd1efc5d30e08e2a4174b8e000000000d0a7b7ba20208494d4147000000000000002878aab9648275359faee8d6baf5b059584571182619c75427b1f553dc51be8c5257563fffd249dafb429041abc0d10fbb1cc06e89c7b38e0e7d0f3ec0d61ea0d1dc3b90a133ce01c8fc3dab89d3709339649c74673f712f913e0657072326a7beb949604b985481c023d1b1c8aa6b664d8fffd3927e2a949d4d368c8cbbdd774eb2996de7bb8965638d9bbeeffbdd97af7c55d42180208c1a407fffd4af1ad4e005524f614de8668f35f15de79b7b39cf4f907f9fc6b9903271528a89ffd5f0f639638e9da92800a2803fffd6f0ea2800a9ad23f36e635ed9c9a00fffd7e62ce116fa3d847905a446b863b71f7ce47fe3813f2a750958c9f9852e29dfa08fffd0c6b588181db38c903a551ba89589e7e949b31b19b25b2ee3ced3ed556e6176c2c91a4a8c40c1193489d8ffd1f3c82ced720812c0c471b0918fc3a7e95a11595ccb1f9705f24841053ce883329c83c152b8e9e86a3d4c1333f59b6d651a37686dd884d8822918aa2800000374e83a753542d81b2819eee2b849253cc922109f407a54b8dd6856963fffd2f27d48a4d77b62689e38b11a956041c753ee3249fc6a7d3f483aa3ac091b1e790b8c9fa5657b232bd91af69e01799e4f2a65916199a099725195d4f4391c8da55815ce41f5e2b52d7c30da7242f716f1c66294b23a4d92e4a91f32edc743fdeeddba50a577607ccd1fffd3c900d2fd29dae642818a78000000000d0a7b7ba20208494d41470000000000000029a181ffd4eabe1ad9816da85f903f792a5baf1d91771e7dcb8ffbe6bb12a298e3a20d8a793fa534c6b9c7391fa5051fffd5f5768c83f4ae73558659ef2597cb25376d5239e0000fea0d293b229940c400cf18f5a8da1acc2dd0ffd6db7848c8c0aaef1ba12f13b46c47247438e808ee3afe6718acb67a0da1af74ac1d2e23d83043646e4653d79c631ea0fea39ae6b54f0ee01bbd30178490fe52b1250f5cc67a9ec40ea3b67802d48cdec7ffd7a1a1eaf3cc248ee099b680564040c76c1c0e7ebed5d7683afcda5cc767cf6f21fdec25b83db72fa37e84707b119b56771464779677b06a16eb716d2078db8f420fa11d8f4a6c99ea3208ef4dec6a7fffd0f4c90638e95465396359b35216c546695847ffd1eca900c01ed591a06587438f5a7acaea3863f8f354981fffd2ed85c3f7029c2646077c60fb9c1cfe952a46970db6ed8e08cd29b6848f9243549a607fffd3ee3ec4474707f0a436b28e42e7e86ab7341be4c83aa11f51498e4f1cd2b0ae8fffd4ed067bd3c0a68b1700743471c53b0ae7ffd5ee7a9c7a53b00639aab141c7ff005a9493db228407ffd6efb712a55b055b9208c83f515cc6b5e0a5d4e567b69acecd71f2a476b8c9c71b886c633edde89c79958271e6563c9f5e8e6b39cc17317937111292a1fe161fcc1ea0f7041a834d8679a262159d49e147722b09ae55a9cf6b23ffd76e9d3cf258bcf7022971000000000d0a7b7ba20208494d4147000000000000002a9270b8ce3d3af39e3f2a8db50d24dc3da5cc5716d38557c86caed39c1efd707a9078a35da267ea71de26f05df5fdfcb7fa4b437713e0e16401f2063a1e3b7626b57c3515ec3646d6f6da785e06da3cc52323af04f51d79a98d9e9d44f6b1ffd08631c629b7d32dbd9c8edc00beb4ded7333c9755984b267a1662e78c75aa2bc1cfa0a9454763ffd1f0ea2800a2803fffd2f0ea2800ad0d1ada4bab911439f36565863c0cfccc401fa91409ec7fffd3c2d41a33772243feaa23e547feea8da3f402abd0b6463a8519e314303fffd4c06b80889086c0efef51baab1eb532662569230b203918c553fed0d35af0c3f6b8832fc9c9c0dd9c633d3f1e95376d81ffd5f3ed2a7b9d4b55112943042ee5c00002b821720f52491d3fc6baa8748f31063673fc2ebb87ff005ab3d8c79594efacdc48911778652a0b0590c814f5ef91e9d062a3bd8670f18fb4473a05cafeec29cf7c90707a7a5525a10f73ffd6e16fec23797fd22c115c0e4e739f7ce05436f66b6a18db4d3c0c576f0db863bf0d9150d1cea4ec6ad8eb5aeda0544bab7bb4dc0959e32a48e9f794f1c63b7615ae7549b534c4d69f6631b71b66122b8206083804639e31de851572d48fffd7ca069c0d163314738e29568ea07fffd0f46f07da0b5f0be9c8319921fb436063990eff00e4c07e15b1d3181d39e299495800f439f7a43efc7d6803ffd1f5b661123160485c000000000d0a7b7ba20208494d4147000000000000002bb118fd2b9985a4525e376cb72e09e49cf7a9916591224c7e751bbdc52359c2c726219f6ac5e856e7ffd2ede4d2e223e5765fa8c8aab2691264ed78c8ec3906b99b2f94a92e993a30fdc923afcbc8f5ed5c35ff0080a4b5bc9aeedf5ad46ca6b9999e59c7dd2492d86da508393807241f6240aa84d22251b9ffd3e4f51f05f8966b85bafed3b7bc78b251c3186539ebdb1938ea5b3ef57b4cd4af74ac5aea3e7315203798c5e48f3df393b87e278e84f02a2ea48cf54767a36b93d8c8b736922b24806e43ca4abd81fa73823907f107bad3f55b6d5a0324248751fbc8d8fcc87fa8f43d0fb1c813b3368b3fffd4f4eb8054e7a75359efd7eb59335206c0e6987f5a7a08ffd5ecb8a4ac7a9a80c30c8340e954d08fffd6ec714e181cd6772c5a5038a607ffd7ee0310782453d66703863fcea762c912e5f8e3f1f4a789d4e0347ed55703ffd0f4202d9b194c1038c52fd9e13f75c8cf1d6aae8b1ad65fdc9074ee29a6d2407a023ebed46e07ffd1ef4c12ae4796c29b823b76aa2839fc3140fad007ffd2ef32453800781c550cf29d4749b2d7f5af126a57cdb12d6e0a461880aee990437209f9635181d77fb5651bf811de4fdcc4eedbb646005507b002b931106dbbedb1cb2773ffd3927065b78a3d8a0ccf9da3be393fae2a1bb1676d72f1dd88a39776c632614b11db27ad4bba33e9a8c6d2a163be1631923ef29feb48a9a9000000000d0a7b7ba20208494d4147000000000000002cdb0212613267eec833ff00d7a7752dc2cd3d0fffd48575150717764c873f7a339ff0aadac429ac69d25b69f77024ae00db70c5323bf419e99ed49a695f7467a33cf759f07f882ce46924d3659231c6f83f7a3a7fb39207d40ac120a02ac30d9c107a8a159ad0a4fa1fffd5f0ea2800a2803fffd6f0ea53d78a004aeafc0d00fed08ee0f4b6492e8f19fba30bff008f94a4c52db43fffd7e5a49e28df6bb05279e69c8cac321830f63556695cc2f7761c295402c33d3bd219ffd0e2efae0ee273de99fda2b0c1e64d2a841dce6a24628c5d53c44b341f67b7cb063fbc63c02bfdd1f5efedf5ac77b9123a48d18254fcca7a35351b1a289fffd1f215d6ef2db51b9d420648e5b92770c6ec2939c7e839eb5aba578c6e92f61fb7ea57c209189b86458f284f00a82a7e50304ae3d6a79513cba68747e10f122eb9e2d934f974b86ee2b98e548d9a40ac985ff5b8c61982ab60003920f1b6bbab9f07e97239484cf06146d58e4de71ce18ef049ce0fb75f4ada9c13563092e567ffd2e6754548aee78a299a68e295a2577ea769c71f976e2aa46aac71c727149ab36732d55cb515ba9703157a18c449b47af739a51dca8a3fffd3cac714a29198fc8a7476ef78f1da444892e1d60423b3390a3f534edd015f63ffd4f5f8e38e35090a058d00540a301540c003db181460e338e9e94ca1318cf19c76a40093d79f6ed401ffd5f58bb5000000000d0a7b7ba20208494d4147000000000000002d2616542549e063d3b8fcb359b35aef196521863e65eb8a9946e5a640d1943f3ae40fe303fa53b73a01b4ee43c8f4350f50573fffd6f430cafc0e0fa1a66319ae67a1a8122987e7c02011efcd16d00fffd7e8eff4910b16b2002e093093819ff649e9e9b7eef4c6dc73857ba7db6a88639d0a4b1f01b6ed9223e9cf3cf0707af07d2b08b639ab18063bef0f5daef1be167c8c1c47371f8956c0faf1dc0aec743bf8ae7cbbcd36e5964538d840df1b704a30fa1191c83d89069c9f52617bd8ffd0f447d4e2b9b73e68114ea3122638fa8cf6f6edefd4d369431ac534cd06139a6139a6901fffd1eca939cf26b2d77340a0d3b01fffd2ecb1c528efe9599a0e03dfde9718a6988fffd3edfbe69c3d7f3a9dcb14641e71c528cd007fffd4ef71cd28c8e98cd4f9164809cf5ed4e595b00eecf34fc8363fffd5f49170d8e707b7f9ff003fad299558e5901fd69a6589b6ddbaa60f4cd37ecf6ec3224284fa9c0fd69dee23ffd6d3f147c45d174366b6d39d755bd53b4ac4f88633df749c8c8f45c9fa579e6a5e3df11eaac564bf36b093feaacc7943fefacee3f9d151f2e9d4ce537b2294b3582e99b9ad19afd272eb74cdcec298d9d79f9b73127d47e1866770739eb58c7dedc88a4cffd7d5d16d7ed7addbc2465620188c67af27f403f2a83c75a687bbb6550774f2127e801e7f3c53b6cc86bdd6727acb3e9124505accf1beddee000000000d0a7b7ba20208494d4147000000000000002e41ee4f4c74a65bf8aeee223ce8e298719fe13fa7f854ad773352e5763fffd0a56de26d32eb092ee849ce7cc5c81f88abbf63d3efe3ca04753d4a1c8a9d61a90ad319fd9d756e49b3bb9147500b71f91cd57bd81eec7fc4db48b4bf5e81da3c3a8f407b74ed8a2ca4efb30b35a1ffd1e5af3c19e18bb04c4d7ba5c857804f99183ef9e7ff001eac8bcf865aaa867d32eecf5141d04720473ce0f078fd6a5c9c7e3fbc95aec739a8e87aae9248bfd3ee2dc0fe278c853f46e86a8d579a1dcfffd2f0e145001d6bbdf0ac1f66d26f2e32732bc76abc718505dff9c545ae4cafd0ffd3e0eee467ba7c8c05381ee31fe355f718d8b29da4f7c56d7691c9a5c996fee2352c240ca393deb6a3dde5163d76f38a8972b5a1716cffd4f3fbf6c96c1ac9ba632dbb40c7e52723eb52cc16e52d434e8ec6384adcacd23825953042f4ef9cf7ee074354ca32aab1e0374a69dcd93b9fffd5f11f94c5803e6073f8534e33c5003edfccf39042e5242c02b06db83f5ed5dc780fc713e8da95e43adea5753c325b98e2dec6655954928324f0a72e38e32c09e9915195889a4d1fffd6f374d509d4ae5a69ccb0c2b8673d09c804f039c9cf4f5f415760d5239ede59a001e48d776c0725bdb8f5a9660d75346d6f035e47095c79919604f63d718fa67f2ad4038a20389fffd7cb14a050643b15bbe0ab3fb678a2cc150c96e1ee5c13d94617ff001f000000000d0a7b7ba20208494d4147000000000000002f64a6ca8a773fffd0f612eb1ae58900f7c528c32e5483f8d3286e327a526def40cfffd1f61568572262467818527bf341b6b6998ac53465bd0302698caf369cfc9c74ee2b3e5b268f25005279e3a1e3d2a248699fffd2f406c03b5c6c27807b1fc69c4b2f0c323d6b166835b04641fc8ff3a8c641391d6a067fffd3eca76cc8c7b66a8dcdb4371cbae1f00075e180eb8cf71c9e0fafaf35cbd4d1f99565d33ce4785d92589c60a48b807a7523f3e0706ab697f0aac35f372352b8952d62d821584a1943724e4ba30dbd0038c9f981e996d29cbdeb0a308b7ef6abd7fe033fffd4e89be17dfe90fe7685e22bc9113256d6e58907f1ceccf6c6d03b923145aea0d25c3d8de446d6fe2e24858100e3a95cf6c738e4e390587cc72a916b52f96caeb62c9675c91d011f8d0b2b63079fc2a2fa5981ffd5eb16604fa7ad3bcc53f5ac5346838329e869467ad5203fffd6eca9462a123443c63f1a0608cfe54fa81fffd7ee31e94a001cd4ad0b17a7269c28047fffd0efb23a734a3ae29163baf51412401fe1d2803fffd1f43e31d3a53a916191ebcd715f1534dbdb9d092f2dae655b6b563f6bb7590aac88c5406207deda40c83c609a109deda1ffd2f2f600703000e000318a612722b03044d7d361563dd8ef545b8e40a715a0e2b43fffd3eb7c0567f68b9babd382a4909f427fa014be248a3b8f10ac439fb2c1ce39f99bff00ac05000000000d0a7b7ba20208494d4147000000000000003053d896fdd3c9bc4975f68d5ae0820aab941f871fd2b30f2b50b4473bd5dcffd4f3e24af5a6a4cd14a1d1d91c7465241fcc534ce535adbc55aa5a27338980c00261bb8faf5fccd6c58f8e609140bbb478988e5a26debfae0d4f2a7b1ac6a773ffd558350d1f52c79773033373b58ec6fc8e0d3e4d12066dd1968dbd41c11f9542938bb31594b542797abdb0222b912a1fe170187f8d655ee91a1df311a9f87a257249325afeedb3dc9008cf27be68e5bbbc1d989b6be247ffd6e46e3e1d68b760ff00666b32db48c7023bc4c8ff00be801fc8d625ff00c37f12d92978ed12f630bbb75ac9bfff001d3863f954f3db49ab7e425e5a9822cae2daf520ba82582407252542a7f235e83669e4e8ba7c27abc46e5b8ef21caffe3823abb1127d8fffd7e65a347e0a83f515049a740f92aa6324755e31f874aa4dad0c1a4f721feca2265732ab282090c9cf5f5ad958c7d95b20658e28bad902563fffd0e3ae74e590365b07b62b9ebbb0749419a32c01c0520e3f1c54982d09ff00b16ce258a555965567cb2b1c9000ce30073c8038e79aad7fa2ee5592d98202c57ca97018630781e9823d3a8e39a9e66b7294cfffd1f1a934abc887faa2debb79aaa2272db029dc3b1a9524f6129262302ac41c71e94adb59be55da0f6cd50cffd2f0f04ae4024678383d6a6b6bdb9b3dfe44a537801b0076a04d5cea3c353c17b730ef9659ae5626000000000d0a7b7ba20208494d4147000000000000003191cb1e1086db83f8118aeb40a228ced667ffd3cb1cd380a666380fcabb8f86165bdb52bf2a1b012da339e87efb8fc731d038eacfffd4f5eb8882451a7384181c7d3fc2a1b7cacca3246ee3eb55d0b2eedc0edeb4857033edcd211fffd5f51fed489ae6588825636db928dc9ef8238c751c8a719ada5caeecf3820157c7ea3f97ad5b8b43bea22feef882e0263f844853f43814c96e6ed412e7721eef182a7f1c73f9d4341b9fffd6f4ab896d5d732654b0e7037647f5aa32bac3bbcb99587f741ebdf1cf4ff3f5ace4baa2ee46b791363ef21cf7a77da23209046474a9959a05a1ffd7eae4662c4e0e33c7151e7f4ae6b58d39ae009070455ab4ba7824592262ac3a114969a85cffd0f55b2d622b801662b1bff7ba29ff000fe5f4e94dd7bc3d63e20b511dd26d9570d0dc20f9e3239183dc7b7e58382145f3c7534387babdb8f0dde8d23c412c51a38dd6d7eee11270070324e370e723ef67a8208637cae532a47238239fa5612567613b5cffd1ea026d000e9ef4ec0ae62c0038e6946477a350b9ffd2eb77b608e0fad3d64e7915827a163c38eb4f0c08f7ab4d01ffd3ee063d69fc1e9528d051c934e00d023fffd4efb1da99711cf242c2da64866ea8ee9bd738e370c8257d7041f434ba167396de384b5bc934ff0010d83e9f75136d678499a239e8781b803d460118efd71d35b5d5bdedbc773693c53c12ae52489c32b0000000000d0a7b7ba20208494d41470000000000000032e87047bd4467ef72bdc88cd4b43fffd5f4303f1a5c8c628f52833f8d457b6905fda4f65731f990dc44d148bd32ac083f4e0d2d8373ffd6e0fc53e1cbcf0c6aaf6174c644237dbdc6dc09d3a67ea3a11d8fd6b1831670ab8eb59c95998dada09772a4ae475dbf2fd6a9bb32fdc27e944568545687ffd7f4cf04588b5d115cae0cae5f8f41c0fe55cbeab70b6936b7aa31c8576db8ff0065471f9d395f5265b1e2b24cea72f82ddcfa9a16e8679e29186e7fffd0f3c96e04807cc0d5767db961da91cc35a6f31140c8f5a962c85f6a63d8ffd1f3bce41046455db3d5efec88f22f25419076eedcbf4c1c8ed4efdce74da7746e5a78daea3f96eada2947f794943fd456b5af8bb47bb509333db1c67132703db70c8fe550e1fca6b1a89ee7ffd2b62d34ebf42f198a4561f7a360463f95443457b7c9b2ba921f4018807f0e950a4f661c9d5092bea46230ddc16da8405705668c3023be7b7e955655d1ee0a8b9b0b8b365508a62ced500600dbce300018dbd85118dbe07f213d749a3fffd3ac3c3b6f3be6c355865e31b251b581ed9233fca9afa3dfd8db5cbc966ce4aaaab46049d4f24639e83d3bd47b457b4b464b8595d6a8c8c7ce508f981c11dc526a9717d686258208a583665958946cf39c3723f0c7e35a3f3333ffd4e064d6823fefade687d4e372fe639fd2ac45a9d9cd1126e22e7d18123ea3b5435d4c751ba74c9733000000000d0a7b7ba20208494d414700000000000000339679143dbc277151b54ee249201cf002819cf73515c22cd7ef3c91cd1c91e2201c1dae39219491d38e838c9f7c9ce4f426e7ffd5e205aef018395c7700723d3f4a7d9f85575a0c8b218e5937ac673819087058804eddd8cfd0fe3c54657958e7d7a188fe02f104365f6b96c6403cc286241be4007f16d5c9da7b119f5e84138f77a65e590569eda6895bee992365cfe62bb5e86aa699ffd6f0ea2803aff025a327daeea48caee0b1a311d4753ffb2d75a28443dcffd7cc031eb4e1c75a0cc502bd63e1f589b5f0b59bb2ed7ba2d70483c3063f211f54094fa1513fffd0f5c99ccbc8e3fc29f6a84c9939e0706997b16c28148d18756538e783f4a449ffd1f657024c8755607b30047eb504d616939f9a103fdd38fd3a55a762dabee5397452147d9ee586380ad9c633f8f3f87e5546e2d6fec6647dca03b84f354f2d9049e4107a03d45568d12e3d8fffd2efa6919dcb31673dcb1c93f8d55719a87b944257078a8d905401ffd3e98eece01349b9bbf35815b08187714f0ebdf20fd293433fffd4eb6390af2ae2afe99aa3d9484a80f1b604aa3a9c7439f51fe7db99371d516bccd8bab0d1bc4f6612f6cedef624390b2a7cf0b1e383d50fb8233eb8ae4755d02ffc25e65e582c9a868c09692df3fbeb507ab2f62b9c9fc79c6198f44d29c6e8b6dc959bdb6fcedf37f89fffd5e9ad2eadafadd6e2d6559636eebd8e01c11d88000000000d0a7b7ba20208494d41470000000000000034cf20f22a63815cda961d28fe9401ffd6eb31ed4a05739638014a3eb4ba05cfffd7ec81229e1c8ac532c7890e083522c83bd3e6b0cfffd0ef83a934f519381c93d8542659c7f8ca5d0f5484086fa16d4ad8111f97975619e63660081dc8e783ec4d60e837f7fe1bbffb4ac72cb653301770c5f3923a798aa3f897f55047f748e6aced34d1cf3694ee8fffd1f4256491559183291b9581c8208ea3f0a5c66945dd1629e08c52f5a047ffd2ea7c5de16b7f16695f6591d62b88897b6988cf96d8c1047752060fe07a815e197ba5de689793da6a36ed05cc1f790f23d883dc1ec454cf58915158cbdd90dd6a266519fbc7bd2407ffd3f5e3e5e95a285dc310c21739ee075cd793f8eef8da785163fbb25f4a338f4cef6cfb5127a9337a5cf2b91f75309f7a0c51ffd4f2895c838071482570bf3720fad1731b13afcd838c55d44c2d0c967fffd5f3c23142fe347439c70381de8079cd023fffd6e57c3d1b1bb7b856da628c953c83b89c0fd4e6bd52df4eb5b4f0f8bebc7954c56cd712befe40c16e73c0c0e3f0a6f633a6b4387b2f1dc6f1a9beb07858ae58c4fbf1c7231f9f4ad9b6d7345d40aaadd43b8f44946c63f9e2b3706b634538cb73ffd7d09f45b2b8190a3d88c11512e9da85a9cd9df481474566c8fc8e6b3e6be92d41c6df0e8327bbbd652ba969f6f791f72cbc8fcc11fcaab5cae87a90c4eb75a7b9fe253951f9e4000000000d0a7b7ba20208494d414700000000000000355349c57b8fe42767f123ffd0c7b9f034d74864d2f52b1bc07a2b9311fcc6e19f6ac0bdf076a7031175a45c05dd80e88245fae53200fae2b3534dd9e8cc9d394755aa33ecb4a93ca7169752246652aea4e55c29c72a7208c83c11d3f2a96fec7c4174e1fedbe6720edc61738c671d3271ce00a6edb3424efa9fffd1f38177ae59a149f4c328ce018f39fd33fcabb7f055f59dde1d2781258e008b0995778673961b41ce7afe75953a518cf9918b4ba1d594c3e194861c6315e7fe2f58f51bd9e695d9d222c8ab9e381b73fa7eb5d359a6ae8ca2eccfffd2f1c5b155ce4e7eb5a761e1f1776ed2081dc8655e0e3009e4e4f1c0c9fc2a1c8ce52d0eb345b11a758241905b712cc09c31e80f3d38038fff005d680e2b48ec247fffd3cdfc297f1a6642ec965c456e3334844710f5763851f9915eed69690d8d9c1690ae21b78d61404f455181fa0a19a45bb1ffd4f676b48dc0c1da3ae051140222486c823ad32b9b4b136d2b9c0fc7ae6a195cf9c10740a49fe58fd68425a9ffd5f65a2acd02b3f546064453d238d9f9f53c0ffd04fe74276137647fffd6ef1c67d6a161cf5e9daa1ea511b0a86674891a4738441b98fa01d6a5ec23ffd7a163e32d73c9125d5a594a5941da81e3c7fc0b2dfcbf2ad28bc6b09c0b9d32e633dcc4cb20fd769fd2b5785fe5665edd5f52f5bf8a7449ff00e5f3ca3dfce8de303f1231fad68db5c5ad000000000d0a7b7ba20208494d41470000000000000036e2efb5b98675fef452ab8fd09ae69d39c375a1aa9c65b33fffd0ea0263d68dac0f52315cecb27b7bb9eda40f0cac8c38c8faf4f71c7435af0789e54005c5bac9cfde46d87f2c107f4aa8cdc4133fffd1e8058d8db6b37fa958c06da3bd4881b718da8c9bf7118e006dc38ff673df02c79aa4f7159d592949b468e5cceeff00afeb71c1d48ce7ad28208ac893ffd2eb473ed4a0735cd75734b5877b52f6ebd29a11ffd3ecba53b66464560595755d5acf4684cb7d2ec24129128cc921f455ea79239e833c902b9b1e3e98cc4ff6647e483f77ce3bc8f5ce319f6c7e3deb394d44ce7514343fffd4e8ac7c4fa66a0998a529281f343261587e39c7eb543c4d67e27d52de48ed45bc769c66da39ff007b7031d19880a07fb21b1ea5ba573c669a137cd1f74e16e1e6d36716f7d04b692f411ce8509ff773f787b8c8abd6b7ec878238e9915cf24f76723d343fffd5e9fc1faada5f48b62f3b42cdc44b238603d874ebfe45742d1bc4c524001ea307391ea0f7e95c587aaeee1215295e231d5c8cc6549f43c03f8d4692ab48616f925c676310091ea3d47b8c8aebbdcb3fffd6efafaee0d3ad24bbbb996182204b39ff003cfe15e31e23bd6f136b1757f701c44d22c512701a2857803a919cee63eec6b39cf963733ab2b2b1ccdfe96d6523149049131c29e87f2acc9a374621c11ed4a1252571465747ffd7f4ff0017dc04d285bf000000000d0a7b7ba20208494d41470000000000000037433b88ff0002706bc6fe29de07bfb5b056005bc2588cf763c7e8b52f733a9a1c1645349ebcd5108fffd0f239df6f5a626f63ce31419a346dd32ca0f6aba07b5066cfffd1f3f0a0d0ab8e941ce388069b8a407fffd2c6f0bd8b4c91c4301aea70838e70303ff4261f9577bf132f458f846e608f68378f1d9aafaab1f9c0ff00802b7f914c88e8aecf220291937020e083d8d2333fffd3e1d353bfd3c0fb25e4f0107f81f8fc8f1fa5749a478b75436e1aefc9b8e70094d848fa8e33f8527152dcca351c7437ad7c4d6b303e6452424639e187e9cfe957124d36fc90925bcafe8080c7f0ebe959ca2e3a9ac6519687ffd4d09f40b7762632d1b7a8e0e33ea3e94c5b6d66cf9b6be765038563b87ebcfeb5973292b490f95ad6245717734b81ab68b6d743046fd986f7e4f4fc0d42b078767206eb8b07f46c95fcc83fce9a835f06abb12ed2f8b467ffd5b571a109f4e9a4d367b6bcb84899adf27004850807b8efdcfea2bcd353f0deb5a746cd7ba15d611095730f9c9ff7da6e1e9d4d64a4afd9994e128eab6372e91b44d3edb55d0bc5377f6795824306e132e48dc5704955da3b15c8e0719ac096ef53bb8de232433338c02508627f038fd2b59cba346716ada9ffd6f25786fe2ff596c5803c94e7f4aeaf40bfd3cdaadbace8b71d5a3906c6cfa007afaf1eb59b5d8c5d9ec6c81c5385688a3fffd7cd1914a0fa55191b3e0d000000000d0a7b7ba20208494d41470000000000000038b2fb7f8a34d84aee48a4371263a8118ca9fa6ff2c7e35ec6fd30013ec293358ed63fffd0f6b51c9f4029c0f3ed400ec5453000e40193d4d3435b9fffd1f65a2acd02b23527c99db23e69046beb851cfeabfad17b2626ec7fffd2eedbeb51b0350ca18456478a2e5acf42bc963826b8629b3ca85373b06214e00e78049fa03495afa89ec7ffd3c0b6f12e8cede4cd28b690755994c78ffbeb156756bfb4b3d25ef6075b827e4842b6e52c41c648fe11d4fe5d48ae9e6ea7172ea5cb4b4b7beb2b7ba10e167892500f070ca0f4c9f5ae87c31a5476915cceb185799d509ef85048ff00d0cf158d59de0694e0f9eccfffd4ecc4431479383d2b9db2c468c28c9ed5989af68f284c6a10a6fe479b98f3f4dc050a2e5b06c7ffd5eb362ca9b919580ee0d31adce78ae57b9633ca23b527cc29858fffd6e9c3b8f71ee29c2461dab9ae6971c271e94e13a13c903b7229a7a01fffd7ec9581e845417ba6db6a5188ee3cf0a39061b99213f9a30cfd0f15ceec598afe00d20966867bc899b924babe4f6ce5727f3accbbf02ea100dd6b35bdda8049193139f60092bf9b0aca74f99dcc2547f94fffd0c3bdd1afac9c1bbb0ba8080712042557fe06b951f9d4ba7f886f74f03ecd7e0a2e7e5660cbefc741f8735e7a6e273fbd066c0f1edf340619f4ab2b8539cfef182e7fdd208ae7aef50b7b80e5749b5b49cf3bace468d14e38cc6db9000000000d0a7b7ba20208494d4147000000000000003971d385dbf51d6a9cd3561ca7ccb547ffd1e7ad357485b136501fe35ce57dc6307f2aecf4bd6efee6359a19fcf6461b1891b0af7563effa1e7d6bc99ae47ce72c5b4f437f4ff13d8ea17ffd9c91cf0dd0562d1ca9b70c00cafbf5fc866b5e5862b85db2a02a3e60492bb7afcc0f51df91ef5dd09a9a3ad494b547ffd2c6f1378aae754ba0167630467fd1c30c32a6382d8c02e463271df1ee79c96e1215c6e1c7a9ae49eb2b239e4db6655e5dbc830b9600e78a23b91751b99797ce14935a28d90eda1fffd3efbc4730b9d5eda100621532b7b1c607f33f9578b789a14d7b5ebe9edb56b3693ce31ac531688fcbf2e031186e9dbd7da96ecce7b98f2e87a959867fb2f9c0a9c18c861f518ac598dd2bec921743e8473ef468f612f33fffd4f1d0c412327e86a64f9dc002910cd2b7538ce2ada0c8a1991fffd5e0b1cd1803b5239c43d31eb405278a7603ffd6b5e03b23fda16a36f16b019491fde6ff00f697f2a87e2c5ef9975a5e9cac0ac6af7520cf218fc89fa7994d93b4753850a2976f152667ffd7f36d5a7649234438e324d6fd947e4db4485b7614649ef423065b693cb8c003926a9cd264e4f5a1bb033fffd0e62df5fd4ecb020be9401ced73bd4fd41ad6b5f1f5d2102eed21987f7a326338fa722a5c632f2338d468d8b7f1a68930db3ce6d78cb79cbf28ff00810c8ad28134dd523125bb5bdc2b2e730b86fe559e000000000d0a7b7ba20208494d4147000000000000003ab0d59b26a7b1ffd1d197c3b6ace5e263131e770e1bf318a6adb6b762c4dbdeb4aa3fbe437f3c1fd6b3e65256921f2b8eb12b5ec915e068f5cf0eda5d863966098624719e79c8e475e326b227f0a7846f726dee752d264c7dd76f313ff1fcff00e854b92515783baecc9718cb7d19ffd2cf8fc0b7c22dd6baa58ea433d7989b1d863e65cffc0aa193c337d6a51ae74b946d60c1c2891410720e572074ef592926edb33274e51f342820f420e3838a756d61ee7fffd3cd1fa528029b2363b6f8616a3ed7a96a4eadb61892dd08e84b1dcc3ebf2c7f9d7a025c23f00f3dc1e0ff009ff0a0d16c7fffd4f69328040cfd69f190c4914c64955e5397c7a50816e7ffd5f65a2acd00bac6a646c6d4058e7d00cd615e96c44adf7b66e6fa93cff2fd693d989be87fffd6eeda998cf359b284c761cd62f89b4f1a9dac56ad079ca251291bf6ed20601ce473c9ef52b413573fffd777fc23f94fb3c925d34649f92e1fed0bd39e240c00fa560f88fc2b0451c1f62d3048edb8bbda5a90f8c040084e0f2fbfeeff00cb23ef582aad3edfd7dc4fb3b93da787b56b7b287c8bcd6ad5822ab44d2432e08e32236202038ced0c700d77de1ab4bab7d0ac92fa669ee0c7e6492344b1b1dc4b005549008040e09e944aaa947950e1077e63ffd0ef4479a7795c5739a19be25be8b44d0af751990b24317dd5e0b16215547d4b01f8d71961ace8dafa000000000d0a7b7ba20208494d4147000000000000003bee482f6278db0dbad19d236c6705d0151c1f5a6bdd8b77227ba3ffd19e6b5b2d2ad5f5092eede3855431756c3907a6d1d49391803ae6b98d4bc77a934e834cba9eda28b0b870921907392c4e7ef641ed8e472306a54535ef11771773a4f01f88756f124d7c97ff006431da47190f1c451999cb633f3118c237403afb73d6b439e79158c924f43584b991ffd2eb5a2c74a6ec15cc5b434a63af1da809da9a771d8fffd3ea36720e3a548a597ee922b9ec58f59641df3cd48b2f1923f5a6b5d00fffd4ed9665072091414b691b32430bb7abc609fd456164f729eba119d334e9061ec2cdc7bc0873fa5477be1ed2b51b7fb2cd61005fe068d046c84f70cb8228e54c5cb17d0fffd5e1eea481aeae16d6632db2cacb0c8460c8818856fc4007f1a75a5fcd693c6c933aa838214e383dab8a70b371399e8ce946a2f752c33cd3bc7711711dcc636b28c60671d783807ad52d4b5dd4eda29165bfb975914c64f9ec5586307bfa7635cd4a4d3e512933ffd6f3e9afa299f7e4e589c9cd5768ad848c1dcbb67b1ae549a39b5423ec51854c7d6a38ace08035cca48dc78507193fe1549b5b0d367fffd7e935ad516d9759d59b1882360bff00015ce3fefa35f3f4b74af39532b827abe7a9f5a51dccdeacab20f2e424b039392719cd74169e23586ce2825b6568d576821f27db8aa7aa068fffd0f347bcd2ae4e5e3084faae3f9569f87f000000000d0a7b7ba20208494d4147000000000000003c4ed265bf0f2177815086c02ca8cc4052d8e40fbc73ed52ee918eab42df89ac34bd22fe3874e94490bc0ae1a39048a4e4e493bd8e4f071803a6075ce6a05c165951867180c33f975a5094a514d89a49e87fffd1e0d6940c9a939c0ae2a7b2b76b8ba8a25192ee07eb4d01ffd2e8fc0f00305ddde301e4f2d71e839fe456b82f1b5e8bff00166a128f99602b6a8d9ce420e7ff001f67a1ab6843d23a986580a697cf43536333ffd3c0d3f4bf03b681757dad6b4f2ebfb247b4d3a37daaa55b1146700ee2e402727857e83696a853803fa0a7696cd58c1a5d18b73c6d03b0aa12b9c9f4a4c47fffd4e09db939a0a6e84b8c020f42339a87a1cc424a4830405c71802a359a4462b1bb0390432b1520fd4509b407ffd5e3acbc63afd8fca9a8bcc33c25c289147b7afeb5bb67f12a5518bfd2c381fc56d2609f7dadc7eb50e117b68671aad6fa9bb67e39f0fde801eecc0c7f86e622833e99e9fad6aadb69f7f1878bca9908cee898118fa8e2a1a705fd58dd38cf63ffd6d497c3b6c4ef824689fd471fa8e69120d76cf06def5a5dbd3761c7ea01ac9c94be243e571d50d9f559a4e356d1edae71c06d9f375ff6b91f81aaed1f866e4726f34f7ce48392bf99dc3ff1e15518ca2bdc7a7606e32f8b467fffd7b3ff0008cb4ebbf4fd4ed2e509e8d943f98dc3f5154ee343d56d43196c262abd5a20251ff8ee71f8e2a54e37b3d05283000000000d0a7b7ba20208494d4147000000000000003d4b4d4ef7c116ad63e108a57568e4bd9e49d81041c676a707d5110d69ab70a06783c62b548a4ee8ffd0f54fb6ca84e1f009ee01ff003d2b46c2733212c4641c600c5534365a238e4fd0fa556639627d4d24113fffd1f65a2acd086edb10151d646083f99fd01ac3be903dd4a400006da31ec31fd294b625be87ffd2ee8f7a681c6076a87a1482b175e0f242c882e71e626f36e640e115b7b01e5fcf83b761db938627d6a42f63ffd3d2d21afd2062e92cdf7635595a5555da8096cca3cdf98b30c10c0600cf049d911ab0f98039ea2b9dda4ffafd34fb86bc872da23b6d0a72781835acb128c28e806d1818e05428a5a948ffd4f4711f6ce69e22e735cda9a799c27c5fd523d3bc3f6d68ef1037b740323ff122296247d1fcafd2b9dd163d5357f0d6931695a76f9123bb7794a7916c409711967c6d6932ac3e53b8f7eecb5385e90a9d4e4aa9ff005b1fffd5e55747d63c4fa83471dc688d72c7271a9c0d90586701199b0383d3a0ee78abde2bf035d78774f82559ed6ee241bae2e52268e40c72395191e58017e73ce719c7789544a56febf12553948ea7e13e9de4786e7bc6550d7b7aeca7bb2461631ff8f093f3aec5e1fa562deacd636e5563ffd6ee5a203fa7155ee14471b3e33819ae6b33439186d2fa02cf15d491bb12c40908193c9e338ea6acc777abc593e6a4a33d1d47f318af1d63ad26ce96a2cfffd7b37fe2cb000000000d0a7b7ba20208494d4147000000000000003e8d26d8dc5d69334ea1803f663923df69ff001ad0d13c4fa66bceb159bc864da49565fbbd0e09e9dc7e62b829575563ce6d28a4ec8d911f14a63fceba1107ffd0ecb6e073e94edbd323358a4581c46a5d9c22a8dcccc70140ea49ec2b9483e22db18350ba58fcc11caa34f8f38372857ef1cfdd00824fb301d69b56d4994f94ffd1f3f282290ac71948f3f22e73b4765ce39c74a639e6b964d395ce56eeee6959dd931807a81524d796e418a7f98370462b9654df3684b3ffd2f35d474d785509ded1cc0bc5220f9480c4633ea31c8aa092cf04f1c52a6e07ee9c75f6ae78352461169ab1ab140f3c724cf1944857731fc7007e754e5dd336e6e9d8528891ffd397c5ebe6f854db3bc90b5dc819d93965e4b1ff000af23baf0bdec20b40c93aff00b2707f2a85a5cc94acccaf9e0731ca8411d54f0453bce8f01554a8f5ad0d373fffd4f122d93c1ad8d2756b8d3183c054e304ab0cf346fa12d175f5cb59f779f6891bb12c5a341f337bfa74aaa2e2293eec807b5257466e2cffd5f378a492300ab671520ba6cfcca0f3d8628398b3037da095891dd80c9555c9fd2b4b4445374f2e013021201e30c7e51fa9a561ad4fffd6ed34111699a04324ac163489a77c9c003ef73ff0102bc59a696e0bdc4c08967769a407b3392cc3f326896f6227a2216ce7da949c1cd2333fffd7e06c177de293ce324d6e440338c83d79c5339d19000000000d0a7b7ba20208494d4147000000000000003f9aaeb204a61b50269b3f337f0afa8f73fa0efd315040b36c2f34a5c9e9c00052633fffd0e1366e19c8a4542ca7eb52ce6229edc8f989c67d055654cb1c1e9de96c07ffd1f37c6d1c7273c9348cc48c6307daa0e604eb8cd4ab23c12f9904af1383c18dca1fd2a93680ffd2e4ac7c6fafda36dfb79b85ddf76e103803d33c1fd6bb2d2fc612dcda4735dd922b3723c97278c9f5f6c1a974d3f87422355ad25a9af0ebfa74cb8797cbf559538fcf91537d92c2ed432889c1ce0a37f8566d389b26a67fffd3d79bc376ccc6489da37ecd8e7f318a60b3d6acb9b5be7902f40cc1bff42e7f2359f3a7b8dc5ad51613c51ae5910b776fe6aaf18dc5727e8c08fc88abb078dacce05d5bcb031eb95207d72323d6ae29af858f997da3ffd4f47d37c49a4dd22ac5329607395c360f4cf1c8e3dab76d2feca50122b988b1fe1ce0fe479a5cd7dca6b4d0b2ecaa85c90a00ce4f6f7af3eb4f8d3e0ab9926592eef2d5236c24b359b94957fbcbb37103bfcc14fa815d786c2cb109b8b4ad6dddaf7ff86eba0b99415d9fffd5f57d2f5fd1b5c2c349d5ac6f99543b2dbdc248ca0f42541c8fc455f208e08c57455a15284b96a46ccb4d495d152f64d924671911234cc3e8303ff66ae7493ce4e6b09bb2426f53ffd6ee739a0115932809c64d654914772a56440ca5b7618647b7142ee0cfffd7e8d6d1d7688ee1d42fdd04600f41852a0e38000000000d0a7b7ba20208494d41470000000000000040c6738ab50a3246ab24864603e6660067f218ac1945bb54ccab91d0e7f2ff00ebd68a2fa54b1a3fffd0f4e55ee4e4e7d29fb6b991a19dab5ec5a6db9b9686099fcc58d12695625c72cedb88380b1abb9e0f086a8dce9be1ff001859b5b5f42de4e768f2af8a2c9f2e481b1f0700a9c32f75e3d1eceeb707b1ffd1edf48f01e8ba1598b2d3eda0f2b21dde7844b2bb0fbac64e338c9c7181db1557c4de19d5efa058f4996d530bb18484838c83d304374e871d7be715cdf12f7b5345ee3bc7465cf0c68dfd89e1bd374e30240d15ba992246dca8edf3ba8249c80ccd8393c7735a0c98ed42d750b72ab1ffd2f4368fd2b9df19eae9a068935fb442521d2354dd8dc598023f22c7f0ae7b37a23456bdd9c75bfc43d22707ed16d75063d50383ff007c9cfe94ed77c4ba6be8a64d32f4fda9a58bcb4552ac70ea5b391c2edc827d481dc57910c04a155296a8ea7f073c763fffd3af75ac5a1d01b5695ff70f0f99b53924b70aa075e4e07b77aa5f05e191c6ad3b03b1562895b6f058e4b60fae113f31eb5e4616838a9c7a5d7e0cda73778b47a2ddddc765e5ef563bf79247f0aa216627db803eac2b365f13441d45b5ac92a93f3348db38f5039cfe38af4545d8cdc923ffd4e92dbc45613656567b77070c24191f8119e3dce2b2756f18dd586a462b4d3a3bcb311a9332ca41ddc92070474c7eb59c75dc7cdd8e7bc61e397d4f4c000000000d0a7b7ba20208494d414700000000000000411a6da5b4d68253fe945d812c80f118c763fc5ed81fc471ce48e365b47fc6b08dfebc9247fe3a57f4a89b56b230ab27267fffd5e32690797b481e9540413dd35cf96c91c76d034f2c8c7800602a8f566665503d4d71423aea72ad464771f67894b02eecb8183819a9a54b6401e4b800b7344ae9dd035d8fffd6c4d3dac67d0edece45dd09cc83793904b1391e879cff00f5ab96d734c7b27401bcc0adb91c0c6e1fe35e6d06e33699cb1d197ae1921d2e0419613fefd88e46070a3f99ac8dde672315bc366347ffd7e73e2ceb935aea36367692b466188c8ea0f07712067d7014fe75c85b78ae4c04bb8030eef19dadfe7f2a98ea8cd4799172d2e2c754d4778646063c32cd805d890781dc8c5599fc35632e4c6ad093d467229ec4ea8fffd0f339fc2cf18df1219076d87f5ac69ade7b694ab065c75c8c525a99a96b66203e61cfe7492c66150eafb81f6e45051fffd1f1d82ff1c3395fa55d8ef49e721a833944b315e85395678dbd54e31f88aeb3c2b035e299431792e678e10c79e14123f238ff0038a6424d33ffd2e83e215d9d33c1b7c96cc034889691fa80e4293f826e3f8578d9d42443865cd3dccea6e3d6f636f5069b2dca08c90c3a5268ccffd3e074460fe7cec42a201966e001c9273d3b54da85dc8f1ecb6b91129077951f3303918073c0ebf5c7d69980cd274e070c63db1af00e31bfff00adfa7eb57ee21214000000000d0a7b7ba20208494d41470000000000000042fc87f0a4c4cfffd4e188238a9e18c6dc0e6a59cc4d2da6fb738c0239e6b24404bec5049278a96ec1d4ffd5e0a5b6544193c81c9f7a81d3777e95941dd5ce6181483eb4d60c32706aee07ffd6f35895988e3259828f727802bb5890451a46a72a8a141249ce063bd073a241d6baaf06450411eb3ac5cc29247656db4290086201661cf7ca63fe074791a4773fffd79f49d4751f3e0b77bb69376030701b8032d8cf39c035d2d8acf776b7371f26d827f240e85f0483f915350e2afa02a97dcad26a96b04cd6f348637e877292a7f119a041637cbbd1619430eb1919fd2a6ce2ee5de323ffd0d7b8f0ed94a4150558742403501d2f54b51fe8ba8ca540fbacfb87e4f9a85534b343e4b6c739f103c59ae693a1b6932cc631a8828db0321318fbfd0edc36429c63827d6bcac5eb839c9ade353dc515b10d36f53fffd1f1bfb5a39cba2b9e9f32835d0697f117c4da314fb07883518d11762c724be74607a0493728fc0577d2c754847d9cbde8f67aaf9767e6acccb92ceeb736adfe37f8a90b8bc7b1be575553e6daec3818e0142b8c9c93904649e31c56dd87c69d3e4e351d1eea123a35bcab283f836dc7e66b9ab385495e0b97cba7f9fe65a6edef6bfd7f5d8fffd2d4d3fe21f85350c2c7acc30b9192972ad0e3db7300bfad7436d2c775109ed6449e22321e260ea7f11c5672bc774568f624601d4839c118aac6c0818498f5000000000d0a7b7ba20208494d41470000000000000043fe25cff2c54858ffd3ec0dadc2e484571d82b727f3ff001a30e9832432ae7fd8271f5232056172b62e69e564f31d1d58ae17839c64f3fcaafae6a75296a7ffd4f5051cd3f91d6b9f448d2c65ea515bdc476c6612131cfe7c4e8f2465182b0cee46523e5723938209e0d49611c57314aa7cc955e525a43312c738e8542e07ca0606791c927929abb4fb01ffd5f568628e15db146a809c90a31938c64d3d86e0074cf1f9f1586c6844e335095cfa5203ffd6f4974c67b5723e3cf0c378a34f8ad7fb422b24b797ed05a48778242b01fc4303e63cf3fa56317cba95257563cf2efe15f89ad416b56b2be4fe1314fb1987b07007eb5cfea3e1df1269c99b9d27508400417588b2e31fde5c8e7fa1a6a49ea2e69462e2f63fffd7f1d3231411172581e57b8c67b7e27f5af65f862b1e8be017d4ee1f314f712dcfca324804441473c92633e9f7bb75aca6b6f51c64ae32f35e935090493aa28c10912f4407a827b9e993df0381d2abfdb00ec16a9e9a12ddcfffd0a25cb9cf9833ea699232a27cf37e66b1b6a4146f248ae4117304174b8c6244049c74c9ea7b7e555aeaeb4db4b8892ee34821824dd70e809924da36edcf39e83f2ea2b3716469d4ffd1e0a6d2f52fb2dbead716f245a6dd4c56d4be3739196190092380483d0e091daaad92db4d25c8ba7757037478008cf231fe7d6b95bd3dd39de9a233afe591238d47240e40e6000000000d0a7b7ba20208494d41470000000000000044a1b3592e481824b3e00ad636b5cb56b5cfffd2e562ca451a6e3845007e02a49221a85b4b6722e51d796c7dce47cc0fa8ed5e5b7adce5ea57d5f49fb7c6891ce6dd630115547cbb46001f80159e747b5d32d3ccbbba2f83840a36ee3d97fcfa66b585476e54877d2c7fffd3f30f1f6a4752f145eb872c91c8624f6da02e3f306b9da98e89131d82af596b5a8587105c36ce9b1fe651f407a7e15436ae7fffd4f3ed3fc5d048556ea0319e85a23fd0fe3deb623974fd5d708d05c7fb2461febebda9356d4c9ab14ae7c2768ce64819a138c6d6195cd62ea7e1bd4228c958448a39cc672284fb826d33ffd5f1292de5898abc6ca476229aa594fcb9c8a0574cb30dc48386f9b9fc6bd83e1dd861b4b5208d90b5cb67b1625ba7b6129a26dae87fffd6aff19f5368a1d274f42016924ba6f5f946c5fc0ef7fcabcc3fb49c0c3a03db22833945363edeeada499167778a32c37b28c903be3deadcf656e2dd66b6bc59727ee360381ce0900fa0fce857b89a4a37ea7fffd7f2db9730db41668d9dee64930739e8003f4c138f7cd4b0299254854632c140f73c53462f63a65b758c288cb26d18186fe7eb4ffdf608055cff00b5c50c47ffd0e61d50ff00ac88af6ce3fc2905bc4c0323023d41a2c7385c34d1db9119693381b0819c77c1acb4bc895c2caad139190aeb838ace716d68163fffd1e0e499263b5181ef5114c30cf1cd6705000000000d0a7b7ba20208494d4147000000000000004564738ec0cf4a923471874e083907d2811fffd2e52ca349645578c29f33cd0a148191ce73f8feb5b029456873a5a0f42a1816fba393f4aea76b69bf0ea346189753ba0cdedf364fe05611ff007d53ea6b4df2ea7fffd39f432b1cd7174e8592da12e79c67d47fdf21abb0b380e9de18b48e43f3b2b4f2927ab9c2e7f1c13f9d2dd8a3648e06f2f7cdba6909e0b13f4a6477099df852d9e0f702b4f3317b9fffd421d52f2320c576e53aed6c38fc339c7e15722f105c0dab2dbc5293d4ab143fd454b82dd682551adf53c8bc73e201e23f10cf771645bc604300241f917bf1d72c58fe35cfd55ada0cffd5f0ea2800a3245007ffd6f0ec9a96d6eee6ca659ed2e258255e8f139461f88e69a6d0ac749a77c4ef17e9c15575792e514e76dd2acd9f62cc0b7eb5d4e9df1d2e5001a9e856f29dc3e6b699a2c0eff2b06c9fc4543a7196da7e5f77f90d368fffd7b7a7fc61f085e644d71776040ff978b72c0fd0c7bbf502baad375cd1f5638d3755b1bb6c676433ab3e3fddce47e22b094651576b42d34f634d50ab6d2083e847352290718e7359a7d8a3ffd0f5056e87073eb449948d8a86f954e0004f6f4eff004ae748d0af1de5b86d86608e7a2b9d8df91e6adae0138c64f391df8ff0a00fffd1f560400071f4a5072c38c8ee3fcfbd617341ad9a89be9d28607fffd2f4b71807dab1758b76bab511aa071f688e461bf69c46c1000000000d0a7b7ba20208494d41470000000000000046c11c1e77229c1e0d616d3534bdb546769da75d5a0b89618a28e477546042212aaa002426e04ee321ce7273c9c62b4e092562caf13c6401f36461be983fce8bb7a092b1ffd3eb6f34cb0d454a5ed8db5d29ea26895ff98ac7f1998348d12c749b58d60899b211060048c0c8c7fbcea7ea0d6515aa29a567638e8652ff003e32a38c549e62741c50d6a6373fffd4c4693d1aabcc8ee72ad592d0c9946e4cb6f1b48c1401d0e7f2acb8a317974b733c864553bfae41279c9f5f5f7a4c9d8fffd5e3ae35068acf78727c9dcd1027211994a6403c746354e1c35b69da688d7f7d335dcd2aafef76edc0404f206067078c8cd60d1817342f0ee8fad5a5d36a17b716f3acc04223db829b792720f2493e9f747ad46fe1b8b4ad43647209d237cc72823749f2f52074e49e3daa24da8b077e5dcfffd6c2b6d31db0f29c03d1475ffeb55b78562b6609b5496e9f41ebf8fe95e3b95d9ca67c926e078c30ea2b8df13df35d5d2da267643fab11cff41f9fad74d0579fa150d647ffd7f26d52c639677b88e70c64725b3cf3dff5acb6b791403b720f7539a4888cb423a963b69a55dc913b0f5029967fffd0f140aebc15c54b1b328203601e295c97a9ab67e23d42d3813991727897e61f4f5c7b022b72d3c576b271710bc0d9fbd19dc3ea4718fd69d9326d6d4fffd1e7163d3b5653b7ecd75c63e52030fc3af6f4acbbcf07d9c84b5bbb45000000000d0a7b7ba20208494d414700000000000000479fe16c607e353b197a196fe16bb8268e331121d9503a7cc064e33fe7d2bd8fc1d02837574a085e224fa0e31f92a1fc6a959ea8716ee7ffd2e2fe2a6a0d79e30b944036da451db823f8881bce78f5908fc2b8d2e46372e695d12d5988ae87be3eb5a304d66a8ab247231c7cc55f69cfb714c4d1ffd3f30820b796512dbccf0ed39cc83791ee00193f4ad71637d60a2f20b259577303348c08233ea0e7923b77eb54ac96a62db1d0f892dc9d971049130c8255848bfa7f4cd68c1716d77ff1ef3c721c676ab7cd8f5da79153b01fffd4e7d91ba1fcaa268549c95e474345ee60472f9d1a0311dc4b0186f4cf3fa669ed92b89210c3d383fa1a2c07ffd5e47ec762cc5913ca6fbbc6547e478efda924d28b6423823b6e1fd69346162b4ba7dc4581b4fd579a91108501c90477c52ea267ffd6c0d3c230255c305f978357318a16860290c576a2ee7621428efedf95759e38db6b2697a3c6495b2b4c927f889c20fc7111ff00bea85b9a45da2cffd7bba75bb9d376aaee7bbb8545ef900e71f421241f8d74de359d6c34b9608c9c451adba11ce70a07f363f9511b364e9cb64795cb392e4d54133024ab95cfa1eb568c4fffd0c0d3a59e794a34ce115771c01ea3be3fcfe750f89f517d33489642ff00bd9730c4509420907278f419fd29bd1992bb3cce8a46a7ffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea29a6d6c06d000000000d0a7b7ba20208494d41470000000000000048697e34f1368a116c35cbe8a38c616232978c0ff71b2bfa5757a67c72f13d9aa25ec1a7ea0a0fccf24263723d0142147fdf35128425ab567dd0d49ad0ffd46e9bf1f346901fed2d16fed883c7d9e449c1ff00beb663f5aebb4df89be0cd4c8583c436b13e012b721a0c7fc09c05cfd09ac9d392db5fcfeeff002b96a49efa7f5dff00e18e9ad6686fedd67b59a2bab77195785c488c3d415c834d5b2b51c24223cffcf3250ffe3b8acd34cab1ffd5f51fb3ba1ca5cc831d9c061fd0feb4e89644dde6ec6c80015047d7ae7dab9d1a8a4803a530934d6e23ffd6f4a98955247000aa1341e6a2aee65c73c639ac168cb632283ca2c7733331c924d29a760e87ffd7edcb2440c92b848d14b3b1e8aa0649fc8579bebdaddc6bb72d712c4224dbb628f3fead33900fab7a9f53e8054452dd8e4f42a423644ab8c60523734ae647ffd0e75875a8d8b0fbbcd6464372edf2ba122a3deb10daa36a8ec293b303ffd1e135322658e08d4079240a38a83cc8d2e2eee8be1631e5c67803e9ed93fceb1e8606be85a7cb25aabec54576dc588c16f7f7e82ba1b5d29554cc79da402edfcbeb5c55eb68e289bdcfffd26ca9e582aa3076f3eb542efeea27dd2067f3e6bc38ab1ca64ddc80292c395fd6b064d392f3518fe5cb672d5d949f2a721a7667ffd3e16fac2693fd52c4460fde07f9d6549657f16d0b0c236f2768350ac60ac646a5e6f9000000000d0a7b7ba20208494d41470000000000000049ff00bc500e3a84c56df874bcb0fcc4920e39f4a9abf01d14acda3fffd4e1752d0a3ba80c91a05940ce477ae59e131b156e08eb5cd87a9ccadd8d2bd3e477ee21047f850a7e6e78ae8303ffd5f2185d810e0e181f948ea2b5ed3c47a8db70d379cb8fbb30ddfaf5fd69e8f73366f68dae47a85d08d2d9e29635698ec7ca1551dc6072491fe35e9be1c885a68f006651bb2ec7dba67f250695ac8707a9ffd6f2ed42edb53bebad41860ddcef3e0f51bd8b01f80207e1542684107039a56b192d0a4e36b118f6a547239dd8a6687fffd7f1a82ea58d83a3e18720838c5588efd8399382cdd4f734d3b19b8dc036fcb020fad38363bd2d80ffd0f32b7d6efedc822e1987f75c061faf4fc2b4adfc4ea462eadf903ef42703fef93fe341917a3d474dbc31b09d1595b2ab21d84123df83c13d3357b6f032300f20fad1b68c9b1fffd1e79a30dd4034d10841f212bdc0078fca9dcc04dd3a1e0ab8c7423073f5ff00eb52fda17fe5ac2c31d481b87e9cfe9401ffd2c6b7316cdd1e36b7391528f6a6b44606b785ecfedbe20d3a12b95594ccd83d360dc3f3da47e34ef12de2dd7883519f3f224c6107da3010feaa4fe34475344ed1b1ffd3e97c3b665b5ad32d186d1690f9f37b37507fefa471ff0002acff008837bb92384e0191cc857dfa9fc3e61f9511d6ec9935cba1e792c84e706a0ddcd5989fffd4e634ebc86d4b0915be7c72000000000d0a7b7ba20208494d4147000000000000004a0038c67fc6b95f19eaa9a86a4b0c2fba1b65da0f62c7963fc87fc069b5a9942d739fa291a9ffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea28026b5bbb9b19d2e2d2e25b7990e56489ca32fd08e4574da6fc54f1ae96a521d7ee6652db88ba0b73e9c6640c40e3a02287697c4aff00d77dc15d6c7fffd1c5d37f681d5a26c6a7a2585ca00306ddde06cfb93bc7e405751a6fc77f0addf9697d69a8d848c3e73b1668d0ff00bca431ff00be2a1d2fe57f7ff9ff00c0f995cfdd1d35878ffc23ab2e6d3c47a78f9b6859a4f2189f6590293f856e805a3122f2846432f20fe3f8d66ef17692b7f5f7149df63fffd2f4c70aea41008f4354dac71c453c8bec48607f3e7f5ac0d08cdbdca7f145277c72bc7eb519f3541df6efc775c37f2a77b08fffd3e9fc457221d0af9b905e3f2402307e7214f07d89fcabcea5ec3b93511f8453b5c71231814c2d458ccfffd4e6b792707d685fbff4a87a18a2bcb7ad14a5414c0f639a89b502724807ea2a3946d9ffd5f3ed42e51a5b6668d542ca1890304f4a758dcb58342eb0877918b15619047f9ef58c95d58c1ec77be1f36fac4226219319df111f3023b1f63cf23d2b59a36b87ca6238a32000a3ef11e83bfd6bc79271934fa127ffd64be6daa634c0457c71cf3ee7b9accbe65766c1c321c63d3fc4578d156390c2d4642c5541c6396c1ea7b0ff003ed5000000000d0a7b7ba20208494d4147000000000000004b6fc316264ba9269406233ffeacfd6b5a8f928b2a1ab3ffd7e7760f4a618437515060452e9d04ea43c6a735523d126b463258ba0cf3b1c641a996d665c1b4ee8fffd0e5a3bbd422f927d36271fde8e6c7e841fe759f73a3c5745a478bca7639215b38fd07f2ae5a5455393945975b10ea4545ab34665ce833c63f76778fd6b3ded5e238742a47a8ae94ee73a91fffd1f2255229dd68333a9f06db3325ccca32cce90ae0771f3b0cfb80a3f1af45f185dff6378475031965296bf6742a7043362307ff001ecd0f61c6e7ffd2f1ff00b5953b4a0c0e063b502f226e0e47d6833e51ff00ba907054fd29a6da2230062905da3fffd3f1efb0a9e55ea29613011939c8a084c743f7fa8c55c86359412b22291fc2cd8cd2133fffd4f2886d66b8b85b7853cc95890aaa47240cff004ab779a16ada7c7e6dd69d3c71e3264003a81ee54903f1c509195ca0a4100a9047a8a9edeeee6d771b79e48f7750adc1fa8e84fe14f603ffd5f3e83c4f751a8134114f8ea7fd5b1fc40c7af6ad3b7f10584d8f34c901271f3aee19f62b9e3dce295bb197a9a113437285e0912551d4c6c1b1f953258caab1452e40c851d4fd053b8ac7ffd6c4863686148db395500e7bd499228303acf033258beabad48094b2b42060fde032ec3f38c8ff008156058c2d71730452b6f6770643d4bf77fcc034d7734bfba91fffd7ecfc3116e9359bf61807168a7d000000000d0a7b7ba20208494d4147000000000000004cbeebfe4cac7f1ae0bc737867d558004045f4c75e69c1684d4775a1c9ee62a6988e58f354607fffd0e06f6ec59dabcf91941f283dcf6ae3598b31663924e49a6d99d3425148d0ffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea2800ab561aa6a1a54a66d3afaeace42305ede668d8fe2a453bbd82c7ffd6f3dd3be2cf8d74e11a0d65eea343f76ea3498b7b1761bff5ae96c3e3e6a28186a5a159dc67ee9b695e023ebbb7e7f4a974e2f6d3faedfe561a935e7fd7f5dce92c3e377856ed916ee0d46c588f999a359117f153b8ff00df35d15878ebc29aa2836be20b03ce02cd2f90c4fb09369350e9c96dafa7f915cc9f91ffd7daf88374174eb1b75208b89ccc083c1545233f9b8fcab83660d37b01511b38e84cd5983c986503396cf23b530392b934c83fffd0e5fcc00720f7fc29518609078ed50f6d0c4ad322a03231000ea7158d3ccc5ce18e33d2a1b1367fffd1f3ebb0979648e83055b2d8ed52da2f9d28b8c10aa8114678e3bd61739cd3b4bf9f4f9a2b9b66db22a95ebc30c9c83edfcbad7a1d8dcc5ab69d05dd9831a48bf30fe2461c11f506bcfc5c796d53e407ffd22f220f74214ca47b4120f6201f5ac3b897f76249080e1487c7b578f1d4e5301a4695f71e0fa7a576be0a8127b695c8e51c2b63a63191fe7daaf1b07ec1d8d29e923fffd386d2d624b586000000000d0a7b7ba20208494d4147000000000000004d278d1995402c40249c0cf3528b1b573cc4bf87159fa19d877f64dab73823e8690e8f0ff0bb03efcd21a563ffd48df46dc30b28fcaabbe833e4e1e223b7273fcab25a12d3644fa05d11c22b7d187f5aab3f87ee19487b52c3d806cfe54eebb91cacffd5e42ebc26c01220962278e50e3359371e1dbd8795899d7d854a663aadcec3c1366238aca3902ab1633b8c1079276e7be76a0a93e2d5fedd22cf4f42775c5c191b07aaa2f7fc5d4fe155b94b63ffd6f1ff00b30641eb8e6abbd9b2f4e4522132131c89d88c5392e254e3391ef4cad19fffd7f194bdc0f994fe14cb8b8129186271eb489e5d47d9366741d79aecee747b0bc259502127236fcb43bad852dcffd0e234ab1bcd12f1e6b6642b22f97bda2de54123f4e3fce2ba3b7f126a16ff00f1f966ac381be13d7d6b4a7cb2462e4d322b93e17f106efb4c11c170fc090030cb9c75cf463c7f167e958ba8780ef22cc9a65c477919e551b092633c60e76b7b9c8fa52946db86faa3ffd1f2424c6cc8c36b2b15607b11c114e561eb4191246db5c48a76b8e8c3823f1ad2835dd42150a661328180b38dff0099ebfad17bee23ffd2e36dfc4d19c79f6d2a1dd8cc4e1c63d70d8c0fa64d6a5a6a3617722ac5731b3679420a310393807ae064e451668c6f73b04ce9df0fceecacba9dd80c31efce7d72216ffbeab3b432b1dc4b792e3cab688bb9f4efff00a086a3689a36b4000000000d0a7b7ba20208494d4147000000000000004e3fffd3edf4b8a4b0f0a5b24bccd36e9e427fbe7e5cfe3c9af24d72e7ed17f71283c339c7d3b5547e122a6bb19abd8531d363f1d0f348c8ffd4f26f115d6f912d94f09f337d4f4fd3f9d63d04c168145051ffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f1286e67b724c1349113d4a315fe557e0f11ea901e2e4c83b8914367f1ebfad0ddf7172a2ec7e30b82733dac4dd71b18aff3cd6843e2bd3dcfef126889ee5411fa1cfe949a25c4ffd4e262d674e987eeeee3c9e818ed24fd0d5847560a10820fa543320ba884b0b2f7ea2b9f983671d0d66c97b9ffd5f31b5b86b7930ec4c6c46e00678fa55cd31059ea2fa7bb048a406580b1e0af3c0f5e87f2ac36bb303465da0ed4e42f19c633ef5d1781b574b3be934eb86020bde13774126303fefa1c73c702b1af0e7a2d75fe9891ffd6bbabd8986fc2a778da46f73b940fd3f9570fab4d891e143c6ee7fc2bcac3fbed1cc508d726ba0f0ecb25bcf2847c02a3209e33cd7557fe1b1ec7ffd7acba6400e541527dea71a7e061679403fed1a8e66649127d96e17016ee4c01c6467b714ab15eaf0270deeca38fca9f32ea8ad4ffd09945f8ebe5363d88feb4f32dda0ff508c7d9aa7dd21362fdaa7032d6edc0fe139a4378dfc50483e8334597463b9fffd19cdf4791be371f5427000000000d0a7b7ba20208494d4147000000000000004ff951f6d87a9620f7e08ff3ff00d7a95017322d69b2453caf344cafb46dc8f73ffd6af35f8ada89b8f10c76a9236db4b7542b9e0337ce4ffdf253f2a12b585bec7fffd2f135b9953a39fc6a45be90750a6827950e37aac3050fe75148f1bf238a416b1fffd3f0ea2801c8ed1b0753823a56d5bf8aae1389e257e7aa9c1a4d5c4d5cffd4e06d3c51692103cd319f47e3ff00ad5ad06ae92286c865ea0a9eb53b19bbadc99a6b3b91b6444607b32d4b6b0436b289ad1a5888fe18e7217fef9395fd2b48d56b464dafaa3fffd5f39bbd06fb7c9318e390bb162626c75392707df3c7359d2db49090b246d1b12400e0827f3a2fcc657b6e0aac0714e563dc5023ffd6f2e53ed5b3e1b81a4bc9a60aafe545b42f7dce7031ff00001251d0c3aea7a578c88b55d2b49562c2cedb731ec49c2038ff00b66dff007d552d3a2234d650a4b5e4eb0a81fc4a3923f259050f446ae49bd4ffd7edbc5938d3b4e785327ecd0ac4a7a6e0abfe27f4af17ba93748c7939356b489954776471e01c9e82abdc5d854795f854526a7cc83fffd0f129e569e6795bab1cd47402d028a00fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea7c734b09cc523a1f55622802e43ae6a508005d3b01d9fe6fd4f35613c40c5899ed2193000000000d0a7b7ba20208494d414700000000000000503fdd257fc454b8a6438267ffd1f32b6d7f4b31b2cf64e8ecc0861f30518e47f9f4a76a3aad8dddac26de44496d18ba1c152c0e32bfa67f3f5acb91a32e5699a36b791ddc2b2a30cb0c900f4a908c8c648f707047b823a1a2dd097a1fffd2a979e285b8d0229518aea5b16091860edc672df974fa8fa57238e49ae2c3d3e44d799ce356f2d607c4d2aa11fdee2b63489e29ae5e48244963da39539078abae9fb360d687ffd37ae38a9948ed59198f1da9c0d03b9fffd4d052697359adb426e2823ad34914319fffd5ba48a8e7916285e427002e49acac264ba1c061b040466473963ee7ff00af9af13f136a3fdabafea17aafbd25b8731b7aa0385ffc740ad3a928ffd6f0f18cf34bb323839a00690475a2803fffd7f0ea2800a2803fffd0f0ea7c53cb036e8a4643fec9c50068dbf886f61c090acabfed0c1fcc56b59f89ade4655759227381c73fca958871ec7fffd1c8d14dadcd979d2c818bc842e5b180383fae7f2ad0fb069d37cac14f1d0366b369929268824f0de8d8cfd9d4671d00e71d3b509e0fd2e5fe1c13e829734907b35d0fffd2a32fc3bb09c02972f1e08e553a7eb8fd3b7e57bc37e0c1a3dec18ba170ad74b236e8b0d845c81c1c6061bb7f1f4f58526f4b0bd925ab637c4575f69d6ef64c9d91c9e48cf608369fd413f8d6ff00872cc3eafa4d9b0dab027da24e3a11923f32b20fc69cfb0934e573ffd3d6000000000d0a7b7ba20208494d41470000000000000051f1f5e936472854cefb9876193b8feb8af3176dcff8d5744633dc64cfb2238ac2d66eb10adb83cb1cb7d07ff5ff009525b0a2aecfffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea2801412a4152411d315d869d756d242b6cc6e85cc4815d9995e390f7f46523a7f1038ed45ae44f63ffd6e299597a8229bbd1796603eb59b39cc9d73caba8556371953927b54fa05ea69966cde602bce4d4555cd0e52afee9ffd7d8d6b473a7b0923c985ce037a1f4ff003ef598a79c5649916b13a0040a7f97ef4ee07fffd0d00a71d6930dd3f9566f4206cb2ac31b4b23044452ccc7a000649ae4eefc7f02b62cacd9fa8dd31dbdfaed1fe23f0a6a370e6b1fffd1e2ae7c6fac905a336b18ff0066139f6ea4d65cfe30d62e321ef9b69eaa234008f4fbbfe726972c519a6d8d3e31d7d5022ead3851c6d0107f25ac12883a01da9bb741ab9fffd2f1511af7a3cb51d06291370d80fe34d28b8eff00850173ffd3f11f2fde90c640cd2b8ae368a633ffd4f0ea2800ad2f0f41e76a21b1b8468ce5719cf6fe6450c4f63fffd575b5bada5b456c00c448138ef818a7b04f41f9565e6220104323e4c484e7a9515ab696f105188d33df8a776524ae7fffd6e81628c630a07d000000000d0a7b7ba20208494d414700000000000000522abe977420bfd5752724c1671f948be6655c2a06738ce33bb7afe1511bb65cb6d4e62ce26b89e28a4f9d9d87987d7bb1fe66bbbf0d21336af7c7055516d541ee0901b9f66593f3a72b5f5328daf767ffd783c7d7a26bd48c1fb8a49efc93ff00d6ae2f7fce49aa969a184b723bd654843f98a477c64600ae4ae6637133487b9e3e9525416b73ffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea280154e083e95b7a2a98adde7e4b31cf4ec28267b1ffd2f3e49eeee17027e3f5a16dc46d9954ca7dcd65b9ce599ad2d65b502741b5bf81793ffd6acc9f4682487ca8ae5d1472148e3f1a22ee34ec7fffd3ee6ec2dee9532e32db4900138c81915c6347b188e4564f46448786da060d3c4949311fffd4bdbf029bbeb2b99a22ba513dbc909e8e854fe22bc72f5a5b7ba747e1958ab0f420e08fce9c770ea7ffd5f26593cc8994f3c7159c5886c7a52338a0dc72690f5a0a3fffd6f15e73eb8a377e548903ea0d273d05007fffd7f13e871dcd1cf201a449174a29947fffd0f0f546760aa0927b0a1919386523ea28012babf0359996e04841c34a3b7641b8fe07a527b0a5b1ffd15f329bbfb8e2b2e821d072f5ad6e703be282a27fffd2dd69d2089e599c2c000000000d0a7b7ba20208494d41470000000000000053680bbb740aa0649fc0563bc8f6be16c3a859afa6df20031f3336f71f4e1bf3c54a5a8e4d22a68a23176f712e7cbb788bb1f4ec7ff1ddff009575ba26a16f6da37d8a56617464334c48e03b28f978c9eec79c727de93f88985ada9fffd3c9f16699ab3dfcd72b6171710139592dd7ce18c7a2e48fc4015c98757765570597865cf2a7dc76a7cca5768c25a3d4cdd66e1e1b710b1f9df8e3d3bd61523482b23fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea28015416200ea4e2bb0d1a3f220242e405d838a4f633a9b1ffd6e21ede291c30023c1e71dea748a2000c938f5ac5c6e73e838c5191d054125b2f624552d3603fffd7eb347b912db490b633d704fb76ae564be11cef0caa461881bbbe0f5ac65a912648af148328d8a7b46c87de8449ffd09cb103914d0d8ac519817c8af33f1cd9341aa49305f926f9c71d33c1fd413f8d547703ffd1f22b63be353dfbd44f6e3cc6ce7148cf66433a2c670a3a0eb518c8eb4147ffd2f145c91914f851a67088a4b1ed46e484b1bc2ec8e36b2f51e94d38c66901ffd3f14c807f0a6eec9dc0524223a298cfffd4f12b790c332483b1a9afdf7c99c0c7634ba89ad4ab5e85e0cb510401cae0ac4064f1f78ee3000000000d0a7b7ba20208494d41470000000000000054fc87f9ea3133ffd5afe67a52196b224b56a726b4627c719eb4ec5c4fffd6b5ad499d3da01826e5d6020f7563f38ffbe379fc2aaf8865c1b5b55e91c65dbdf3c0ff00d04fe75315a84da42695179966d1e39bb984007665fe2ffc77ccae5c78e65377713bd9dbdcc2f33b4046e864d858edcb0cff000e3b6695af27d0cdbb6a8fffd7ceb1f1b698e466e6eec9b8c0b887cc4cff00bc8491f538ad933596baab24d6563aba21da92c7b272b93d8fde53c763dab2945c757a79ff005b12a4a5a1e39e29bab1bbd72e9b4c8847668db2201d9f701d581624e09c91ec4564d6aaf6d4a492d11fffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea28027b24df70be8bcd773120b7b58a365c9c648f7a4f732a9b9fffd2e3f2ac461715379591d2a2c7311cb633cb8f2064fa66aadc59ea16681a6caab1c2e7b9a5e42d4fffd3d1d2752305e47e62fc85806c77ac1d6f509f4bd4268755b178a2794f952a8de8eb93b79ecdeddbaf4ae7bf33462de836096098092d27047b36454e97b35b8f9c71ea0f155e4c2ed1ffd4c887c73a4cd7925a48248c236df3480558e71d01ce3f0adb824b7bc884b6d324a87a346c1856728b8ab9993d9e9b73a834ab6fb0f9580c59000000000d0a7b7ba20208494d41470000000000000055b00123207e44565f8c3e1deb7a8244b6d6ab34e9cb22c8a0852bb89f9881d8562eaa8cacc7cb2b5d1fffd5f3993c27ad694bfe97a65dc4bd7718895ffbe8657f5aa13c6400e3041ee3915319292bc598b7aea664ed976e2a3c81d7e94cd11fffd6f122f8c803ad3ed4cfbc987390392285a0ba6a3a7698b97943127a9c5425c93c9c51bea248ffd7f0fdd499a0028a00ffd0f0ea52c4f524d003a30598054dc7d2b46cbc457f607f712ba8eebbb2a7f03c76ebd68d3a89ab9fffd1e2342f1849a95dc7677100591f3874e9c02791f415d2ac9b88150d59e84791a56a70055c47008e6923447fffd27dc30b9d66ca12159210d70c0f553f750fe23cc159faace67d427727a36c19f4518fe60fe7531266d136a57274bd124951ca496d6523a91c9599f11c7ff8f48dd7d2bcd500450aa380300528913563ffd3f2fcd417376f66be7c2e639cfca9221c30fa1eb4276304aecc5a28373fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea28036fc37642e2e549e99de79ec0ff008d75f22066e074a9ea633dcfffd6e6a28724715761b70e7b54d8e736f4ad3565914e391cd66fc4130dadfda58af062b5f358e3825d8e39fa20fceae11bddf60d8fffd7d986000000000d0a7b7ba20208494d41470000000000000056c60b14022dedc83b9dd9c9fccf4fa55ab8b5b6d420682ed1678dc64abf41edf4ae2a8e528d96e6292bea703af781ef74d99ae744924639cb40ad974fa1cfcc31d8f3f5240ae71bc63a858c52457308790232abfdd2ad8c027f1fa74ada12556367b895ef63ffd0e07c2f61a45de9b8baf2dae6491989dff381c600f6a96f3c377f664cda45ec8ac0f40e51ff000231fcc54f3b8bb3d8c9ee6569be33f117877579aed2f5cdc16c4eb27cc8f8007ccbd3a0c7183ef5e93e1ff8d5637120fed28a4b499b2ad228f311b3edf787ea3de94e9a9eb1dcd632b7a1ffd1e9e1f10596a71fda2c2ea0922202830b038c7d3fcfe9593aa695a4ea677dd584123f4326ddafff007d0c1fd6bcf717095d68ca7692d4e3755f879a7c80b59dcc909e3e590798a07b1e08fc735c66b5e16bfd1d1e79514c0a40f311c329cf4f71f8815d34eadf4919f2b8fa1fffd2f0eab76b7a2dd7614e0f523ad026ae8bcb7504c005233ef51dc41116c100fb8a933574cfffd3f1696d957956c0f7aafd29212770a298cfffd4f0ea2803474eb2ba495a636939088481e51393d00e9ef51ae89ab391b74cbd39ee2ddffc295d27b929ab9fffd5e3fc3be1a3a75bc73cb6e5aedc6e2cc8418c103e4c1ee39c9f723a75e822b6981e508ac9cb5b91b9a11ab2a8e3152e5bd28ba7b169d8ffd69ecad248afa7bd9363348aa898272107383c7f78b9efc1aa4ba2000000000d0a7b7ba20208494d41470000000000000057b9387955b3cb617a9eff009d64a7625aec54f175b5edc699245026f69ee232ca180dd120271ce3fe5a36efa0ae0e6b79ad8e2786488838f9d48c9f6f5fc29c1a644d599fffd7f2fac8d426f327d83a271f8f7a4650dcab453353ffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea5552cc1475271401d96836e2de02d8ebc0fa56ca918f5a12306eecffd2c089c0353c571b5f1458e73a8f0eb79b228ea49ae4fc4778357f185f2614c7f6a16c9dfe540233faab5691d1360df43fffd3d232b45f70903d339c5392fe54e4edfcb15cc9990e6d523cfcc8530300a9ae33e235a69d368f26a29ff1f2855010002c0b73bbd71cff00fab8331872b4d04b567fffd4f118e59226dd1b953ed5b161e2dd42cc05722751d9e9349e8c4d5cc79a569a5795f967393f5a65319fffd5f1482f2e6d5c4904f246e380cac411f423915d0d9fc42d72db025996e17a1f357240f62307f32689252d242b763621f89504ab8bab1910fac7206cfe040c7e66b3f5fbeb2f11ce891ea860b68f1849223cb742c76e7ffac33ea6b354b95dd6a4b6fb1fffd6f24d62df4bb6b6b5874f9269e51b8dc4cc85118f18c03cf1cfb74ebcd64d257ea28bba0a7acb2274634c67ffd7f116000000000d0a7b7ba20208494d414700000000000000589598734d18ee3340076a4a00ffd0f0eab7a4a2bea56eae4637823ea391fad0c19e856fa95ca01fbd6603b31cd6843ac2123cd8c8f52bcfe9583827b10a4cffd18e0b8827ff00572293d719e7f2ab0171dab9b6d092455e39a9123e70314c7a9fffd2d554f9450a809c9ed5cc9a028dfc7bdc0c70055092d51810ca0fd68b12d1ffd3e5f5fd234bd3f4cb9bd30088c4984119da0b138518e9d4f5c6700d79b1249c9ea6a29b6d6a4c55828ab28fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0eab5a7c7be6dfd92813d8e820d5da1411f96a540ed5a36daac4e996565a76307a1ffd6e456feddff00e5a01f5a956e903021c1fc6aac731d6786b5082d0fdae57558edd0ccc49e005527f0e95c569e6e12e1ee6e401294691883c33367247e249a6b48340f5563ffd7d1b878d4ed1c1154a5ba8d4ed2e01eb8ae58993430cd1b81f38cd73fe2f589f41bb0eea1405239efb85357b8ac7fffd0f0ea2800a2803fffd1f0ea2800a920389063bd3407ffd2c1f0ddae833e890d95f4513caccd23b4918c649c0c30e461428cf1516a7f0baca753369972d106195563bd0fd187207b9dd59c9ca2cc92ea8e3b55f066b5a4e5a5b569231ff2d23f997a73d3a0ff00000000000d0a7b7ba20208494d414700000000000000597b1586411d455a6a4ae8b52be87fffd3f0ea2800cd1401ffd4f0ea5562a4152411d08a00e821d42f6cac92524c9850487e6ac5a78a6de4016e10c4d9ebd454d93d8cd2b9ffd5e3edef61b95dd14a8f8f43d2b46df54b9830164dca3b37359dba332bd8d4835e89b0258994f4254e456a5adddb5c9fdcccac7d3a1fcbad66e2d6a689df43ffd6d700818fce9eabc1ae41904d16e26ab3dbf3d07ad311ffd7e0fe25ea61af20d2633f2c03cd980fefb0f941fa2f3ff03f6ae2aa60bdd120a2a867ffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ead5b18fcab70c7ab734133d8980c9ad35b49a3b71218cedc75aad8c647fffd2f361c9cd23310dc123156728f7ba97c8688c8db18619771c11e87daa7d2259594a6e3e5f61e94a52d067ffd392f241923bd63dc316735cb1b99b232fb47279ae6fc6776574e5847fcb5939fa0e7fc2b45b892d4fffd4f0ea2800a2803fffd5f0ea2800a9acc21b98c48c1549e49e82803fffd6e36d5219115ede40a31c6d3915ab677f7d624b46ec4772a7afd477a57be8cc5686ddaf88e39b02ea204f765e0fe5d0fe9497fe1ff0ff00883733c3179cc012cbfbb7cf38ce386fd6b1707177897a48ffd7e4756f853346ccda000000000d0a7b7ba20208494d4147000000000000005a75c86006764bf29f7e4707f215c95e786752d389fb6db4908c800b0e093e84707f0359c6a2968f733e66b733e4b49a2272b9fa543d2b42d3b9ffd0f0ea2803a6800b9d191b033b369fc38ae725431c8c87b1a95b911dd9ffd1f10491e360c8eca47420e0d695af88afadb019c4abc0c3ff008d26931357366d3c5569290b3a3424e393cad6bc17b6f72b9866471ec7352d3445ac7fffd2c8b4d5ef205c2cc4ae7a3fcc3f5e47e15b16fe2542009e12a73c94391f91ff001ac1c53254ba33420d46cef06239d0923ee37ca7f2349a95d41a5d94f7d743115ba191f240240ec33c649c01ea48159b56762add8fffd3f15bebd9b51bd9ef6e1b74b3c86473db24f6f6a8284ac0145007ffd4f0ea2800a2803fffd5f0ea2800a2803fffd6f0ea2800a2803fffd7f0ea2800a2803fffd0f0ea2800a2803fffd1f0ea2800a2803fffd2f0ea2800a2803fffd3f0ea2800a2803fffd4f0ea2800a2803fffd5f11863f36554f535b19c8031d29adc898f881dea31debafb578eead063fbb822866523ffd6f3dbcb6fb2ce53f87f86aa9ea4d59ca32660b1d69e931f976e588ed513d0ae87ffd90000102cfce36d5219115ede40a31c6d3915ab677f7d624b46ec4772a7afd477a57be8cc5686ddaf88e39b02ea204f765e0fe5d0fe9497fe1ff0ff00883733c3179cc012cbfbb7cf38ce386fd6b1707177897a48ffd7e4756f853346ccda000000000d0a7b";
		
		$data_aray = explode("7ba2",$datasss);
		$picture_data = "";
		for($n=1;$n<count($data_aray);$n++){
			//echo $data_aray[$n];exit;
			$picture_data.= substr($data_aray[$n],28,1024);
		}
		
		$filepath = "D:/xampp/htdocs/WlMonitor/data/";//路径
		$filename = "c2.jpg";//图片名称
		$binary_string = pack("H*" , $picture_data); //将16进制的字符串转换成二进制 
		$binary_string = hex2bin($picture_data);
		$file = fopen($filepath.$filename,"w");
		fwrite($file,$binary_string); //写入
		fclose($file);
		
		echo $picture_data;
		exit;
		
		
		echo dechex(11);exit;
		echo hex2bin("7BA2000A")."IMAG".hex2bin("0F0C0501020301".dechex(11))."XXXX\r\n".hex2bin("7B");exit;
		echo hex2bin("7BA20208")."IMAG".hex2bin("0F0C0501020301")."01XXXX\r\n".hex2bin("7B"); exit;
		
		$str_data = "7ba1000a494d414707e0070f0a18c2c34f01000000000d0a7b";
		echo "图片名称:".substr($str_data,16,14)." 十进制:".hexdec(substr($str_data,16,4)).substr($str_data,20,2).hexdec(substr($str_data,22,2)).hexdec(substr($str_data,24,2)).hexdec(substr($str_data,26,2)).substr($str_data,28,2)."<br>";//图片名称
		echo "总块数:".substr($str_data,30,2)." 十进制:".hexdec(substr($str_data,30,2))."<br>";//总块数
		echo "图像大小:".substr($str_data,32,4)." 十进制:".hexdec(substr($str_data,32,4))."<br>";//图像大小
		exit;
		
		//包数据
		$data_array = $this -> getDataAnalysis();
		
		//echo $data_array['datetime'];exit;
		
		$datetime = hex2bin($data_array['datetime']);//时间
		$stationnumber = hex2bin($data_array['stationnumber']);//站号
		$voltage = hex2bin($data_array['voltage']);//太阳能板电压
		$accumulator = (float)hex2bin($data_array['accumulator'])/100;//蓄电池电压
		$waterlevel = (float)hex2bin($data_array['waterlevel'])/100;//水位
		$watertemperature = (float)hex2bin($data_array['watertemperature'])/100;//水温
		$sluice = (float)hex2bin($data_array['sluice'])/10;//堰水计
		$rainfall = (float)hex2bin($data_array['rainfall'])/10;//雨量
		$capacity = hex2bin($data_array['capacity']);//水位存储容量
		$rainfallstorage = hex2bin($data_array['rainfallstorage']);//雨量存储容量
		$advocate = hex2bin($data_array['advocate']);//主信道包数
		$always = hex2bin($data_array['always']);//总信道包数
		
		echo "时间：".$datetime."----"."站号：".$stationnumber."----"."太阳能板电压：".$voltage."----"."蓄电池电压：".$accumulator."----"."水位：".$waterlevel."----"."水温：".$watertemperature."----"."堰水计：".$sluice."----"."雨量：".$rainfall."----"."水位存储容量：".$capacity."----"."雨量存储容量：".$rainfallstorage."----"."主信道包数：".$advocate."----"."总信道包数：".$always;
			
			
		exit;
		
		echo "------------------<br><br>";
	
		
		echo "数据解析测试：<br>";
		
		$datetime = hex2bin("3230313630363136313830333434");//时间
		$stationnumber = hex2bin("3030303030303031");//站号
		$voltage = hex2bin("30303030");//太阳能板电压
		$accumulator = hex2bin("31313734");//蓄电池电压
		$waterlevel = hex2bin("30303035");//水位
		$watertemperature = hex2bin("32353731");//水温
		$sluice = hex2bin("3030373230");//堰水计
		$rainfall = hex2bin("30303030");//雨量
		$capacity = hex2bin("303030303030");//水位存储容量
		$rainfallstorage = hex2bin("3030303030");//雨量存储容量
		$advocate = hex2bin("3030303030");//主信道包数
		$always = hex2bin("3030303030");//总信道包数
		
		echo "时间：".$datetime."----"."站号：".$stationnumber."----"."太阳能板电压：".$voltage."----"."蓄电池电压：".$accumulator."----"."水位：".$waterlevel."----"."水温：".$watertemperature."----"."堰水计：".$sluice."----"."雨量：".$rainfall."----"."水位存储容量：".$capacity."----"."雨量存储容量：".$rainfallstorage."----"."主信道包数：".$advocate."----"."总信道包数：".$always;
		
		//echo "++++".preg_replace('# #', '', 'ab     ab bb cc');//正则去掉空格
	
		
		echo "<br><br>图片解析测试：<br>";

		$filepath = "D:/xampp/htdocs/WlMonitor/data/";//路径
		$filename = "test22.jpg";//图片名称
		
		
		$str_picture = preg_replace('# #', '',"FF D8 FF E0 00 10 4A 46 49 46 00 01 01 00 00 01 00 01 00 00 FF DB 00 43 00 0C 08 08 0A 08 08 0C 0A 0A 0A 0C 0C 0C 0E 10 1C 12 10 10 10 10 22 18 1A 14 1C 28 22 2A 28 26 22 26 26 2C 30 3E 34 2C 2E 3A 2E 26 26 36 4A 36 3A 40 42 46 46 46 2A 34 4C 52 4C 44 50 3E 44 46 42 FF DB 00 43 01 0C 0C 0C 10 0E 10 20 12 12 20 42 2C 26 2C 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 42 FF C4 00 1F 00 00 01 05 01 01 01 01 01 01 00 00 00 00 00 00 00 00 01 02 03 04 05 06 07 08 09 0A 0B FF C4 00 B5 10 00 02 01 03 03 02 04 03 05 05 04 04 00 00 01 7D 01 02 03 00 04 11 05 12 21 31 41 06 13 51 61 07 22 71 14 32 81 91 A1 08 23 42 B1 C1 15 52 D1 F0 24 33 62 72 82 09 0A 16 17 18 19 1A 25 26 27 28 29 2A 34 35 36 37 38 39 3A 43 44 45 46 47 48 49 4A 53 54 55 56 57 58 59 5A 63 64 65 66 67 68 69 6A 73 74 75 76 77 78 79 7A 83 84 85 86 87 88 89 8A 92 93 94 95 96 97 98 99 9A A2 A3 A4 A5 A6 A7 A8 A9 AA B2 B3 B4 B5 B6 B7 B8 B9 BA C2 C3 C4 C5 C6 C7 C8 C9 CA D2 D3 D4 D5 D6 D7 D8 D9 DA E1 E2 E3 E4 E5 E6 E7 E8 E9 EA F1 F2 F3 F4 F5 F6 F7 F8 F9 FA FF C4 00 1F 01 00 03 01 01 01 01 01 01 01 01 01 00 00 00 00 00 00 01 02 03 04 05 06 07 08 09 0A 0B FF C4 00 B5 11 00 02 01 02 04 04 03 04 07 05 04 04 00 01 02 77 00 01 02 03 11 04 05 21 31 06 12 41 51 07 61 71 13 22 32 81 08 14 42 91 A1 B1 C1 09 23 33 52 F0 15 62 72 D1 0A 16 24 34 E1 25 F1 17 18 19 1A 26 27 28 29 2A 35 36 37 38 39 3A 43 44 45 46 47 48 49 4A 53 54 55 56 57 58 59 5A 63 64 65 66 67 68 69 6A 73 74 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 02 75 76 77 78 79 7A 82 83 84 85 86 87 88 89 8A 92 93 94 95 96 97 98 99 9A A2 A3 A4 A5 A6 A7 A8 A9 AA B2 B3 B4 B5 B6 B7 B8 B9 BA C2 C3 C4 C5 C6 C7 C8 C9 CA D2 D3 D4 D5 D6 D7 D8 D9 DA E2 E3 E4 E5 E6 E7 E8 E9 EA F2 F3 F4 F5 F6 F7 F8 F9 FA FF C0 00 11 08 01 E0 02 80 03 01 21 00 02 11 01 03 11 01 FF DD 00 04 00 02 FF DA 00 0C 03 01 00 02 11 03 11 00 3F 00 E5 C5 5C B0 6C 4A 2B 43 9C E8 10 FC A2 A6 5E 95 90 1F FF D0 B8 29 0F 5A E3 C4 AD 0C A1 B9 6A 1E 56 98 E3 93 5E 6A F8 99 B1 FF D1 BC 6A 6B 73 5E 14 B6 1A 26 98 71 55 EB 28 6C 55 8F FF D2 D2 A7 0A F0 16 E5 92 AD 48 B5 EB 51 D6 27 3C B7 3F FF D3 E8 D0 54 87 A5 41 81 13 0E 6A CD B9 E9 4D 01 FF D4 ED C9 1B 6B 1F 50 19 CD 0C E7 39 7D 45 70 0D 73 57 5F EB 0D 64 6B 13 FF D5 E2 A9 31 59 00 E1 C5 4A 94 01 FF D6 E5 D6 9F D4 56 22 18 E3 8A 81 C5 30 3F FF D7 E3 71 45 64 01 45 00 7F FF D0 E3 69 45 64 31 0D 14 08 FF D1 E3 48 A2 B2 00 A5 A4 07 FF D2 E3 80 A5 C5 64 01 8A 43 48 0F FF D3 E3 68 AC 86 2D 2D 21 1F FF D4 E3 8D 25 64 02 8A 5A 00 FF D5 E3 8D 15 90 08 68 A0 0F FF D6 E3 A9 0D 64 02 51 40 1F FF D7 E3 28 AC 80 42 29 B4 08 FF D0 E2 B1 9A 31 59 08 43 49 9A 60 7F FF D1 E2 28 AC C4 37 14 98 A0 0F FF D2 E0 CD 37 9A CC 91 0D 25 30 3F FF D3 E5 AA 7B 56 C4 82 B4 39 CE 8E 13 B9 05 58 41 59 01 FF D4 B8 38 A4 3C 9A E5 AE BD D3 18 EE 59 83 EE D3 64 E0 D7 95 F6 8D FA 1F FF D5 BA 7A D4 B0 1F 9A BC 37 B1 45 B7 5D C9 55 0F 06 B1 81 47 FF D6 D2 14 E1 D6 BC 05 B9 A1 2A D4 AA 31 5E A6 1D FB A7 3C F7 3F FF D7 E8 90 D4 BD 45 41 81 14 95 24 0D 8C 50 86 7F FF D0 ED 01 CA 56 65 F8 C8 34 8E 76 73 1A 92 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 03 F0 6B 97 BC 18 90 D4 1A C4 FF D1 E2 79 A5 15 90 0A 2A 64 A0 0F FF D2 E6 16 9E 2B 21 0D 7E 95 5D E8 19 FF D3 E3 69 33 59 00 66 8C D0 07 FF D4 E3 29 45 64 02 E2 8C 50 07 FF D5 E3 8D 36 B2 01 69 45 00 7F FF D6 E3 E8 AC 80 28 3D 28 03 FF D7 E3 69 6B 20 16 8A 40 7F FF D0 E3 A8 AC 80 28 A0 0F FF D1 E3 88 A4 AC 80 5A 28 03 FF D2 E3 A9 08 AC 80 4A 31 40 1F FF D3 E3 29 6B 20 12 90 FB 50 23 FF D4 E2 C5 06 B2 10 86 93 14 C0 FF D5 E1 C8 A4 AC C4 28 A4 26 80 3F FF D6 E0 DA 93 A8 AC C9 12 90 8C 53 03 FF D7 E5 AA 48 5B 0E 2B 44 73 9D 0D 93 E6 31 F4 AB AA 6B 27 B8 1F FF D0 B9 8C D2 11 C5 73 D6 5E E9 8C 77 2C 5B 9A 49 BA D7 90 FE 33 7E 87 FF D1 BB 4F 88 E1 AB C3 65 17 81 CC 75 51 C6 1A B0 8E E5 1F FF D2 D2 14 E1 5F 3F D4 D0 95 2A 60 38 AF 4F 0C FD D3 09 EE 7F FF D3 E8 57 AD 4A BC 54 18 8D 94 53 61 6C 1A 00 FF D4 EC 62 39 4A AB 78 9C 1A 46 0C E5 F5 45 C1 6A E5 6F 87 EF 0D 41 A4 4F FF D5 E2 A8 15 98 0A 2A 64 34 80 FF D6 E5 D6 A4 1D 2B 21 0C 73 55 DE 81 9F FF D7 E3 0D 15 90 C2 8A 00 FF D0 E3 45 28 AC 86 2D 19 A0 47 FF D1 E3 49 A2 B2 18 A0 52 D0 23 FF D2 E3 E8 AC 40 28 A6 07 FF D3 E3 A8 AC 80 51 4B 48 67 FF D4 E3 CD 25 64 02 D1 40 1F FF D5 E3 E9 31 59 00 50 68 03 FF D6 E3 A8 AC 80 43 45 00 7F FF D7 E3 0D 02 B2 00 A2 81 1F FF D0 E3 33 47 5A C8 42 62 93 18 A6 07 FF D1 E2 48 A6 8E 95 98 82 8C 50 07 FF D2 E1 0D 26 2B 31 08 69 08 A6 23 FF D3 E5 A9 54 FC C2 B4 39 CD DD 31 B7 20 15 A8 B5 9B DC 0F FF D4 BC B4 35 63 59 7B A6 11 DC 7D BF 5A 7C C3 8C D7 8F 2F 8C E8 5B 1F FF D5 BC A3 26 9F 8D A4 57 84 CA 2E 44 72 95 04 A3 0D 58 C7 72 8F FF D6 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 04 D3 A5 AF 9F 34 25 8E A7 07 8A F4 70 AE E8 C2 A6 E7 FF D7 E8 45 4A A6 A1 18 8D 71 9A 6C 60 EE C5 03 3F FF D0 EC AD C6 45 47 76 BF 29 A4 60 CE 53 57 04 13 5C 95 F0 F9 EA 19 A4 0F FF D1 E2 A8 C5 64 02 D4 88 68 03 FF D2 E5 90 D4 83 9A C8 43 58 54 0C 28 19 FF D3 E3 4D 25 64 01 45 03 3F FF D4 E3 45 19 AC 80 5C D1 40 1F FF D5 E3 4D 00 56 43 16 96 81 1F FF D6 E3 F1 49 58 80 52 E6 98 CF FF D7 E3 E8 C5 64 00 28 A0 0F FF D0 E3 E8 02 B2 00 A5 A0 0F FF D1 E3 F1 45 64 02 50 68 03 FF D2 E3 A8 AC 80 4A 28 03 FF D3 E3 0D 15 90 83 14 50 07 FF D4 E3 00 A0 D6 62 12 83 40 1F FF D5 E2 F1 4D 23 8A CC 42 77 A0 9A 00 FF D6 E1 88 EF 4D 22 B3 24 43 4D A6 07 FF D7 E5 68 CD 68 73 9B 1A 54 9D AB 65 5A A2 5B 8C FF D0 BC 87 22 94 8C D6 55 3E 13 05 B8 E8 46 1A A4 98 7C B5 E3 4F 49 1D 2B 63 FF D1 D0 41 CD 3C 8A F0 1B D4 B2 CD BF DD C5 32 E1 70 6B 3D A4 51 FF D2 D2 A7 0A F9 F3 52 54 E2 A6 5E 95 DD 85 66 35 0F FF D3 E8 45 4A 95 06 23 98 71 51 AF 0F 40 CF FF D4 EC AD CE 3F 1A 2E D7 E5 34 91 8B 39 4D 64 72 6B 8F BE FB F5 2C B8 1F FF D5 E2 E8 AC 86 14 F4 A0 0F FF D6 E5 90 64 54 C1 6B 20 11 85 57 71 CD 20 3F FF D7 E3 88 E6 92 B2 18 62 8C 66 80 3F FF D0 E3 68 AC 86 14 B9 A0 0F FF D1 E3 A8 C5 64 30 A5 14 01 FF D2 E3 CD 25 62 30 A2 98 1F FF D3 E3 C5 15 90 C5 02 8A 00 FF D4 E4 29 2B 11 8B 41 34 01 FF D5 E3 E8 AC 80 29 28 19 FF D6 E3 A8 C5 64 02 62 94 50 07 FF D7 E3 88 A6 D6 40 14 BC 50 07 FF D0 E3 73 49 59 88 6F 34 50 07 FF D1 E2 B0 68 C5 66 21 BD 29 0D 00 7F FF D2 E1 BA 52 56 62 1A D4 DA 62 3F FF D3 E5 29 6B 43 9C D0 D3 1F 6B 8A DF 8C E4 0A 89 EE 07 FF 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 05 D4 BA 9D 29 FD 45 67 3D 8C 3A 8B 19 C3 54 B2 72 B5 E2 D5 5E F1 D0 B6 3F FF D5 D1 5E B5 29 E4 57 CF 48 D0 96 D8 F6 A7 5C 8A 87 F1 0C FF D6 D3 A0 57 CF 9A 92 C6 6A 75 E9 5D 98 5D CC AA 1F FF D7 E8 73 52 2B 62 A0 C4 90 9C 8A 85 BE F5 03 3F FF D0 EB ED 8D 4B 70 32 99 A4 8C 4E 53 5C 52 01 AE 32 F0 FC E6 A5 97 03 FF D1 E2 E8 15 90 0B 4E 5A 00 FF D2 E5 A3 A9 D4 D6 40 0D C8 AA F2 0A 40 7F FF D3 E4 0D 34 D6 43 12 96 80 3F FF D4 E3 A8 AC 86 02 97 14 80 FF D5 E3 E9 2B 11 8B 4B 4C 0F FF D6 E3 CD 15 88 C3 14 B8 A0 0F FF D7 E3 E8 CD 64 01 9A 5A 40 7F FF D0 E3 C9 A0 0A C8 05 A0 D0 07 FF D1 E4 29 2B 20 17 14 84 50 07 FF D2 E3 A8 AC 80 5A 4A 00 FF D3 E3 8D 25 64 02 D2 1A 00 FF D4 E3 29 7B 56 62 10 D1 40 1F FF D5 E2 E9 08 AC C4 36 92 80 3F FF D6 E1 CD 21 AC C9 18 45 18 E2 98 1F FF D7 E5 29 6B 43 9C B1 67 26 C9 05 74 56 EF B9 01 A8 98 D1 FF D0 BB 1F 4A 78 15 0F 63 01 57 83 53 11 94 AF 1A B2 B4 8E 88 EC 7F FF D1 D0 1C 1A 99 79 15 F3 F2 D8 D1 0E 80 E1 EA 79 86 56 B3 96 E8 6B 63 FF D2 D3 3D 69 6B E7 8D 47 21 AB 08 78 AE AC 2B F7 88 A9 B1 FF D3 E8 29 CA 6B 33 22 50 78 A6 38 A0 0F FF D4 EA ED DB A5 5D 7F 9A 3F C2 A5 19 1C B6 BA BF 2B 57 0D 78 3F 78 6A 4A 81 FF D5 E3 31 45 64 30 A5 5E B4 01 FF D6 E5 62 AB 0A 2B 20 1C 57 8A 82 51 48 0F FF D7 E3 DB 83 4D EB 59 0C 28 A0 0F FF D0 E3 69 6B 11 8B 45 00 7F FF D1 E3 A8 AC 46 2D 14 01 FF D2 E3 E8 AC 46 2D 25 30 3F FF D3 E3 8D 02 B1 18 A2 8A 00 FF D4 E3 F1 4B 59 00 99 A2 81 9F FF D5 E4 31 45 64 30 A6 D0 23 FF D6 E3 B1 4B 8A C8 02 92 80 3F FF D7 E3 A8 AC 80 4C 52 11 40 1F FF D0 E3 29 6B 31 06 29 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 06 08 A0 0F FF D1 E3 0D 21 AC C4 36 93 14 01 FF D2 E1 9A 90 73 50 48 36 05 37 B5 00 7F FF D3 E5 29 7A 56 87 39 46 7D 40 47 70 91 29 E4 9E 6B B1 D2 D8 B5 BA 93 E9 51 2D 4A B6 87 FF D4 B6 86 A5 07 8A 86 60 04 D4 CA D9 4A F2 31 2B DE 3A 21 B1 FF D5 D0 3C 35 4D 1F 22 BE 7E 5B 1A 0A BF 2B D5 92 37 25 67 3E 83 47 FF D6 D4 61 83 49 5F 3C 6A 39 7A D4 E9 5D 18 77 69 13 3D 8F FF D7 E8 05 38 56 48 C8 91 4D 35 A9 8C FF D0 E9 E0 24 1A BC 5B 11 54 A7 A1 93 39 AD 74 E5 1B E9 5C 45 C8 CB 93 52 CA 81 FF D1 E3 9A 92 B2 01 29 47 5A 06 7F FF D2 E5 22 35 69 3A 56 40 38 9E 2A 09 39 A0 0F FF D3 E4 18 53 6B 21 85 18 A0 0F FF D4 E3 A8 AC 46 14 53 03 FF D5 E3 A8 AC 86 2D 14 01 FF D6 E3 E8 CD 62 30 06 8A 60 7F FF D7 E3 B1 40 E2 B2 18 B4 A2 80 3F FF D0 E4 0D 15 90 C4 C5 2D 00 7F FF D1 E4 05 06 B2 18 94 94 01 FF D2 E3 C5 15 90 0B 8A 43 40 1F FF D3 E3 A9 71 59 00 52 1A 00 FF D4 E3 45 06 B2 10 50 4D 30 3F FF D5 E2 E8 C5 66 21 B4 50 07 FF D6 E1 DA 90 0A CC 90 23 3C D3 7A 0A 60 7F FF D7 E5 2A BD ED C8 B7 84 9E FD 85 5B 30 5A 99 9A 6C 0D 75 75 E6 BF 45 39 3F 5A EF 34 B7 CC 60 54 B5 EE 97 2D EC 7F FF D0 B4 95 20 A8 66 02 9A 92 32 76 D7 95 89 DC DE 9E C7 FF D1 BC FF 00 7A A7 83 A5 7C FC B6 34 43 9F 86 06 AC C6 72 95 94 B6 29 1F FF D2 D6 94 7C D4 CA F9 D5 B1 B0 E5 EB 53 A1 AD E8 3F 78 89 EC 7F FF D3 E8 05 15 92 D8 C8 95 29 5A 98 CF FF D4 EA 21 1F 30 AB 8D FE AF 15 28 C8 E6 B5 C1 95 22 B8 DB A1 B5 8D 4B 2A 27 FF D5 E3 DB AD 21 AC 86 36 81 40 1F FF D6 E4 A2 35 69 0D 64 31 E4 71 51 48 29 08 FF D7 E4 9C 54 59 E6 B2 18 B4 52 03 FF D0 E3 A8 AC 86 2E 28 C5 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 07 20 3F FF D1 E3 A8 15 88 C5 22 92 98 1F FF D2 E3 E8 C5 62 30 14 53 03 FF D3 E4 29 2B 21 85 14 01 FF D4 E4 29 2B 21 86 69 68 03 FF D5 E4 28 AC 86 14 86 81 1F FF D6 E3 E9 6B 20 03 49 40 1F FF D7 E3 E8 CD 64 02 52 50 07 FF D0 E3 A8 AC C4 34 D1 D0 50 07 FF D1 E2 E8 CD 66 20 34 94 01 FF D2 E1 C8 A5 03 15 98 84 34 DE D4 08 FF D3 E4 D9 B6 82 4D 61 5F 4E 6E AE 36 2F 20 1C 01 EA 6A 99 94 16 A6 C5 95 B8 B6 85 50 75 EE 6B 7B 49 93 9C 53 7B 12 DD D9 FF D4 B2 95 2A 54 33 01 CD D2 9F 11 E3 15 E6 62 96 A6 F4 CF FF D5 D0 71 F3 54 B0 71 5F 3E F6 34 43 E4 15 34 5F 76 B2 97 C2 52 5A 9F FF D6 D8 98 73 4C 02 BE 71 6C 6C 2D 3D 4D 6B 4B E2 14 B6 3F FF D7 DE 53 4F 03 35 8C 5E 86 63 D4 53 C8 E2 A8 0F FF D0 EA 22 E0 D5 B1 CA 54 A3 23 03 58 8F 20 D7 1B 7C 98 73 52 CA 89 FF D1 E4 18 73 4D 35 90 C4 22 92 80 3F FF D2 E4 10 E0 D5 B8 8F 15 90 D9 2D 47 27 14 84 7F FF D3 E4 9E A3 AC 86 18 A2 80 3F FF D4 E3 E8 AC 86 28 A0 D2 03 FF D5 E3 E8 C5 62 31 4D 25 00 7F FF D6 E4 28 AC 46 21 A2 98 1F FF D7 E3 E9 6B 11 86 28 A0 0F FF D0 E4 38 A0 F3 58 8C 31 45 30 3F FF D1 E4 28 AC 80 29 0D 03 3F FF D2 E3 F1 4B 59 00 62 97 14 01 FF D3 E4 0D 25 64 01 45 00 7F FF D4 E3 F1 48 78 AC C4 34 9A 28 03 FF D5 E3 28 C5 66 21 29 0D 00 7F FF D6 E2 28 C7 15 99 22 1A 29 81 FF D7 E1 75 3B AF 26 2D AA 7E 66 AA 5A 64 69 E6 19 64 23 E5 E9 F5 AA DD 99 AD 23 73 51 AF 50 74 C9 AB DA 55 F6 E9 40 C0 1C D6 BC BA 19 D9 9F FF D0 92 27 DC A2 A7 43 50 CC 49 47 22 9D 15 79 F8 B4 6B 4C FF D1 D2 90 60 D3 A2 38 35 F3 FB A3 42 67 E5 6A 4B 7A C5 FC 25 75 3F FF D2 DC 9D 71 50 0A F9 A8 3D 0D 87 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 08 62 94 71 5A D3 76 90 9E C7 FF D3 DC 43 CD 4C 0D 61 0D 52 33 7B 92 2D 3E AC 0F FF D4 EA 14 E1 BA D5 A8 F2 57 EB 50 8C 8C 9D 55 01 06 B8 CD 49 31 21 A4 C7 1D CF FF D5 E4 9C 60 D3 0D 64 31 B4 50 07 FF D6 E3 97 AD 5C 84 F1 58 8C 9F B5 45 20 E2 80 3F FF D7 E4 9C 73 4C AC 86 18 A3 14 80 FF D0 E3 E8 AC 8A 16 8A 42 3F FF D1 E3 E8 AC 86 04 D2 0A 40 7F FF D2 E4 28 AC 46 26 29 71 40 1F FF D3 E4 71 45 62 30 A4 34 C0 FF D4 E3 E9 6B 21 85 2D 00 7F FF D5 E4 28 C5 64 30 A3 14 08 FF D6 E4 28 AC 80 5A 42 68 03 FF D7 E3 F3 49 59 80 A2 83 48 0F FF D0 E3 E9 0D 64 02 71 48 69 88 FF D1 E2 C1 A4 CD 66 20 A5 A0 0F FF D2 E2 8D 20 15 99 22 11 48 78 A6 07 FF D3 F2 E9 A6 7B 89 37 31 C9 3D 05 5B B7 82 40 A0 63 15 A4 15 DD C9 76 48 B2 96 C4 FD E3 57 AC 11 62 94 56 D6 31 94 8F FF D4 4B 36 DD 18 AB 4B 4A 4A CC C0 98 1E 29 F1 9E 6B CF C5 AD 0D 69 9F FF D5 D4 97 91 4D 43 86 15 F3 CB 63 52 C9 E5 29 F6 E7 9A C9 EC C6 B7 3F FF D6 E8 2E 05 55 1D 6B E6 61 B1 BB 1C 0D 38 0A D2 1B 89 9F FF D7 DD 51 52 2D 73 53 F8 48 7B 92 A5 3C 9E 2B 51 1F FF D0 E9 C7 DE AB 71 74 A8 46 66 7E A6 A4 A9 AE 33 53 4F 9C D2 63 8E E7 FF D1 E5 65 5F 9A A2 22 B2 18 94 DA 00 FF D2 E3 96 AD 42 6B 21 96 47 4A 63 83 8A 42 3F FF D3 E5 5C 73 51 62 B1 18 94 50 07 FF D4 E3 E8 CD 62 50 B4 50 23 FF D5 E3 E8 AC 46 21 E6 94 0A 00 FF D6 E4 05 15 88 C5 14 B4 01 FF D7 E4 4F 14 95 88 C3 34 84 D0 07 FF D0 E3 C5 2D 64 31 69 28 03 FF D1 E4 05 2D 64 30 A4 A0 47 FF D2 E3 E9 6B 20 0A 4A 00 FF D3 E3 F1 45 64 01 49 40 1F FF D4 E3 E8 AC C4 34 D2 50 07 FF D5 E2 E8 AC C4 19 A4 CD 00 7F FF D6 E2 73 46 6B 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 09 32 44 ED 48 69 81 FF D7 F3 5B 08 B2 C6 42 3A 70 2B 49 4F B5 6F 4F 44 63 3D C7 03 EF 52 C4 DB 5C 1A D1 19 B3 FF D0 AF A5 C9 BA 21 5A 4B 44 FE 23 05 B1 28 E9 4A BD 6B 8B 12 B4 34 A7 B9 FF D1 D3 73 C5 31 0F 35 F3 EB 63 52 D2 F2 B4 F8 38 6A C9 EC C6 8F FF D2 E8 E7 19 5C D5 32 39 AF 97 A7 B1 BB 17 A5 39 7A D6 B1 DC 4C FF D3 E8 50 0A 70 15 CB 47 E1 44 4B 71 EB 52 76 AD 84 7F FF D4 EA 00 E6 AD 43 D3 9A CD 19 95 35 05 F9 0D 71 FA AC 78 63 43 1A DC FF D5 E6 66 5C 1A 80 8A C8 68 61 E2 9A 68 03 FF D6 E3 81 AB 10 D6 23 2E 20 E2 91 D7 8A 40 7F FF D7 E6 24 15 03 56 23 1B 45 03 3F FF D0 E3 F1 45 62 50 0A 75 00 7F FF D1 E4 28 AC 4A 13 14 B4 08 FF D2 E4 68 AC 4A 0A 5A 04 7F FF D3 E4 4D 26 2B 21 86 28 C1 A0 0F FF D4 E4 31 4B 8A C8 61 45 00 7F FF D5 E4 68 35 90 C6 D2 D0 07 FF D6 E4 28 AC 80 5A 4A 00 FF D7 E4 29 A6 B2 00 14 50 07 FF D0 E3 A8 AC C4 21 A4 34 01 FF D1 E2 C9 A4 AC C4 14 50 07 FF D2 E2 68 AC C9 03 D2 92 80 3F FF D3 F3 E8 00 8D 02 83 D2 A6 06 BA 16 88 C6 5B 8F 0D 4E 56 E6 A8 86 8F FF D4 CA D1 27 C8 02 B7 94 D3 AB B9 82 D8 99 79 14 0E B5 C7 88 5E E9 A4 37 3F FF D5 D2 6E 56 9A BD 6B E7 CD 11 6A 3F BB 4B 19 C3 D6 2F A9 67 FF D6 E9 64 19 4A A6 C3 9A F9 7A 67 43 42 52 A9 E6 B4 8E E2 67 FF D7 E8 23 35 2E 2B 8F 0E FD D2 65 B8 0A 90 1E 2B 71 1F FF D0 EA 47 5A B5 08 C8 A8 46 64 17 C3 2A 6B 92 D5 A3 EA 69 30 47 FF D1 E7 6E 16 AB 11 59 0C 8D A9 94 80 FF D2 E3 45 58 86 B1 28 BB 19 E2 95 A8 11 FF D3 E6 65 AA ED 58 8C 65 2D 31 9F FF D4 E4 29 2B 12 85 02 96 80 3F FF D5 E4 29 45 62 50 62 92 80 3F FF D6 E4 68 AC 4A 0A 28 11 FF D7 E4 73 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 0A 45 64 31 69 29 01 FF D0 E4 68 AC 46 14 50 07 FF D1 E4 68 35 90 C4 22 8A 00 FF D2 E4 29 71 59 00 94 50 07 FF D3 E4 29 0D 64 02 51 40 1F FF D4 E3 A8 AC C4 25 06 80 3F FF D5 E2 E9 2B 31 06 68 34 01 FF D6 E2 A9 09 AC C9 0A 43 C5 30 3F FF D7 F3 C8 DB DC 54 AB 5B A3 26 3C 53 C5 51 0C FF D0 E7 34 79 76 C8 05 75 31 9C 80 6A EA EE 61 12 D2 74 A0 F0 6B 92 AA F7 4B 8E E7 FF D1 D0 2D C5 0A 6B C0 68 D5 16 62 E9 4B D1 EB 1E A3 3F FF D2 E9 7A C7 55 24 EB 5F 2D 0D CE 86 36 94 56 A8 0F FF D3 DF 8E A6 15 C1 86 77 88 4F 70 C5 48 BD 2B A8 83 FF D4 EA 80 E6 AC C3 59 A2 06 5E 2F CA 6B 96 D5 13 20 F1 43 12 3F FF D5 C2 B9 4E B5 49 87 35 88 22 36 A6 1A 06 7F FF D6 E3 6A 68 8E 2B 12 8B B1 9E 29 ED C8 A4 23 FF D7 E6 65 15 59 AB 12 86 D2 81 40 1F FF D0 E4 31 49 58 94 28 E9 4B 40 1F FF D1 E4 31 45 62 50 B4 50 07 FF D2 E4 29 45 62 50 B4 86 80 3F FF D3 E4 29 D5 88 C2 8A 00 FF D4 E4 68 CD 64 30 A2 80 3F FF D5 E4 69 2B 21 85 14 01 FF D6 E4 05 04 D6 40 25 2E 68 19 FF D7 E3 E8 AC 80 4A 3A 53 03 FF D0 E3 A8 AC C4 21 34 99 A0 0F FF D1 E2 CD 15 98 86 E6 80 68 03 FF D2 E2 49 A6 E6 A0 91 73 4D 27 D6 80 3F FF D3 F3 B5 05 1C A9 C0 C1 A9 96 B7 46 4C 91 69 C0 FB D5 90 CF FF D4 E4 AC 24 2B 28 AE C2 D1 B7 44 0D 6B 58 E7 89 72 33 4A C6 B8 E6 AE 8B 47 FF D5 BB 9A 54 EB 5E 14 95 9B 34 45 A8 4D 3D 87 CC 2B 99 EE 59 FF D6 E9 63 E5 2A B4 C3 9A F9 58 FC 47 4B 18 29 6B 41 1F FF D7 DE 4A 9C 57 9D 84 96 81 51 6A 38 54 8A 2B B0 83 FF D0 EA F1 CD 58 87 8A CD 10 25 D0 CA 9A E6 F5 34 CE 68 90 BA 9F FF D1 C9 BA 4C 31 AC F9 53 06 B2 04 40 C3 9A 8C F5 A0 A3 FF D2 E3 69 F1 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 0B 9E 6B 22 8B 91 1E 2A 6E D5 22 3F FF D3 E7 24 15 59 85 62 51 1E 39 A5 02 80 3F FF D4 E4 69 2B 12 80 52 D0 07 FF D5 E4 68 C5 62 50 94 50 07 FF D6 E4 29 40 AC 4A 16 83 40 1F FF D7 E4 45 2D 62 31 29 68 03 FF D0 E4 68 C6 6B 21 8B 8C 52 01 40 1F FF D1 E4 BB 52 11 59 0C 31 46 28 03 FF D2 E4 71 48 6B 20 0C 51 40 1F FF D3 E3 CD 26 2B 20 16 9A 68 03 FF D4 E3 05 06 B3 10 84 D1 40 1F FF D5 E2 89 C5 34 9A CC 42 13 49 BA 98 1F FF D6 E1 4B 0A 63 DC 22 67 2C 05 41 25 79 35 05 1F 70 64 D5 69 2E E5 93 BE 07 B5 34 87 63 FF D7 E0 2E 97 64 D9 E3 06 91 0D 6F 13 2E 84 8A 7D A9 C0 FB D5 90 7F FF D0 E2 ED DB 6C 80 D7 61 A4 C8 1E 10 2B 6A BF 09 CE B7 34 52 A4 23 8A E4 9E A8 A4 7F FF D1 BA C3 9A 55 AF 12 A6 EC D2 3B 16 22 3C D4 AD CE 2B 96 5B 96 7F FF D2 E9 20 39 5A 86 71 CD 7C AA F8 8E 92 21 4B 9A D0 47 FF D3 DD 8C D4 E3 A5 79 78 47 D0 AA 83 D7 AD 4A B8 AE F3 23 FF D4 EB 31 93 53 C5 C5 66 88 16 71 95 AC 1D 46 3C E6 9B 11 FF D5 A5 74 9F 31 E2 B3 67 4A C4 11 51 D7 8A 80 D0 33 FF D6 E3 69 E9 D6 B1 28 B7 0F 4A 9C 1A 42 3F FF D7 E7 A4 C5 56 6A C1 14 46 69 33 4C 0F FF D0 E4 28 15 89 41 4B 40 1F FF D1 E4 68 AC 4A 0C 51 8A 00 FF D2 E4 B1 47 4A C4 A1 33 4B D6 80 3F FF D3 E4 B1 45 62 50 98 A5 A0 47 FF D4 E4 69 45 62 30 A5 A6 07 FF D5 E4 A8 15 90 C5 A4 34 01 FF D6 E4 68 35 90 08 0D 21 A0 0F FF D7 E3 C5 06 B2 01 33 4D 26 98 8F FF D0 E2 8B 73 41 35 98 86 92 28 CD 00 7F FF D1 E1 C9 A6 96 C5 66 22 29 2E 11 07 24 55 77 BF 1D 15 49 A6 90 1F FF D2 F2 F7 B9 91 FB E0 7B 54 79 CF 5A 49 58 04 A2 98 1F FF D3 E1 AF 93 31 EE 18 E2 AA A3 71 D6 B5 89 92 D8 95 4D 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 0C 3C 7E 15 A1 2C FF D4 E0 E6 9D 60 5C 93 5D 1F 86 EF 7C E5 1E 86 B4 9B BA 31 4B A9 D3 29 A9 47 22 B9 D8 1F FF D5 BC D4 2D 78 D5 95 A4 5C 76 26 8C E0 D4 A4 E4 57 24 B7 34 47 FF D6 E8 AD CF 14 DB 81 5F 2B F6 CE 9E 85 71 45 6B D4 47 FF D7 DC 8F AD 59 41 C5 79 18 57 A9 75 07 8A 50 6B D2 31 3F FF D0 EB 45 4E 82 B3 44 04 A7 8A C7 D4 17 39 A2 42 3F FF D1 8A ED 39 35 9B 3A 75 AC 50 8A 12 AE 33 55 58 73 41 47 FF D2 E3 69 50 D6 45 16 E2 6A B0 0F 15 20 7F FF D3 E7 64 E9 55 9A B0 28 61 A4 A6 07 FF D4 E3 E9 45 62 50 B4 50 07 FF D5 E4 A8 AC 4A 0A 28 03 FF D6 E4 A8 AC 4A 0A 28 03 FF D7 E4 89 A3 35 88 C2 83 40 1F FF D0 E4 A8 CD 64 30 A3 34 01 FF D1 E4 85 19 AC 86 19 A2 81 1F FF D2 E4 A9 0D 64 02 51 9A 00 FF D3 E3 CD 26 6B 30 1A 4D 34 9A 04 7F FF D4 E2 09 C5 34 B5 66 21 A5 A9 AD 3A A0 C9 20 50 07 FF D5 F3 99 35 05 1F 74 66 AB C9 77 23 F1 9C 0F 6A 94 84 42 49 3D 4E 69 2A 86 7F FF D6 F2 AA 28 00 A2 80 3F FF D7 E3 24 5D C8 45 66 29 DA C5 49 39 15 AC 4C A2 4A A7 EB 52 23 62 AF A0 99 FF D0 F3 7B B0 D2 CA AA 3A 57 43 E1 CC 40 CA B5 A2 57 B9 93 76 48 EC D3 05 41 A9 57 91 5C EC 47 FF D1 D0 61 51 83 CD 79 18 85 EF 15 07 A1 32 1A 97 B5 72 48 D1 1F FF D2 DF B6 6E 6A 49 D7 8C D7 CB 4B 49 9D 2B 62 A7 7A 50 2B 4B 08 FF D3 DB 41 CD 58 43 5E 2E 17 73 4A 9B 12 03 9A 7A 8C D7 A8 60 7F FF D4 EB 94 74 A9 D4 56 68 81 26 E9 59 57 A3 20 D1 21 1F FF D5 96 E6 3C E7 8A CC B9 8F 02 B0 12 32 6E 46 2A A3 72 69 94 8F FF D6 E3 88 A0 71 58 94 58 88 D5 A5 A1 81 FF D7 E7 9C 54 0C 39 AC 0A 22 22 9A 69 81 FF D0 E4 28 AC 4B 14 52 D0 23 FF D1 E4 A8 AC 4A 0A 28 03 FF 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 0D D2 E4 85 1D 6B 12 85 C5 06 80 3F FF D3 E4 68 AC 4A 14 51 40 8F FF D4 E4 B1 46 2B 11 8B 8C 51 8E 28 03 FF D5 E4 A8 AC 86 25 02 80 3F FF D6 E4 73 41 35 90 0D 34 50 07 FF D7 E3 E9 A4 D6 40 34 D3 49 C5 31 1F FF D0 E0 E5 99 57 A9 AA 72 DF 0C E1 06 6A 12 11 03 5D 48 DD F1 51 16 2D D4 93 55 61 9F FF D1 F2 AA 28 00 A2 80 3F FF D2 F2 AA 28 00 A2 80 3F FF D3 E3 AB 36 ED 7C B9 F3 CE 1A B4 5B 98 C7 70 43 4E 2D 8A B7 B0 CF FF D4 F3 E4 50 EC 0D 6E 68 F6 B2 19 03 60 E2 B7 81 CF 36 75 C8 76 20 06 A6 46 04 57 34 95 99 49 DD 1F FF D5 D1 71 C5 45 DE BC DC 4A 0A 6C 92 33 56 14 64 57 9F 23 64 7F FF D6 DC 87 87 AB 4E 32 95 F2 D5 37 47 4A D8 A6 CB CD 28 15 A8 8F FF D7 DD 03 9A 95 05 78 78 77 EF 1A 4F 62 55 18 A7 03 5E B1 89 FF D0 EB 50 E6 AC C7 59 A2 06 5C 36 05 63 DD 3E 4E 29 31 1F FF D1 B7 30 DD 54 67 8B 70 3C 57 38 91 89 7D 11 5C D6 69 E2 A8 A4 7F FF D2 E3 E8 02 B1 28 9E 2A B2 BD 28 03 FF D3 C0 6A AF 25 60 8A 22 6A 6D 30 3F FF D4 E4 28 15 89 63 85 2D 02 3F FF D5 E4 F1 49 8A C4 A0 A3 14 01 FF D6 E4 A8 AC 4A 1D 4D 34 01 FF D7 E4 A8 AC 4A 0A 5A 62 3F FF D0 E4 E8 AC 46 14 50 07 FF D1 E4 8D 15 90 C4 A2 80 3F FF D2 E4 0D 1D 6B 20 0E 94 DC D0 07 FF D3 E3 B3 C5 46 F2 2A F5 35 98 8A D2 DF C6 9D 0E 4D 54 97 50 77 E1 46 07 AD 52 40 7F FF D4 F2 C7 76 73 96 24 D3 68 00 A2 80 3F FF D5 F2 AA 28 00 A2 80 3F FF D6 F2 AA 28 00 A2 9D 80 FF D7 E3 EA 9E A1 1E E8 C3 0C E5 6B 43 15 B9 52 23 C7 73 52 32 33 8C 28 AB 29 9F FF D0 F3 A8 7C DB 67 1E 62 9D BE B5 D6 68 BA 84 41 46 31 9A D5 68 61 35 75 74 6B CD 7B 1E DC EE A8 E1 D5 23 C8 5D C3 F3 A8 9A B9 30 B9 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 0E FF D1 B6 93 89 17 82 28 35 C3 88 8E 84 D3 DC 72 36 2A DC 47 22 BC D9 A3 A1 1F FF D2 DC 1F 2B 0A B4 0E 52 BE 5E 7D CE 84 42 50 16 A7 08 80 AD 16 C2 3F FF D3 E9 56 31 4E DA 01 AF 06 86 92 35 90 B4 0A F5 CE 73 FF D4 EA D0 D5 A8 CF 15 92 20 8A E7 95 AC 99 90 92 69 B1 1F FF D5 BF 24 78 A8 9A 0D CA 6B 9D 92 64 DF DB 0D AD 5C EC AA 55 C8 A7 1D 8B 47 FF D6 E3 C5 2D 62 51 2C 66 AC A1 A4 07 FF D7 C0 EB 50 48 2B 02 88 5A 98 69 81 FF D0 E4 29 45 62 58 A2 97 A5 00 7F FF D1 E4 E8 AC 4A 0A 5C 50 07 FF D2 E4 E8 AC 4A 14 50 68 03 FF D3 E4 F1 49 58 94 2D 25 02 3F FF D4 E4 E8 AC 86 2D 21 A4 07 FF D5 E4 A8 C5 64 31 28 26 81 1F FF D6 E3 CD 02 B2 01 AF 2A A8 E4 8A A7 2D FC 69 DC 53 48 47 FF D7 F3 89 75 26 6E 14 7E 75 55 E7 92 4F BC C7 E9 49 21 11 D1 4C 67 FF D0 F2 AA 28 00 A2 80 3F FF D1 F2 AA 28 00 A2 80 3F FF D2 F2 AA 28 02 58 82 39 0A 78 CD 5C 82 CA 37 70 28 B9 2D B4 7F FF D3 E4 29 92 A6 F8 CA FA 8A B3 03 2A 18 9C CC 63 00 F0 6B A6 D2 74 43 20 0C CB F9 D6 D1 41 52 56 47 FF D4 CD BF D0 E2 30 F0 BD AB 0C 5A 9B 36 3B 49 15 B4 B4 39 61 2B 90 5D 6A D2 46 BB 41 C9 AA D6 D3 DF B4 9B F6 12 BE DD AA 16 A6 C9 24 AE CF FF D5 C9 D3 35 27 50 04 80 FE 35 B9 15 C2 48 33 9A CB 11 4D D8 CE 2F 51 E0 F3 C5 5C B6 E4 8A F2 27 1D 0E 84 CF FF D6 DF 68 F3 DA A5 8F 91 5F 33 35 A1 BA 1B 20 20 E6 9D 1B 66 AA 3B 03 3F FF D7 EA 41 06 83 5E 05 3D 24 68 F6 1B DE 9C 05 7A E9 DD 18 9F FF D0 EA D6 A7 88 D6 4B 72 07 49 1E E5 E9 54 E5 B7 F6 A6 C4 7F FF D1 DB 9E 20 2A AB B0 4C 8A C1 90 8C 8D 41 C7 35 CF 4E 99 62 68 46 88 FF D2 E4 48 C5 25 62 50 F4 35 66 33 9A 4C 0F 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 0F FF D3 C0 C7 15 1C 83 8A C0 A2 B3 D3 29 8C FF D4 E4 29 6B 12 C5 14 99 A0 0F FF D5 E4 45 2D 62 58 B8 A5 A0 47 FF D6 E4 E9 6B 12 84 A3 34 01 FF D7 E4 E9 2B 12 80 52 E2 81 1F FF D0 E4 F1 45 64 30 26 93 34 01 FF D1 E4 89 A3 35 90 0D 26 9A D2 2A 8E 4D 00 7F FF D2 E1 A5 BD 8D 3B 8A A7 2E A6 4F 08 2A 12 11 52 4B 89 25 FB CD C7 B5 45 56 33 FF D3 F2 AA 28 00 A2 80 3F FF D4 F2 AA 28 00 A2 80 3F FF D5 F2 AA 28 00 A2 80 3F FF D6 F2 AA 28 01 41 C1 C8 AB D6 42 56 1B 81 EF 40 A5 B1 FF D7 E4 09 14 9B 97 D6 AC C0 B1 A7 5B C2 26 DC 40 E6 BA 48 F5 1B 78 23 0A B8 AD 14 99 9C D5 CF FF D0 A9 3E A8 92 02 01 15 81 A9 CF 90 48 AD 66 EE 72 C2 36 39 F6 98 79 B9 6C 60 56 B6 9F A9 D9 A0 C3 BE D3 F4 A9 89 B4 93 6B 43 FF D1 E6 8E A5 6F D6 39 54 FE 34 E8 35 C5 46 03 72 E3 EB 57 51 73 2B 18 45 34 6D D9 6A 91 CC 07 CC 2B 6A D6 55 6C 15 35 E4 54 A7 CA CD D3 3F FF D2 E8 90 EE 14 F5 E2 BE 6E 7D 8D 62 F5 12 41 91 51 2E 41 E2 95 3D 51 52 3F FF D3 E9 15 AA 40 6B E7 E3 B9 AB 13 BD 38 57 AD 07 EE 98 33 FF D4 EA D7 AD 4D 17 5A C5 12 5A 51 91 50 CC BC 55 92 7F FF D5 E8 AE 46 01 AC 5B C9 71 9C 56 12 21 18 57 93 17 6C 66 A8 4B 41 A2 3F FF D6 E4 C8 C9 A6 91 58 94 20 38 AB 31 35 26 33 FF D7 C0 07 8A 63 8C 8A C0 B2 B4 82 A3 A6 07 FF D0 E4 69 33 58 96 28 34 50 07 FF D1 E4 80 A7 56 05 89 45 30 3F FF D2 E4 E8 AC 4A 0A 28 03 FF D3 E4 C0 A3 15 89 41 45 31 1F FF D4 E4 E8 AC 86 06 93 8A 04 7F FF D5 E3 DA 45 5E F5 5E 5B D4 41 C9 15 9A 41 72 9C BA 9E 7E EE 4D 55 92 EE 59 3B E0 7B 55 24 23 FF D6 F2 B2 49 EB 49 40 05 14 01 FF D7 F2 AA 28 00 A2 80 3F FF D0 F2 AA 50 A5 BA 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 10 02 68 02 54 B5 91 BB 62 A7 8F 4E 66 EB 9A 09 72 3F FF D1 E0 23 D2 C7 75 A9 D7 4B 43 D5 07 E5 4E C6 6E 40 74 58 DB A0 C7 D2 90 78 7D 49 EA 7F 3A 2C 1C C7 FF D2 E1 A6 F0 EB 2A 6E 56 35 40 69 B2 2B 61 FA 50 C8 52 B9 32 58 AA 73 B4 1A 95 4B 44 30 05 45 C6 CF FF D3 F3 97 D4 89 E8 09 A8 BE DB 33 9F 94 55 10 A2 3C 5D 5E E3 E5 DC 3E 82 8D F7 ED FC 4F 4F 50 B4 4F FF D4 E0 EC DA E5 4F EF 4B 7E 35 7E 65 13 47 8C F6 AA 91 93 B5 F4 30 67 B2 95 24 38 52 41 A8 8D B4 C3 FE 59 B7 E5 4A C5 A9 23 FF D5 F2 D3 0C 83 AA 37 E5 49 B5 87 62 3F 0A 76 62 BA 2D 5A 6A 73 DA 30 C3 16 5F 43 5D 5E 8F E2 54 93 68 2F 86 1D 8F 6A C6 AD 3E 64 07 FF D6 D2 B0 D4 E2 99 46 58 66 B5 15 D5 C6 41 15 F3 D5 E0 E2 CD 22 C3 6E 4D 37 CB DB 59 52 65 C8 FF D7 E9 07 5A 78 15 F3 DD 4D 85 22 8A F5 69 3F 75 18 3D CF FF D0 EA D4 D4 8A D8 39 15 82 24 B5 1B F1 44 A3 2B 9A D0 47 FF D1 E8 6F 4E 01 AE 72 FA 4E 4D 63 2D C9 46 2C AD 96 35 5A 43 9A 46 87 FF D2 E5 71 48 56 B1 28 8C 8C 1A 96 36 A0 67 FF D3 E7 D0 E4 52 B0 E2 B0 2C AD 28 A8 0F 5A 60 7F FF D4 E4 68 C5 60 58 0A 5A 60 7F FF D5 E4 C5 06 B0 2C 28 A0 0F FF D6 E5 29 08 AC 4A 0E 94 B4 01 FF D7 E5 28 AC 4A 12 8A 04 7F FF D0 E4 C9 C5 46 D3 2A F5 35 90 CA F2 DF 22 0E A2 AA 4B A9 E7 EE E4 D5 28 8A E7 FF D1 F3 19 2F 25 93 BE 3E 95 09 24 9C 93 93 49 2B 00 94 53 03 FF D2 F2 AA 28 00 A2 80 3F FF D3 F2 AA 70 46 3D 14 9A 00 91 2D 25 7F E1 C5 4E 9A 64 8D D4 FE 54 12 E4 7F FF D4 E0 22 D2 7B 90 4D 5C 8F 4C 03 F8 7F 4A 76 33 72 2C A5 88 1D AA 75 B3 C7 6A AB 10 D9 FF D5 C1 5B 5C 76 A9 16 D4 FA 56 96 39 EE 48 B6 84 F6 A9 56 CC D3 B0 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 11 AE 7F FF D6 A4 2D 3B 13 51 C9 A5 C6 FD 85 6A D1 CB CD 63 3E EB 4B 31 FD CA AC 74 F7 03 24 56 4E 06 AA 67 FF D7 C0 5F 0B A9 51 FB B1 8F A5 68 5A 78 55 30 3E 40 3F 0A D6 2C E6 94 CB 7F F0 8C 20 FE 11 47 FC 23 B1 AF F0 8A BB 19 FB 43 FF D0 A3 79 A2 A4 68 48 15 83 2A 1B 79 0A 90 71 5B 34 72 C2 57 27 86 28 A5 E4 D5 C4 B1 85 87 41 52 91 4D 9F FF D1 C5 7D 2E 12 3A 0A A9 2E 97 17 60 2B 46 73 A6 CC FB AD 39 02 9F 94 56 3B 41 24 73 7E EF 23 07 82 2A 19 A4 59 FF D2 E6 74 8B BB 88 95 77 12 71 5D 4D 96 AD 90 03 35 73 D7 C3 A9 AB A3 35 3B 33 72 D2 F1 65 C0 24 55 A7 01 87 15 E1 4E 2E 9C AC 75 5E E8 FF D3 E9 42 10 69 E0 1A F9 C5 23 61 48 E2 90 57 A9 41 DE 26 12 DC FF D4 EA D6 9F 8C D6 04 93 46 6A 53 F3 25 5A 11 FF D5 E8 B5 01 85 35 CA 6A 0D F3 1A C5 A2 62 64 B7 2C 6A 37 5A 46 87 FF D6 E5 8F 06 8C 56 05 0C 71 4D 53 83 4C 67 FF D7 E7 22 39 A9 4F 4A C0 B2 BC A2 A0 22 84 07 FF D0 E4 68 AC 0B 12 94 1A 60 7F FF D1 E4 B3 4B 58 16 14 50 07 FF D2 E5 28 AC 4A 12 8C D0 07 FF D3 E5 29 09 02 B1 18 C6 95 57 A9 AA F2 5F 22 F7 02 9A 42 B9 FF D4 F3 C9 B5 3E CB 93 55 24 BB 92 4E F8 15 29 08 84 92 C7 24 E6 92 A8 67 FF D5 F2 AA 28 00 A2 80 3F FF D6 F2 B0 A4 F4 15 22 DB BB 76 A0 57 27 8E C1 9B AE 6A CC 5A 56 EF E1 A0 4D 9F FF D7 E2 62 D1 B3 FC 35 6A 3D 23 6F F0 D5 58 C9 C8 B3 1E 9A 07 6A B0 9A 78 1D A9 D8 96 CF FF D0 CA 5B 20 3B 54 82 D4 7A 56 B6 39 AE 3C 5B A8 ED 4F 11 28 ED 4C 57 3F FF D1 AB B5 47 6A 50 00 ED 5B D8 E4 1D 9C 74 14 6E 6F 4A AB 08 FF D2 AE 03 93 4E 0A C6 BA 6C 71 0A 62 CF 5A 4F 24 11 82 28 B2 0B 9F FF D3 D2 68 D6 31 CD 24 33 E1 B0 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 12 3A 55 AD CE 1D D1 A5 0A EE 5C D3 66 88 63 8A E9 5B 19 33 FF D4 BF 71 09 60 45 60 EA 1A 66 EC 90 2B A9 A3 82 2E C6 41 B6 96 16 E3 38 AB 76 CC DF C4 0D 4A 46 AD DD 1F FF D5 CF 2A 0A E7 91 55 26 1B 7F 88 D7 4C 92 67 22 66 74 ED 9C F3 54 D9 00 6C D6 0D 1A A6 7F FF D6 E4 22 BA 58 C6 32 2A 4F ED 20 BC EE C5 59 8D 8B D6 5E 23 F2 D8 02 F5 D2 D8 78 85 25 03 2C 0D 79 D8 BC 37 36 A8 D6 12 B1 FF D7 E8 AD EE E3 9F 04 11 9A B8 88 3A 8A F9 89 A7 06 6C 9D C6 BA FA 53 00 AF 4F 09 2B C4 CA 7B 9F FF D0 EA C5 3D 4F 18 AC 48 1E A7 06 AC 47 CA E2 A9 01 FF D1 E9 F5 35 01 4D 71 5A 9B E2 43 59 32 23 B9 9A 0E 4D 0C 38 A9 35 3F FF D2 E6 1D 7B D0 BD 2B 12 84 65 A8 4F 06 81 9F FF D3 E5 A2 6A B2 1B 22 B0 65 91 C9 CD 57 6A 00 FF D4 E4 68 AC 0D 04 34 0A 04 7F FF D5 E4 69 41 AC 4B 16 8A 00 FF D6 E5 29 2B 12 80 90 2A 36 99 57 BD 31 1F FF D7 E2 24 BF 44 EE 2A 9C BA 9F F7 49 35 09 01 52 4B C9 5F BE 2A 12 49 39 27 35 49 58 0F FF D0 F2 AA 28 00 A2 80 3F FF D1 F2 B0 A4 F4 06 A4 5B 79 1B B5 01 72 68 EC 59 BA D5 98 B4 DC FF 00 0D 22 5B 3F FF D2 E2 62 D3 3D AA DC 5A 5F B5 09 19 B6 5C 87 4D 51 DA AE 45 64 A3 B5 5A 44 36 7F FF D3 AA 90 28 FE 1A 7F 92 31 D2 B5 B1 CB 71 3C 83 9E 94 EF B3 B7 A5 09 05 CF FF D4 80 C4 C2 81 19 AD EC 72 5C 70 88 D3 84 1E D5 49 0A E7 FF D5 05 BD 2F D9 C5 75 58 E2 B8 79 22 9C 21 14 C5 73 FF D6 7E C0 05 2E D1 5D 67 00 D2 05 26 28 19 FF D7 BD 2C 86 43 C5 49 6D 6E 4B 66 9C 77 B9 C6 F4 46 A4 60 22 E2 9A E4 1E B5 D1 7D 0C AC 7F FF D0 DD 91 03 55 49 AD 43 F6 CD 74 DC F3 D2 29 4D A5 2B F6 E6 AA BE 95 24 7C AD 1C D6 2D 33 FF D1 A7 25 BC 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 13 CB C1 40 6A A4 D6 B2 BF F0 1A E9 6D 33 8D 22 84 BA 6C DE 9F A5 67 DC DB 4B 1F 51 59 CA C6 A9 9F FF D2 F3 C0 AE 5B 18 39 A9 96 CA 67 1C 29 AB 4A E6 4D D8 70 D3 27 EA 01 AB 16 F0 5E C0 C0 A9 E2 9F 22 7B 87 32 3F FF D3 CE D3 35 0B 88 C8 0E 48 AE A2 CB 52 76 03 26 B8 71 78 3B EA 89 85 4B 17 DA E5 48 EB CD 44 B7 20 3E DC 8A E7 C3 41 C1 D9 97 26 99 FF D4 EA 55 C3 53 D4 D6 24 0F C5 4C 8F 8A 68 0F FF D5 EA 35 2E 63 26 B8 6D 61 48 90 91 59 75 26 3B 99 88 DC F3 52 F5 15 26 87 FF D6 E7 5D 6A 31 C5 60 50 37 22 A1 71 40 CF FF D7 E4 94 E0 D4 E8 FC 56 0C B1 5B A5 40 F4 0C FF D0 E4 29 6B 02 C4 A2 98 1F FF D1 E4 29 6B 12 C5 14 16 03 AD 02 3F FF D2 E3 DA 75 5E F5 04 B7 CA 83 AD 65 61 94 A6 D4 F3 C2 E4 D5 49 2E A4 93 BE 2A D4 44 7F FF D3 F2 B2 49 EA 73 49 40 05 14 01 FF D4 F2 BC 66 9C B1 33 74 14 01 32 59 96 EB 56 63 B0 FF 00 66 91 2D 9F FF D5 E1 A3 B0 27 F8 6A D4 7A 71 3D A8 48 CD B2 DC 5A 6F B5 5B 8B 4E C7 6A A5 12 5C 8F FF D6 CF 4B 2C 76 A9 D2 D3 1D AB 55 13 99 B2 74 B6 F6 A9 92 D6 A9 44 86 CF FF D7 B2 2D 05 38 5A 8F 4A EA E5 38 B9 87 2D BE 3B 54 CB 00 F4 A6 A3 DC 97 23 FF D0 D1 6B 45 3E B4 C3 68 07 6A ED E5 3C FB 8C 36 F8 ED 49 B3 15 36 1D CF FF D1 B3 C0 A0 E2 BA EC 70 86 29 30 68 B0 8F FF D2 9F 61 34 79 46 BA EC 70 5C 3C 83 4B E4 51 61 5C FF D3 DA 8E D3 DA AD 47 12 A0 E9 5A C5 58 E0 6E E2 93 9A 43 4D 81 FF D4 E8 F6 FB 52 15 1E 95 A9 C2 20 40 7B 0A 1A 25 3D A9 0E C7 FF D5 DC 6B 64 3F C2 29 BF 60 89 BF 84 55 A6 71 95 EE 6C 21 0A 70 2B 0A EF 4D 49 5B 00 54 39 3B 97 13 FF D6 A9 6F E1 C5 63 9D 95 7D 34 14 41 C8 15 B4 51 C9 29 0A 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 14 74 78 C7 61 49 FD 97 10 ED FA 55 D8 8B B3 FF D7 90 69 D1 03 FF 00 D6 A9 A2 84 44 7E 52 6B A5 A4 F4 67 15 CB 0D 2F CB 55 0C CC 1F 39 AE 57 45 26 69 19 9F FF D0 D8 B4 BD CE 03 9A D3 8F 0C 03 29 CF B5 66 E3 6D 0C 93 26 18 14 74 A4 51 FF D1 EA EE 57 CC 42 2B 94 D6 6D 08 C9 C5 63 D4 85 B9 CD 48 A6 36 A7 46 FC 50 CD 0F FF D2 E7 DB 9A 8D 85 60 50 87 A5 46 D4 0C FF D3 E4 4F 06 A4 43 58 16 48 79 15 13 D0 33 FF D4 E4 28 AC 0D 02 96 98 8F FF D5 E4 32 07 7A 6B 4E AB DE B1 28 82 4B E5 51 D7 15 4E 5D 4B FB BC D5 28 89 B3 FF D6 F3 19 2E E4 73 D7 15 09 25 8E 49 26 92 40 25 14 C0 FF D7 F2 AA 72 C6 CD D0 1A 00 95 2C E4 6E D5 66 2D 31 DB A8 CD 04 B6 7F FF D0 E1 62 D2 1C FF 00 0D 5B 8B 46 6F 4A 76 33 6C B7 16 8C C3 B5 5C 8B 48 23 B5 16 25 C8 FF D1 A5 1E 96 07 6A B0 9A 6A 81 D2 B4 48 E6 72 2C 47 A7 FB 54 EB A7 90 38 15 A2 85 CC DC 8F FF D2 BA 2C 9B D2 94 5A 30 FE 1A EC 54 EC 70 73 0E 16 C4 76 A7 AC 44 76 A6 A3 60 B9 FF D3 D8 08 68 D9 5D E9 1E 75 C5 09 8A 70 18 A6 90 AE 7F FF D4 DC C1 A3 69 F4 AF 46 C7 98 D8 D6 87 3D AA 27 B7 3E 94 9C 46 99 FF D5 D0 36 E7 D2 81 05 77 5A C7 9F 71 7C A0 29 76 28 A0 57 3F FF D6 D1 3B 45 26 E1 5D A7 9C 21 71 8A 61 92 95 C2 C7 FF D7 E9 B7 62 8C E6 BA 0F 38 4A 2A 4A 47 FF D0 E9 68 C5 6A 70 A0 C5 25 21 9F FF D1 E9 02 D3 B1 81 56 71 94 EE CF 06 A9 DB C0 1E 40 48 EF 59 3F 88 D1 6C 7F FF D2 EA 21 B6 0A 83 02 92 48 BD AB 7D 91 C2 F5 2B 3C 55 13 45 4E E0 7F FF D3 D4 31 E3 B5 30 AD 74 9C 23 48 A8 A4 B7 0F C8 E0 D2 63 47 FF D4 B3 17 EE CE 1C 1F AD 5F 8A E4 A0 F9 5A AE A4 6E 72 A9 16 62 D4 81 E1 F1 9A B7 1D C2 BE 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 15 30 D5 84 95 8D 93 3F FF D5 EC 0A 86 53 59 3A 9D AE F5 6E 2B 12 0E 37 50 B6 28 C7 8A CE 52 54 E0 D0 68 8F FF D6 E6 D5 F8 A5 3C D6 05 0C 34 C6 A0 67 FF D7 E4 4D 3D 0E 2B 03 42 5C F1 51 3F 4A 00 FF D0 E4 0D 21 60 3B D6 25 8D 69 94 77 A8 24 BB 00 75 A6 90 9B 3F FF D1 F3 B9 75 01 D0 1C D5 59 2E E4 7E 87 15 29 05 C8 4B 16 39 27 34 95 40 7F FF D2 F2 AA 50 A4 F4 06 80 24 4B 69 5F A2 E3 EB 56 23 D3 9D BA 83 40 9B 3F FF D3 E0 A1 D2 8F F7 6A E4 5A 51 F4 A2 C4 36 5B 8B 4C F6 AB D0 69 C0 76 A6 91 0E 47 FF D4 AB 15 8A 8E D5 6A 3B 54 1D AB 45 13 95 C8 B0 B6 EB E9 52 2D BA 8E D5 6A 24 B9 1F FF D5 D0 58 80 ED 52 04 1E 95 D5 CA 70 B6 4A 81 45 59 8D A3 C5 68 A2 43 67 FF D6 EA 14 C4 7D 29 C5 62 22 BD 1B 33 CC 1A D1 27 62 2A 33 08 ED 8A 00 FF D7 E9 3C 8A 43 06 2B D2 48 F3 03 CA 14 86 30 28 48 0F FF D0 E8 76 81 4E 18 AF 4C F2 C5 C8 A6 97 5A 56 03 FF D1 DD 90 83 D2 A0 60 6B D0 68 F3 51 0B 96 15 19 73 50 CA 47 FF D2 B7 B8 9A 50 2B AC F3 C3 6F 14 D2 94 C0 FF D3 E9 48 A3 15 D0 79 E8 5A 5C 52 19 FF D4 E9 F1 45 6C 70 86 28 0B 52 33 FF D5 EA 76 81 4D 6A D1 A3 8C A7 70 33 9A 4B 44 F9 C7 15 92 D6 45 F4 3F FF D6 ED 11 46 CF C2 A0 90 0C 9A D9 9C 44 25 73 4C 31 8A 48 6D 1F FF D7 DF 68 FD AA 26 8A B7 38 48 CC 78 A3 CB A6 07 FF D0 D4 65 53 F7 85 41 28 09 F7 1A B7 96 9B 1C 71 2A 34 EE 1B 91 8F 7A BD 67 75 D3 0D 59 49 26 8D 56 87 FF D1 EA AD EE 54 A7 26 92 E0 2C 8A 71 CD 62 F4 20 E6 75 4B 50 49 38 AE 6E E6 DF 04 90 28 45 23 FF D2 E5 41 20 D4 AA D5 89 42 9E 69 8E B4 86 7F FF D3 E4 98 52 6F 02 B0 34 03 30 C5 31 A7 1E B4 D2 15 CF FF D4 E1 E4 B9 03 BD 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 16 55 96 F4 0E F5 9A 88 DB 2A BD EB 37 4A 84 B3 BF 52 4D 5A 42 3F FF D5 F2 E1 0B 9E D4 F5 B4 73 40 AE 48 B6 0C DD EA C4 7A 59 3F C2 4D 21 5C FF D6 E1 E2 D1 FF 00 D9 AB 71 68 FF 00 EC D1 63 37 22 DC 5A 46 31 F2 D5 A8 F4 AC 7F 0D 55 89 72 3F FF D7 A5 16 9A 07 6A B5 1E 9E 07 6A D1 44 E6 72 27 4B 10 3B 0A 9D 6C 94 76 AB 51 21 C8 FF D0 D0 16 A0 76 A5 F2 42 F3 8A EA B1 C5 71 8D 20 4E C2 90 5D 2F B5 63 2A 96 66 D1 A4 E4 AE 7F FF D1 D3 8A 65 6E F5 60 28 6E 95 D3 4E 6A 48 E2 A9 07 16 2F 94 29 C2 2A DA F6 33 3F FF D2 DF D8 C3 BD 2E 58 0E B5 E8 A6 79 A1 E6 1E F4 A2 4F 73 4E E0 7F FF D3 E9 04 A2 9D E7 0F 5A F4 8F 30 4F 30 1E F4 84 67 BD 35 A0 1F FF D4 E8 8A 1A 4F 2C D7 A9 63 CB B8 79 24 FA D2 18 0D 16 0B 9F FF D5 DF 30 91 49 E5 8F 4A F4 DA 3C BB 8C 68 41 ED 51 3D B7 A0 A9 68 77 3F FF D6 BE F0 11 4D DA 45 76 B4 79 D7 17 06 93 14 86 7F FF D7 EA 29 31 5D 07 9E 85 C5 14 0C FF D0 EA 0D 25 6E 70 A0 14 E1 48 67 FF D1 EA AA 37 3C 56 8C E2 2A 48 79 A9 EC D3 2C 08 AC E2 B5 2F A1 FF D2 ED F6 E1 2A B4 82 B7 67 11 19 14 9B 6A 50 CF FF D3 E9 CA 54 6D 1D 6E 8E 21 86 3E 7A 52 18 FD AA 92 25 9F FF D4 D6 B8 42 07 15 99 3E E5 3C 57 53 89 CB 4C 8B 73 30 F9 C5 3E 36 11 F4 AC DC 7B 1A BB 1F FF D5 B9 1D FE C0 05 59 8E F4 30 EB 8A 53 85 8C 93 20 BA 75 91 48 3D 6B 02 EA 13 E6 1E 2B 25 12 D3 3F FF D6 C0 92 DB 0C 78 A8 8C 45 6B 2B 0D 31 3A 53 19 B0 28 B0 EE 7F FF D7 E3 9E 4A AE F2 9A CD 44 6D 91 34 84 D3 18 BB 74 AB B1 17 3F FF D0 F3 96 81 DF D6 81 63 9E A0 D0 4D C9 53 4F CF F0 D5 88 F4 D3 FD DA 2C 2B 9F FF D1 E3 A3 D2 D8 FF 00 0D 58 4D 28 F1 C5 3B 19 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 17 DC B1 16 94 7F BB 57 A1 D3 71 D5 69 F2 92 E4 7F FF D2 82 3B 14 5E AB 53 AC 11 8E C2 9C A5 CA 60 A2 E4 58 8A DD 58 F4 15 60 59 0C 67 15 A5 3B 49 19 CD 38 9F FF D3 D2 16 C0 52 EC 55 AE 9B A4 71 59 B1 43 28 A7 09 14 52 F6 89 21 F2 36 7F FF D4 D7 F3 94 54 6F 70 31 5A 4A A9 CD 1A 2C CF B9 94 9C E0 D5 16 76 CF 5A E0 A9 51 DC F5 28 D3 49 1F FF D5 8A DE E4 A1 C1 35 AB 6F 76 08 1C D6 14 6A D8 DB 13 46 FA A2 E2 4A 18 53 C3 FB D7 A9 09 A6 79 52 8D 99 FF D6 E8 37 51 9A F4 51 E6 86 28 C7 B5 16 03 FF D7 DF DB ED 46 D1 DA BD 23 CC 00 40 34 E0 E2 80 3F FF D0 E8 FC C1 4E 12 8A F4 91 E5 8E F3 45 1E 68 34 EC 23 FF D1 E9 8B 8A 61 22 BD 33 CA 19 45 00 7F FF D2 DE 65 06 A2 78 C5 7A 32 47 96 99 03 2E 2A 26 38 AC DA 2C FF D3 E9 89 A0 35 75 1E 72 1D 9A 37 52 1A 3F FF D4 E9 F3 45 74 33 80 51 4B 4A C3 3F FF D5 EA 49 A8 A4 3C 56 AC E3 45 57 3C D5 DB 21 92 2A 21 B9 4F 63 FF D6 EE 58 7C B5 59 C6 6B A5 A3 88 66 DA 5D B5 28 67 FF D7 EB 4A D2 14 AE 8B 1C 43 4C 74 9E 5D 52 11 FF D0 E9 5E 00 C3 A5 53 96 C4 13 91 5D 8C E1 52 B1 56 4B 23 E9 50 B5 A1 1D 8D 24 8B 53 3F FF D1 26 42 83 A5 55 37 46 36 EB 5B CD 5C E7 8B B9 6A 29 BC E0 39 CD 4E D6 5E 60 CE 2A 61 4C 52 95 8F FF D2 6C BA 7E 39 C5 51 9A D3 1D AB 57 4E C7 3A A8 52 92 DF 1D AA AC B1 11 59 B8 A3 45 2B 9F FF D3 E3 9E 2A 84 C3 9E D4 AE 4D 99 22 5A 16 ED 53 26 9C C7 B5 34 4B D0 FF D4 E6 93 4A 27 B5 58 8F 49 F6 AA B1 93 65 A8 B4 A1 E8 2A CC 7A 62 8E B8 A3 42 5B 67 FF D5 23 B0 8C 76 A9 85 9C 43 B5 68 9A 39 AC D9 20 B6 8C 74 14 15 55 1C 01 52 E6 35 06 CF FF D6 59 65 DB DA AA B5 C3 67 8A E7 A9 52 E6 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 18 F4 69 25 B9 6A D6 E4 8E A6 AF 8B C0 17 AD 38 55 69 13 5A 8D D9 FF D7 B8 F7 83 D6 A1 7B B1 EB 5C F2 AC 6F 0C 3E 84 26 EC D2 7D AD FB 54 FB 53 45 41 1F FF D0 6F DA 24 6E 80 D1 BA 66 EC 6B 8D B6 CE E5 08 C7 71 A6 29 5B AE 69 3E C8 C7 AD 1E CD B1 FB 48 A3 FF D1 88 DA 30 F5 A6 86 78 8F 7A E2 71 71 3B D4 94 F4 2D C3 7B 8C 0C D5 B8 EE F7 71 9A E8 A7 54 E4 AD 40 FF D2 DC 89 F7 0A 9B 03 AD 76 53 77 47 0C D5 98 71 40 AD 4C CF FF D3 E8 A9 08 AF 48 F3 44 C0 34 6C CD 02 3F FF D4 DF F2 E8 D8 45 7A 47 98 26 0D 2E 29 A0 67 FF D5 DE 24 D3 77 E2 BD 23 CC 13 CC A3 79 A0 2C 7F FF D6 DC C9 34 86 BD 23 CB 1A 53 35 13 41 9A 9B 68 34 CF FF D7 E9 73 45 74 9E 70 66 93 34 0D 1F FF D0 E9 01 A7 83 5D 07 00 EA 28 B0 CF FF D1 E9 CD 45 21 AD A4 8E 24 57 23 2D 5A 56 29 8C 54 C1 5C A9 1F FF D2 EE 64 3C 54 0D D6 BA 99 C2 36 8C 52 B0 CF FF D3 EC 28 03 15 D2 8E 21 71 4C 7C 0A 1B B2 04 AE CF FF D4 EA 1E 41 50 BC 99 AD 9C CE 6E 42 07 6C D4 64 66 9C 67 71 3A 76 3F FF D5 B9 77 1F C9 5C FD E2 7C FC 57 44 D9 C9 02 E6 97 19 C8 CD 74 90 C7 FB B1 C5 6D 49 5C CE AB 3F FF D6 D9 96 00 47 4A A1 71 6B 9E D5 DF 38 9E 7C 19 97 73 68 C3 38 15 9F 2C 27 38 22 BC FA 8E C7 7D 28 73 23 FF D7 E7 9A D8 1E D4 8B 69 CF 4A E7 73 3A 15 22 DC 16 A0 76 AB B1 C0 A2 9C 6A 19 4E 91 FF D0 8D 63 51 DA 9E 02 8A C7 DA 14 A8 8E 12 28 A3 CF 1E D5 3E D0 B5 40 FF D1 5F B5 01 47 DA F3 5C CE A9 D0 A8 07 DA 98 FA D3 1A 67 6E 80 D4 39 B7 B1 A2 A4 91 FF D2 85 B7 BF 6A 67 D9 D8 F6 AE 1B 36 7A 0B 96 21 B1 92 94 3B 1E F5 2D B4 52 57 D4 FF D3 89 06 7A D4 CB 00 6E D5 C1 08 DC F4 25 3E 51 C2 CF 9E 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 19 95 66 2B 10 7B 56 F1 A2 61 2A FA 1F FF D4 D6 4D 3C 7F 76 A5 16 03 D2 9C 28 91 3C 40 1B 25 1E 94 9F 65 51 5B 2A 46 32 AC D9 FF D5 D8 92 D9 08 E2 B3 EE 6D 97 9A 2B 41 22 28 55 77 29 18 00 3D 6A CD BA 05 35 CB 1D 19 DF 39 B7 13 FF D6 D4 8E 65 41 52 0B B0 4E 33 5A 46 AE 86 12 A4 E4 EE 4F 1B 87 14 FC 71 5D 50 95 D1 CD 38 F2 BB 1F FF D7 E8 68 CD 7A 47 98 1C 53 81 14 20 3F FF D0 E8 F7 0A 46 22 BD 33 CB 1B 45 00 7F FF D1 DE 6A 66 2B D2 3C B0 00 52 85 14 90 CF FF D2 DD E0 51 91 5E 91 E5 07 14 12 28 03 FF D3 E8 C5 3A BA AC 79 C1 46 29 58 68 FF D4 E8 F1 4E 07 15 D2 70 0E 06 94 50 07 FF D5 E9 D8 F1 55 A4 7C 56 B2 38 D0 D8 46 E6 15 AF 68 98 02 9C 10 33 FF D6 ED E5 A8 8D 75 B3 84 40 28 C5 21 9F FF D7 EC 68 02 BA 8E 11 6A 19 8E 33 53 3D 8A 86 E7 FF D0 DE 76 25 B0 29 84 13 53 7D 46 96 83 1C 1C 50 82 9C 5B 4C 99 25 CA 7F FF D1 D5 B9 4C A5 60 DD 43 99 2B 67 AA 39 23 B9 A1 A6 5B 72 38 AE 82 28 C0 41 80 2B A6 96 C6 55 35 67 FF D2 E9 9A 2A 85 ED C1 15 E9 4B 53 CC 5A 14 E7 B2 0D DA B3 A7 D3 C7 3C 57 9F 5A 07 A1 87 A8 8F FF D3 AA F6 7B 7B 54 7E 50 1D AB 82 69 A6 7A 30 6A 43 95 76 F4 A7 EE 6A 95 2B 0D C1 33 FF D4 85 15 D8 F5 A9 85 A3 B7 73 5C 31 4E 47 A1 26 A2 06 C9 BD E8 16 67 3D 2B 45 4D 99 FB 64 7F FF D5 7A D9 67 B5 4E 9A 78 F4 AE 78 D2 3A 65 5C 94 69 FE D4 EF B0 7B 56 CA 89 84 AB 9F FF D6 D2 FB 0F FB 34 A6 CB 8E 94 7B 22 5D 72 A5 C5 A7 5C 0A A4 60 20 D7 2D 4A 76 3B 68 55 4D 1F FF D7 74 31 01 D7 9A D1 85 63 C6 2B 9E 8A 57 D4 DB 11 26 D6 85 81 0A E3 20 53 95 D5 0F 4A EF 49 58 F3 5B 6D D8 FF D0 E8 16 ED 47 1C 50 D7 8B 5B FB 55 13 91 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 1A 52 6C 85 EF 47 AD 40 F7 A3 D6 B1 95 63 78 61 CF FF D1 B8 D7 A3 D6 A0 92 E8 37 7A C2 55 6F B9 B4 30 F6 D4 AE D2 AD 20 B8 DB D2 B0 52 3A 7D 9D CF FF D2 88 DD 31 E9 9A 12 E2 4C F7 AE 3E 76 77 FB 24 6A 59 DC 33 63 39 AD 24 7D C2 BD 1C 34 9B 47 93 89 85 A5 A1 FF D3 E8 4D 25 7A 27 9A 02 96 9A 11 FF D4 E8 71 49 8A F4 CF 2C 50 29 4E 31 40 1F FF D5 E8 08 A6 95 AF 4C F2 C6 D1 48 0F FF D6 DC EB D6 93 15 E8 9E 58 B8 A6 9A 77 11 FF D7 E8 C1 A5 CD 75 2F 23 CD 14 1A 5A 06 7F FF D0 E9 69 00 AE AB 1E 78 F5 14 B4 58 77 3F FF D1 E9 25 6C 0A A4 EC 58 E2 B5 91 C7 12 D5 A4 64 F3 5A B1 65 16 B4 8A 13 DC FF D2 ED 5D B3 51 E6 BA CE 10 06 97 34 80 FF D3 EC 69 6B AC E1 10 F4 A8 26 E9 51 3D 8A 86 E7 FF D4 DC 6C EE A5 C7 15 36 D4 6D E8 35 86 69 AB 9A 16 E2 7B 1F FF D5 D7 98 E5 2B 31 E3 0D 27 E3 5A 5C E5 EA 6B 69 D0 63 1C 56 C2 C6 02 F4 AE B8 6C 63 2D CF FF D6 EC 19 2A 26 51 5E 8B 3C D4 44 C8 0F 6A 82 58 03 56 73 8A 91 71 93 4C FF D7 D1 9A D3 DA A8 4D 6B ED 59 D6 A4 5D 0A C5 56 42 B4 D0 6B 86 4A CC F4 62 EE 8F FF D0 5B 6F 98 D6 BD BC 40 8E 71 5C D4 15 CE 8C 4C AC 89 CC 11 E3 B5 46 62 8C 57 75 91 E7 73 C9 9F FF D1 D9 FD DA 9A 91 65 8C 7A 56 A9 A4 73 5A 4C 79 B8 41 E9 51 B5 DA 8E 95 2E A2 48 71 A4 D9 FF D2 D9 17 1B 8F 5A 99 3E 6A D2 9C EE CE 7A 94 F9 50 92 C0 1A A8 4F 6E 06 69 57 85 D5 CA C3 D4 B3 B1 FF D3 94 B0 8C E3 8A 92 3B A0 0E 2B 91 4B 95 9D 6E 9F 3A 2F 43 38 6A 59 B9 19 15 D9 4E 77 47 9F 52 9F 2C 8F FF D4 B5 33 BA 9A AC F7 32 03 DE B3 AA DA 36 A3 18 B2 23 70 E7 D6 98 65 63 DE B8 E5 26 76 C6 29 1F FF D5 AC 09 63 8C D4 A9 6E CF D2 BC D8 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 1B AE 66 7A 92 92 8A 25 16 2C 7B 1A 7A E9 E7 D2 BA 23 44 E7 95 74 8F FF D6 B0 BA 77 B5 4A BA 77 B5 44 68 97 2C 41 66 1B 4F 2C D5 A5 01 47 26 BA A9 A5 04 71 D5 93 9B 3F FF D7 DD 79 95 6A 06 BA 03 B8 AE 99 D6 47 24 68 B6 22 DD 82 78 35 3C 72 6E A7 4E A5 C5 52 93 89 FF D0 E8 32 69 32 6B D3 3C B1 73 46 68 03 FF D1 E8 32 69 30 6B D3 3C B1 0A 9A 69 53 45 80 FF D2 DB E6 93 35 E9 33 CB 0C D2 13 48 0F FF D3 E8 32 69 73 5D 47 9C 38 1A 70 34 C0 FF D4 E9 09 A0 1A EA 3C F2 40 45 29 3C 53 03 FF D5 E8 27 7E B5 51 0E 5E B6 7A B3 89 1A D6 48 38 AB AC 00 1C 56 D1 D8 96 7F FF D6 EC 9A 98 4D 76 33 80 4C D2 E6 90 CF FF D7 EB F3 CD 28 35 D6 70 05 41 31 A8 9E C5 C3 73 FF D0 DF 38 CD 19 18 A5 D4 91 84 8E 94 D1 C5 24 F5 1B 5A 1F FF D1 D5 9C F0 6A 8C 60 B4 9F 8D 68 BA 1C A6 E5 80 C6 3A 56 89 95 40 EB 5D 29 A4 8C AC D9 FF D2 EB A4 9D 7D 6A 06 B8 5C 57 63 A8 91 C2 A9 B6 44 D7 2B 51 B5 C8 F5 15 93 AA 68 A9 33 FF D3 D8 7B 80 7A 9A AB 2B 02 0E 2A 6A 4E E4 53 A6 E2 CA 13 72 4D 56 23 06 B8 26 CF 52 9A D0 FF D4 86 36 2A 72 2A EC 37 85 7A 9A E2 A7 3B 1D D5 61 CC 4C 75 01 8E B5 1B 5F 82 7A D6 EE AE 87 32 A0 7F FF D5 95 AF 47 AD 30 DE 9F 5A E4 75 4E C8 D0 10 DF 1F 7A 61 BC 63 59 BA 86 B1 A2 91 FF D6 96 D6 66 66 E4 D6 C5 B1 C8 AC B0 D2 6D 9A E3 52 48 99 8D 55 9E 3D C3 8A EE 9C 6F 13 CD A7 2B 48 FF D7 96 E2 03 93 54 CE 50 D7 15 68 34 EE 7A 14 2A 29 22 CD B5 D6 0E 33 5A 70 CE 18 76 AD 30 F3 E8 73 E2 A9 F5 47 FF D0 D8 78 43 8E 2A B4 96 80 9E 95 52 87 32 32 A7 51 C7 42 B4 B6 A0 76 AA 52 C7 B4 D7 0D 58 58 F4 A8 D4 E6 3F FF D1 AF 19 19 E6 B5 2D 15 78 E4 57 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 1C 05 2D CE FC 46 91 34 95 22 C0 E9 4A 4C 4B E9 5E 9C 5A 48 F1 DF 33 67 FF D2 DD 33 46 3D 2A 36 BB 45 AD 1C D2 39 D5 36 C8 5E F9 47 7A 85 EF C7 AD 61 3A A7 4D 3C 39 FF D3 92 4B EC F4 35 5D EE 99 AB 8A 55 59 E8 42 8A 48 21 B8 6D C3 35 AD 6B 30 20 1A DF 0D 51 DC E6 C5 D3 56 D0 FF D4 E8 57 69 A7 6D 15 E8 C5 E8 79 8D 0B B0 52 6D 15 42 3F FF D5 E8 C8 14 86 BD 33 CB 13 22 93 22 90 8F FF D6 DF 20 1A 61 02 BD 26 79 68 6E 29 0D 20 3F FF D7 E8 76 D0 05 75 9E 68 A0 52 81 40 CF FF D0 E9 31 40 15 D7 63 CF 0E 69 19 F0 28 60 7F FF D1 D6 B8 9A A2 85 B2 E2 B4 EA 72 25 A1 B5 66 E0 2D 5A 32 03 5D 11 D8 CD 9F FF D2 EC 49 CD 34 D7 6B 38 04 02 94 0A 40 7F FF D3 EB F1 4A 05 76 1C 02 B2 F1 55 66 18 15 13 5A 17 0D CF FF D4 DC 62 72 69 A5 A9 05 86 93 4A 1F 02 92 07 B1 FF D5 D1 99 B2 0D 43 00 CB D6 91 EE 73 3D CD 6B 73 81 4C 9E E8 A1 E4 D4 D4 9F 2A B9 AD 1A 7C CE C7 FF D6 D2 92 FB DE A0 7B EF 7A E6 95 63 AA 38 62 06 BD 39 EB 4C 37 8C 6B 3F 6A CD 55 04 7F FF D7 69 B9 73 47 9C E7 D6 B8 39 DB 3D 1F 67 14 35 8B 37 6A 67 94 C7 B5 43 4D 96 9A 8A 3F FF D0 88 44 FD A8 F2 5F D6 B8 14 59 E8 B9 A0 F2 18 F5 26 8F B3 9A AE 52 7D A2 47 FF D1 68 B6 CF 5A 71 B6 00 57 0B 82 3B FD A9 13 C4 05 45 8C 56 4D 1A C5 DC FF D2 6D BB ED 35 AB 05 CE 05 72 D0 97 29 D3 89 87 31 37 DA 47 AD 21 B8 04 73 5D BE D8 F3 FD 89 FF D3 D0 9D 81 04 D6 6C F8 39 AE 6A CE EC DF 0D 1B 15 79 07 8A B5 6F 72 CA 71 5C F0 95 99 DB 52 1C D1 3F FF D4 D1 82 E7 35 71 59 5C 56 94 66 A4 8C 2B C1 C5 8C 92 30 C3 81 59 F7 16 F9 CF 14 55 A6 AC 3A 15 5A 7A 9F FF D5 86 58 4A 1C 8A 74 37 4D 1F 04 D7 13 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 1D 4E 12 3B D3 F6 91 2C 8D 47 8E B4 D6 D4 09 EF 5A 7B 5D 0C 55 0D 4F FF D6 6B 5E B9 A8 9A E5 CD 71 3A 8D 9D F1 A4 90 CD F2 37 73 4A 11 CD 4B BB 2D 5A 27 FF D7 84 5B B9 A9 16 CD 8F 6A E2 8D 2B 9E 84 AA A4 4C 96 47 D2 AE 41 11 4C 0C 57 65 1A 5C AC E1 AD 5B 99 58 FF D0 DB 56 2A 29 FE 61 AF 42 28 F3 A5 B8 A2 4C D3 B7 1A B4 41 FF D1 E8 72 4D 21 06 BD 4B 1E 50 85 4D 37 69 A4 33 FF D2 DE 0A 68 2A 6B D3 B1 E5 0D 28 69 A5 71 49 A0 B9 FF D3 E8 73 4B 5D 96 3C D0 A7 03 40 1F FF D4 E9 69 46 2B B6 C7 9C 1C 62 A2 97 A1 A9 90 D1 FF D5 BF 70 79 E2 92 DD 4E EA D1 2B B3 93 A1 AD 03 15 5A 98 49 5D 09 68 66 7F FF D6 EA FC CA 3C CA EC 38 00 49 4E 0E 29 A0 3F FF D7 EB 83 D3 D5 85 76 9C 03 F8 C5 55 9C 8A 89 EC 5C 37 3F FF D0 DD 7C 66 A3 35 0C 69 09 DA 9B D4 50 0D 68 7F FF D1 D0 94 64 62 A1 87 E5 7E 6A E3 B1 CF 2D CD 68 14 B0 E2 A2 BA B4 66 A9 AB 16 D5 8D B0 F5 14 59 FF D2 B2 F6 8C 0F 35 11 B5 15 E7 BA 76 67 A3 ED 57 40 FB 28 A3 EC EB E9 54 A2 91 2E AB 3F FF D3 94 42 A3 B5 3C 44 A3 B5 71 A4 75 B9 B0 F2 D7 D0 51 B4 7A 53 26 ED 9F FF D4 B1 80 29 08 15 C8 74 09 C5 1C 53 15 99 FF D5 9F 72 8E F4 8C EA 47 5A E4 3A 54 59 04 86 A0 61 ED 58 49 1D 50 D8 FF D6 AC 0E D3 9A 95 6E 08 F5 AF 36 32 B1 EA 4A 3C C4 82 E9 FD E8 FB 4B 8E C6 B4 E7 66 7E CD 1F FF D7 43 74 DD C1 A8 5E 5D D5 C1 27 73 D1 8C 12 64 59 A5 07 06 B2 36 BE 87 FF D0 4B 7B 8C 1E 6B 56 DA 60 C0 57 35 09 D9 9B E2 A9 DD 5C B8 00 61 91 4D 92 00 C3 A5 7A 89 73 44 F2 7E 16 7F FF D1 D0 B8 B5 EB C5 66 5C 5B 90 7A 54 56 A6 5E 1A A9 54 82 A7 06 85 39 AE 06 AC CF 45 6A 8F FF D2 86 34 06 AC C7 6A 1B 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 1E B5 71 53 8D CE EA 93 E5 26 4B 2F 6A 99 2C F1 DA BA E3 44 E3 96 20 FF D3 D5 5B 40 3B 54 AB 6E A3 D2 B6 8D 24 8E 79 D7 6C 90 46 83 D2 8F 92 B5 51 49 18 37 29 1F FF D4 DC 67 41 E9 51 F9 AB 5D 6E A2 D8 E1 54 D9 22 30 35 20 C5 6B 17 73 39 2B 1F FF D5 E8 41 02 9C 0A 9A F4 D3 3C A1 78 A3 02 80 3F FF D6 E9 30 28 C0 AF 54 F2 46 9C 62 9A 40 A4 23 FF D7 E8 33 40 35 DA 8F 30 70 A5 14 0C FF D0 E9 29 D8 AE E3 CE 10 F0 2A B4 F2 60 1A 89 EC 34 7F FF D1 BC C7 73 55 CB 58 37 60 D6 F1 57 67 1B 76 45 E1 16 D1 D2 90 A9 15 B3 33 3F FF D2 E9 4E 45 26 4D 76 1C 01 BA 8D E6 90 1F FF D3 E9 7C CC 52 89 B1 DE BA EE 70 0A 6E 38 AA F2 5C 66 B3 A8 CD 69 AD 4F FF D4 D7 F3 72 4D 2E 73 59 96 07 91 48 A3 9C 50 B7 07 B1 FF D5 D6 68 F3 55 CA 6D 7A D2 07 34 F7 35 AC 30 40 06 AE 4D 08 29 5A CD 5D 13 07 66 7F FF D6 DA B8 8F 6B 1C D5 46 DB 5C B3 D1 9B C3 55 A1 19 65 1D E9 86 45 15 17 34 51 6C FF D7 9F CD 5A 4F 3C 7A 57 15 CE DF 66 C4 F3 8F A5 21 91 8F 6A 07 C8 8F FF D0 76 F7 34 9F 39 AE 33 BA D1 40 15 8F 73 4A 23 63 DE 99 37 47 FF D1 90 45 EF 4E F2 85 72 9D 7C E2 18 C0 15 0C 8A 2B 39 15 09 33 FF D2 AC C3 06 85 E4 D7 96 CF 57 A1 6A 18 B7 62 AD AD 98 61 D2 BA 29 46 E7 2D 6A 9C A7 FF D3 B4 D6 3C F4 A8 9A C7 DA B1 74 8D E3 5C 89 AC F1 DA A2 92 DF 1D AB 19 52 B1 B4 6B 5C FF D4 A9 CA 1A BB 6B 71 8C 02 6B CF 8B E5 91 E9 54 8F 34 4D 6B 7B 80 40 E6 AD 86 0E 2B D6 A1 3B A3 C4 AF 4D A7 73 FF D5 E8 5D 03 0E 95 4A E2 D4 1C F1 5D 55 21 74 71 D2 9F 2B 32 EE 6D 71 9E 2A 91 52 87 9A F2 6B 46 CC F6 E8 CF 99 1F FF D6 86 09 06 70 6B 4A DE 45 AE 5A 2D 1D 38 98 BB 17 56 64 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 1F C7 6A 43 70 A2 BB D4 D5 8F 33 D9 B6 CF FF D7 D5 6B B0 3B D3 0D E0 F5 AD 25 58 C6 34 19 1B 5E 8F 5A 85 AF 8F AD 61 2A E7 4C 30 E7 FF D0 7B DF 1F 5A 6A DE 12 DD 6B 95 D6 D4 EC 58 75 62 FD BC FB 85 5D 53 91 5E 96 1E 77 47 95 89 87 2B 3F FF D1 DB 24 8A 4D E6 BD 3B 9E 65 87 09 0D 38 49 46 C2 3F FF D2 DF F3 28 DE 6B D3 3C A1 85 CD 34 B1 A0 2C 7F FF D3 DF 34 03 5D A7 98 38 1A 50 69 8C FF D4 E9 41 A7 57 79 E6 8C 90 F1 59 F7 2F 8C D6 53 2E 3B 9F FF D5 B5 11 2C F5 B7 62 BC 0E 2B A6 9A 38 A6 5D 2B C7 4A 89 D6 B6 68 CC FF D6 E9 D8 53 48 AE D3 CF 13 14 86 A4 67 FF D7 E8 8D 46 D5 D6 CE 02 37 62 07 5A AE EE 6B 0A 86 D4 B7 3F FF D0 BC 8D 53 2B 56 68 A6 C7 6E A5 57 15 49 0A FA 1F FF D1 DA DC 31 55 DC FC D5 AC 1E 87 2C F7 34 6C 18 71 5A 8C 7E 4F C2 B6 7B 11 1D CF FF D2 E8 2F 62 76 27 02 B2 5E 09 09 AC 2B 46 EC EA A1 38 A8 89 F6 56 EE 68 16 95 92 89 A3 A9 D8 FF D3 B5 F6 60 28 F2 54 76 AE 3B 58 EC 73 61 B0 7A 52 6D 14 68 4D D9 FF D4 B0 40 A6 F0 2B 90 E9 0D C2 8D C2 95 C2 C7 FF D5 9B CC 03 BD 06 65 1D EB 8E E7 52 83 63 1A 75 F5 A8 1E 40 7A 54 49 9A C2 0D 1F FF D6 AA 72 4D 00 E0 D7 94 CF 5D 17 2D 65 19 19 AD 7B 77 52 2B B2 83 47 9F 8A 8B 3F FF D7 E8 B6 23 73 8A 63 5B A9 ED 5D 51 82 68 E2 73 69 91 B5 B0 3D AA B4 F6 9C 74 AC EA 53 D0 D6 9D 56 99 FF D0 2E 2D 48 3D 2A A0 CA 35 71 55 8F 2B 3D 0A 33 E6 89 72 DE EF 18 04 D6 A4 17 3B B1 CD 6D 42 A6 A7 2E 26 97 53 FF D1 E8 A3 21 85 24 80 11 5D 89 DE 37 38 2D 66 65 DE 2E 33 C5 65 4D D6 BC 9C 42 B3 3D 9C 2E C7 FF D2 A2 38 E9 53 C5 39 5E B5 E6 46 56 67 AD 38 73 22 6F B6 71 D6 98 D7 A7 D6 B7 F6 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 20 A7 3F B1 47 FF D3 AC D7 64 D3 4D C3 1E F5 C2 EA 1E 8A A6 90 D3 33 1E F4 9B C9 EF 59 B9 36 5A 48 FF D4 A0 5A 94 3E 2B CB 67 AF 72 F5 A5 C6 08 AD 9B 79 03 0A F4 30 92 3C BC 6C 3A 9F FF D5 E8 36 83 4D 31 F1 5E 9D AE 8F 2F 61 BE 5E 29 42 50 17 3F FF D6 DE DB 46 DA F4 CF 28 36 52 6C A6 23 FF D7 DE A5 15 DA 79 82 8A 51 55 60 3F FF D0 E8 C1 E2 97 76 2B BC F3 46 48 DC 56 7D C5 65 32 E2 7F FF D1 BB 6E BF 3D 6E D8 A7 02 BA E9 23 86 65 C6 1C 55 79 3A D6 AD 19 9F FF D2 EA 4D 34 8A EE 3C E1 B8 A0 8A 43 3F FF D3 E9 31 4D 64 AE C6 8E 02 19 13 8A A9 22 D7 3D 43 6A 47 FF D4 B8 B4 F5 27 35 08 6C 71 27 14 81 8E 71 4C 48 FF D5 D2 32 60 54 4F 27 35 A4 4E 79 2D 4B 96 32 E0 8E 6B 72 19 43 27 26 BA 23 B1 8B DC FF D6 EA EE B1 CD 66 C8 06 69 56 D0 54 1D D1 19 C0 A4 2C 2B 91 B3 A9 45 B3 FF D7 D1 2E A3 BD 46 65 5F 5A E0 72 3B A3 4D B1 86 51 51 B4 DE 82 A3 9C D1 51 3F FF D0 56 95 8F 41 4C 2C E7 B1 AE 05 23 D1 F6 69 6E 27 EF 3D 29 44 72 35 3B 36 1E EA 3F FF D1 41 03 9F 5A 70 B5 63 EB 5C 4A 0C F4 5D 44 85 16 64 D3 85 9D 3F 66 66 EB 1F FF D2 7B DB 00 2A B4 91 E2 B8 27 14 7A 14 E6 D8 C4 62 87 35 7E DE EB 1D E9 52 95 99 55 A1 CD 13 FF D3 D6 86 EB 81 CD 59 59 C1 15 74 EA E8 63 56 93 4C 90 3A 9E F4 AD 18 65 AE 94 D4 8E 66 9C 4F FF D4 D6 B9 B6 C8 3C 56 45 CD BE 09 AC F1 10 34 C2 D4 29 E4 A1 AB B6 B7 18 C7 35 C5 4E 5C B2 3B EA C7 9A 27 FF D5 D5 82 E8 63 AD 4E F3 82 3A D5 42 AE 86 73 A5 69 14 AE 24 DD 9E 2B 36 68 B3 CD 72 D6 7C C7 6E 1F DD 3F FF D6 A6 CA 47 5A 43 5E 53 56 67 B0 9D C6 9A 43 42 60 CF FF D7 CE C1 A3 07 D2 BC C3 D5 62 84 63 DA 9E B0 B1 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 21 ED 54 A2 D9 2E 56 3F FF D0 AC 2D 58 D0 D6 AC 05 71 46 95 CE F7 55 21 B1 A3 A3 8A DB B2 CE D1 5D 78 78 72 B3 8B 13 51 49 1F FF D1 E8 53 A5 38 D7 A6 B4 47 96 F7 1A 45 25 31 1F FF D2 DF CD 1B 85 7A 67 94 05 C5 34 C8 28 15 8F FF D3 DD CD 28 3C D7 71 E6 0E 14 E1 8A 68 0F FF D4 E8 A8 35 DE 79 A4 52 1A A5 39 AC A6 5C 4F FF D5 BD 6E 7E 6A DF B1 1F 28 AE CA 47 04 CB 6F 8C 55 49 0D 6A C8 3F FF D6 EA 09 A2 BB CF 38 6E 29 71 48 67 FF D7 E9 F1 91 49 B6 BB 4F 3C 8E 45 AA 72 AD 61 55 1B D2 3F FF D0 BE 05 28 E2 B3 45 0B 49 8A A4 49 FF D1 BE C3 8A AF 2F 5A A8 98 4B 72 CD 99 20 8A D8 85 C8 5A E9 86 C6 32 DC FF D2 E9 27 94 80 6B 32 79 9B 3C 53 AE 83 0E 54 6B 87 F5 A8 DA 76 3D EB CE 93 3D 38 A4 7F FF D3 43 2B 1E F4 06 CD 79 0D B3 DA 56 44 B1 A8 6E B5 6A 3B 60 C3 A5 6B 4E 17 32 A9 52 C8 FF D4 D4 16 20 F6 A5 FB 08 1D AB 08 53 56 37 9D 6B B0 FB 1A 8E D4 7D 9D 47 6A B7 14 8C B9 DB 3F FF D5 D5 F2 C0 A4 2A 05 61 73 45 76 26 05 21 23 15 0E 45 A8 B6 7F FF D6 B9 2E 31 54 A5 E7 35 E7 4E 47 A1 4A 05 72 B4 2B 15 E6 B1 4E C7 53 57 3F FF D7 64 77 3B 6A D4 77 9D 39 AE 1A 75 0F 42 A5 1B 9A 16 D3 6E 03 9A BF 19 C8 AF 52 83 BA 3C 8A F1 B3 3F FF D0 E9 A4 8C 38 AC CB BB 6E 0F 15 D1 5E 17 47 36 1E 7C AC C7 B9 83 69 E9 8A 81 58 A9 AF 1A A2 E5 91 EE D3 7C D1 3F FF D1 8E 3B A2 BD EA 75 BC 3E B5 C1 1A 96 3D 19 D1 4C 9E 23 E7 54 8D 6D 91 9C 57 4C 63 CC AE 71 CE 5C 8E C7 FF D2 7C F6 A7 D2 AA 35 BB 74 AE 2A 94 DD CF 42 95 54 D0 0B 66 35 22 D9 13 D8 D4 46 9D CB 95 54 8F FF D3 12 C0 9E D5 32 E9 FE D5 CF 1A 27 54 AB D8 91 34 FF 00 6A 9D 2C 00 ED 5D 10 A2 72 CE 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 22 B9 FF D4 D7 5B 11 8E 83 14 4B 63 C7 4A E8 8D 23 9A 55 D9 5C 58 80 DD 2A E5 BD B8 41 5A 46 9A 4C C6 75 1C 8F FF D5 E9 31 8A 63 30 AF 51 1E 50 C2 F4 C2 FE F4 AE 3B 1F FF D6 D9 32 53 4C B5 E8 DC F3 2C 34 CB 51 B4 B4 AE 34 8F FF D7 D9 DD 4A 1A BB 11 E6 8E 57 A7 87 E2 A9 05 8F FF D0 E8 37 52 16 AE E3 CD 22 91 AA 8C FC D6 53 34 89 FF D1 B7 6D F7 C5 74 16 27 81 5D 94 8E 19 97 1C F1 55 24 3C D6 AC CD 1F FF D2 EA 29 2B B8 F3 82 96 9D 86 7F FF D3 EA 29 2B B8 F3 C8 E5 AA 52 9A E6 AA 6F 48 FF D4 BE 4D 19 AC D1 40 4D 26 FC 53 EA 23 FF D5 BA CF 91 4C 09 BD AA E3 A9 84 B4 2F 5A C0 72 38 AD 15 4D 8B 5D 0B 44 60 F5 67 FF D6 DF 9D B8 35 9B 39 06 95 67 A9 34 13 45 39 2A 06 38 AE 19 1E 8C 19 FF D7 AF BE 81 26 2B CB B1 EA DC B1 0C B8 AB F0 4E 30 39 AE 8A 68 E5 AC D9 FF D0 DA 4B 85 1D E9 C6 E9 3D 6A 2F 64 25 16 D9 1B DD 20 EE 2A 17 BA 51 DC 56 12 9D 8E 98 51 B9 FF D1 B8 F7 63 D6 A2 6B BF 43 5E 7B A9 63 D1 8D 14 30 DD 13 48 67 26 B2 73 37 54 92 3F FF D2 53 23 1E F4 D2 33 5E 33 93 67 B8 A2 90 D2 94 C6 4A 49 8D A3 FF D3 AA 56 A4 86 32 58 57 93 1D 59 EC C9 D9 1B 36 51 B0 02 B5 62 5C 0E 6B DB C2 A7 63 C1 C5 4B 5D 0F FF D4 EA C8 A8 27 88 30 CD 77 CD 5D 1E 7C 5D 9D CC 8B BB 7E B5 93 2C 65 0D 78 B8 98 59 9E EE 16 77 47 FF D5 A4 2A 78 22 2E 45 79 51 57 76 3D 89 3B 2B 9A F6 96 D8 03 8A D0 58 46 DE 6B D8 C3 C3 43 C4 C4 D4 BB 3F FF D6 DA 9A 11 55 1A 25 F4 AA AB 1B 11 46 6E C0 23 5C F4 15 34 51 29 AC A2 93 66 93 93 B1 FF D7 DF 4B 75 F6 A9 04 2A 2B AE 14 8E 29 54 62 88 D4 53 B0 A0 56 CA 09 19 39 36 7F FF D0 E9 77 81 48 64 18 AF 52 CA C7 96 44 C5 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 23 73 40 94 0A 91 9F FF D1 DE 32 D4 4D 26 6B D2 6C F3 2C 30 B9 A6 96 A9 19 FF D2 D1 24 D3 49 35 DE 79 C2 1A 4C 52 03 FF D3 D5 E6 97 26 BA CF 38 50 69 C1 A9 81 FF D4 DA 0D 41 7A EC B9 E7 D8 8A 47 AA 92 B0 35 94 D9 71 3F FF D5 B9 6B CB 0A DE B3 1C 0A ED A7 B1 C1 32 DB F4 AA CE BC D6 AC 84 7F FF D6 EA 0D 25 77 D8 F3 80 51 9A 00 FF D7 EA 29 2B BC F3 88 A5 EF 54 A5 AE 5A A7 4D 13 FF D0 BE 05 04 56 68 B2 27 6D B5 01 97 9E B4 81 1F FF D1 99 18 93 57 2D E3 CE 0D 6B 48 E7 AA 6A DB 44 05 4B 36 00 35 BC B6 31 8E E7 FF D2 D7 BB 93 6E 6B 2E 5B 8C 9A C2 BC AC CE 8C 3D 3B A2 BB CB 9A 89 98 9A E4 72 3A D4 2C 7F FF D3 A5 9A 09 AF 30 F5 6C 02 42 BD E9 EB 72 CB DE B4 8C 8C E5 1B 9F FF D4 80 5F 37 BD 3B ED AC 7B D7 1B 99 DF 1A 69 09 F6 A6 3D E9 3C E2 7B D6 0D B6 6E AC 8F FF D5 83 79 34 6E AF 26 C7 B3 CC 01 8D 3B 26 8E 50 E6 3F FF D6 8C 13 4E 19 AF 23 90 F6 B9 D0 BC D3 5B E9 53 CB 62 B9 91 FF D7 8B 6E 4D 5F B4 B7 CE 38 AF 32 8C 6F 23 D4 AF 2B 44 D8 B6 87 03 A5 5B 0B 81 5E F5 08 D9 1F 3D 5A 57 91 FF D0 EB 71 4D 61 9A F4 AD 73 CD 29 5C C3 90 78 AC 7B AB 62 73 81 5E 66 26 17 3D 3C 25 4B 1F FF D1 89 6D 58 9C 73 5A 56 96 98 C7 15 C5 4A 93 72 3D 0A F5 52 89 AF 04 01 40 A9 59 70 2B D9 A5 1B 23 C2 AB 2B B3 FF D2 DD 9D B1 9A CC B8 B8 D8 6A F1 04 61 A3 72 BF DB B0 6A D5 BD E6 71 CD 72 D3 9E A7 65 5A 3E E9 FF D3 DC 8A E4 60 73 53 79 A0 F4 35 DB 4A 6A C7 0D 48 59 88 64 A6 19 7D EB 64 CC AC 7F FF D4 DA 69 4F AD 34 CA 6B D2 B9 E6 D8 61 73 49 B8 D4 8C FF D5 D4 24 9A 33 5E 85 CF 38 4C D2 16 14 AE 07 FF D6 D1 DC 29 32 2B B8 F3 AC 27 14 71 45 C4 7F FF 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 24 D7 D8 DB 4B B6 BB 6C 79 A2 15 A4 C5 16 19 FF D0 D6 34 D6 6C 57 5B 3C F4 56 96 5C 55 57 97 26 B1 6C D6 28 FF D1 B9 64 72 45 6F DA 74 15 DD 4F 63 82 7B 96 5C 8A 81 CD 68 C8 3F FF D2 EA 4D 34 D7 A1 63 CE 13 A5 2D 20 3F FF D3 E9 F1 45 77 B3 CE 21 94 D5 49 07 35 CB 51 1D 34 B4 3F FF D4 D3 C7 14 D7 38 15 1B 0C A5 33 F3 55 F9 2D 49 6E 51 FF D5 9E DE 3C E2 B5 6D A2 C6 2B 7A 27 35 63 46 2F 94 03 4D 9B 91 5B B5 A1 8C 5E A7 FF D6 D7 BA 8B 76 6B 26 6B 72 0D 61 5E 17 3A 30 D5 34 B1 5D A2 23 B5 37 61 F4 AE 37 16 77 29 26 7F FF D7 AB E5 9F 4A 0C 4C 7B 57 98 93 3D 66 D0 9E 43 1E D4 7D 99 AA D4 59 9B 92 3F FF D0 AA 2D 5A 9C 2D 5B DE B8 54 19 E8 39 A1 C2 D5 BD 0D 3D 6D 4F A5 57 B3 27 DA D8 FF D1 05 A1 3C E2 9E 2C CF A5 72 AA 47 5B AC 3D 6C 8F A5 3C 59 1F 4A B5 44 87 5C FF D2 B5 F6 32 07 22 94 5A FB 56 4A 8A 36 FA C0 BF 66 F6 A8 DE DB DA A2 54 0B 86 20 FF D3 9E 3B 6F 9B A5 69 5B 43 8C 71 58 D1 A5 66 6D 5E B5 D1 A3 1E 00 A9 03 57 AB 05 64 79 32 77 67 FF D4 EB 89 14 D6 35 E9 DC F3 48 A4 01 85 53 96 10 7B 57 3D 58 DC DE 94 EC 7F FF D5 D6 5B 71 9C E2 AD C3 18 51 D2 B5 85 2B 19 54 AD 74 59 57 00 53 59 C1 AE C4 AC 71 BD 59 FF D6 DB B9 5C 83 59 77 10 16 CD 6B 88 83 66 78 6A 89 14 1E DC 8E 45 24 72 18 CE 0D 79 CD 38 BB 9E A7 3A 92 B1 FF D7 7C 57 98 EF 56 52 EF 3D EA 69 D4 B1 55 68 96 12 6D DD E9 F8 24 57 74 27 74 79 F3 8F 2B 3F FF D0 D6 2A 69 0A 9A F4 11 E7 5C 4C 52 52 B0 1F FF D1 D3 34 D2 4D 77 D8 F3 86 16 A6 16 A4 C6 7F FF D2 B3 BA 8D E6 BB 4E 01 77 D2 79 94 5C 47 FF D3 D9 06 9C 0D 77 1E 60 51 8A 06 7F FF D4 D9 22 A1 90 70 6B B2 47 9E 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 25 8A 17 07 15 55 41 66 AC 1A D4 DA 3B 1F FF D5 BF 65 19 E2 B6 E0 F9 45 77 D3 D8 F3 E7 B9 23 3F 15 13 36 6A D9 27 FF D6 E9 F3 9A 2B D0 3C D0 A5 C5 03 3F FF D7 E9 E9 09 AF 43 A1 E7 10 4A 79 AA B2 1C 57 25 53 AA 91 FF D0 D2 07 35 0C CF 8E 2A 1E A5 24 54 73 9A 48 A2 2C D5 37 2E C7 FF D1 D3 B6 87 18 AD 28 53 02 B7 A0 73 D6 26 CD 21 E6 BA 7A 1C C7 FF D2 E9 24 87 70 AA 92 5A E7 B5 6F 52 9D CE 7A 55 2C 56 6B 2F 6A 61 B2 F6 AE 49 51 3B 23 5C FF D3 B8 2C B3 DA 9C 2C BD AB 15 48 D9 D6 1C 2C 7D A9 CB 63 C7 4A D1 52 32 75 8F FF D4 D4 16 5E D4 E1 64 31 D2 9A A4 4B AC 38 59 0F 4A 51 68 3D 2A D5 33 37 56 E7 FF D5 DC 16 83 1D 2A 4F B2 0E 38 AD D5 33 95 D5 24 5B 51 E9 4E FB 2A FA 55 AA 76 21 D4 3F FF D6 E8 DA 00 3B 54 66 15 15 D7 C8 70 F3 B1 A6 31 51 B4 54 9C 06 A7 66 7F FF D7 D7 11 E0 D4 F1 B6 DA E8 8C 2C 72 4A A5 D1 30 96 94 4B 5D 08 C4 FF D0 E9 8C B4 D3 2D 7A 27 9C 30 C9 4C 66 06 93 49 81 FF D1 DE 04 53 83 57 A0 92 47 9C D8 BE 61 A4 DC 4D 53 11 FF D2 DE 61 9E B5 0B C0 08 E9 5D F3 8D D1 C1 09 59 94 E7 83 19 E2 B3 67 8F 19 AF 36 B4 2C 7A 74 2A 5C FF D3 A1 B8 A9 A9 62 9C 82 01 35 C2 A4 E2 CF 45 C5 49 1A 36 F3 67 1C D6 84 4E 08 AF 42 84 EE 79 78 9A 76 3F FF D4 DC DA 0D 21 4A F4 55 99 E6 B1 A5 69 A5 28 0B 9F FF D5 D5 2B 8A 63 0A F4 19 E7 11 91 9A 61 5A 91 9F FF D6 B2 56 93 15 D8 70 09 8A 42 B4 01 FF D7 D5 CD 38 35 77 23 CD 1C 0D 2D 30 3F FF D0 DA 35 0C BC 0A EC 91 E7 23 3A 7C 13 8A 20 8C 13 59 25 A9 B7 43 FF D1 DB B3 87 81 C5 68 AA E0 0A F4 62 AC 8F 36 4F 51 18 53 08 CF AD 53 42 3F FF D2 E9 F1 8A 31 5E 95 8F 34 50 29 71 4A C0 7F FF 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 26 D3 E9 CD 21 AF 41 9E 71 5E 5A A7 2D 71 D5 5A 1D 74 4F FF D4 BA 4E 05 55 91 8B 35 64 68 85 48 F3 56 A1 87 1C E2 8E A5 1F FF D5 DB 89 71 8A BD 10 E2 B7 A2 8E 7A E3 F6 D2 ED AE BB 1C A7 FF D6 EB 76 D3 4C 60 D7 A0 D5 CF 39 31 A6 00 69 A6 DC 7A 56 6E 08 B5 33 FF D7 E9 BC 85 A0 42 BE 95 D6 A0 8E 17 36 38 44 B4 BB 14 55 72 AB 8B 99 9F FF D0 EA 76 AD 26 00 AE EE 53 CF BB 10 E2 93 23 34 F9 50 AE 7F FF D1 E9 F7 53 B7 8A EF 48 F3 85 12 51 E6 0E 86 98 1F FF D2 E9 D9 AA 26 35 DE 79 C4 64 D2 13 9A 00 FF D3 DC A2 BB EC 79 E2 8A 5C 9A 10 8F FF D4 DE 24 D3 4B 1A F4 0F 38 6E 68 14 86 7F FF D5 DC 51 4F 0B 5E 8A 3C E6 38 25 28 8E 9A 11 FF D6 E8 8A 53 1B 81 5E 99 E6 15 66 19 AC EB 88 F3 9E 2B 92 B4 0E BA 33 B1 FF D7 A9 2C 78 AA F9 2A 6B 8E A4 35 3B E9 4E E8 B1 05 C6 D3 83 5A 30 5D F4 E6 B4 A1 2B 33 1C 44 2E 8F FF D0 D4 8E E4 11 D6 A6 F3 41 AE DA 73 4C E0 9C 2C C4 DE 29 0B 8A D6 E4 58 FF D1 D4 66 A8 CB 57 A0 CF 39 0D 2D 4D 26 A4 67 FF D2 B4 4D 26 6B B4 F3 C3 34 86 90 1F FF D3 D3 CD 28 3C D7 6A 3C E1 E2 9E 2A 90 8F FF D4 DC C7 15 5A 73 81 ED 5D B3 D8 F3 A3 B9 97 33 FC D5 35 A1 C9 AC 56 E6 CF 63 FF D5 E9 2D 31 81 8A B7 C5 7A 71 5A 1E 63 DC 6B 0A 6E DA 6C 47 FF D6 EA F6 8A 5D B5 EA 1E 60 84 53 4D 2B 74 03 FF D7 E9 F1 CD 26 2B D2 68 F3 48 65 1C 55 39 45 72 56 3A A8 9F FF D0 B9 20 E2 A1 D9 93 50 91 77 26 89 39 AB 40 01 8A 2D A8 D3 3F FF D1 DB 8D C0 AB 71 38 AD E8 98 56 25 0D 9A 70 6A EB 39 0F FF D2 EB 77 81 51 B4 C0 57 A0 D9 E7 25 72 33 72 3D 69 86 EC 7A D6 2E A1 AA A6 7F FF D3 E8 3E D4 0F 7A 7A CF 9A EA 8C EE 71 CA 16 1E 24 F7 A3 7D 6C 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 27 8C 8F FF D4 E9 77 D2 17 AF 40 F3 86 97 A4 2D 40 1F FF D5 E8 77 51 BA BB CF 38 37 9A 5D D4 D0 1F FF D6 E8 B3 41 5C D7 A0 79 A2 6C A3 65 30 3F FF D7 E8 BC BA 4D 95 E8 D8 F3 44 DB ED 46 28 60 7F FF D0 DE 61 51 9A F4 2C 79 C8 61 6A 50 F5 23 3F FF D1 DB 57 E6 A6 4E 6B D1 47 9A C9 94 52 91 81 54 84 7F FF D2 E9 18 D4 0E 7B 57 A6 D1 E6 10 BA E6 A0 92 20 41 E2 B2 9A B9 A4 5D 8F FF D3 7C F6 FE D5 46 5B 72 29 56 86 85 50 A8 56 65 2B 4E 8A 72 87 AD 72 2F 75 9D 8F DE 47 FF D4 8E 1B AF 7A B9 1D CF BD 3A 55 09 AD 4C 9D 65 DD DE 97 7D 76 29 5D 1C 4D 58 FF D5 BC 58 D3 49 AE EB 9E 78 D2 D4 D2 D4 86 7F FF D6 9F 77 14 9B AB B1 9C 02 6F A6 97 A4 98 58 FF D7 D0 DD 4A 1A BA EE 79 E4 8A D5 22 B5 52 62 B1 FF D0 DB 67 18 AA 57 2F C1 AE C9 EC 79 F1 46 54 EF F3 55 8B 36 E4 56 31 7A 9B 35 A1 FF D1 DE B4 7E 05 5D 0F 91 5E 9C 5E 87 98 D6 A0 5A 93 75 3B 88 FF D2 EA B7 51 BE BD 3B 9E 60 9B E9 A5 B8 A4 3B 1F FF D3 E9 B7 52 66 BD 27 B1 E6 A2 19 5A AA 48 D5 C9 54 EA A4 7F FF D4 B8 DC D3 45 49 44 D1 F1 4E 66 E2 90 CF FF D5 D3 47 AB 70 BD 6D 49 98 D6 26 0F 4E 0F 5D 68 E4 3F FF D6 E9 1D F8 AA 93 4E 45 75 D4 95 8E 3A 4A EC A1 2D E6 D3 D6 A0 6B D3 EB 5E 7C AA 59 9E 9C 29 5D 1F FF D7 96 3B C2 4F 5A BB 04 E4 F7 A9 A5 52 E5 D6 A7 64 5D 46 C8 A7 64 D7 A3 17 A5 CF 32 5A 33 FF D0 E8 28 AF 40 F3 84 E6 8A 40 7F FF D1 DE 34 99 AE F3 CE 14 1A 70 A0 19 FF D2 E8 96 9C 05 7A 08 F3 47 01 9A 70 4A AB 08 FF D3 EA 36 D3 48 AF 48 F3 46 91 48 45 20 3F FF D4 E8 18 54 4C 2B D1 68 F3 51 13 0C 53 2A 0B 3F FF D5 D5 42 73 56 A2 3D 2B D0 89 E7 48 9C 1C 0A 46 7E 2A C9 3F FF D6 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 28 DF 77 EB 51 96 AF 4D 9E 6A 1B 91 48 56 A6 C3 3F FF D7 D4 78 81 15 52 6B 71 CF 15 D3 56 07 25 29 D9 99 D3 DB E3 3C 55 29 23 2A 6B CD A9 13 D5 A5 2B A3 FF D0 C9 49 0A 9A B7 0D C5 72 D3 95 8E CA 90 BA 2E C5 35 59 56 04 57 A1 4A 57 3C BA B1 B3 3F FF D1 BA 46 69 08 AE E3 CF 18 45 34 8A 96 33 FF D2 98 F1 51 93 8A EA 6C E1 48 8D A4 02 A3 69 85 43 91 6A 27 FF D3 BB 9A 50 6B A9 1C 03 95 A9 FB F1 4C 56 3F FF D4 D0 79 78 AA 57 12 E7 35 D1 26 71 45 14 1C EE 6A B7 68 39 15 9C 77 34 7B 1F FF D5 D9 B5 E8 2A F2 F4 AF 4A 2B 43 CD 96 E2 13 48 4E 2A 98 8F FF D6 E8 CB D2 6F C5 7A 4C F3 10 6F 34 9B A8 63 47 FF D7 E8 77 E2 90 C9 C5 77 BD 8F 39 15 E5 92 AA 48 F5 C9 55 9D 94 91 FF D0 B4 0E 69 CA 2A 51 44 80 62 91 81 A4 CA 47 FF D1 D0 41 56 E2 06 B5 A4 65 58 9A 8C D7 59 C6 7F FF D2 DF 7E 95 4A E0 70 6B A2 B6 C7 2D 0D CC 9B 90 41 35 50 93 9A F2 2A 37 73 DC A5 F0 9F FF D3 A9 11 C1 AD 4B 56 AE 3A 32 D4 EE C4 2D 0D 38 4E 45 4B 8A F6 29 EB 13 C3 A8 AC CF FF D4 E8 71 49 8A F4 19 E7 06 28 C5 02 3F FF D5 DE 61 4C CD 77 9E 72 0C D3 83 50 07 FF D6 DF 0D 4F 0F 5E 81 E6 8F 57 A7 86 AA 03 FF D7 EA 73 4C 63 5E 96 E7 98 34 91 48 4D 03 3F FF D0 E8 8D 30 8C D7 A5 63 CD 23 65 A8 99 3D 2A 1A 1A 67 FF D1 D8 8D 2A CA 26 05 7A 51 3C E6 3D 8E 05 42 EF 54 F6 25 1F FF D2 D7 67 A6 EF AF 46 E7 9D 61 41 CD 38 52 40 CF FF D3 D9 A8 E4 50 45 7A 12 57 47 9F 17 66 51 9E 21 CD 67 4F 17 5E 2B CF AD 03 D0 A1 33 FF D4 C8 91 0A 9A 6A B9 53 5C 4D 59 9D E9 DD 16 E1 B8 F7 AB D0 CF D3 9A E9 A5 23 8E B4 0F FF D5 B4 B2 82 3A D0 64 18 AE A8 CF 43 89 C6 CC 8D A6 03 BD 44 D3 8F 5A 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 29 52 90 D4 0F FF D6 8D AE 07 AD 42 F7 22 AE 53 30 8D 32 BB DC FB D4 2D 71 EF 58 CA 67 44 69 9F FF D7 BB 8A 2B AA C7 9E 14 85 A8 63 3F FF D0 9A 47 AA 93 3F 5A D6 4C E4 89 5B 39 6A BF 66 32 45 10 DC A9 6C 7F FF D1 DD B5 4C 81 57 42 57 A7 05 A1 E6 37 A8 8C 94 C2 94 EC 23 FF D2 E9 4A E2 9A 52 BD 26 8F 32 E2 15 A6 1A 96 B5 19 FF D3 DC 27 14 D6 35 DC F6 3C F5 B9 5E 43 55 9C F3 5C 75 0E CA 47 FF D4 B2 A2 A6 45 A9 43 25 0B 9A 0A FB 50 C6 99 FF D5 D5 45 AB 09 C5 6D 4D 18 D6 24 34 D2 6B A4 E4 3F FF D6 DD 27 22 AA CE 3A D7 55 55 74 71 D2 76 66 6D CA F5 AA 0C 30 6B C7 AA AC CF 76 83 BC 4F FF D7 A2 A7 04 56 8D AB F4 AE 0A 5A 33 D1 AC AF 13 5A DD B8 15 68 0C 8A F6 A8 BB A3 C2 AC AD 23 FF D0 E8 C0 EB 46 2B D1 B1 E6 89 8C 52 11 48 0F FF D1 DF 22 98 56 BD 06 79 A2 62 8A 43 3F FF D2 DB A7 02 6B D0 B1 E7 0F 06 A4 56 E2 98 8F FF D3 E9 77 53 49 E2 BD 24 79 A3 33 46 68 03 FF D4 E8 33 49 9A F4 AE 79 A2 1A 68 5C D2 B0 1F FF D5 E8 23 8F 9E 95 38 4C 0A F4 D2 3C C6 47 27 02 AA 48 D4 36 34 7F FF D6 D1 66 A0 1A EF 38 07 A9 A9 05 34 49 FF D7 D8 26 98 C6 BD 26 79 C8 86 40 08 AA 53 C6 2B 9A AC 53 36 A5 2B 33 FF D0 A3 3A 01 9A A4 E3 15 CF 51 1D 34 A5 74 22 B9 53 56 62 B8 C7 7A 98 3B 32 AA 2B A3 FF D1 AA B7 40 77 A5 37 7C 75 A4 AA 68 27 4F 52 17 BB F7 A8 5E E8 9E F5 32 99 71 A6 7F FF D2 C5 6B 83 EB 51 34 C4 D6 0E 46 CA 16 23 69 0D 30 B1 A8 B9 76 3F FF D3 BB 9A 5A EB 3C F1 0D 31 8E 29 31 A3 FF D4 74 86 A9 CC 6B 49 1C B1 20 07 E6 AD 2B 26 E4 53 86 E3 96 C7 FF D5 E8 2C C8 C0 AB E8 33 CD 7A 91 D8 F2 DE E2 94 E2 A3 64 AA 62 3F FF D6 EA 8A D3 48 AF 51 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 2A 9E 61 1B 54 6D 52 C2 E7 FF D7 DB 6A 63 F4 AE F9 1E 7A 2B 48 6A 06 EB 5C 75 0E CA 47 FF D0 B4 95 3A 50 B6 06 4A 0D 21 6A 18 CF FF D1 D7 42 2A 74 22 B6 A6 63 54 79 34 C6 35 D2 72 1F FF D2 DC CD 41 2E 08 35 D7 53 63 8A 9E E6 75 C8 AC F7 18 6A F2 2B 2D 4F 6B 0E F4 3F FF D3 A0 2A DD B3 E2 BC FA 7B 9E 9D 4D 8D 6B 67 E9 5A 31 9C AD 7A F4 1D CF 0E BA B3 3F FF D4 E9 40 A4 35 E9 1E 68 84 D2 66 90 1F FF D5 E8 4D 26 2B D1 3C D1 36 D2 6D A4 07 FF D6 DE C5 1B 6B D1 3C D1 40 A7 0E 29 88 FF D7 E8 B3 8A 42 6B D2 B9 E6 8C 26 9A 5B 9A 40 7F FF D0 DB DD 46 6B D1 B9 E6 8E 5E 6A 54 5A 68 19 FF D1 EA 15 71 4A C4 0A F5 0F 2C AB 33 D5 49 1B 35 32 2E 27 FF D2 BC 4D 28 35 DC 70 0F 56 1E B4 F1 20 F5 A7 71 58 FF D3 D4 69 45 42 F3 81 DE BB 5D 4B 1C 31 81 03 DC 0F 5A AF 24 E0 8E B5 CF 39 E8 6F 0A 67 FF D4 A1 3B E6 A9 B9 E6 B9 E6 CE 8A 6B 42 23 40 6C 56 57 36 67 FF D5 C2 12 90 28 F3 4D 72 73 1D 7C A3 4B 93 4D 24 D2 E6 1D 8F FF D6 E7 09 A6 9A E2 3B 06 9A 69 35 44 9F FF D7 B6 0D 3B 35 D4 8F 3C 43 4C 6A 18 D1 FF D0 24 AA 92 D6 B2 39 62 43 8E 6A ED A3 60 8A 50 DC A9 6C 7F FF D1 DC B2 6E 05 6A 44 78 AF 52 1B 1E 64 B7 24 3D 2A 27 AA 64 9F FF D2 EA 98 D3 19 B3 5E AD 8F 2C 8C 9A 8C D4 8C FF D3 DC 22 A3 71 C5 77 CB 63 CE 5B 95 A4 E2 AB 9E B5 C5 51 9D D4 CF FF D4 B2 A7 15 2A BD 20 B0 ED FC 52 6F E6 93 29 23 FF D5 D2 57 A9 E3 6A D2 9B 32 AA 4B BB 22 9A 6B AD 6C 71 F5 3F FF D6 DB 35 14 82 BA E7 B1 C3 0D CC FB 91 59 D2 8C 35 79 35 D1 EC E1 DE 87 FF D7 CF CD 4D 03 73 5E 6C 74 67 A9 2D 8D 5B 57 E9 5A 90 B6 45 7A D8 76 78 D8 95 A9 FF D0 E9 69 0D 7A 57 3C D1 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 2B 99 A4 CD 20 3F FF D1 E8 69 2B D0 B9 E6 85 15 40 7F FF D2 E8 40 14 62 BD 13 CC 0A 4C D3 03 FF D3 E8 09 A6 96 E2 BD 1B 9E 69 19 6A 69 6A 91 D8 FF D4 D7 2D 4A A7 35 DE 79 C4 F1 8C D5 85 5C 56 91 25 9F FF D5 EA 49 02 A0 96 50 3B D7 A7 74 8F 32 C5 49 66 15 55 E5 AC 65 34 6D 18 33 FF D6 B2 66 14 C3 70 05 6E EA 1C EA 98 86 E4 0A 6B 5D FB D4 3A A5 AA 47 FF D7 6B DE 7B D4 2F 77 EF 4A 55 4A 8D 22 BB DD 1C F5 A8 9A E0 9E F5 83 99 B4 69 9F FF D0 C5 69 73 51 93 9A E3 6E E7 6A 56 1A 69 33 52 33 FF D1 E7 73 40 AE 13 B4 5A 43 40 1F FF D2 E7 29 A6 B8 8E C1 A6 9A 69 92 7F FF D3 B6 29 6B A8 F3 C4 34 C6 A1 8D 1F FF D4 24 AA B2 56 B2 39 22 42 7A D5 BB 5C E4 52 86 E5 CB 63 FF D5 D9 B2 1C 0A D5 88 F0 0D 7A 90 D8 F3 25 B8 F6 6C 54 2E D8 AA 26 E7 FF D6 E9 59 B3 4D 2D C5 7A AC F2 C6 93 49 52 07 FF D7 DF C5 45 20 AF 42 7B 1E 74 77 2A CA 2A 02 BC D7 14 D1 DD 4F 63 FF D0 B4 06 29 4D 16 15 C3 75 1D 6A 59 68 FF D1 BC 87 9A 9D 2B 4A 68 CE A1 3A 8C D2 E2 BA D6 C7 1B DC FF D2 DC 22 A3 90 71 5D B3 D8 E0 8B D4 A3 70 BD 6B 32 75 C1 AF 2A BA D4 F5 F0 CC FF D3 CE CF 34 F8 8E 1A BC C5 B9 EA BD 8D 3B 56 AD 6B 73 C0 AF 4B 0E CF 2B 14 8F FF D4 E9 85 35 AB D1 47 9A 46 69 33 40 1F FF D5 E8 09 A4 CD 7A 07 9B 61 33 4E 15 57 03 FF D6 E8 69 71 5E 91 E6 0D A4 27 14 31 9F FF D7 DD 2D C5 46 5C 57 7B 68 F3 92 23 2F 4C 32 0F 5A 96 D1 76 3F FF D0 D1 F3 06 6A 44 71 5D AA 47 0F 29 61 25 02 9E 6E 40 14 9C F4 05 4D 9F FF D1 D9 92 F0 7A D5 49 6F 3D EB 69 55 B1 8C 29 15 24 BB CF 7A AE F7 3E F5 CF 2A 87 44 69 1F FF D2 AA D7 35 1B 5C D6 0E A1 BA A6 46 6E 4F AD 46 6E 0D 4B 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 2C 99 A2 81 FF D3 C7 33 13 4C 2E 4D 71 39 1D CA 29 0D 26 92 95 C6 7F FF D4 C0 34 D2 6B 84 EE 13 34 84 D3 B0 8F FF D5 E6 C9 A3 35 C4 76 0B 9E 29 33 40 1F FF D6 E6 89 A4 26 B8 8E B1 B4 DC 13 4C 4C FF D7 B5 4E AE B3 CF 1A 6A 37 A4 C6 8F FF D0 6C 86 AA C8 6B 49 1C B1 22 CF 35 7A CC 64 8A 70 DC 72 D8 FF D1 DC B2 5E 05 69 A0 C2 D7 AB 0D 8F 2E 5B 8D 76 AA EE D4 D8 91 FF D2 E8 49 A4 26 BD 53 CB 42 66 94 54 8C FF D3 E8 2A 39 2B D1 9E C7 9D 1D CA CE B5 13 2D 71 CD 1D 70 67 FF D4 BB B6 9A C2 AA DA 10 9E A2 01 4E C5 41 77 3F FF D5 BE 82 AC 20 AD 29 A3 2A 84 EA 38 A5 AE B4 B4 38 DE E7 FF D6 DE A6 3F 22 BB 9A D0 F3 91 4A E0 75 AC BB 85 AF 36 BA 3D 5C 33 3F FF D7 CD 3D 69 CA 79 AF 33 63 D6 E8 68 5A B7 22 B5 ED DB 81 5D D8 76 79 D8 A4 7F FF D0 E9 51 B2 B4 86 BD 05 AA 3C D7 B9 19 A6 93 4C 0F FF D1 DD DD 49 91 5D E7 9D 60 DC 29 77 8A 39 92 0B 33 FF D2 DE F3 00 A0 CA 31 5D DC E7 9E A2 31 A6 18 A8 DA 61 8E B5 0E A1 6A 99 FF D3 D2 69 C7 AD 42 D7 03 D6 B6 75 11 CE A9 91 3D C7 BD 44 D7 3E F5 9F B4 34 54 CF FF D4 70 B8 E7 AD 4A 97 18 14 3A 82 54 87 FD AF 03 AD 45 25 EF 1D 6B 37 54 DA 34 4F FF D5 8A 4B CC F7 AA EF 74 4F 7A E5 94 CE C8 D3 B1 0B 4E 4F 7A 8C CA 4D 66 E4 5A 89 FF D6 C6 2E 69 09 CD 70 5C F4 6C 34 D2 51 71 1F FF D7 C1 A5 AE 03 D0 12 83 40 8F FF D0 E7 CD 34 D7 09 DC C4 34 DC D3 24 FF D1 E6 73 4A 0D 71 1D 81 9A 4C D0 23 FF D2 E6 09 A4 AE 33 A8 72 A6 6A 65 87 3D AA E2 8C A5 2B 1F FF D3 B3 9A 75 75 A3 CF 10 D4 4F D2 93 1A 3F FF D4 8E 43 C1 AA 92 35 69 23 96 24 4A 72 D5 AB 62 B9 C5 55 3D C2 7B 1F FF D5 E8 6C C7 02 B4 3A 0A F5 A3 B1 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 2D E5 3D C8 64 35 5D 8F 34 30 47 FF D6 DF 34 84 D7 A6 79 62 66 97 34 0C FF D7 DF A6 3D 7A 2F 63 CD 44 0F 51 1E B5 CD 33 A6 1B 1F FF D0 BF 4C 35 A3 D0 C9 07 4A 33 50 CD 11 FF D1 BE 86 AC 46 6B 5A 66 35 0B 0B D2 83 5D 71 D8 E3 7B 9F FF D2 DD 34 C3 D2 BB DA D0 F3 91 5A 61 C1 AC CB 91 D6 BC FA E8 F4 70 CC FF D3 CD 7E 0D 22 9A F3 5E E7 AC B6 2E 5B 3D 6A DB CB 80 39 AE 9A 12 B1 C5 89 8D CF FF D4 DD 8E 61 8A 1A 61 5D 71 9E 87 14 A1 A9 1B 4C 3D 6A 36 9C 7A D4 BA 85 2A 67 FF D5 D3 37 03 D6 98 6E 00 EF 5B 3A A7 3A A6 21 B9 14 86 E4 7A D4 BA A5 AA 47 FF D6 B8 6E 86 3A D3 4D DF BD 37 54 4A 91 19 BB F7 A8 9A EF DE B2 75 0D 23 48 FF D7 89 AE BD EA 36 B9 AC 1C CE 85 4C 8D AE 33 51 99 F3 52 E6 57 B3 3F FF D0 A0 B2 D4 82 53 5C 72 99 DD 18 01 98 E2 A0 79 09 EF 59 73 1A F2 A4 7F FF D1 C8 2C 69 85 AB CF B9 E9 08 4D 19 A0 57 3F FF D2 C3 CD 2D 79 E7 A2 25 21 A0 47 FF D3 C1 A2 B8 0E F1 28 CD 00 7F FF D4 E7 C9 A6 9A E1 3B 46 90 69 30 69 A1 33 FF D5 E6 82 13 4E 11 D7 25 8E A6 C0 C6 69 8C B8 A1 A1 27 73 FF D6 E5 8D 2A D7 21 D4 C9 E2 15 6A 35 15 B4 11 CB 51 9F FF D7 B0 0D 2E 6B A9 1E 78 84 F1 4C 7E 94 31 A3 FF D0 8E 5A A5 29 EB 5A 48 E5 88 D8 86 5A B6 6C 53 A5 69 49 6A 29 9F FF D1 E8 ED 46 00 AB 65 B8 AF 55 6C 79 2F 72 19 1A A0 63 43 1A 3F FF D2 DE 34 84 D7 A6 CF 30 4C D0 0D 20 3F FF D3 DE 15 1B 9A F4 25 B1 E6 AD C8 5C D4 25 B0 6B 96 6F 53 AA 08 FF D4 BA 5A 9B 9A A6 CC EC 21 34 99 A4 CB 48 FF D5 BD 1D 59 8E B6 A6 63 51 93 AF 4A 53 5D 4B 63 91 EE 7F FF D6 DD 3C D3 48 AF 41 9E 69 5E 61 D6 B3 6E 47 5A E0 AE 77 E1 99 FF D7 CE 94 73 51 D7 9B 2D 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 2E CF 52 2F 42 58 5F 69 AB D1 4F 81 D6 B4 A6 EC 65 56 37 3F FF D0 B0 97 58 1D 68 6B BF 7A CD 54 2D D2 D4 8D AE AA 33 73 EF 52 EA 16 A9 1F FF D1 61 B9 F7 A6 9B 9F 7A E6 75 0E A5 4C 69 B9 F7 A6 9B 9F 7A 97 32 95 33 FF D2 A8 6E 69 86 E0 FA D7 17 39 DD C8 30 DC 1F 5A 63 4C 7D 69 73 14 A2 7F FF D3 C8 32 93 4D DE 6B 82 E7 A3 60 DD 4A 05 17 03 FF D4 CC 51 52 01 C5 79 8D 9E A4 44 6A 85 CD 24 36 7F FF D5 C4 26 9B 9A F3 D1 E8 30 CD 19 A6 23 FF D6 C2 CD 19 AE 03 BC 33 49 9A 00 FF D7 C0 A0 D7 01 DD 71 28 E6 9D 84 D9 FF D0 E7 F6 13 4B E5 9F 4A E3 51 3A DC 87 08 7D A9 C2 0F 6A D1 40 C9 CC FF D1 C7 16 F4 FF 00 B3 F1 D2 B3 50 1C AA 0D 68 71 50 49 1D 12 88 E3 3B 9F FF D2 E6 1D 71 4C CE 0D 72 B4 74 DE E4 D1 C9 56 63 96 B5 8B 30 A9 13 FF D3 9B 34 B9 AE AB 9E 78 84 D3 18 D2 65 1F FF D4 8A 53 C5 53 97 93 5A 4B 73 96 24 96 F1 E4 8A DA B2 4E 9C 56 D4 89 A8 CF FF D5 E9 20 18 15 2B 37 15 EB 2D 8F 21 91 3B 54 44 D2 91 48 FF D6 DC 27 8A 4C D7 A5 63 CC 12 94 50 07 FF D7 DC CD 44 E6 BB E7 B1 E7 43 72 BC 8D 50 33 F3 5C 53 91 DD 4D 1F FF D0 9F 75 2E EA 49 83 43 59 E8 56 A5 71 D8 FF D1 B9 19 AB 51 9A D6 9B 31 A8 4E 86 94 9A EB 5B 1C 8F 73 FF D2 DD A4 ED 5E 81 E6 90 4C 2B 3A E4 75 AE 2A E8 ED C3 BD 4F FF D3 A1 30 F9 8D 43 5E 74 B7 3D 28 3D 05 07 15 22 C9 8A 49 8D AB 9F FF D4 A6 27 23 BD 21 9C 9A F3 F9 8F 47 94 69 94 9A 69 90 9A 97 22 B9 4F FF D5 CD DC 68 DC 6B CC E6 3D 4B 08 4D 37 34 5C 2C 7F FF D6 C9 2D 48 4D 79 A8 F4 D8 C2 D4 85 A9 92 7F FF D7 C2 CD 28 35 E7 9E 80 E5 15 2A 8A 4C A4 7F FF D0 CF 51 8A 52 71 5E 53 3D 54 46 CD 51 31 AA 89 2C FF D1 00 00 00 00 0D 0A 7B 7B A2 02 08 49 4D 41 47 07 E0 06 14 11 16 01 2F C3 34 D3 5E 7A 3D 06 25 14 C4 7F FF D2 C1 A2 B8 0E EB 86 0D 28 53 55 61 5C FF D3 C3 11 9A 51 11 35 C8 A2 75 39 0E 10 D3 84 1E D5 A4 62 67 29 9F FF D4 CC 58 3D A9 E2 0F 6A 85 00 95 41 E2 0F 6A 7A C2 3D 2B 58 C0 C6 53 3F FF D5 80 46 07 6A 0A 0A D5 44 E5 72 B9 13 A7 15 5A 54 A8 9C 4B 84 8F FF D6 E7 E5 4A AA EB 83 58 49 1A C2 43 43 60 D4 B1 CB 8A 98 8E 4A E7 FF D7 94 52 D7 51 E7 8D 63 8A 89 DA A5 94 8F FF D0 AD 2B D5 62 72 6B 4B 9C A8 B9 6A 99 22 B6 ED 23 E9 5D 14 91 95 46 7F FF D1 E9 D0 61 45 35 8E 2B D7 E8 79 04 2C 6A 32 6B 36 52 3F FF D2 DA 26 8C D7 A2 79 81 45 00 7F FF D3 DA A8 A4 35 DD 33 CF 8E E5 69 2A B9 EB 5C 13 DC EF A6 7F FF D4 93 34 84 D4 14 26 69 56 90 1F FF D5 B1 1B 55 A8 DA AE 99 95 44 4E AD 4F CD 76 44 E3 96 87 FF D6 DC A4 CD 7A 1B 1E 69 14 BD 2A 85 C0 EB 5C 95 96 87 55 07 A9 FF D7 A7 3A F3 55 8D 79 F3 56 67 A1 4D DD 09 4B 50 68 7F FF D0 CB 14 B5 E5 9E A0 B4 52 19 FF D1 CD A3 15 E5 1E A8 D3 4D 34 20 67 FF D2 C7 34 D2 6B CD 47 A6 C6 13 4D CD 51 07 FF D3 C0 CD 39 6B CF 3D 02 55 15 2A 8E 2A 59 68 FF D4 CE CE 29 0B 57 94 7A A4 6C D5 19 AB 48 86 7F FF D5 C3 22 93 69 AE 04 77 B6 28 43 4A 22 35 49 12 E4 7F FF D6 C6 10 9F 4A 70 80 FA 57 2A 81 D2 E6 3C 5B 9F 4A 78 B7 F6 AD 14 0C DD 43 FF D7 A2 2D FD AA 41 6F ED 52 A9 8A 55 07 08 3D A9 C2 11 5A A8 18 B9 9F FF D0 8C 46 28 DA 05 6E A2 72 39 0B 8A 2A 92 25 B3 FF D1 8C D3 49 AE 96 71 21 8D 50 C8 B5 12 29 33 FF D2 C6 91 2A AC B1 D4 4D 0A 12 2B 3A E2 98 18 83 59 58 DE F7 3F FF D3 9A 83 5D 47 9E 46 E6 AB C8 F8 A8 65 A3 FF D4 CE 96 4A 8D 1B E6 AB 5B 9C C8 D3 B2 E4 8A 00 00 00 00 0D 0A 7B 7B A2 01 68 49 4D 41 47 07 E0 06 14 11 16 01 30 DF B3 5E 05 75 D2 39 EA 9F FF D5 EA F6 F1 51 48 2B D8 67 8E 57 7E 2A 22 6B 16 68 8F FF D6 D7 CD 19 AF 40 F3 07 03 4B 9A 60 7F FF D7 D9 26 A2 73 5D B3 D8 F3 E1 B9 59 FA D4 44 64 D7 14 D6 A7 6D 36 7F FF D0 97 6D 21 5E 6A 12 0B 89 B6 97 6D 3B 05 CF FF D1 95 38 AB 31 9A AA 6C 89 96 10 D4 B9 AE B8 6A 8E 29 6E 7F FF D2 DB CD 21 AF 40 F3 46 49 C8 AA 37 03 8E 95 CD 59 1B D1 7A 9F FF D3 AB 70 2A 9B 75 AE 2A 88 ED A4 F4 1B 4E 06 B1 36 3F FF D4 CA A7 57 98 7A 61 46 69 0C FF D5 CD CD 04 F1 5E 51 EA 8D 34 D3 42 03 FF D6 C6 34 C3 5E 72 3D 26 30 D2 55 10 7F FF D7 E7 C7 35 22 0A E0 67 7A 26 50 7D 29 F8 38 AC DA B9 A2 67 FF D0 CC C1 34 9B 4D 79 CA 27 A2 E4 27 96 4D 28 84 FA 56 8A 26 6E 67 FF D1 CD 10 13 DA 9C 2D FD AB 99 40 E8 94 C7 0B 7F 6A 91 6D FD AB 48 C0 C6 55 0F FF D2 80 5B 8F 4A 78 80 7A 53 8C 0C E5 50 70 87 DA 9C 22 1E 95 A2 81 9B 99 FF D3 5F 2C 0A 36 81 5D 0A 27 1B 90 62 92 9D 84 7F FF D4 4C D3 4D 75 1C 41 49 48 0F FF D5 88 9A 69 35 D0 71 0C 26 98 DC D2 63 3F FF D6 CC 75 AA EF 1F B5 54 91 84 19 56 48 EA B3 A6 2B 06 8E 98 B3 FF D9 00 00 41 47 90 D5");
		
		//包数据
		$picture_array = $this -> getDataPicture();
		
		
		$str = "Hello world!";echo bin2hex($str);echo "--------";echo pack("H*",bin2hex($str));
		
		echo "<br>++++++++++<br>";
		
		echo "图片名称：".$picture_array['picture_name']."---".(int)hexdec("07E0").(int)hexdec("06").(int)hexdec("14").(int)hexdec("11").(int)hexdec("16").(int)hexdec("01");  
		exit; 
				
		$start_tag = hex2bin($picture_array['start_tag']);//起始标识
		$type = hex2bin($picture_array['type']);//数据包类型
		$parameter_length = hex2bin($picture_array['parameter_length']);//参数数据长度
		$datas = hex2bin($picture_array['datas']);//数据域头
		$picture_name = hex2bin($picture_array['picture_name']);//图片名称
		$lumps = hex2bin($picture_array['lumps']);//当前块数
		$image_data = $picture_array['image_data'];//图像数据
		$intended_effect = hex2bin($picture_array['intended_effect']);//CRC效验
		$trail = hex2bin($picture_array['trail']);//数据域尾
		$ending_tag = hex2bin($picture_array['ending_tag']);//结束标识
		
		echo $picture_name."---".$image_data;
		$filename="tttt33.jpg";
		
		$str = $image_data; //图片16进制字符串
		$binary_string = pack("H*" , $str); //将16进制的字符串转换成二进制 
		$binary_string = hex2bin($str_picture);
		$file = fopen($filepath.$filename,"w");
		fwrite($file,$binary_string); //写入
		fclose($file);
		//file_put_contents()
		
		//echo "图片："."<img src='data/".$filename."'>";  
		
	}
	
	//客户端IP
	function GetIP(){ 
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown")) 
			$ip = getenv("HTTP_CLIENT_IP"); 
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown")) 
			$ip = getenv("HTTP_X_FORWARDED_FOR"); 
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown")) 
			$ip = getenv("REMOTE_ADDR"); 
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown")) 
			$ip = $_SERVER['REMOTE_ADDR']; 
		else 
			$ip = "unknown"; 
		return($ip); 
	}
}
