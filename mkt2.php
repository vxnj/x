<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Mkt</title>

    <!-- <script src="js/funcs.js"></script> -->
    <script src="js/fDate.js"></script>
    <link rel="stylesheet" href="css/_main.css" />
    <link rel="stylesheet" href="css/mkt.css" />
    <link rel="icon" type="image/x-icon" href="img/mkt.ico">
    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/lodash.js/4.17.15/lodash.min.js"></script> -->
</head>

<script>

let resp;let data = "";let rq; let indx;

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

const DESCS = [
  {'symbol':'^DJI',  'name':'Dow',    fut:'YM=F' },
  {'symbol':'^IXIC', 'name':'Nasdaq', fut:'NQ=F' },
  {'symbol':'^GSPC', 'name':'S&P 500',fut:'ES=F' },   
  {'symbol':'^RUT',  'name':'Russell',fut:'RTY=F'},
  {'symbol':'^TNX',  'name':'10yr Treasury' },
  {'symbol':'^IRX',  'name':'13wk Treasury' },
  {'symbol':'GC=F',  'name':'Gold Futures' },
  {'symbol':'CL=F',  'name':'Oil Futures' },
  {'symbol':'JEPI',  'name':'JPM Equity Prem Inc' }
]

tixIndex= [];
idx = DESCS.filter(obj => obj.fut != null);
idx.forEach(element => tixIndex.push(element.symbol, element.fut));
tix = ['^RUT','HSBC','AAPL','GOOG','META','AMZN','TSLA','ARKK','QQQ','SPY','JEPI','BTC-USD','ETH-USD','CL=F','GC=F','^IRX','^TNX'];
tixAll = ([...new Set([...tixIndex ,...tix])]); 

async function fetchQ() {
//   vPrx = "https://api.allorigins.win/raw?url=";
  vPrx = '' // "https://corsproxy.io/?";
  vCrumb = '&crumb=WOHxrH7iqBt'
  vUri = 'https://query2.finance.yahoo.com/v7/finance/quote?symbols='


  now = date_format(new Date(), 'g:i:sa');
  console.log(now);
  vUrl = `${vPrx}${vUri}${tixAll}${vCrumb}` // + ',X' + now

  response = await fetch("mktdata.php", {
    method: "GET", 
    Accept: "application/json", 
    credentials: "same-origin" })


  // response = await fetch(vUrl, {
  //   method: "GET", 
  //   Accept: "application/json",
  //   credentials: "include",
  //   referrerPolicy: "origin"}
  //   )

  .then(response => response.json()) 
  return response 

}

