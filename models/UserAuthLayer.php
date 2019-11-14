<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

class UserAuthLayer extends model {
    
    public $name;
    public $email;
    public $password;
    public $activated;
    public $verifyCode;
    
    private $activation_key;

    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            ['email', 'unique', 'targetClass' => 'app\models\UserAuth'],
            ['name', 'string', 'min' => 2, 'max' => 20],
            ['email', 'email'],
            ['password', 'string', 'min' => 6, 'max' => 20],
            ['verifyCode', 'captcha'],
        ];
    }

    public function signup() {
        $model = new UserAuth();
        
        $code = 0;
        $model->name = $this->name;
        $model->email = $this->email;
        $model->setPassword($this->password);
        $model->activated = false;
        $code = random_int(UserAuth::CODE_MIN, UserAuth::CODE_MAX);
        $model->activation_key = $code;
        
        $this->contact($model, $code);
        
        return $model->save();
    }

    public function attributeLabels()
    {
        return [
            'verifyCode' => 'Verification Code',
        ];
    }

    private function contact($model, $code) {
            Yii::$app->mailer->compose()
                ->setTo($model->email)
                ->setFrom([Yii::$app->params['senderEmail'] => Yii::$app->params['senderName']])
                ->setReplyTo([$model->email => $model->name])
                ->setSubject('Registration on MySite')
                ->setTextBody('Your registration link: ' . Url::to(['auth/confirm', 'code' => $code], true))
                ->send();
    }

    public static function checkCode($code) {
        if (!((int) $code >= UserAuth::CODE_MIN && (int) $code <= UserAuth::CODE_MAX)) {
            return false;
        }
        $user = UserAuth::findOne([
            'activation_key' => $code,
        ]);
        if (!$user) {
            return false; 
        }
        $user->activated = UserAuth::STATUS_ACTIVATED;
        return $user->save();
    }
}
