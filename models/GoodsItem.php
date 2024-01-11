<?php

namespace models;

class GoodsItem extends StringMessage
{
    public string $price;
    public string $name;
    public function fields(): array
    {
        return array_merge(parent::fields(), [
            "name",
            "price"
        ]);
    }

    public static function prefix(): string
    {
        return "goods_item_";
    }
}