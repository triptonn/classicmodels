<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="style.css" />
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic Models - Mitarbeiter-Login</title>
</head>

<body>

<h1 class="page_title">Mitarbeiter Login</h1>

<form method="POST" action="">
    <table>
        <td>
        <h2 class="input_name">Email: </h2>
        </td>
        <td>
        <input type="text" placeholder="Benutzernamen eingeben" name="username" id="username" required>
        </td>
        <td>
        <h2 class="input_name">Passwort: </h2>
        </td>
        <td>
        <input type="password" placeholder="Passwort eingeben" name="password" id="password" required>
        </td>
    </table>
    <input type="submit" value="Absenden">
</form>


<?php
///////////////////////////////////////////////////////////////////////////////
// Database modifications to make exercise work:
///////////////////////////////////////////////////////////////////////////////





///////////////////////////////////////////////////////////////////////////////
?>

<?php
if(!empty($_POST)) {
    $username = htmlspecialchars(trim($_POST["username"]));
    $pwd_hash = password_hash(htmlspecialchars(trim($_POST["password"])), PASSWORD_BCRYPT);

    echo "Username: ".$username;
    new_line();
    echo "Password hash: ".$pwd_hash;
}
?>


<?php

    function new_line() {
        echo "<br>";
    }
?>

</body>