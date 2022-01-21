
<?php
// $servername = "sql5.freemysqlhosting.net";
// $database = "sql5466078";
// $username = "sql5466078";
// $password = "G3bSlhDS8P";

$servername = "212.1.208.51";
$database = "u571834012_test";
$username = "u571834012_vaxier";
$password = "W234hopp=";


// $servername = "sql113.epizy.com";
// $database = "epiz_30364343_test";
// $username = "epiz_30364343";
// $password = "deBr4YQkA2rZpny";


// Create connection
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO listo (item, done) 
    VALUES ('currenttime()', 'n')";

$result = $conn->query($sql);

$sql = "SELECT * FROM listo";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
    echo "done: " . $row["done"] . "    - item: " . $row["item"]. "<br>";
  }
} else {
  echo "0 results";
}

?>  