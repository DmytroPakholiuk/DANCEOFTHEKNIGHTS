<?php

namespace models;

class GoodsItem extends StringMessage
{

    public static function prefix(): string
    {
        return "goods_item_";
    }
}