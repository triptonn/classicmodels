<?php

include "connection.php";

try {
    $conn = new PDO("mysql:host=$servername; dbname=$dbname", $user, $password);
    $conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "DB Verbindung erfolgreich";


    //SQL
    $sql = "SELECT * FROM employees WHERE officeCode = 1";
    $stmt = $conn ->query($sql);

    echo "<table border='1' cellpadding='5'>";
    echo "<tr>
            <th> Employee Number </th>
            <th> Last Name </th>
            <th> First Name </th>
            <th> Extension </th>
            <th> Email </th>
            <th> Office Code </th>
            <th> Reports To </th>
            <th>Job Title </th>
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
