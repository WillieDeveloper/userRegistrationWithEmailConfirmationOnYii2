<?php

namespace app\models;

use yii\db\ActiveRecord;

class UserAuth extends ActiveRecord {

    const CODE_MIN = 10000;
    const CODE_MAX = 99999;
    const STATUS_ACTIVATED = true;

    public static function tableName() {
        return 'userauth';
    }

    public function setPassword($password) {
        $this->password = sha1($password);
    }
}