<?php
/**
 * @var string $email
 */
?>

<h1>We sent you an activation email. Please be sure to check it</h1>
<h3>
    Didn't receive any email?
    <a href="/olx/send-activation-email?email=<?= urlencode($email) ?>">
        <button>
            Resend my link!
        </button>
    </a>
</h3>