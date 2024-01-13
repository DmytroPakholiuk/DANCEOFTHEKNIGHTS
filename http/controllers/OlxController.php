<?php

namespace http\controllers;

use components\Config;
use components\Mailer;
use exceptions\NotFoundException;
use models\Email;
use models\EmailsForItem;

class OlxController extends Controller
{
    public function actionTest()
    {
//        $mailer = new Mailer();
//        var_dump($mailer->render("testMail"));
//        $mailer->setReceiver("dmytro0pakhoilluk@gmail.com")
//            ->setSubject("Test Mail Lol")
//            ->setMessage($mailer->render("testMail"));
//        var_dump($mailer->sendMail());
        $model = new Email();
        $model->active = false;
        $model->id = "dmytro0pakhoilluk@gmail.com";
        $model->generateConfirmationHash($model->generateConfirmationKey());
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
        $appUrl = Config::getConfigArray("main")["base_url"];

        $mailer = new Mailer();
        $mailer->setReceiver($email)
            ->setSubject("Confirm your email, please");

        $emailModel = Email::getModel($email);

        if (!$emailModel?->active){
            if ($emailModel === null){
                $emailModel = new Email();
                $emailModel->id = $email;
                $emailModel->active = false;
            }
            $confirmationKey = $emailModel->generateConfirmationKey();
            $emailModel->generateConfirmationHash($confirmationKey);
            $emailModel->saveModel();

            $mailer->setMessage($mailer->render("activationMail", [
                "email" => $email,
                "confirmation" => urlencode($confirmationKey),
                "appUrl" => $appUrl
            ]));
            $mailer->sendMail();

            $this->render("activationEmailSent", [
                "email" => $email,
            ]); die();
        }

        $this->render("emailActivated", ["email" => $email]); die();
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
                $this->render("emailActivated", ["email" => $email]); die();
            }
        } catch (NotFoundException) {

        } finally {
            $this->render("incorrectEmailActivation", ["email" => $email]);
        }
    }
}