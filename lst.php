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
        <img    id="currentUser" onclick="userSettings()" src=""></img>
        <input  id="itemNew" class="item" type="text"placeholder="add item ..." autofocus>
        <button id="btnShr" class="btn"  ></button>
    </header>

    <div id="itemList">
        <ul id="itemsOpen"></ul>
        <ul id="itemsDone"></ul>
    </div>

    <footer>
        <div id="cats"></div>
    </footer>

</body>

<?php
    include "lstDetails.html";
    include "lstHeads.html" 
?>

<script>

data = [];
usr =''; shr=''; ctg='';

//init cat btns
const cats = [
  { value: 'todo',   text: 'ToDo'},
  { value: 'food',   text: 'Food'},
  { value: 'rx',     text: 'Rx'},
  { value: 'notes',  text: 'Notes'},
  { value: 'thrift', text: 'Thrift'},
  { value: 'long',   text: 'Long'}
]
catBtns = '';
cats.forEach(cat =>
    catBtns += `<button id="cat-${cat.value}" value="${cat.value}" class="btn catBtns">${cat.text}</button>`
);
document.getElementById("cats").innerHTML = catBtns;

//addnew
document.getElementById("itemNew").addEventListener('keypress', addnew);
function addnew(e) {
    if (e.key != 'Enter' || e.target.value == '') { return; }
    $.ajax({     
        type: "POST",
        url: "db/lstAct.php",
        data: `actn=add&id=&item=${e.target.value}&usr=${usr}&cat=${ctg}` ,
        success: function(output, status, xhr) {
            console.log(xhr.status, ' addnew');
            doAjax(loadTbl); }
    }) //ajax
    e.target.value = '';
}

//btn click
function getIdx ( arr, fld, val) {
    var index = data.findIndex(p => p[fld] == val);
    return index;
}
let id;
function doBtn(e) { 
    id =   e.currentTarget.parentElement.id;
    actn = e.currentTarget.attributes.name.value.substring(3, 10);
    if (actn == 'det'){ 
             
        x = getIdx ( data, 'id', id);
        document.getElementById("det-id").value =   data[x].id ;
        document.getElementById("det-usr").value =  data[x].usr ;
        document.getElementById("det-cat").value =  data[x].category ;
        modal.style.display = "block";

    } else { 
        $.ajax({ 
            method: "POST",
            url: "db/lstAct.php",
            data: "actn=" + actn +"&id=" + id,
            success: function() {
                doAjax(loadTbl);
                chgUsr; 
            }
        }); //ajax
    } //if
    
} //doBtn


let f;
function doEdit(e)  {  
    if (e.type == 'keydown' && e.code != 'Enter') { return }
    f=e;
    id =      e.target.id; 
    newVal =  e.target.value;
    change = (e.target.value !== e.target.defaultValue)
    if(change) {
        $.ajax({  
            type: "POST",
            url: "db/lstAct.php",
            data: `actn=upd&id=${id}&item=${newVal}`
        });

        document.getElementById(id).animate(
            [{ color: '#22bd2a8c' }, { color: 'var(--lst-col-dim0)' }], 
            { duration: 1400, easing: 'ease-in', iterations: 1}
        )//animate
    } //if
} //doEdit


function userSettings() {
    pick = document.getElementById('userSettings');
    pick.style.display = (pick.style.display =='none') ? 'block' : 'none';
    if (pick.style.display == 'none') {pick.focus();}
}

//------------------------
//  Local Storage stuff

function getLocals() {
    usr = localStorage.getItem("lsUsr") || 'vx';
    ctg = localStorage.getItem('lsCtg') || 'todo';
    shr = localStorage.getItem('lsShr') || 'false';
}       

function chgUsr(newusr) {
    localStorage.setItem("lsUsr", newusr);
    document.getElementById("currentUser").src = `img/usrPics/head-${newusr}.png`;
    usr = localStorage.getItem("lsUsr");
    doAjax(loadTbl);
    document.getElementById('userSettings').style.display = 'none';     
}

function chgShr() {
    shr = (localStorage.getItem("lsShr") == 'true') ? 'false' : 'true' ;
    localStorage.setItem("lsShr", shr);
    loadTbl();
}

function chgCtg(x) {
    localStorage.setItem("lsCtg", x.value); 
    document.querySelectorAll('.catBtns').forEach( function(button) {
        if (x.id == button.id) {
                    button.classList.add   ('btnSelected');
        } else {    button.classList.remove('btnSelected');
        }
    })
    loadTbl();
}

//------------------------
//  Refresh table

function loadTbl() {
    console.log('loadtbl')
    getLocals();
    ulOpen=''; ulDone='';

    document.getElementById("btnShr").innerHTML = (shr=='true') ? 'All' : 'Me';
    
    data.forEach(el => { 
        isFin = (el.fin<'2') ? ['itemopen', svgfin , svgdet, ''] : ['itemdone', svgund , svgdel, ' disabled'] ;
        isDis = (el.fin>'1' || (usr != el.usr)) ? [true,' disabled="disabled"'] : [false,''];
        isOwn = (usr == el.usr) ? 'itemmine' : ''; 
        owner = `img/usrPics/head-${el.usr}.png`;

        noShow = (shr=='false' && usr!=el.usr) || (ctg!=el.category);
        if (!noShow) {
            newRow = `<li class="${isFin[0]}">
                        <img    class="ownerhead" src="${owner}"></img>
                        <input type="text" id="${el.id}" class="item ${isFin[0]} ${isOwn}" value="${el.item}" ${isDis[1]}>
                        <span id="${el.id}" class="column-right ${isOwn}">${isFin[1]}${isFin[2]}</span>
                      </li>`
            if (el.fin<'2') { ulOpen += newRow }
            if (el.fin>'2') { ulDone += newRow }  
        } //if
    }) //foreach    
    document.getElementById("itemsOpen").innerHTML = ulOpen;  
    document.getElementById("itemsDone").innerHTML = ulDone;  


    items = document.querySelectorAll("[name^=svg]");
    for (let i = 0; i < items.length; i++) {
        isMine = items[i].parentElement.classList.contains('itemmine');
        if (isMine) {
            items[i].addEventListener('click', doBtn); 
        }else{
            items[i].classList.add("btnDisabled");
        }
    }
    $(".itemopen").on("focusout keydown",  function(e){ doEdit(e);});    

} //loadTbl

$( "#btnShr"   ).click(function(e) { chgShr(); })
$( ".catBtns"  ).on('click touchstart', function(e) { chgCtg(e.currentTarget); })
$( ".userhead" ).click(function(e) { usr = e.currentTarget.id; chgUsr(e.currentTarget.id); })


window.onload = function() {
    getLocals();
    $('#cat-todo').click();
    chgUsr(usr);
}

function doAjax(myCallback) {
    $.ajax({ 
        method: "POST",
        url: "db/lstRead.php",
        data: '&usr=' + localStorage.getItem('lsUsr'),
        success: function(output, status, xhr) {
            data = JSON.parse(output);
            myCallback(JSON.parse(output));
            
        } 
    })//ajax
} //doAjax

</script>

</html>