<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<html>
<head>
<title>Listo!</title>
</head>

<?php
$item = (empty($_GET['item'])) ? '' : $_GET['item'];
$id = (empty($_GET['id'])) ? '' : $_GET['id'];
$actn = (empty($_GET['actn'])) ? '' : $_GET['actn'];

// Create connection & check
require_once('../../../resources/config.php');
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

switch ($actn) {
    case 'add':  $sql = "INSERT INTO listo (item, fin) VALUES ('" . $item . "', 0)";  break;
    case 'upd':  $sql = "UPDATE `listo` SET `item`='" . $item . "' WHERE id='$id'";   break;
    case 'fin':  $sql = "UPDATE `listo` SET `fin`=now()            WHERE id='$id'";   break;
    case 'und':  $sql = "UPDATE `listo` SET `fin`=0                WHERE id='$id'";   break;
    case 'del':  $sql = "UPDATE `listo` SET `removed`='Y'          WHERE id='$id'";   break;
    default: ''; break;
}

//$sql = "INSERT INTO listo (item, fin) VALUES ('" . $item . "', 0)";
$result = $conn->query($sql);

$conn->close();

header('location:../test.php'); //return
?>

</html>