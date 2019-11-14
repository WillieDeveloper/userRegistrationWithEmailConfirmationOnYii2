<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\UserAuthLayer;

class AuthController extends Controller {

    public function actionRegister() {

        $model = new UserAuthLayer();

        if(isset($_POST['UserAuthLayer'])) {
            $model->attributes = Yii::$app->request->post('UserAuthLayer');
            
            if ($model->validate() && $model->signup()) {
                return $this->render('register'); 
            }
        }
        
        return $this->render('auth', compact('model'));
    }

    public function actionConfirm() {
        $request = isset($_GET['code']) ? str_replace('3D', '', $_GET['code']) : 0;
        if (isset($request) && $request != 0 && UserAuthLayer::checkCode(trim($request))) {
            return $this->render('confirm');
        }
        return $this->render('fail');
    }
}