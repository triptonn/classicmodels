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
// - adding column "hash" to the employee table of 'classicmodels'
///////////////////////////////////////////////////////////////////////////////


$host = "localhost";
$db = "classicmodels";
$admin = "root";
$admin_pwd = "";
$charset = "utf8mb4";

$dsn = "mysql:host=$host;dbname=$db;";

try {
    $conn = new PDO($dsn, $admin, $admin_pwd);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $sql = <<<HERE
    SELECT COUNT(*) as col_count
    FROM INFORMATION_SCHEMA.COLUMNS
    WHERE TABLE_SCHEMA = :db
    AND TABLE_NAME = 'employees'
    AND COLUMN_NAME = 'hash'
    HERE;

    $stmt = $conn->prepare($sql);
    $stmt->execute(['db' => $db]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if(!($result['col_count'] > 0)) {
        new_line();
        new_line();
        echo "DB needs to be modified...";
        new_line();

        try {
        $create_column_sql = <<<HERE
        ALTER TABLE `employees` ADD `hash` VARCHAR(60) NOT NULL AFTER `jobTitle`;
        HERE;

        $stmt = $conn->prepare($create_column_sql);
        $stmt->execute();
        } catch (Exception $e) {
            echo "Exception caught: ".$e->getMessage();
            new_line();
        }

        echo "Created 'hash' column in table employees.";
        new_line();

        $add_test_pwd_sql = <<<HERE
        UPDATE `employees`
        SET `hash` = '$2y$10$/ulWpIi3aWR3Xi6qghXZkueK3twjLYUnlTbiToUaI.wyOeX9WPdr6'
        WHERE `employeeNumber` = 1102;
        HERE;

        $stmt = $conn->prepare($add_test_pwd_sql);
        $stmt->execute();

        echo "Fake Password: '1111' added for employee with email: gbondur@classicmodelcars.com";
        new_line();
    }


} catch (Exception $e) {
    echo "Exception caught: ".$e->getMessage();
}

///////////////////////////////////////////////////////////////////////////////
?>

<?php

///////////////////////////////////////////////////////////////////////////////
// Functionality: 
///////////////////////////////////////////////////////////////////////////////

    if(!empty($_POST)) {
        $username = htmlspecialchars(trim($_POST["username"]));
        // $pwd_hash = password_hash(htmlspecialchars(trim($_POST["password"])), PASSWORD_BCRYPT);
        $pwd = htmlspecialchars(trim($_POST["password"]));

        $host = "localhost";
        $db = "classicmodels";
        $charset = "utf8mb4";

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES      => false,
        ];

        if($username != NULL) {
            session_start();

            try {
                $conn = new PDO($dsn, $admin, $admin_pwd, $options);
                
                $sql = "SELECT * FROM employees WHERE email = ?";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$username]);
                $user = $stmt->fetch();

                if($user && password_verify($pwd, $user['hash'])) {
                    $_SESSION['user_id'] = $user['employeeNumber'];
                    header("Location: produkt_anlegen.php");
                    exit;
                } else {
                    $error = "Falscher Benutzername oder Passwort!";
                }

                if(!empty($error)) {
                    echo "<p style='color:red;'>$error</p>";
                }

            } catch (Exception $e) {
                echo "<p stlye='color:red;'>Exception caught: ".$e->getMessage()."</p>";
            }
        }
    }
?>

<?php

    function new_line() {
        echo "<br>";
    }
?>

</body>