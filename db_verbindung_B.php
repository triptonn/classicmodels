<?php

include "connection.php";

try {
    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $user, $password);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "DB Verbindung erfolgreich";


    //SQL
    $sql = "SELECT * FROM products";
    $stmt = $conn ->query($sql);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th> Product Code </th>
            <th> Product Name </th>
            <th> Product Line </th>
            <th> Product Scale </th>
            <th> Product Vendor </th>
            <th> Product Description </th>
            <th> Quantity in Stock </th>
            <th> Buy Price </th>
            <th> MSRP </th>
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
