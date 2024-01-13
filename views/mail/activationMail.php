<?php
/**
 * @var string $email
 * @var string $appUrl
 * @var string $confirmation
 */
?>
<h1>Your activation mail for DANCEOFTHEKNIGHTS has arrived</h1>

<h3> Just click the
    <a href="<?= $appUrl ?>/olx/confirm-email?email=<?= $email ?>&confirmation=<?= $confirmation ?>">
        activation link
    </a>
    to confirm the email.
</h3>
