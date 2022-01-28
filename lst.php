<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="images/lst.svg">  
    <title>Listo!</title>
</head>

<body>
    <script src="js/funcs.js"></script>
    <script src="js/fDate.js"></script>
    <link rel="stylesheet" href="css/_main.css" />
    <link rel="stylesheet" href="css/lst.css" />

    <header>
        <form id="head" action="db/listoAction.php?actn=add" method="get">
                    <input type="text"   id="item" name="item" placeholder="add item ..." autofocus>
                    <input type="submit" id="subm" name="actn" value="add">
                </form>
    </header>

<?php

// Create connection & check  

require_once('../resources/config.php');
$conn = new mysqli( $servername, $username, $password, $database); 
if ($conn->connect_error) {die("Connection failed: " . $conn->connect_error);}

// display records
$sql = "SELECT * FROM listoSort limit 0,30";

$result = $conn->query($sql);
echo '<table id="itemlist" class="tblX">
        <colgroup>
            <col white-space="nowrap" />
            <col width="70px" />
        </colgroup>
';

;
if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        if ($row['fin']==0) {
            $svgfin = '<svg class="action" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="36" height="36" viewBox="0 0 36 36" xml:space="preserve"> <desc>Created with Fabric.js 4.6.0</desc> <defs> </defs> <g transform="matrix(1 0 0 1 18 18)" id="JT9cCN1KaDrU2tfbZ6gpJ" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(40,97,0); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 0 C 7.199999999999999 0 0 7.2 0 16 C 0 24.8 7.2 32 16 32 C 24.8 32 32 24.8 32 16 C 32 7.199999999999999 24.8 0 16 0 z M 14.2 23.4 L 5.799999999999999 15.2 L 8.599999999999998 12.399999999999999 L 13.999999999999998 17.799999999999997 L 24 7.9999999999999964 L 26.8 10.799999999999997 z" stroke-linecap="round" /> </g> </svg>';
            $svgdet = '<svg class="action" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="36" height="36" viewBox="0 0 36 36" xml:space="preserve"> <desc>Created with Fabric.js 4.6.0</desc> <defs> </defs> <g transform="matrix(1 0 0 1 16.72 18)" id="LwUe2HalQGUY_lAsXu04r" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 0;" vector-effect="non-scaling-stroke" transform=" translate(-12, -12)" d="M 0 24 L 0 0 L 24 0 L 24 24 z" stroke-linecap="round" /> </g> <g transform="matrix(1 0 0 1 16.72 18)" id="SfdVFiuNcXNQrRXpQpiUc" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(62,79,122); fill-rule: evenodd; opacity: 1;" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 0 C 7.16344 0 0 7.16344 0 16 C 0 24.83656 7.16344 32 16 32 C 24.83656 32 32 24.83656 32 16 C 32 7.16344 24.83656 0 16 0 z M 21.2875 8.96 C 21.649910000000002 8.96 22.01275 9.097740000000002 22.285 9.370000000000001 L 23.9125 10.995000000000001 C 24.45693 11.539510000000002 24.45693 12.445490000000001 23.9125 12.990000000000002 L 22.675 14.225000000000001 L 22.2 14.700000000000001 L 13.952499999999999 22.9475 L 8.514999999999999 24.76 L 10.327499999999999 19.3225 L 18.595 11.095000000000002 L 18.58 11.080000000000002 L 20.29 9.370000000000001 C 20.56225 9.097740000000002 20.92509 8.96 21.287499999999998 8.96 z M 21.2875 10.23 C 21.25657 10.23 21.22689 10.245560000000001 21.1975 10.275 L 19.94 11.530000000000001 L 21.765 13.3275 L 23.0075 12.085 C 23.06634 12.026760000000001 23.06634 11.95888 23.0075 11.9 L 21.38 10.275 C 21.35061 10.245560000000001 21.31843 10.23 21.287499999999998 10.23 z M 19.05 12.45 L 12.0625 19.405 L 13.875 21.2175 L 20.8575 14.232500000000002 z M 11.3 20.455 L 10.540000000000001 22.737499999999997 L 12.825000000000001 21.974999999999998 z" stroke-linecap="round" /> </g> </svg>';
            $a1 =  '<a href="db/listoAction.php?id=' . $row['id'] . '&actn=fin">' . $svgfin ;
            $a2 =  '<a >' . $svgdet ;
            $done= 'class="itemopen" id="' .$row['id']. '"'; 
        } else {
            $svgund = '<svg class="action" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="36" height="36" viewBox="0 0 36 36" xml:space="preserve"><desc>Created with Fabric.js 4.6.0</desc><defs/><g transform="matrix(1 0 0 1 16.72 18)" id="LwUe2HalQGUY_lAsXu04r"><path style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-dashoffset:0;stroke-linejoin:miter;stroke-miterlimit:4;fill:#000;fill-rule:nonzero;opacity:0" vector-effect="non-scaling-stroke" transform=" translate(-12, -12)" d="M 0 24 L 0 0 L 24 0 L 24 24 z" stroke-linecap="round"/></g><g transform="matrix(1 0 0 1 18 18)" id="m-KAR6gpYS_E84f2eiFJe"><path style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-dashoffset:0;stroke-linejoin:miter;stroke-miterlimit:4;fill:#9f8100;fill-rule:nonzero;opacity:1" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 32 C 24.83656 32 32 24.83656 32 16 C 32 7.16344 24.83656 0 16 0 C 7.16344 0 0 7.16344 0 16 C 0 24.83656 7.16344 32 16 32 z M 10.096 13.024 L 14.896 8.448 C 14.96542 8.38028 15.046790000000001 8.326030000000001 15.136000000000001 8.288 C 15.21128 8.22006 15.29794 8.1659 15.392000000000001 8.128 C 15.77926 7.95684 16.220740000000003 7.95684 16.608 8.128 C 16.8044 8.20415 16.98383 8.31833 17.136 8.464 L 21.936 13.264 C 22.5634 13.891399999999999 22.5634 14.9086 21.936 15.536 C 21.3086 16.1634 20.2914 16.1634 19.664 15.536 L 17.6 13.456 L 17.6 22.4 C 17.6 23.283659999999998 16.883660000000003 24 16 24 C 15.11634 24 14.4 23.28366 14.4 22.4 L 14.4 13.344 L 12.304 15.344 C 11.66335 15.953719999999999 10.64972 15.92865 10.040000000000001 15.288 C 9.430280000000002 14.64735 9.455350000000001 13.63372 10.096 13.024000000000001 z" stroke-linecap="round"/></g></svg>';
            $svgdel = '<svg class="action" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="36" height="36" viewBox="0 0 36 36" xml:space="preserve"> <desc>Created with Fabric.js 4.6.0</desc> <defs> </defs> <g transform="matrix(1 0 0 1 18 18)" id="IYPy1S1g1e4DMe_O8pmT3" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(152,46,15); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 0 C 7.164 0 0 7.164 0 16 C 0 24.836 7.164 32 16 32 C 24.836 32 32 24.836 32 16 C 32 7.1640000000000015 24.836 0 16 0 z M 23.914 21.086 L 21.086000000000002 23.913999999999998 L 16 18.828 L 10.914 23.914 L 8.086 21.086000000000002 L 13.172 16 L 8.086 10.914 L 10.914 8.086 L 16 13.172 L 21.086 8.086 L 23.913999999999998 10.914 L 18.828 16 L 23.914 21.086 z" stroke-linecap="round" /> </g> </svg>';
            $a1 = ' <a href="db/listoAction.php?id=' . $row['id'] . '&actn=und">' . $svgund ;
            $a2 =  '<a href="db/listoAction.php?id=' . $row['id'] . '&actn=del">' . $svgdel ;
            $done= 'class="itemdone"';
        }
        echo '<tr>  <td ' . $done .'>
                        <input type="text" name="item" value ="' . $row['item'] . '">
                    </td>';
        echo     '<td>' . $a1 . $a2 . '</td>';
        echo '</tr>';
    }
    
} else {
    echo "0 results"; 

}
?>

<!-- in line update -->
<script>
    let loc; let id;
    let items = document.getElementsByClassName("itemopen");
    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener('focusout', doEdit );
        items[i].addEventListener('touchend', doEdit );
    }

    function doEdit(e) { 
        console.log(e);
        id =      e.originalTarget.parentElement.id; 
        newVal =  e.originalTarget.value;
        change = (e.originalTarget.value !== e.originalTarget.defaultValue)
        console.log(change)
        if(change) {
            console.log(id, newVal);
            console.log(e);
            let loc = `db/listoAction.php?id=?id=${id}&item=${newVal}&actn=upd`
            location.replace(loc);
        }
        
    }
</script>

</body>
</html>