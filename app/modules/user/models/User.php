<?php

/**
 * This is the model class for table "users".
 *
 * The followings are the available columns in table 'users':
 * @property integer $id
 * @property string $role
 * @property string $username
 * @property string $email
 * @property string $password
 * @property string $last_activity
 * @property string $server
 */
class User extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return User the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'users';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username, email, password, role', 'required'),
			array('username, email, password, role', 'length', 'max'=>255),
			array('server', 'length', 'max'=>100),

            array('username', 'unique', 'message' => __('user',"This user's name already exists.")),
            array('email', 'unique', 'message' => __('user',"This user's email address already exists.")),
            array('email', 'email', 'allowEmpty' =>false),

			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, password, last_activity, server', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

    public function beforeSave()
    {
        $this->last_activity = time();

        if($this->isNewRecord)
        {
            $this->password = $this->hash($this->password);
        }


        return parent::beforeSave();
    }


	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'role' => 'Role',
			'username' => 'Username',
			'email' => 'Email',
			'password' => 'Password',
			'last_activity' => 'Last Activity',
			'server' => 'Server',
		);
	}

    public function hash($password)
    {
        return md5($password);
    }

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('role',$this->role,true);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('last_activity',$this->last_activity);
		$criteria->compare('server',$this->server,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}