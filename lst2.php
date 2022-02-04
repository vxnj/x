<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/x-icon" href="img/lst.svg">  
    <!-- <script src="js/funcs.js"></script> -->
    <!-- <script src="js/fDate.js"></script> -->
    <link rel="stylesheet" href="/css/_main.css" />
    <link rel="stylesheet" href="/css/lst.css" />
    <title>Listo!</title>

</head>

<body>

<header>
    <div id='catGroup'></div>
    <input type="text" id="itemNew" placeholder="add item ..." autofocus>    

</header>

<?php 
    include 'db/lstRef.php'; 
    include "zzExamples/zzModal.html";
    include "lstHeads.html" 
    
?>

<script>
    let heads = document.getElementsByClassName("userhead");
    for (let i = 0; i < heads.length; i++) {
        heads[i].addEventListener('click', function(e){alert(heads[i].id)});
    }
</script>


<script>
    document.title = 'LISTO 2!'
    //console.log (window.innerWidth);

    //add
    document.getElementById("itemNew").addEventListener('keypress', addnew);
    
    //update
    let items = document.getElementsByClassName("itemopen");
    for (let i = 0; i < items.length; i++) {
                items[i].addEventListener('focusout', doEdit );
                items[i].addEventListener('keydown',  doEditK );

    }

    items = document.querySelectorAll("[name^=svg");
    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener('click', doBtn );
    }
    
    let vx= 'as';
    function doBtn(e) { 
        id =   e.currentTarget.parentElement.id;
        actn = e.currentTarget.attributes.name.value.substring(3, 10);
        if (actn == 'det'){ 
            modal.style.display = "block";

        } else {
            $.ajax({ 
                method: "POST",
                url: "db/lstAct.php",
                data: "actn=" + actn +"&id=" + id,
                statusCode: {404: function() {alert( "page not found" );}} , 
                success: function(output, status, xhr) {
                    console.log(xhr);
                    vx = xhr;
                    location.reload(); 
                }
            }); //ajax
        } //if
       
    } //doBtn

    function doEditK(e) { if (e.code == 'Period' || e.code == 'Enter') {doEdit(e);} };
    function doEdit(e)  { 
        id =      e.originalTarget.parentElement.id; 
        newVal =  e.originalTarget.value;
        change = (e.originalTarget.value !== e.originalTarget.defaultValue)
        if(change) {
            $.ajax({ 
                type: "POST",
                url: "db/lstAct.php",
                data: "actn=upd&id=" + id + "&item=" + newVal + ""
            });
        }
    }

    function addnew(e) {
        if (e.key != 'Enter' || e.target.value == '') { return; }
        $.ajax({ 
            type: "POST",
            url: "db/lstAct.php",
            data: "actn=add&id=&item=" + e.target.value + "",

            success: function(output, status, xhr) {
                    console.log(xhr.status);
                    location.reload(); } ,

            error: function (xhr, status, thrownError) {
                console.log(xhr.status);}
        }) //ajax
        e.target.value = '';
   
    }

</script>

</body>
</html>