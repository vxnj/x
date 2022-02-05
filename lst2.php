<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/x-icon" href="img/lst.svg">  
    <link rel="stylesheet" href="/css/_main.css" />
    <link rel="stylesheet" href="/css/lst.css" />
    <title>Listo!</title>
</head>

<body>
<header>
    <div class="myWidth">
        <img id="currentUser" onclick="userSettings()"></img>
        <input type="text" id="itemNew" placeholder="add item ..." autofocus>    
    </div></header>

<?php 
    include 'db/lstRef.php'; 
    include "zzExamples/zzModal.html";
    include "lstHeads.html" 
?>
<script>
    document.title = 'LISTO 2!'

    //add
    document.getElementById("itemNew").addEventListener('keypress', addnew);
    window.onload = getUser;

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
    
    let vx= 'init';
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
            data: `actn=add&id=&item=${e.target.value}&usr=${usr}` ,
            success: function(output, status, xhr) {
                    console.log(xhr.status);
                    location.reload(); } ,

            error: function (xhr, status, thrownError) {
                console.log(xhr.status);}
        }) //ajax
        e.target.value = '';
   
    }

    function userSettings() {
        pick = document.getElementById('userSettings');
        pick.style.display = (pick.style.display =='none') ? 'flex' : 'none';
        if (pick.style.display == 'none') {pick.focus();}
    }

    let usr; let usrGet;
    function getUser() {
        usr =    localStorage.getItem("userx") || 'vx';
        usrGet = `head-${usr}`;
        document.getElementById("currentUser").src = `img/usrPics/${usrGet}.png`;
        document.getElementById('userSettings').style.display = 'none'; 
        newCol = `var(--${usrGet})`;
        console.log (newCol);
        document.documentElement.style.setProperty('--head0',newCol);
        document.getElementById("itemNew").focus();
      }

    let f;
    function setUser(e) {
        f = e;
        usr = f.target.id;
        localStorage.setItem("userx", usr);
        getUser();  
    }       

    let heads = document.getElementsByClassName("userhead");
    for (let i = 0; i < heads.length; i++) {
        heads[i].addEventListener('click', setUser)
    }
</script>

</body>
</html>