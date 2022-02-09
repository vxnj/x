<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="icon" type="image/x-icon" href="img/lst.svg">  
    <link rel="stylesheet" href="/css/_reset.css"/>
    <link rel="stylesheet" href="/css/lst.css"/>
    <title>Listo!</title>
</head>

<body>

<header>
    <img    id="currentUser" onclick="userSettings()"></img>
    <input  id="itemNew" class="item" type="text"placeholder="add item ..." autofocus>
    <button id="btnMine" class="btn"  onclick="setShowOthers()"></button>
</header>


<div id="tblHolder"></div>

<?php
    // include "zzExamples/zzModal.html";
    include "lstHeads.html" 
?>

<script>
window.onload = getUser;

document.getElementById("itemNew").addEventListener('keypress', addnew);
 
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
                doAjax(loadTbl); 
            }
        }); //ajax
    } //if
    
} //doBtn

function doEditK(e) {if (e.code == 'Enter') { doEdit(e);} };
function doEdit(e)  {     
    id =      e.originalTarget.id; 
    newVal =  e.originalTarget.value;
    change = (e.originalTarget.value !== e.originalTarget.defaultValue)
    if(change) {
        $.ajax({  
            type: "POST",
            url: "db/lstAct.php",
            data: `actn=upd&id=${id}&item=${newVal}`
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
            doAjax(loadTbl); } ,
        error: function (xhr, status, thrownError) {console.log(xhr.status);}
    }) //ajax
    e.target.value = '';
}

let heads = document.getElementsByClassName("userhead");
for (let i = 0; i < heads.length; i++) {
    heads[i].addEventListener('click', setUser)
}

//------------------------
//  Local Storage stuff

function userSettings() {
    pick = document.getElementById('userSettings');
    pick.style.display = (pick.style.display =='none') ? 'block' : 'none';
    if (pick.style.display == 'none') {pick.focus();}
}

let usr; let showOthers;
function getUser() {
    usr = localStorage.getItem("lsUser") || 'vx';
    document.getElementById("currentUser").src = `img/usrPics/head-${usr}.png`;
    document.getElementById('userSettings').style.display = 'none'; 
    newCol = `var(--head-${usr})`;
    document.documentElement.style.setProperty('--head0',newCol);
    document.getElementById("itemNew")
    
    doAjax(loadTbl);
}

function setUser(e) {
    usr = e.target.id;
    localStorage.setItem("lsUser", usr);
    getUser();  
}       

function setShowOthers() {
    newval = (localStorage.getItem("lsShowOthers") == 'true') ? 'false' : 'true' ;
    localStorage.setItem("lsShowOthers", newval); 
    showOthers = localStorage.getItem("lsShowOthers")
    doAjax(loadTbl); 
}

//------------------------
//  Refresh table

