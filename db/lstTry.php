<?php
require '../../resources/config.php';
$conn = new mysqli( $host, $user, $pwd, $db); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

//pass where .. user=currentuser
//pass sort etc
$sql = "SELECT * FROM listoSort limit 0,30";
$result = $conn->query($sql);
while($row = $result->fetch_assoc()) {$result_array[] = $row;} 	
echo json_encode($result_array);
?>