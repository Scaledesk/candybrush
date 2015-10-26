<!DOCTYPE html>
<html lang="en-US">
<head>
    <meta charset="utf-8">
</head>
<body>
<h2>Verify Your Email Address</h2>

<div>
    Thanks for creating an account at candybrush.
    Please follow the link below to verify your email address.
    <?php
    echo 'http://localhost:3000/#/activate?code='.$confirmation_code;
?>
    .<br/>
</div>

</body>
</html>
