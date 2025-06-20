<!DOCTYPE html>
<html lang="de">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dunkles Produktformular</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background-color: #1e1e1e; /* Dunkles Grau */
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
      background-color: #2a2a2a; /* Etwas heller als Body */
      padding: 2rem;
      border-radius: 12px;
      box-shadow: 0 0 12px rgba(0, 0, 0, 0.5);
      width: 100%;
      max-width: 700px;
    }

    h2 {
      text-align: center;
      margin-bottom: 2rem;
      color: #f1f1f1;
    }

    .logout-row {
      display: flex;
      align-items: left;
      margin-bottom: 0rem;
      flex-wrap: wrap;
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

    .form-row select {
      flex: 1;
      padding: 0.5rem;
      border: 1px solid #444;
      background-color: #3a3a3a;
      color: #eee;
      border-radius: 6px;
    }

    .form-row select:focus {
      border-color: #5fa8d3;
      outline: none;
      background-color: #444;
    }

    .submit-row {
      text-align: center;
      margin-top: 2rem;
    }

    button {
      background-color:rgb(98, 101, 102);
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
  </style>
</head>
<body>
  <?php
  ///////////////////////////////////////////////////////////////////////////////
  // This needs to be at the top of this page!!
  ///////////////////////////////////////////////////////////////////////////////

  session_start();
  if(isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header("Location: suche.php");
    exit;
  }

  if(isset($_POST['suche'])) {
    header("Location: suche.php");
  }

  if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
  }

  ///////////////////////////////////////////////////////////////////////////////
  ?>

  <form method="POST"  style="text-align:right;">
    <div class="logout-row">
      <button type="submit" name="logout" value="1">Logout</button>
      <div style="margin-right: 50px; width: 300px"></div>
      <button type="submit" name="suche" value="1">Suche</button>
    </div>
  </form>

  <br>

  <form id="produktForm" method="POST" action="">

    <h2>Produktinformationen</h2>

    <div class="form-row">
      <label for="p_code">Produkt Code:</label>
      <input type="text" id="p_code" name="p_code" required>
    </div>

    <div class="form-row">
      <label for="name">Name:</label>
      <input type="text" id="name" name="name" required>
    </div>

    <div class="form-row">
      <label for="linie">Linie:</label>
      <select id="linie" name="linie" required>
        <option value="">Bitte wählen Sie eine Produktlinie</option>
        <?php
        // Fetch product lines from database
        require "connection.php";
        $charset = "utf8mb4";
        $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";
        
        try {
          $conn = new PDO($dsn, $admin, $admin_pwd);
          $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
          
          $stmt = $conn->prepare("SELECT productLine FROM productlines ORDER BY productLine");
          $stmt->execute();
          $productLines = $stmt->fetchAll(PDO::FETCH_COLUMN);
          
          foreach ($productLines as $productLine) {
            echo "<option value=\"" . htmlspecialchars($productLine) . "\">" . htmlspecialchars($productLine) . "</option>";
          }
        } catch (Exception $e) {
          echo "<option value=\"\">Fehler beim Laden der Produktlinien</option>";
        }
        ?>
      </select>
    </div>

    <div class="form-row">
      <label for="massstab">Maßstab:</label>
      <input type="text" id="massstab" name="massstab" required>
    </div>

    <div class="form-row">
      <label for="lieferant">Lieferant:</label>
      <input type="text" id="lieferant" name="lieferant" required>
    </div>

    <div class="form-row">
      <label for="details">Details:</label>
      <input type="text" id="details" name="details" required>
    </div>

    <div class="form-row">
      <label for="quantity_in_stock">Auf Lager:</label>
      <input type="text" id="quantity_in_stock" name="quantity_in_stock" required>
    </div>
    
    <div class="form-row">
      <label for="buy_price">EK:</label>
      <input type="text" id="buy_price" name="buy_price" required>
    </div>

    <div class="form-row">
      <label for="msrp">UVP:</label>
      <input type="text" id="msrp" name="msrp" required>
    </div>

    <!--Button  -->
    <div class="submit-row">
      <button type="submit">Anlegen</button>
    </div>
  </form>
  <?php



///////////////////////////////////////////////////////////////////////////////
// Functionality
///////////////////////////////////////////////////////////////////////////////
  require "connection.php";

  if(!empty($_POST)) {
    $p_code = htmlspecialchars(trim($_POST['p_code']));
    $name = htmlspecialchars(trim($_POST['name']));
    $linie = htmlspecialchars(trim($_POST['linie']));
    $massstab = htmlspecialchars(trim($_POST['massstab']));
    $lieferant = htmlspecialchars(trim($_POST['lieferant']));
    $details = htmlspecialchars(trim($_POST['details']));
    $quantity_in_stock = htmlspecialchars(trim($_POST['quantity_in_stock']));
    $buy_price = htmlspecialchars(trim($_POST['buy_price']));
    $msrp = htmlspecialchars(trim($_POST['msrp']));


    $charset = "utf8mb4";

    $dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

    try {
      $conn = new PDO($dsn, $admin, $admin_pwd);
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = <<<HERE
      INSERT INTO `products` (`productCode`, `productName`, `productLine`, `productScale`, `productVendor`, `productDescription`, `quantityInStock`, `buyPrice`, `MSRP`)
      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
      HERE;

      $stmt = $conn->prepare($sql);
      $stmt->execute([$p_code, $name, $linie, $massstab, $lieferant, $details, $quantity_in_stock, $buy_price, $msrp]);
      echo "<p style = color:green;'>Produkt erfolgreich hinzugefügt!</p>";
    } catch (Exception $e) {
      echo "<p stlye='color:red;'>Exception caught: ".$e->getMessage()."</p>";
    }
  }
  ?>
</body>
</html>