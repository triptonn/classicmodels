<?php
session_start();

if (!empty($_POST)) {
    $username = htmlspecialchars(trim($_POST["username"]));
    $password = htmlspecialchars(trim($_POST["password"]));

    $host = "localhost";
    $db = "classicmodels";
    $admin = "root";
    $admin_pwd = "";
    $charset = "utf8mb4";
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";

    try {
        $conn = new PDO($dsn, $admin, $admin_pwd, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $stmt = $conn->prepare("SELECT * FROM employees WHERE email = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['hash'])) {
            $_SESSION['user_id'] = $user['employeeNumber'];
            header("Location: produkt_anlegen.php");
            exit;
        } else {
            $error = "Falscher Benutzername oder Passwort!";
        }

    } catch (Exception $e) {
        $error = "Fehler: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <title>Login</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Du nutzt genau das gleiche CSS wie für die Produktseite -->
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #1e1e1e;
      color: #ccc;
      margin: 0;
      padding: 2rem;
      display: flex;
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

    h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: #f1f1f1;
    }

    .form-row {
      display: flex;
      align-items: center;
      margin-bottom: 1.2rem;
      flex-wrap: wrap;
    }

    .form-row label {
      width: 180px;
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

    button {
      background-color: rgb(98, 101, 102);
      color: white;
      border: none;
      padding: 0.75rem 2rem;
      font-size: 1rem;
      border-radius: 8px;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
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

    .error {
      color: red;
      text-align: center;
      margin-top: 1rem;
    }
  </style>
</head>
<body>

  <form method="POST">
    <h2>Login</h2>

    <div class="form-row">
      <label for="username">E-Mail:</label>
      <input type="text" id="username" name="username" required>
    </div>

    <div class="form-row">
      <label for="password">Passwort:</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div class="submit-row">
      <button type="submit">Einloggen</button>
    </div>

    <?php if (!empty($error)): ?>
      <p class="error"><?= $error ?></p>
    <?php endif; ?>
  </form>

</body>
</html>
