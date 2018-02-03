<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\User;
use backend\models\AuthAssignment;
/**
 * Signup form
 */
class SignupForm extends \yii\db\ActiveRecord
{
    // public $username;
    // public $email;
    public $password;
    public $permissions;


    /**
     * @inheritdoc
     */

    public static function tableName(){
        return 'user';
    }

    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if ($this->validate()) {
        
            $user = new User();
            $user->username = $this->username;
            $user->email = $this->email;
            $user->setPassword($this->password);
            $user->generateAuthKey();
            $user->save();

            // add the permission

            $permissionList = Yii::$app->request->post()['SignupForm']['permissions'];

            foreach ($permissionList as $permission) {
                $newPermission = new AuthAssignment;
                $newPermission->user_id = $user->id;
                $newPermission->item_name = $permission;
                if ($newPermission->save()) {
                }else{
                    var_dump(\yii\widgets\ActiveForm::validate($newPermission));die;
                }
            }

            return $user;

        }

        return null;
    }
}