// let data = [];
svgfin = '<svg name="svgfin"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 36 36" xml:space="preserve">  <g transform="matrix(1 0 0 1 18 18)" id="JT9cCN1KaDrU2tfbZ6gpJ" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(40,97,0); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 0 C 7.199999999999999 0 0 7.2 0 16 C 0 24.8 7.2 32 16 32 C 24.8 32 32 24.8 32 16 C 32 7.199999999999999 24.8 0 16 0 z M 14.2 23.4 L 5.799999999999999 15.2 L 8.599999999999998 12.399999999999999 L 13.999999999999998 17.799999999999997 L 24 7.9999999999999964 L 26.8 10.799999999999997 z" stroke-linecap="round" /> </g> </svg>';
svgdet = '<svg name="svgdet"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 36 36" xml:space="preserve"> <desc>Created with Fabric.js 4.6.0</desc> <defs> </defs> <g transform="matrix(1 0 0 1 16.72 18)" id="LwUe2HalQGUY_lAsXu04r" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(0,0,0); fill-rule: nonzero; opacity: 0;" vector-effect="non-scaling-stroke" transform=" translate(-12, -12)" d="M 0 24 L 0 0 L 24 0 L 24 24 z" stroke-linecap="round" /> </g> <g transform="matrix(1 0 0 1 16.72 18)" id="SfdVFiuNcXNQrRXpQpiUc" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(62,79,122); fill-rule: evenodd; opacity: 1;" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 0 C 7.16344 0 0 7.16344 0 16 C 0 24.83656 7.16344 32 16 32 C 24.83656 32 32 24.83656 32 16 C 32 7.16344 24.83656 0 16 0 z M 21.2875 8.96 C 21.649910000000002 8.96 22.01275 9.097740000000002 22.285 9.370000000000001 L 23.9125 10.995000000000001 C 24.45693 11.539510000000002 24.45693 12.445490000000001 23.9125 12.990000000000002 L 22.675 14.225000000000001 L 22.2 14.700000000000001 L 13.952499999999999 22.9475 L 8.514999999999999 24.76 L 10.327499999999999 19.3225 L 18.595 11.095000000000002 L 18.58 11.080000000000002 L 20.29 9.370000000000001 C 20.56225 9.097740000000002 20.92509 8.96 21.287499999999998 8.96 z M 21.2875 10.23 C 21.25657 10.23 21.22689 10.245560000000001 21.1975 10.275 L 19.94 11.530000000000001 L 21.765 13.3275 L 23.0075 12.085 C 23.06634 12.026760000000001 23.06634 11.95888 23.0075 11.9 L 21.38 10.275 C 21.35061 10.245560000000001 21.31843 10.23 21.287499999999998 10.23 z M 19.05 12.45 L 12.0625 19.405 L 13.875 21.2175 L 20.8575 14.232500000000002 z M 11.3 20.455 L 10.540000000000001 22.737499999999997 L 12.825000000000001 21.974999999999998 z" stroke-linecap="round" /> </g> </svg>';
svgund = '<svg name="svgund"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 36 36" xml:space="preserve"><desc>Created with Fabric.js 4.6.0</desc><defs/><g transform="matrix(1 0 0 1 16.72 18)" id="LwUe2HalQGUY_lAsXu04r"><path style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-dashoffset:0;stroke-linejoin:miter;stroke-miterlimit:4;fill:#000;fill-rule:nonzero;opacity:0" vector-effect="non-scaling-stroke" transform=" translate(-12, -12)" d="M 0 24 L 0 0 L 24 0 L 24 24 z" stroke-linecap="round"/></g><g transform="matrix(1 0 0 1 18 18)" id="m-KAR6gpYS_E84f2eiFJe"><path style="stroke:none;stroke-width:1;stroke-dasharray:none;stroke-linecap:butt;stroke-dashoffset:0;stroke-linejoin:miter;stroke-miterlimit:4;fill:#685811;fill-rule:nonzero;opacity:1" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 32 C 24.83656 32 32 24.83656 32 16 C 32 7.16344 24.83656 0 16 0 C 7.16344 0 0 7.16344 0 16 C 0 24.83656 7.16344 32 16 32 z M 10.096 13.024 L 14.896 8.448 C 14.96542 8.38028 15.046790000000001 8.326030000000001 15.136000000000001 8.288 C 15.21128 8.22006 15.29794 8.1659 15.392000000000001 8.128 C 15.77926 7.95684 16.220740000000003 7.95684 16.608 8.128 C 16.8044 8.20415 16.98383 8.31833 17.136 8.464 L 21.936 13.264 C 22.5634 13.891399999999999 22.5634 14.9086 21.936 15.536 C 21.3086 16.1634 20.2914 16.1634 19.664 15.536 L 17.6 13.456 L 17.6 22.4 C 17.6 23.283659999999998 16.883660000000003 24 16 24 C 15.11634 24 14.4 23.28366 14.4 22.4 L 14.4 13.344 L 12.304 15.344 C 11.66335 15.953719999999999 10.64972 15.92865 10.040000000000001 15.288 C 9.430280000000002 14.64735 9.455350000000001 13.63372 10.096 13.024000000000001 z" stroke-linecap="round"/></g></svg>';
svgdel = '<svg name="svgdel"  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" width="32" height="32" viewBox="0 0 36 36" xml:space="preserve"> <desc>Created with Fabric.js 4.6.0</desc> <defs> </defs> <g transform="matrix(1 0 0 1 18 18)" id="IYPy1S1g1e4DMe_O8pmT3" > <path style="stroke: none; stroke-width: 1; stroke-dasharray: none; stroke-linecap: butt; stroke-dashoffset: 0; stroke-linejoin: miter; stroke-miterlimit: 4; fill: rgb(152,46,15); fill-rule: nonzero; opacity: 1;" vector-effect="non-scaling-stroke" transform=" translate(-16, -16)" d="M 16 0 C 7.164 0 0 7.164 0 16 C 0 24.836 7.164 32 16 32 C 24.836 32 32 24.836 32 16 C 32 7.1640000000000015 24.836 0 16 0 z M 23.914 21.086 L 21.086000000000002 23.913999999999998 L 16 18.828 L 10.914 23.914 L 8.086 21.086000000000002 L 13.172 16 L 8.086 10.914 L 10.914 8.086 L 16 13.172 L 21.086 8.086 L 23.913999999999998 10.914 L 18.828 16 L 23.914 21.086 z" stroke-linecap="round" /> </g> </svg>';

