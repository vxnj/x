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
    input[type=submit] { display:none; }

    #head {
        /* background-color: #52579aa1; */
        width: 100%;
        height: 30px;
        /* position: fixed; */
        padding: 0px;
        top: 0;
        left: 0;
        z-index: 5;
    }

    #item {
        background: black; */
        color: #fff;
        /* margin-right: -6px !important; */
        border: 0;
        margin: 0;
        height: 24px;
        width: 100%;
        /* max-width: 490px !important; */
        font-size: 18px;
        font-family: 'Open Sans'; 
        padding: 0;
    }
    
    #action {
        float: right;
        margin-left: 5px;
    }

    
    .tblx, .tblX tr, .tblX td, .tblX th { 
        line-height: 32px !important;
        font-size:   18px !important;
        vertical-align: center !important;
    }

    #itemlist { 
        /* top: 39px; */
        position: relative;
    }
</style>

<header>
    <form id="head" action="db/recAdd.php" method="post">
                <input type="text"   name="item" id="item" placeholder="add item ...">
                <input type="submit" name="subm" id="subm" value=""/>
            </form>
    </header>

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
    echo "<table id='itemlist' class='tblX'>";
    // output data of each row
    while($row = $result->fetch_assoc()) {

        $svg = $row['fin']==0 ? 'lstfin' : 'lstundo';
        $act = $row['fin']==0 ? 'Fin'    : 'Undo';
        $a1  = '<a href="db/rec' . $act . '.php?id='  . $row['id'] . '"><img alt="' . $svg . '" id="action" src="images/' . $svg . '.svg" height="28px"</a>';
        $a2  = $row['fin']==0 ? '' : '<a href="db/recDel.php?id=' . $row['id'] . '"><img alt="Del" src="images/lstdel.svg" id="action" height="28px"</a>';

        echo '<tr><td>' . $row['item'] . '</td>';
        echo    '<td>' . $a1 . $a2 . '</td>';
        // echo    '<td>' . $mmdd . '</td>';
        echo '</tr>';
    }
    //echo "<br><br>" . $_SERVER['HTTP_USER_AGENT'];
} else {
    echo "0 results";  
}

?>
</html>