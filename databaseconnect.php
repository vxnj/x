
<?php



$servername = "212.1.208.51";
$database = "u571834012_test";
$username = "u571834012_vaxier";
$password = "W234hopp=";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$sql = "INSERT INTO listo (item, done) 
    VALUES (now(), 'y')";

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