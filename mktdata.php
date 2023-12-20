<?php 

/* 1 - Get cookie */
//https://stackoverflow.com/questions/76065035/yahoo-finance-v7-api-now-requiring-cookies-python
$url_yahoo = "https://fc.yahoo.com";
$yahoo_headers = get_headers($url_yahoo, true);
//print_r($yahoo_headers);
$cookie_name = 'Set-Cookie';

/* 2 - Get crumb , setting cookie */
$url_yahoo2 = "https://query2.finance.yahoo.com/v1/test/getcrumb";
$c = curl_init($url_yahoo2);
curl_setopt($c, CURLOPT_VERBOSE, 1);
curl_setopt($c, CURLOPT_COOKIE, $yahoo_headers[$cookie_name]);
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$crumb = curl_exec($c);
curl_close($c);

echo "<BR>Crumb:" . $crumb;

/* 3 - Get quotes and return json */
$ticks = "^DJI,^IXIC,^GSPC,^RUT,HSBC,AAPL,GOOG,META,AMZN,TSLA,ARKK,QQQ,SPY,JEPI,BTC-USD,ETH-USD,CL=F,GC=F,^IRX,^TNX";

$url_cotacao = "https://query2.finance.yahoo.com/v7/finance/quote?symbols=" . $ticks . "&crumb=" . $crumb;
$c = curl_init($url_cotacao);
curl_setopt($c, CURLOPT_VERBOSE, 1);
curl_setopt($c, CURLOPT_COOKIE, $yahoo_headers[$cookie_name]);
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$data_quote = curl_exec($c);
curl_close($c);

echo $data_quote;
echo "11";

?>

