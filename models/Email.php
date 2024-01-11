<?php

namespace models;

class Email extends StringMessage
{
    public bool $active;
    public static function prefix(): string
    {
        return "email_";
    }
}