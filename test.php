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
<style>
    input[type=submit] { display:none;}
</style>

</body>

<?php


// Create connection & check  
require_once('../../resources/config.php');
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

// display records
// $sql = "SELECT * FROM listo ORDER BY updated DESC limit 0,30";

$sql = "SELECT * FROM listoSort limit 0,30";

$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<table class='tblX'>
        <tr>
            <td>Item</td>
            <td>Actions</td>
            <td>Upd</td>         
        </tr>
        <tr>
            <td>";           
?>     

        <form action="db/recAdd.php" method="post">
            <input type="text"   name="item" id="item" placeholder="add item ...">
            <input type="submit" name="subm" id="subm" value=""/>
        </form>

<?php 

    echo "
    
    </td><td></td>";
 
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $mmdd = date_format(date_create($row['updated']),"m/d");
    
  
        $svg = $row['fin']==0 ? 'lstfin' : 'lstundo';
        $act = $row['fin']==0 ? 'Fin'    : 'Undo';
        $a1  = '<a href="db/rec' . $act . '.php?id='  . $row['id'] . '"><img alt="' . $svg . '" src="images/' . $svg . '.svg" height="24px"</a>';
        $a2  = $row['fin']==0 ? '' : '<a href="db/recDel.php?id=' . $row['id'] . '"><img alt="Del" src="images/lstDel.svg" height="24px"</a>';

       

        echo '<tr><td>' . $row['item'] . '</td>';
        echo    '<td>' . $a1 . $a2 . '</td>';
        echo    '<td>' . $mmdd . '</td>';
        echo '</tr>';
    }
    //echo "<br><br>" . $_SERVER['HTTP_USER_AGENT'];
} else {
    echo "0 results";  
}

?>
</html>