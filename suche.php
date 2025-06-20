
<!DOCTYPE html>
    <html lang="de">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
<!--Link einfügen -->
        <?php
        session_start();
        if(isset($_POST['user_id'])) {
            // Show logout button
            echo '<form method="post" action="">
                <button type="submit" name="logout">Logout</button>
                </form>';
        } else {
            // Show login button
            echo  '<a href="login.php">Login</a>';
            
            if (isset($_POST['logout'])) {
                session_unset();    
                session_destroy();
                header("Location: suche.php");
                exit;
            }
        }
        
        ?>
        <h1> Willkommen bei Classicmodels</h1>

        <form action="?" method="post">
        <fieldset>
            <legend>Wähle eine Kategorie:</legend>

            <input type="radio" id="productCode" name="kategorie" value="productCode">
            <label for="productCode">Produktnr.</label><br>

            <input type="radio" id="productName" name="kategorie" value="productName" checked>
            <label for="productName">Name</label><br>

            <input type="radio" id="productLine" name="kategorie" value="productLine">
            <label for="productLine">Produktlinie</label><br>

            <input type="radio" id="productScale" name="kategorie" value="productScale">
            <label for="productScale">Maßstab</label><br>

            <input type="radio" id="productDescription" name="kategorie" value="productDescription">
            <label for="productDescription">Beschreibung</label><br>

            <input type="radio" id="productVendor" name="kategorie" value="productVendor">
            <label for="productVendor">Hersteller</label><br>
        </fieldset>

        <label for="suchbegriff">Suchbegriff eingeben:</label>
        <input type="text" id="suchbegriff" name="suchbegriff" placeholder="z.B. Harley Davidson"><br><br>

        <button type="submit">Suchen</button>
        </form>

        <?php
        include "connection.php";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // Formulardaten
            $kategorie = $_POST['kategorie'] ?? '';
            $suchbegriff = trim($_POST['suchbegriff'] ?? '');

            try {
                
                $conn = new PDO("mysql:host=$servername;dbname=$dbname", $admin, $admin_pwd);
                $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                echo "DB Verbindung erfolgreich";
                
                $whitelist = ['productCode', 'productName', 'productLine', 'productScale', 'productDescription', 'productVendor'];
                if (!in_array($kategorie, $whitelist)) {
                    die("Ungültige Kategorie.");
                }


                //SQL
                $sql = "SELECT * FROM products WHERE LOWER($kategorie) like LOWER(:suchbegriff)";
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
