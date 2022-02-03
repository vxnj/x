<!-- < ?php
require '../../resources/config.php';
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}
$sql = "SELECT * FROM listoSort limit 0,30";
$result = $conn->query($sql);
echo "Affected rows: " . $conn -> affected_rows . '<br>';
while($row = $result->fetch_assoc()) {
    echo  $row['updated'] . ' ' . $row['id'] . ' ' . $row['item'] . '<br>';
} //while
? >
 -->


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>

// $.ajax({ 
//     method: "POST",
//     url: "db/lstAct.php",
//     data: "actn=" + actn +"&id=" + id,
//     success: function(output, status, xhr) {
//         console.log(xhr);
//         vx = xhr;
//         location.reload(); 
//     }
// }); //ajax -->

let vx;
function getKey(site) {
    $.getJSON("../k.json", function(json) {
        console.log (site);
        vx = (json[site].key);
        console.log(vx);
    })
}

vx = getKey('mkt');

</script>