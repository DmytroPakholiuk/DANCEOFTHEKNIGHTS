<?php

namespace http\controllers;

use exceptions\NotFoundException;
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

    public function actionSubscribe()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST"){
            var_dump($_POST);die();
            //todo process the form application
        } elseif ($_SERVER['REQUEST_METHOD'] === "GET"){
            $this->render("subscriptionForm");
        }
    }

    public function actionSendActivationEmail()
    {
        if (!isset($_GET["email"])){
            $this->redirect("/olx/index");
        }
        $email = $_GET["email"];
        //todo actually send the email

        $this->render("activationEmailSent", ["email" => $email]);
    }

    /**
     * Accepts query string parameters "email" and "confirmation" and then checks
     * whether confirmation matches the saved data and activates the email if it matches
     * @return void
     * @throws \RedisException
     * @throws \ReflectionException
     */
    public function actionConfirmEmail()
    {
        try {
            $email = $_GET["email"];
            $confirmation = $_GET["confirmation"];

            $model = Email::getModel($email);
            $model->activate($confirmation);

            if ($model->active){
                $this->render("emailActivated", ["email" => $email]);
            }
        } catch (NotFoundException) {

        } finally {
            $this->render("incorrectEmailActivation", ["email" => $email]);
        }
    }
}