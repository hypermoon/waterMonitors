<?php

namespace res\waterMonitor\frontend\controllers;

use Yii;
use res\waterMonitor\common\models\WaterMonitor;
use res\waterMonitor\common\models\search\WaterMonitor as WaterMonitorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\Pagination;


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
			//welcome  发送到客户端
			$msg = "<font color='red'>server send:welcome</font><br/>";
			socket_write($msgsock, $msg, strlen($msg));
			echo 'read client message\n';
			// 从客户端获取得的数据
			$buf = socket_read($msgsock, 8192);
			
			//$binary_string = pack("H*" , $str); //将16进制的字符串转换成二进制 
			//$binary_string = hex2bin($str_picture);
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
			}
			socket_close($msgsock);
		} while(true);
		//关闭socket
		//socket_close($sock);

	}
	
	//socket数据客户端
	public function actionSocketclient(){
		echo "<h2>tcp/ip connection </h2>\n";
		$service_port = 9000;
		$address = '183.230.176.58';

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
		$in = "测试数据流test123456789";
		$out = "";
		echo "sending http head request ...";
		socket_write($socket, $in, strlen($in));
		echo  "OK\n";
		//socket_close($socket);
		

		echo "Reading response:\n\n";
		while ($out = socket_read($socket, 8192)) {
			echo "<br><br><br>服务器数据：".$out."<br><br><br>";
			//写入文件
			$myfile = fopen("D:/xampp/htdocs/WlMonitor/data/client.txt", "w") or die("Unable to open file!");
			$txt = "客户端获取数据：".$out;
			fwrite($myfile, $txt);
		}
		echo "closeing socket..";
		//socket_close($socket);
		echo "ok .\n\n";

	}
	
	//水位监测
	 public function actionWatermonitor()
    {
        //分页读取类别数据
        $model = WaterMonitor::find();
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
		$this->layout = false;
        return $this->render('watermonitorydetail');
    }  
	
	//获取数据包内容
	public function getDataAnalysis(){
		$urls = "D:/xampp/htdocs/WlMonitor/data/shuju1.txt";
		$file = file_get_contents($urls);
		
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
	
	//获取图片包内容
	public function getDataPicture(){
		$urls = "D:/xampp/htdocs/WlMonitor/data/tupian1.txt";
		$file = file_get_contents($urls);
		
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
		//包数据
		$data_array = $this -> getDataAnalysis();
		
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
}
