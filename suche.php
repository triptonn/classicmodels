
<!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
<!--Link einfügen -->
        
        <a href="login.php">Login</a>

        <h1> Willkommen bei Classicmodels</h1>

        <form action="?" method="post">
          <p>Wähle eine Kategorie:</p>

          <input type="radio" id="productCode" name="kategorie" value="productCode">
          <label for="productCode">Produktnr.</label><br>

          <input type="radio" id="productName" name="kategorie" value="productName" checked>
          <label for="productName">Name</label><br>

          <input type="radio" id="productLinie" name="kategorie" value="productLinie">
          <label for="productLinie">Produkt Linie</label><br>

          <input type="radio" id="producScale" name="kategorie" value="productScale">
          <label for="productScale">Maßstab</label><br>

          <input type="radio" id="producDescription" name="kategorie" value="productDescription">
          <label for="productDescription">Maßstab</label><br>

          <input type="radio" id="producVendor" name="kategorie" value="productVendor">
          <label for="productVendor">Hersteller</label><br>

            <p>Suchbegriff eingeben:</P>
            <input type="text" name="suchbegriff" placeholder="z.B. Harley Davidson"><br><br>
          <button type="submit">Suchen</button>
        </form>

        <?php
        include "connection.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Formulardaten
            $kategorie = $_POST['kategorie'] ?? '';
            $suchbegriff = trim($_POST['suchbegriff'] ?? '');

            try {
                
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $user, $password);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "DB Verbindung erfolgreich";

                //SQL
                $sql = "SELECT * FROM products WHERE $kategorie like :suchbegriff";
                $stmt = $conn ->prepare($sql);
                $stmt->execute([':suchbegriff' => "%$suchbegriff%"]);

                echo "<table border='1' cellpadding='5'>";
                echo "<tr>
                        <th> Produktnr. </th>
                        <th> Name </th>
                        <th> Produktline </th>
                        <th> Maßstab </th>
                        <th> Hersteller </th>
                        <th> Beschreibung </th>
                        <th> Anzahl </th>
                        <th> Preis </th>
                        <th> UVP </th>
                    </tr>";
                
                while ($row = $stmt-> fetch(PDO::FETCH_ASSOC)) {
                    echo "<tr>";
                    foreach ($row as $value) {
                        echo "<td>" . $value . "</td>";

                    }
                    echo "</tr>";
                }
                echo"</table>";

            } catch(PDOException $e) {
                echo "Die Verbindung war nicht erfolgreich: ". $e->getMessage();

            }

        }

        ?>
        </body>
    </html>
