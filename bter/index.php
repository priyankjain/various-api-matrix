<?php
function plain_curl($url = '', $var = '', $header = false, $nobody = false) {
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, $header);
    curl_setopt($curl, CURLOPT_HEADER, $nobody);
    curl_setopt($curl, CURLOPT_TIMEOUT, 10);
    if ($var) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $var);
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    curl_close($curl);
    return $result;
}

function curl($url = '', $var = '', $header = false, $nobody = false) {
    global $config, $sock;
    $curl = curl_init($url);
    curl_setopt($curl, CURLOPT_NOBODY, $header);
    curl_setopt($curl, CURLOPT_HEADER, $nobody);
    curl_setopt($curl, CURLOPT_TIMEOUT, 100);
  
    if ($var) {
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $var);
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($curl);
    if(curl_errno($curl))  echo 'error:' . curl_error($curl);
    curl_close($curl);
    return $result;
}

function fetch_value($str, $find_start, $find_end) {
    $start = strpos($str, $find_start);
    if ($start === false) {
        return "";
    }
    $length = strlen($find_start);
    $end = strpos(substr($str, $start + $length), $find_end);
    return trim(substr($str, $start + $length, $end));
}
$json = curl("https://data.bter.com/api/1/tickers");
$json = json_decode($json);
$markets=$json;
$currency_pairs= array();
foreach($markets as $key=> $value){
    if(strpos($key,"btc") !== false){
        if(strpos($key,"btc_")!==false){
            echo strtoupper(substr($key,4,strlen($key)-4))." ".(1/$value->last).'<br/>';
            $currency_pairs[] = strtoupper(substr($key,4,strlen($key)-4));
        }
        else
        {
            echo strtoupper(substr($key,0,strpos($key,"_")))." ".$value->last.'<br/>';
            $currency_pairs[] = strtoupper(substr($key,0,strpos($key,"_")));
        }
    }
}
var_dump(implode("\",\"",$currency_pairs));
?>