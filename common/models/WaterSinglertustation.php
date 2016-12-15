<?php

//namespace app\models;

namespace res\waterMonitors\common\models;
use Yii;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
/**
 * This is the model class for table "water_singlertustation".
 *
 * @property integer $id
 * @property string $state
 * @property integer $statno
 * @property string $waterlv
 * @property integer $rainfall
 * @property string $watertemp
 * @property string $date
 * @property string $bakup1
 * @property string $bakup2
 */
class WaterSinglertustation extends \frontend\core\BaseActiveRecord
//class WaterSinglertustation extends \yii\db\ActiveRecord
{
     public static $table='';
  
    public function _construct($table,$config = [])
    {
        self::$table = $table;
        parent::_construct($config);
    }



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
      //     return "rtu_555555";
      //     return 'water_singlertustation';
             return self::$table;
     }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['statno', 'rainfall','dgtype'], 'integer'],
            [['waterlv', 'watertemp','volte', 'bakup1'], 'number'],
            [['date'], 'safe'],
            [['state'], 'string', 'max' => 32],
            [['bakup2'], 'string', 'max' => 256],
            [['originstr'], 'string', 'max' => 512],
            [['rainfallmulti'], 'string', 'max' => 24],
            [['waterlvmulti'], 'string', 'max' => 48],
            [['waterflow'], 'string', 'max' => 48]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'state' => Yii::t('app', '站点'),
             // 'state' => '站点',
            'statno' => '站点编号',
           // 'waterlv' => '水位',
            'waterlv' =>Yii::t('app','水位'),  // '水位',
            'rainfall' => '24小时雨量',
            'watertemp' => '水温',
            'date' => '记录时间',
            'bakup1' => 'Bakup1',
            'bakup2' => 'Bakup2',
            'rainfallmulti' =>'rainfallmulti',
            'waterlvmulti' => 'waterlvmulti',
            'waterflow' => '流量',
            'volte' => '电压',   
            'originstr' => 'originstrings',     
            'dgtype' => 'type',   
        ];
    }

  public static function findx($table)
    {
        if (self::$table != $table) {
            self::$table = $table;
        }
        //echo get_called_class();
        return Yii::createObject(ActiveQuery::className(), [get_called_class(), ['from' => [static::tableName()]]]);
    }

    /**
     * @param array $row
     * @return static
     */
//    public static function instantiate($row)
 //   {
       // return new static(static::tableName());
 //   }

    /**
     * @param $table
     * @param $condition
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function findOnex($table, $condition)
    {
        return static::findByConditionx($table, $condition)->one();
    }

    /**
     * @param $table
     * @param $condition
     * @return mixed
     * @throws InvalidConfigException
     */
    public static function findAllx($table, $condition)
    {
        return static::findByConditionx($table, $condition)->all();
    }

    /**
     * @param $table
     * @param $condition
     * @return mixed
     * @throws InvalidConfigException
     */
    protected static function findByConditionx($table, $condition)
    {
        $query = static::findx($table);

        if (!ArrayHelper::isAssociative($condition)) {
            // query by primary key
            $primaryKey = static::primaryKey();
            if (isset($primaryKey[0])) {
                $condition = [$primaryKey[0] => $condition];
            } else {
                throw new InvalidConfigException('"' . get_called_class() . '" must have a primary key.');
            }
        }

        return $query->andWhere($condition);
    }

}
