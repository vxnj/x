<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<html>
<head>
<title>Listo!</title>
</head>

<?php
// Create connection & check
require_once('../../../resources/config.php');
$conn = new mysqli( $servername, $username, $password, $database); 
$id=$_GET['id'];
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

echo ($id);
$sql = "UPDATE `listo` SET `fin`=now() WHERE id='$id'";
$result = $conn->query($sql);
$conn->close();

header('location:../test.php');

?>

</html>