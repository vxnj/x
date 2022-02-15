<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <script src=https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js></script>
    <link rel="icon" type="image/x-icon" href="img/lst.svg">  
    <link rel="stylesheet" href="/css/_reset.css"/>
    <link rel="stylesheet" href="/css/lst.css"/>
    <title>Listo!</title>
</head>

<body>
    <header>
        <div style="padding-bottom: 5px"> 
            <span    id="cats"></span>   
            <button id="btnThm" class="btnx" ></button>
        </div>
        <img    id="currentUser" onclick="userSettings()" src=""></img>
        <input  id="itemNew" class="item" type="text"placeholder="add item ..." autofocus>
        <button id="btnShr" class="btn btnSelected"  ></button>

    </header>

    <div id="itemList">
        <ul id="itemsOpen"></ul>
        <ul id="itemsDone"></ul>
    </div>

<!-- <footer></footer> -->

</body>

<?php include "lstHeads.html" ?>

<script>

data = []; id='';
usr =''; shr=''; ctg=''; thm='';
lastLoad = '';

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
cats.forEach(cat => catBtns += `<button id="cat-${cat.value}" value="${cat.value}" class="btn catBtns">${cat.text}</button>`);
$( "#cats" ).html(catBtns);

function getIdx ( arr, fld, val) {
    var index = data.findIndex(p => p[fld] == val);
    return index;
}

function addnew(e) {
    $.ajax({     
        type: "POST",
        url: "db/lstAct.php",
        data: `actn=add&id=&item=${e.target.value}&usr=${usr}&cat=${ctg}` ,
        success: function() { doAjax(loadTbl) ;}
    })//ajax
    e.target.value = '';
} //addnew

function doBtn(e) { 
    id =   e.currentTarget.parentElement.id;
    actn = e.currentTarget.attributes.name.value.substring(3, 10);
    if (actn == 'det'){ 
        x = getIdx ( data, 'id', id);
        console.log(data[x])
    } else { 
        $.ajax({ 
            method: "POST",
            url: "db/lstAct.php",
            data: "actn=" + actn +"&id=" + id,
            success: function(output, status, xhr) { doAjax(loadTbl); }
        }); //ajax


    } //if
    
} //doBtn

function doEdit(e)  {  
    if (e.type == 'keydown' && e.code != 'Enter') { return }
    id = e.target.id; 
    x =  getIdx( data , 'id' , e.target.id);
    newVal = e.target.value;
    change = (newVal !== data[x].item)
    
    if(change) {
        $.ajax({  
            type: "POST",
            url: "db/lstAct.php",
            data: `actn=upd&id=${id}&item=${newVal}`,
            success: function() { doAjax( function(){console.log('edit saved')}); }
        });

        document.getElementById(id).animate(
            [{ color: 'var(--col-save)' }, { color: 'var(--col-dim0)' }], 
            { duration: 2000, easing: 'ease-in', iterations: 1}
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
    thm = localStorage.getItem('lsThm') || 'dark';
}       
function chgUsr(newusr) {
    localStorage.setItem("lsUsr", newusr);
    usr = localStorage.getItem("lsUsr");
    doAjax(loadTbl);
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

function chgThm(tog) {
    thm = localStorage.getItem("lsThm");
    if (tog) { 
        thm = (thm == 'dark') ? 'lite' : 'dark';
        localStorage.setItem("lsThm", thm);
    }

    if (thm == 'dark') {
        newcols = [ '70%', '50%', '30%', '15%', '0%']; 
    } else {
        newcols = [ '0%', '30%', '50%', '70%', '90%'];
    }
    document.documentElement.style.setProperty('--col-dim0',`hsl(0,0%,${newcols[0]})`);
    document.documentElement.style.setProperty('--col-dim1',`hsl(0,0%,${newcols[1]})`);
    document.documentElement.style.setProperty('--col-dim2',`hsl(0,0%,${newcols[2]})`);
    document.documentElement.style.setProperty('--col-hdr', `hsl(0,0%,${newcols[3]})`);
    document.documentElement.style.setProperty('--col-bkg', `hsl(0,0%,${newcols[4]})`);
}


//------------------------
//  Refresh table
function loadTbl() {
    if (data.length ==0 ) {return}
    getLocals();
    ulOpen=''; ulDone='';

    $("#btnShr").html( (shr=='true') ? 'All' : 'Me' );
    
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

    $("#itemsOpen").html ( ulOpen );  
    $("#itemsDone").html ( ulDone );  
    $(".itemopen").on("focusout keydown",  function(e){ doEdit(e);});    

    items = $("[name^=svg]");
    for (let i = 0; i < items.length; i++) {
        isMine = items[i].parentElement.classList.contains('itemmine');
        if (isMine) {
            $( items[i] ).click ( doBtn ); 
        }else{
            items[i].classList.add("btnDisabled");
        }
    }
    $( '#userSettings').css("display","none");     
    $( "#currentUser" ).attr('src', `img/usrPics/head-${usr}.png`);

    lastLoad = (new Date());

} //loadTbl

$( "#itemNew" ).keypress(function(e) { if (e.key != 'Enter' || e.target.value == '') { return; }; addnew(e);})
$( ".catBtns" ).click(function(e)    { chgCtg(e.currentTarget); })
$( ".userhead").click(function(e)    { chgUsr(e.currentTarget.id); })
$( "#btnShr"  ).click(function(e)    { chgShr(); })
$( "#btnThm"  ).click(function(e)    { chgThm(true); })

$(' #userSettings').css('top', $('#currentUser').position().top )


// setInterval(reloadit, 5000);
// function reloadit(){
//     console.log(lastLoad)
// }


$(document).on('keydown onclick',  
    function(e) { 
        if (e.key == 'Escape' || e.target.id != 'currentUser') { 
            $('#userSettings').css("display","none");
        }    
    }
)

// hide userheads when other clicks
$( "*" ).click(function(e) { if (e.target.id != 'currentUser') { $('#userSettings').css("display","none"); } })


x = 0;
window.onload = function() {
    getLocals();
    $('#cat-todo').click();
    chgUsr(usr);
    chgThm(false)
    
    x =  $('header').css('height') + ' - ' + $('footer').css('height')

    $('#itemList').css('top', $('header').css('height') )
    console.log(x)
}

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

</html>