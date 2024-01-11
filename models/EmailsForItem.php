<?php

namespace models;

class EmailsForItem extends ListMessage
{
    public static function prefix(): string
    {
        return "emails_for_item_";
    }
}