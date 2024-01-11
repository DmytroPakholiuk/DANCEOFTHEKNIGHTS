<?php

namespace http\controllers;

use models\Email;
use models\EmailsForItem;

class OlxController extends Controller
{
    public function actionTest()
    {
        $model = new Email();
        $model->active = false;
        $model->id = "dmytro0pakholiuk@gmail.com";
        $model->saveModel();
        var_dump(Email::getModel("dmytro0pakholiuk@gmail.com"));

        $list = new EmailsForItem();
        $list->id = "goods1";
        $list->appendModel($model);

        var_dump(EmailsForItem::getModel("goods1"));
    }

    public function actionIndex()
    {
        echo "index";
    }
}