tblBeg = '<table id="itemlist">' +'';

let datax = [];

function loadTbl(datax) {
    rowsHtml='';
    showOthers =  localStorage.getItem("lsShowOthers") || 'false';
    document.getElementById("btnMine").innerHTML = (showOthers=='true') ? 'All' : 'Me';
    
    datax.forEach(el => { 
        isFin = (el.fin<'2') ? ['itemopen', svgfin , svgdet, ''] : ['itemdone', svgund , svgdel, ' disabled'] ;
        isDis = (el.fin>'1' || (usr != el.usr)) ? [true,' disabled="disabled"'] : [false,''];
        isOwn = (usr == el.usr) ? 'itemmine' : ''; 

        owner = `img/usrPics/head-${el.usr}.png`;
        if (showOthers=='true' || usr==el.usr) {
            rowsHtml += 
                `<tr class="${isFin[0]}">
                    <td>
                        <span class="column-left"><img class="ownerhead" src="${owner}"></img></span>
                        <input type="text" id="${el.id}" class="item ${isFin[0]} ${isOwn}" value="${el.item}" ${isDis[1]}>
                        <span id="${el.id}" class="column-right ${isOwn}">${isFin[1]}${isFin[2]}</span>
                    </td>
                
                </tr>`;
        } //if
    }) //foreach    

    document.getElementById("tblHolder").innerHTML = tblBeg + rowsHtml + '</table>';  

    items = document.querySelectorAll("[name^=svg");
    for (let i = 0; i < items.length; i++) {
        isMine = items[i].parentElement.classList.contains('itemmine');
        if (isMine) {
            items[i].addEventListener('click', doBtn); 
        }else{
            items[i].classList.add("btnDisabled");
        }
    }

    items = document.getElementsByClassName("itemopen"); 
    for (let i = 0; i < items.length; i++) {
        items[i].addEventListener('focusout', doEdit );
        items[i].addEventListener('keydown',  doEditK );
    }
} //loadTbl


function doAjax(myCallback) {
    $.ajax({ 
        method: "POST",
        url: "db/lstRead.php",
        data: `&usr=${usr}`,
        success: function(output, status, xhr) {
            data = JSON.parse(output)
            myCallback(JSON.parse(output));
        } 
    })//ajax
} //doAjax


</script>

</body>
</html>