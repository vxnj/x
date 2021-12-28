// Get Url Parameter
function gup (name) {
    name = RegExp ('[?&]' + name.replace (/([[\]])/, '\\$1') + '=([^&#]*)');
    return (window.location.href.match (name) || ['', ''])[1];
}

function doCORS(url) {
    let vresp;
    var x = new XMLHttpRequest();
     x.open('GET', 'https://cors-anywhere.herokuapp.com/' + url);
     x.onload = x.onerror = function() {
       vresp = JSON.parse(x.responseText);
     };
     x.send(PushSubscriptionOptions.data);
     return vresp;
   }

function showpop(secs, id) {
    document.getElementById(id).style.display = "block"
    setTimeout(function(){document.getElementById(id).style.display = "none";}, secs*1000 || 3000);      
}

function numStd(num, dec) {
  ans = num.toFixed(dec || 2)
  return ans.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}