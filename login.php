<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classic Models - Mitarbeiter-Login</title>
    <style>
      body {
        font-family: 'Segoe UI', sans-serif;
        background-color: #1e1e1e;
        color: #ccc;
        margin: 0;
        padding: 2rem;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
      }
      form {
        background-color: #2a2a2a;
        padding: 2rem;
        border-radius: 12px;
        box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
        width: 100%;
        max-width: 500px;
      }
      h1 {
        text-align: center;
        margin-bottom: 2rem;
        color: #f1f1f1;
        font-size: 2rem;
      }
      .form-row {
        display: flex;
        align-items: center;
        margin-bottom: 1.2rem;
        flex-wrap: wrap;
      }
      .form-row label {
        width: 120px;
        font-weight: 600;
        color: #ddd;
      }
      .form-row input {
        flex: 1;
        padding: 0.5rem;
        border: 1px solid #444;
        background-color: #3a3a3a;
        color: #eee;
        border-radius: 6px;
      }
      .form-row input:focus {
        border-color: #5fa8d3;
        outline: none;
        background-color: #444;
      }
      .submit-row {
        text-align: center;
        margin-top: 2rem;
      }
      button, input[type="submit"] {
        background-color:rgb(98, 101, 102);
        color: white;
        border: none;
        padding: 0.75rem 2rem;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background-color 0.3s ease;
      }
      button:hover, input[type="submit"]:hover {
        background-color: #3b7ca7;
      }
      @media (max-width: 600px) {
        .form-row {
          flex-direction: column;
          align-items: flex-start;
        }
        .form-row label {
          width: 100%;
          margin-bottom: 0.5rem;
        }
        .form-row input {
          width: 100%;
        }
      }
    </style>
</head>

<body>

<h1>Classic Models - Mitarbeiter Login</h1>

<br>

<form method="POST" action="">
    <div class="form-row">
        <label for="username">Email:</label>
        <input type="text" placeholder="Benutzernamen eingeben" name="username" id="username" required>
    </div>
    <div class="form-row">
        <label for="password">Passwort:</label>
        <input type="password" placeholder="Passwort eingeben" name="password" id="password" autocomplete="current-password" required>
        <input type="checkbox" onclick="show_password()">Passwort anzeigen
    </div>
    <div class="submit-row">
        <input type="submit" value="Absenden">
    </div>
</form>

<br>

<?php
///////////////////////////////////////////////////////////////////////////////
// Database modifications to make exercise work:
// - adding column "hash" to the employee table of 'classicmodels'
///////////////////////////////////////////////////////////////////////////////

require "connection.php";

$charset = "utf8mb4";

$dsn = "mysql:host=$servername;dbname=$dbname;";

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
    $stmt->execute(['db' => $dbname]);
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

    }
    provide_test_user();
} catch (Exception $e) {
    echo "Exception caught: ".$e->getMessage();
}
///////////////////////////////////////////////////////////////////////////////




///////////////////////////////////////////////////////////////////////////////
// Functionality: 
///////////////////////////////////////////////////////////////////////////////

    if(!empty($_POST)) {
        $username = htmlspecialchars(trim($_POST["username"]));
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
    ///////////////////////////////////////////////////////////////////////////
    // Helper Functions
    ///////////////////////////////////////////////////////////////////////////

    function new_line() {
        echo "<br>";
    }

    function provide_test_user() {
        new_line();
        echo "Test User: gbondur@classicmodelcars.com";
        new_line();
        echo "Password: '1111'";
        new_line();
    }

    ///////////////////////////////////////////////////////////////////////////
?>

  <!-- A little JavaScript cheating... -->

<script>
    function show_password() {
        var element = document.getElementById("password");
        if(element.type === "password") {
           element.type = "text";
        } else {
          element.type = "password";
        }
    }
</script>

  <!-- Ends here -->


</body>