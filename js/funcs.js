// Get Url Parameter
function gup (name) {
    name = RegExp ('[?&]' + name.replace (/([[\]])/, '\\$1') + '=([^&#]*)');
    return (window.location.href.match (name) || ['', ''])[1];
}


function showpop(secs, id) {
    document.getElementById(id).style.display = "block"
    if (secs>0) { setTimeout(function(){document.getElementById(id).style.display = "none";}, secs*1000 || 3000); }
}

function numStd(num, dec, plus) {
  ans = num.toFixed(dec || 0)
  sign = (plus && ans>0) ? '+':' ';
  return sign + ans.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ',');
}


function chgCol(pct) {
  if (pct === undefined) return;
  hslColor = pct > 0 ? '100,70%,20%' : '0,70%,20%';
  hslOpacity = Math.min(Math.abs(pct/4)+.05, 1);
  hslA = hslOpacity.toFixed(3);
  return `style="background-color: hsla(${hslColor},${hslA})"`;
}

function mktSt(st, pre) {
  switch (st) {
    case "PRE":       stCls = (pre == 'pre') ? "stPre" :"stOff";break;
    case "REGULAR":   stCls = "stReg";break;
    case "POST":      stCls = "stPst";break;
    case "POSTPOST":  stCls = "stOff";break;
    case "CLOSED":    stCls = "stOff";break;
    default:          stCls = "stOff";break;
  }
  return stCls;
}


