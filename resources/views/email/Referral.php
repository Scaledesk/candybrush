<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Sign up</h2>

<div>
    You have been referred by to create account in out website.
    Click the link below to make account<br/>
    <?php
    echo '<a>http://localhost:3000/#/referralSignUp?code='.$referral_code.'</a>';
    ?>
    .<br/>
</div>

</body>
</html>
