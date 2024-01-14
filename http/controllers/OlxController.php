<?php

namespace http\controllers;

use components\Config;
use components\Mailer;
use components\OlxDomParser;
use exceptions\NotFoundException;
use models\Email;
use models\EmailsForItem;
use models\GoodsItem;

class OlxController extends Controller
{
//    public function actionTest()
//    {
//        GoodsItem::getAllModels();
//
//        die();
//        $parser = new OlxDomParser();
//        $item = new GoodsItem();
//        $item->id = "https://www.olx.ua/d/uk/obyavlenie/lenovo-20v-4-5a-90w-usb-pin-originalnyy-blok-pitaniya-dlya-noutbuka-IDU3qhK.html";
//        $parser->populateGoodsItem($item);
//        var_dump($item);
//
//        die();
//        $model = new Email();
//        $model->active = false;
//        $model->id = "dmytro0pakhoilluk@gmail.com";
//        $model->generateConfirmationHash($model->generateConfirmationKey());
//        $model->saveModel();
//        var_dump(Email::getModel("dmytro0pakholiuk@gmail.com"));
//
//        $list = new EmailsForItem();
//        $list->id = "goods1";
//        $list->appendModel($model);
//
//        var_dump(EmailsForItem::getModel("goods1"));
//    }

    public function actionIndex()
    {
        $this->redirect("/olx/subscribe");
    }

    public function actionSubscribe()
    {
        if ($_SERVER['REQUEST_METHOD'] === "POST"){
            $email = $_POST["email"];
            $goodsLink = $_POST["link"];

            $emailModel = Email::getModel($email);
            if (! $emailModel?->active){
                $this->redirect("/olx/send-activation-email?email=" . urlencode($email)); die();
            }

            $goodsModel = GoodsItem::getModel($goodsLink);
            if ($goodsModel === null){
                $goodsModel = new GoodsItem();
                $goodsModel->id = $goodsLink;
                $parser = new OlxDomParser();
                $parser->populateGoodsItem($goodsModel);
                $goodsModel->saveModel();
            }

            $emailsForGoods = EmailsForItem::getModel($goodsLink);
            $emailsForGoods->appendUniqueModel($emailModel);

            $mailer = new Mailer();
            $mailer->setReceiver($email)
                ->setSubject("Subscription Successful")
                ->setMessage($mailer->render("subscriptionSuccessful", [
                    "goodsModel" => $goodsModel,
                    "emailModel" => $emailModel
                ]))
                ->sendMail();

            $this->render("subscriptionSuccessful", [
                "goodsModel" => $goodsModel,
                "emailModel" => $emailModel
            ]);

        } elseif ($_SERVER['REQUEST_METHOD'] === "GET"){
            $this->render("subscriptionForm");
        }
    }

    public function actionSendActivationEmail()
    {
        if (!isset($_GET["email"])){
            $this->redirect("/olx/index"); die();
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