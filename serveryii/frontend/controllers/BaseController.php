<?php

namespace frontend\controllers;

use common\components\TextUtility;

class BaseController extends \yii\web\Controller
{
    public $name;
    public $baseUrl;

    public function init()
    {
        parent::init();
        $this->layout = "@frontend/views/layouts/base";
        $this->name = get_class($this);
        $this->baseUrl = TextUtility::getBaseUrl();
    }

}
