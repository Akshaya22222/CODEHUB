<?php
$password = "codeadmin123"; // Change this to your desired password
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Hashed password for codeadmin123: <br>" . $hash;
?>
