<?php

use components\Mailer;
use components\OlxDomParser;
use models\EmailsForItem;
use models\GoodsItem;

$olxParser = new OlxDomParser();
$mailer = new Mailer();

while (true){
    echo "Starting the checking cycle \n\n";
    /**
     * @var $goodsModel GoodsItem
     */
    foreach (GoodsItem::getAllModels() as $goodsModel){
        try {
            $newGoodsModel = new GoodsItem();
            $newGoodsModel->id = $goodsModel->id;
            $olxParser->populateGoodsItem($newGoodsModel);

            if ($newGoodsModel->price != $goodsModel->price){
                echo "Detected advert with changed price: \n";
                echo "Old price: {$goodsModel->price} \n";
                echo "New price: {$newGoodsModel->price} \n";

                $mailer->reset();
                $emailsForItem = EmailsForItem::getModel($goodsModel->id);
                $receivers = [];
                foreach ($emailsForItem->containedModels as $email){
                    $receivers[] = $email->id;
                }

                $mailer->setMultipleReceivers($receivers);
                $mailer->setSubject("The advert you subscribed on has changed its price");
                $mailer->setMessage($mailer->render("priceChanged", [
                    "oldGoods" => $goodsModel,
                    "newGoods" => $newGoodsModel
                ]));

                $mailer->sendMail();
                echo "Mail sent!\n\n";
            } else {
                echo "The price hasn't changed on $goodsModel->name\n";
            }

            sleep(5);
        } catch (Exception $exception) {
            echo "Something went terribly wrong!\n\n";
            echo $exception->getMessage() . "\n";
            echo $exception->getTraceAsString();
            continue;
        }
    }

    sleep(60);
}