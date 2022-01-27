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
$sql = '';

// Create connection & check
require_once('../../../resources/config.php');
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}


switch ($actn) {
    case 'add':  if($item>'') {$sql = "INSERT INTO listo (item, fin) VALUES ('" . $item . "', 0)";}  break;
    case 'upd':  $sql = "UPDATE `listo` SET `item`='" . $item . "' WHERE id=?";   break;
    case 'fin':  $sql = "UPDATE `listo` SET `fin`=now()            WHERE id=?";   break;
    case 'und':  $sql = "UPDATE `listo` SET `fin`=0                WHERE id=?";   break;
    case 'del':  $sql = "UPDATE `listo` SET `removed`='Y'          WHERE id=?";   break;
    default:     $sql = ''; break;
}

$stmt = $conn->prepare($sql);
$stmt->bind_param('i', $id);
$stmt->execute();
$result = $stmt->get_result();

header('location:../test.php'); //return
?>

</html>