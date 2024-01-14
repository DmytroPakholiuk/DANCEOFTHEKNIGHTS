<?php

namespace components;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    protected string $viewDirectoryPath = "/../views/mail/";
    protected string $emailRegEx = "/^\S+@\S+\.\S+$/";
    protected string $from = "no-reply@danceoftheknights.com";
    protected PHPMailer $mailer;

    public function setReceiver(string $email): self
    {
        if (preg_match($this->emailRegEx, $email)){
            $this->mailer->addAddress($email);
            return $this;
        }
        throw new \Exception("The email you tried to set was incorrect");
    }

    public function getReceivers()
    {
        return $this->mailer->getAllRecipientAddresses();
    }

    public function setSubject(string $subject): self
    {
        $this->mailer->Subject = $subject;
        return $this;
    }

    public function getSubject(): string
    {
        return $this->mailer->Subject;
    }

    public function setMessage(string $content): self
    {
        $this->mailer->Body = $content;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->mailer->Body;
    }

    public function sendMail(): bool
    {
        return $this->mailer->send();
    }

    public function reset(): self
    {
        $this->mailer->clearAllRecipients();
        $this->mailer->Subject = "";
        $this->mailer->Body = "";

        return $this;
    }

    public function setMultipleReceivers(array $receivers)
    {
        if (empty($receivers)){
            return;
        }
        $this->mailer->addAddress($receivers[0]);
        unset($receivers[0]);
        foreach ($receivers as $receiver){
            $this->mailer->addBCC($receiver);
        }
    }

    public function render(string $viewName, array $params = []): string
    {
        extract($params, EXTR_OVERWRITE);
        ob_start();
        ob_implicit_flush(false);
        require __DIR__ . $this->viewDirectoryPath . $viewName . ".php";
        return ob_get_clean();
    }

    public function __construct()
    {
        $this->mailer = new PHPMailer();
        $this->mailer->isSMTP();
        $this->mailer->Host = "smtp.gmail.com";
        $this->mailer->SMTPAuth = true;
        $this->mailer->Port = 587;
        $this->mailer->SMTPSecure = "tls";
        $gmailConfig = Config::getConfigArray("gmail_client");
        $this->mailer->Username = $gmailConfig["email"];
        $this->mailer->Password = $gmailConfig["app_key"];
        $this->mailer->isHTML();
        $this->mailer->setFrom($gmailConfig["from_email"], $gmailConfig["from_name"]);
    }
}