<?php

namespace http\controllers;

use models\Email;

class OlxController extends Controller
{
    public function actionTest()
    {
        $model = new Email();
        $model->active = false;
        $model->saveModel();
        var_dump(Email::getModel("kkk"));
    }

    public function actionIndex()
    {
        echo "index";
    }
}