<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<html>
<head>
<title>Listo!</title>
</head>

<script src="js/funcs.js"></script>
<script src="js/fDate.js"></script>
<link rel="stylesheet" href="style.css" />

<body>
</body>

<?php
// Create connection & check
require_once('../resources/config.php');
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}


// display records
$sql = "SELECT * FROM listo ORDER BY updated DESC limit 0,30";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<table class='tblX'>
        <tr>
            <td>Updated</td>
            <td>ID</td>
            <td>Item</td>
            <td>Del</td>
        <tr>
        <tr>
            <td> </td>
            <td> </td>
            <td>";           
?>     
        
        <form action="/db/recAdd.php" method="post">
            <input type="text"   name="item" id="item" placeholder="add item ...">
            <input type="submit" name="subm" id="subm" value=""/>
        </form>

<?php 

    echo "
    
    </td><td></td>";
 
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo '<tr><td>' . $row['updated'] . '</td>';
        echo     '<td>' . $row['id']      . '</td>';
        echo     '<td>' . $row['item']    . '</td>';
        echo '<td><a href="db/recDel.php?id='  . $row['id'] . '">x</a></td>';

        echo '</tr>';
    }
    //echo "<br><br>" . $_SERVER['HTTP_USER_AGENT'];
} else {
    echo "0 results";
    
}

function addRec() {
    // echo $_POST['item'] . "<br><br><hr>";
    $item = $_POST['item'];
    // Create connection & check
    require('../resources/config.php');

    $conn = new mysqli( $servername, $username, $password, $database); 
    if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

    if ($item != '') {
        $sql = "INSERT INTO listo (item, done) VALUES ('" . $item . "', 'p')";
        $result = $conn->query($sql);
    }
    $conn->close();
}

if(array_key_exists('add',$_POST)){
    // echo 'pressed add<br><br>';
    // echo $_POST['item'] . "<br><br>";
    addRec();
}

?>

</html>