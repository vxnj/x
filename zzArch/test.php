<!DOCTYPE html>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<html>
<head>
<title>Listo!</title>
</head>

<script src="js/funcs.js"></script>
<script src="js/fDate.js"></script>
<!-- <link rel="stylesheet" href="style.css" /> -->

<body>
<form method="post">
    <input type="text"   name="item" id="item" placeholder="item ..." style="color:blue">
    <input type="submit" name="test" id="test" value="RUN" /><br/>
</form>
</body>

<?php
echo $_SERVER['HTTP_USER_AGENT'] . "<br><br><br>";

function addRec() {
    $item = $_POST['item'];
    // Create connection & check
    require_once('../../resources/config.php');
    $conn = new mysqli( $servername, $username, $password, $database); 
    if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

    if ($item != '') {
        $sql = "INSERT INTO listo (item, done) VALUES ('" . $item . "', 'p')";
        $result = $conn->query($sql);
    }

    // redisplay records
    $sql = "SELECT * FROM listo ORDER BY updated DESC limit 0,10";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        // output data of each row
        while($row = $result->fetch_assoc()) {
            echo $row["updated"] . " :: " . $row["id"] . " :: " . $row["item"] .  "<br>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
}

function delRec($id){
    echo $id;
}

if(array_key_exists('test',$_POST)){
    addRec();
}

?>

</html>