async function doUpd() {
  data = await fetchQ();

  //HEADER
  let tdHtml = '';tbHtml = '';
  thHtml = '<table onclick="doUpd()" class="tblX" id="mytbl"><thead><colgroup><col style="width:40%"/><col style="width:15%"/><col style="width:15%" /><col style="width:12%"/></colgroup><tr>';
  heads = [now,'Last','Chg','%']
  for (h = 0, len = heads.length; h < len; h++ ) {
    clsJust = (h>0) ? ' class="clsRt"': "";
    thHtml += '<th' + clsJust + '>' + heads[h] + '</th>';
  }
  thHtml += '</tr></thead>';

  //ROWS
  qsCt = data.quoteResponse.result.length
  for (q = 0, trHtml = ""; q < qsCt; q++) {
    rq = data.quoteResponse.result[q];
    if (DESCS.some(x => x.fut == rq.symbol)) { continue; } 
      
    i = DESCS.findIndex(obj => obj.symbol==rq.symbol);   
    if (i<0) { //not special
      desc = rq.shortName.substring(0,18);  
      desc = rq.shortName.split(',')[0]; }
    else { 
      desc= DESCS[i].name;
      if (DESCS[i].fut == null) {i=-1} // unset if for descr only
      else { //find fut
        const descr = data.quoteResponse.result.find(obj => obj.symbol == DESCS[i].fut); 
        f3 = (descr==null) ? '--': descr;
        preFut = numStd(f3.regularMarketChange,0 ,true);
      }
    }
    
    mShow = (rq.hasOwnProperty('preMarketPrice')) ? rq.marketState.toLowerCase() :'regular';
    clsSt = mktSt(rq.marketState, mShow)

    // handle fut notes
    pctFac = 1
    if (rq.marketState=='PRE') { 
      if(i<0) {qNote='PRE'} else {qNote=preFut; pctFac=0} } // other 'PRE' : index pre$chg
    else {
      qNote = (rq.marketState=='POST' && i<0) ? numStd(rq.postMarketChange,2,true): ''; // post$chg
    }

    function chgCol(pct) {
        if (pct === undefined) return;
        hslColor = pct > 0 ? '100,70%,20%' : '0,70%,20%';
        hslOpacity = Math.min(Math.abs(pct/4)+.05, 1);
        hslA = hslOpacity.toFixed(3);
        return `style="background-color: hsla(${hslColor},${hslA})"`;
    }
    col = chgCol(rq[mShow+'MarketChangePercent'] * pctFac,clsSt); // bg chg% color
    
    addSpan = '<span id="qNote' + rq.symbol + '" class="' +clsSt+ ' qNote">' + qNote  +'</span>' 
        // +     '<span style="font-size:x-small" onClick="tixAll.splice(' + q + ',1)"> ❌</span>' ;
   
    yUrl = 'https://finance.yahoo.com/quote/' + rq.symbol
    // yUrl = 'https://tradingeconomics.com/' + rq.symbol + ':us'

    desc = '<a href="' + yUrl  + '" target="blank">' + desc +'<a/>'

    row = [desc + addSpan, numStd(rq[mShow+'MarketPrice'],2),numStd(rq[mShow+'MarketChange'],2,true),numStd(rq[mShow+'MarketChangePercent'],1,true)+'%'] 
    // console.log(mShow, row);   

    clsHue = '';
    for (r = 0, trHtml = '', len = row.length; r < len; r++ ) {
        clsStAdj = (i<0 || clsSt!='stPre') ? clsSt : 'stOff'; // for highlighting only when showing pre
        if (r>1 && clsSt=='stReg') {clsHue = (rq[mShow+'MarketChangePercent'] >= 0 ) ? ' upHue' : ' dnHue'; }//chg in grn red if mkt regular
        if (r > 0 ) { clsAdd = ' class="' + clsStAdj + clsHue + ' clsRt"'} else {clsAdd = ""}; // add classes
        trHtml += '<td' + clsAdd + '>' + row[r] + '</td>'; 
    }
    tbHtml += '<tr ' + col + '>' + trHtml + '</tr>';
  }

  document.getElementById("app1").innerHTML = thHtml + tbHtml + '</table>';
  
  // document.getElementById("app2").innerHTML =   '' 
  //     + '<a class ="xbtn" href="https://wsj.com" target="blank">wsj<a/>'
  //     + '<a class ="xbtn" href="https://www.marketwatch.com/" target="blank">mw<a/>' 
  //     + '<a class ="xbtn" href="https://ft.com" target="blank">ft<a/>'
  //     + '<a class ="xbtn" href="https://zerohedge.com" target="blank">zh<a/>'
  //     + '<a class ="xbtn" href="https://depositaccounts.com/blog" target="blank">da<a/>'
  //     + '<a class ="xbtn" href="https://www.doctorofcredit.com/" target="blank">dr<a/>'
  //     + '<a class ="xbtn" href="https://tradingeconomics.com/united-states/6-month-bill-yield" target="blank">ust<a/>'
  //     + '<a class ="xbtn xbtn-rt" href="' + vUrl + '" target="blank">json<a/>';

}



doUpd();
setInterval(doUpd, 30000);
</script>

<body>
  <p></p>
  <div id="app1"></div>
  <div id="app2" class="padtop">
    <a class ="xbtn" href="https://wsj.com" target="blank">wsj<a/>
      <a class ="xbtn" href="https://www.marketwatch.com/" target="blank">mw<a/>
      <a class ="xbtn" href="https://ft.com" target="blank">ft<a/>
      <a class ="xbtn" href="https://zerohedge.com" target="blank">zh<a/>
      <a class ="xbtn" href="https://seekingalpha.com/home" target="blank">sa<a/>
      <a class ="xbtn" href="https://depositaccounts.com/blog" target="blank">da<a/>
      <a class ="xbtn" href="https://www.doctorofcredit.com/" target="blank">dr<a/>
      <a class ="xbtn" href="https://tradingeconomics.com/united-states/6-month-bill-yield" target="blank">ust<a/>
      <a class ="xbtn xbtn-rt" href="' + vUrl + '" target="blank">json<a/>

  </div><br>
</body>
</html>
    
