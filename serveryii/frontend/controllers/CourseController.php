<?php

namespace frontend\controllers;

use common\models\mysql\modeldb\Lession;

class CourseController extends BaseController
{
    public function actionIndex($id)
    {
        $listLession = Lession::GetListByCourseId($id);
        return $this->render('index',[
            'listLession'=>$listLession
        ]);
    }

}
