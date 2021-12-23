// Get Url Parameter
function gup (name) {
    name = RegExp ('[?&]' + name.replace (/([[\]])/, '\\$1') + '=([^&#]*)');
    return (window.location.href.match (name) || ['', ''])[1];
}

function doCORS(url) {
    var x = new XMLHttpRequest();
     x.open('GET', 'https://cors-anywhere.herokuapp.com/' + url);
     x.onload = x.onerror = function() {
      // vresp = (x.responseText || '');
       vresp = JSON.parse(x.responseText);
     };
     x.send(PushSubscriptionOptions.data);
     return vresp
   }