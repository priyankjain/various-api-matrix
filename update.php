<?php
$start = microtime(true);
ini_set('memory_limit','1024M');
require_once("config.php");
ini_set('precision', $config['precision']);
$start = microtime(true);   
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
$sql = "select * from rates";
$mysqli = new mysqli($config['host'],$config['user'],$config['pwd'],$config['db']);
if($mysqli->connect_errno > 0){
    echo "Error connecting to database";
    exit;
}
if(!$result=$mysqli->query($sql)){
    echo "Error executing query";
    exit;
}
$global_cur = array();
while($row=$result->fetch_assoc()){
    $global_cur[$row['currency']] = array($row['mintpal'],$row['cryptsy'],$row['bter'],$row['btce'],$row['vircurex'],$row['bittrex'],$row['poloniex'],$row['kraken']);
}

//Update mintpal values
$json = curl("https://api.mintpal.com/v1/market/summary/BTC");
$json = json_decode($json);
foreach($json as $market){
        $key = strtoupper($market->code);
        if(!array_key_exists($key, $global_cur))
        {
            $mysqli->query("insert into `rates` (`currency`) values('".$key."')");
            $global_cur[$key] = array('-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][0]=$market->last_price;
}

//Update cryptsy values
$json = null;
$markets = null;
$json = curl("http://pubapi.cryptsy.com/api.php?method=marketdatav2");
$json = json_decode($json);
$markets=$json->return->markets;
foreach($markets as $key=> $value){
    if(strpos($key,"BTC") != false){
        $keys = explode("/",$key);
        $key = $keys[0];
        if(!array_key_exists($key, $global_cur))
        {
            $mysqli->query("insert into `rates` (`currency`) values('".$key."')");
            $global_cur[$key] = array('-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][1] = $value->lasttradeprice;
    }
}

//Update bter values
$json = null;
$json = curl("https://data.bter.com/api/1/tickers");
$json = json_decode($json);
foreach($json as $key=> $value){
    if(strpos($key,"btc") !== false){
        if(strpos($key,"btc_")!==false){
            $key = strtoupper(substr($key,4,strlen($key)-4));
            $value = (1/$value->last);
        }
        else
        {
            $key = strtoupper(substr($key,0,strpos($key,"_")));
            $value = ($value->last);
        }
         if(!array_key_exists($key, $global_cur))
        {
            $mysqli->query("insert into `rates` (`currency`) values('".$key."')");
            $global_cur[$key] = array('-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][2] = $value;
    }
}

//Update btce values
require_once('btce-api.php');
$BTCeAPI = new BTCeAPI(
                    /*API KEY:    */    '96QE22NB-I471TW61-M1XOOFFE-LP9G5W30-RAGNPWZI', 
                    /*API SECRET: */    '50f3441fa7a00300c09b03d9bfba718791c044989d499e932a6bf9fa0314ecb4'
                      );

$btc_usd = array();
$keys = array('btc_usd','btc_rur','btc_eur','btc_cnh','btc_gbp','ltc_btc','nmc_btc','nvc_btc','trc_btc','ppc_btc','ftc_btc','xpm_btc');
$currency_pair=array();
foreach($keys as $key){
$value = json_decode($BTCeAPI->getPairTicker($key));
$value = $value->ticker->last;
if(strpos($key,"btc_")!== false)
{
    $global_cur[strtoupper(substr($key,4,strlen($key)-4))][3] = (1/$value);
}
else{
    $global_cur[strtoupper(substr($key,0,strpos($key,"_")))][3] = $value;
}
}

//Update vircurex values
$currencies=array(
'ANC','AUR','BC','DGC','DOGE','DVC','FLT','FRC','FTC','I0C','IXC','LTC','NMC','NVC','NXT','PPC','QRK','TRC','VTC','WC','WDC','XPM','ZET');
$json = null;
foreach($currencies as $currency){
$json = curl("https://api.vircurex.com/api/get_last_trade.json?base=".$currency."&alt=btc");
$json = json_decode($json);
$global_cur[$currency][4] =$json->value;
}

//Update bittrex values
$json = null;
$markets = null;
$json = curl("https://bittrex.com/api/v1/public/getmarketsummaries");
$json = json_decode($json);
$markets=$json->result;
foreach($markets as $market){
    if(strpos($market->MarketName,"BTC") !== false){
        $key = strtoupper(substr($market->MarketName, 4));
        if(!array_key_exists($key, $global_cur))
        {
            echo 'Inserting now';
            $mysqli->query("insert into `rates` (`currency`) values('".$key."')");
            $global_cur[$key] = array('-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][5] = $market->Last;
    }
}

//update poloniex values
$json = null;
$json = curl("https://poloniex.com/public?command=returnTicker");
$json = json_decode($json);
foreach($json as $key=>$value){
    if(strpos($key,"BTC_") !== false){
        $key = strtoupper(substr($key, 4));
        if(!array_key_exists($key, $global_cur))
        {
            $mysqli->query("insert into `rates` (`currency`) values('".$key."')");
            $global_cur[$key] = array('-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][6] = $value->last;
    }
}

//Update kraken values
$json = null;
$markets = null;
$json = curl("https://api.kraken.com/0/public/Ticker?pair=XXBTXLTC,XXBTXNMC,XXBTXXDG,XXBTXXRP,XXBTXXVN");
$json = json_decode($json);
$markets=$json->result;
foreach($markets as $key=>$value){
        $global_cur[strtoupper(substr($key,5))][7] = (1/$value->c[0]);
}
$queries = "";
foreach($global_cur as $key=>$value){
    $queries.= "update `rates` set `mintpal` = '".$value[0]."', `cryptsy` = '".$value[1]."', `bter` = '".$value[2]."', `btce` = '".$value[3]
    ."', `vircurex` = '".$value[4]."', `bittrex` = '".$value[5]."', `poloniex` = '".$value[6]."', `kraken` = '".$value[7]."' where `currency` = '".$key."'; ";
}
$queries.="update `last_updated` set `ts` = now() where 1;";
if(!$result=$mysqli->multi_query($queries)){
    echo "Error executing multiple queries";
    exit;
}
$mysqli->close();
$total = microtime(true) - $start;
echo $total;
?>