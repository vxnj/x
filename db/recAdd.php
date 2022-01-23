<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<html>
<head>
<title>Listo!</title>
</head>

<?php
// Create connection & check
$item=$_POST['item'];
echo $item;

require_once('../../../resources/config.php');
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

if ($item != '') {
     $sql = "INSERT INTO listo (item, done) VALUES ('" . $item . "', 'p')";
     $result = $conn->query($sql);
}

$conn->close();

header('location:../test.php');

?>

</html>