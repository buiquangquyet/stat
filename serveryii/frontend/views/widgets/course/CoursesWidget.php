<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 09/11/2018
 * Time: 10:57
 */

namespace frontend\views\widgets\course;


use common\models\mysql\modeldb\Course;
use frontend\views\widgets\BaseWidget;

class CoursesWidget extends BaseWidget
{
    public function run()
    {
        $Courses = Course::find()->all();


        return $this->render("CoursesWidgetView", [
            'Courses'=>$Courses
        ]);
    }
}