<?php
require '../../resources/config.php';
$conn = new mysqli( $host, $user, $pwd, $db); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

$usr = (empty($_POST['usr'])) ? ''   : $_POST['usr'] ;
$sort ="Order By srtFin, CASE WHEN usr='" . $usr . "' THEN '_a' ELSE usr END, id DESC";

$sql = "SELECT * FROM listoSort " . $sort;
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {$result_array[] = $row;} 	
echo json_encode($result_array);
?>
