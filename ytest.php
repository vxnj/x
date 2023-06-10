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
//echo "<BR>Crumb:" . $crumb;

/* 3 - Get quotes with crumb, setting cookie. Using sample tickets*/
$tickets_list = "AAPL,TSLA";
$url_cotacao = "https://query2.finance.yahoo.com/v7/finance/quote?symbols=" . $tickets_list . "&crumb=" . $crumb;
$c = curl_init($url_cotacao);
curl_setopt($c, CURLOPT_VERBOSE, 1);
curl_setopt($c, CURLOPT_COOKIE, $yahoo_headers[$cookie_name]);
curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
$data_quote = curl_exec($c);
curl_close($c);


/* 4 - Get data from yahoo */
$resJson_decode = json_decode($data_quote, false);
if (!$resJson_decode->quoteResponse->result) {
    $resultado = "Ticket dont exists in yahoo!";
} else {
    foreach ($resJson_decode->quoteResponse->result as $ticket_result){
        echo "<BR>Ticket:" . $ticket_result->symbol;
        echo "<BR>Price:" . $ticket_result->regularMarketPrice;
    }
    
}