<?php
/**
 * @var \models\GoodsItem $oldGoods
 * @var \models\GoodsItem $newGoods
 */
?>

<h1>The advert "<?= $oldGoods->name ?>" has changed its price</h1>

    <ul>
        <li>Old price: <i> <?= $oldGoods->price ?> </i> </li>
        <li>New price: <i> <?= $newGoods->price ?> </i> </li>
    </ul>

<?php if ($oldGoods->name != $newGoods->name){
    echo "It also changed its name to <b>{$newGoods->name}</b>";
} ?>


