<?php

namespace res\waterMonitor\common\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property string $id
 * @property string $account
 * @property string $password
 * @property string $auth_key
 * @property string $password_hash
 * @property string $password_reset_token
 * @property integer $created_at
 * @property integer $updated_at
 * @property integer $role
 * @property string $username
 * @property string $jpin
 * @property string $qpin
 * @property string $nickname
 * @property string $sex
 * @property string $idcard
 * @property string $stdid
 * @property string $nation
 * @property string $birthday
 * @property string $mobile
 * @property string $qqnum
 * @property string $wechat
 * @property string $homephone
 * @property string $email
 * @property string $image
 * @property integer $sorting
 * @property string $address
 * @property string $orgin
 * @property string $father
 * @property string $fathphone
 * @property string $guardian
 * @property string $gdphone
 * @property string $midschool
 * @property integer $status
 * @property string $mather
 * @property string $mathphone
 * @property string $university
 * @property string $specialty
 * @property string $level
 * @property string $workunit
 * @property string $quarters
 * @property string $position
 * @property string $nexus
 * @property string $workphone
 * @property string $remark
 * @property integer $grow
 * @property string $schfrom
 * @property string $midtid
 * @property string $trasaction_id
 * @property integer $studentpay
 * @property string $alternativephone
 * @property string $signature
 */
class User extends \frontend\core\BaseActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created_at', 'updated_at', 'role', 'sorting', 'status', 'grow', 'studentpay'], 'integer'],
            [['birthday'], 'safe'],
            [['signature'], 'string'],
            [['account', 'nickname', 'nation', 'father', 'guardian', 'mather', 'midtid'], 'string', 'max' => 24],
            [['password', 'auth_key'], 'string', 'max' => 32],
            [['password_hash', 'password_reset_token', 'email', 'address', 'orgin', 'quarters'], 'string', 'max' => 255],
            [['username', 'qqnum', 'image', 'midschool', 'specialty', 'schfrom'], 'string', 'max' => 50],
            [['jpin'], 'string', 'max' => 12],
            [['qpin', 'wechat', 'university', 'workunit', 'remark'], 'string', 'max' => 120],
            [['sex'], 'string', 'max' => 2],
            [['idcard', 'mobile', 'homephone', 'fathphone', 'gdphone', 'mathphone', 'workphone', 'alternativephone'], 'string', 'max' => 36],
            [['stdid'], 'string', 'max' => 38],
            [['level', 'position', 'nexus'], 'string', 'max' => 20],
            [['trasaction_id'], 'string', 'max' => 10]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'account' => Yii::t('app', 'Account'),
            'password' => Yii::t('app', '初始密码'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'password_hash' => Yii::t('app', 'Password Hash'),
            'password_reset_token' => Yii::t('app', 'Password Reset Token'),
            'created_at' => Yii::t('app', '创建时间'),
            'updated_at' => Yii::t('app', '修改时间'),
            'role' => Yii::t('app', '角色'),
            'username' => Yii::t('app', '用户名'),
            'jpin' => Yii::t('app', '性别'),
            'qpin' => Yii::t('app', '角色'),
            'nickname' => Yii::t('app', '姓名'),
            'sex' => Yii::t('app', '性别'),
            'idcard' => Yii::t('app', 'Idcard'),
            'stdid' => Yii::t('app', 'Stdid'),
            'nation' => Yii::t('app', 'Nation'),
            'birthday' => Yii::t('app', 'Birthday'),
            'mobile' => Yii::t('app', '联系电话'),
            'qqnum' => Yii::t('app', 'QQ号码'),
            'wechat' => Yii::t('app', 'Wechat'),
            'homephone' => Yii::t('app', 'Homephone'),
            'email' => Yii::t('app', 'Email'),
            'image' => Yii::t('app', 'Image'),
            'sorting' => Yii::t('app', 'Sorting'),
            'address' => Yii::t('app', 'Address'),
            'orgin' => Yii::t('app', 'Orgin'),
            'father' => Yii::t('app', 'Father'),
            'fathphone' => Yii::t('app', 'Fathphone'),
            'guardian' => Yii::t('app', 'Guardian'),
            'gdphone' => Yii::t('app', 'Gdphone'),
            'midschool' => Yii::t('app', 'Midschool'),
            'status' => Yii::t('app', 'Status'),
            'mather' => Yii::t('app', 'Mather'),
            'mathphone' => Yii::t('app', 'Mathphone'),
            'university' => Yii::t('app', 'University'),
            'specialty' => Yii::t('app', 'Specialty'),
            'level' => Yii::t('app', 'Level'),
            'workunit' => Yii::t('app', '部门'),
            'quarters' => Yii::t('app', 'Quarters'),
            'position' => Yii::t('app', 'Position'),
            'nexus' => Yii::t('app', 'Nexus'),
            'workphone' => Yii::t('app', 'Workphone'),
            'remark' => Yii::t('app', 'Remark'),
            'grow' => Yii::t('app', 'Grow'),
            'schfrom' => Yii::t('app', 'Schfrom'),
            'midtid' => Yii::t('app', 'Midtid'),
            'trasaction_id' => Yii::t('app', 'Trasaction ID'),
            'studentpay' => Yii::t('app', 'Studentpay'),
            'alternativephone' => Yii::t('app', 'Alternativephone'),
            'signature' => Yii::t('app', 'Signature'),
        ];
    }
	
	 /*
     * 保存之前数据赋值
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert){
                $this->password_hash = Yii::$app->security->generatePasswordHash($this->password);
                $this->auth_key = Yii::$app->security->generateRandomString();
                $this->created_at = $this->updated_at = time();
            }  else
                $this->updated_at = time();
            return true;
        } else {
            return false;
        }
    }
}
