<?php

namespace models;

class Email extends StringMessage
{
    public bool $active;
    protected function fields(): array
    {
        return array_merge(parent::fields(), [
            "active"
        ]);
    }

    public static function prefix(): string
    {
        return "email_";
    }
}