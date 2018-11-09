<?php

namespace frontend\controllers;

use common\models\mysql\modeldb\Lession;

class LessionController extends BaseController
{
    public function actionIndex($id)
    {
        $lession = Lession::GetById($id);
        return $this->render('index',['lession'=>$lession]);
    }

}
