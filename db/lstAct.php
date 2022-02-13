<?php

$actn = (empty($_POST['actn'])) ? ''   : $_POST['actn'] ;
$item = (empty($_POST['item'])) ? ''   : $_POST['item'] ;
$id =   (empty($_POST['id']))   ? ''   : $_POST['id']   ;
$qry =  (empty($_POST['qry']))  ? ''   : $_POST['qry']  ;
$usr =  (empty($_POST['usr']))  ? ''   : $_POST['usr']  ;
$cat =  (empty($_POST['cat']))  ? ''   : $_POST['cat']  ;

require '../../resources/config.php';
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

switch ($actn) {
    case 'add':  $sql = "INSERT INTO listo (item, usr, category, fin) VALUES ( ? , ? , ?, 0 )";      break;
    case 'upd':  $sql = "UPDATE `listo` SET `item`= ?         WHERE id= ? ";   break;
    case 'fin':  $sql = "UPDATE `listo` SET `fin`=now()       WHERE id= ? ";   break;
    case 'und':  $sql = "UPDATE `listo` SET `fin`=0           WHERE id= ? ";   break;
    case 'del':  $sql = "UPDATE `listo` SET `removed`='Y'     WHERE id= ? ";   break;
    default:     $sql = ''; break;
}

$stmt = $conn->prepare($sql);
if          ($actn=='add')  { $stmt->bind_param('sss', $item, $usr, $cat); }
elseif      ($actn=='upd')  { $stmt->bind_param('s',   $item, $id); }
else                        { $stmt->bind_param('i',   $id); }
$stmt->execute();
// $result = $stmt->get_result();

?>