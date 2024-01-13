<?php
/**
 * @var \models\Email $emailModel
 * @var \models\GoodsItem $goodsModel
 */
?>

<h1>Subscription successful</h1>
<h3>You have successfully subscribed to advert <i><?= $goodsModel->name ?></i></h3>
<b>at <?= $emailModel->id ?></b>
