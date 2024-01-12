<?php

namespace models;

/**
 * The class that represents an email in database. May be activated or not.
 * The ID of the model is the email itself
 */
class Email extends StringMessage
{
    public bool $active = false;
    public string $confirmationHash;
    protected function fields(): array
    {
        return array_merge(parent::fields(), [
            "active",
            "confirmationHash"
        ]);
    }

    public static function prefix(): string
    {
        return "email_";
    }

    public function generateConfirmationHash(string $confirmation)
    {
        $confirmHash = hash("md5", $confirmation);
        $this->confirmationHash = $confirmHash;
    }

    public function activate(string $confirmation): void //todo change it
    {
        if ($this->confirmActivation($confirmation)){
            $this->active = true;
            $this->saveModel();
        }
    }

    protected function confirmActivation(string $confirmation): bool
    {
        $confirmHash = hash("md5", $confirmation);
        if ($this->confirmationHash === $confirmHash) {
            return true;
        }
        return false;
    }
}