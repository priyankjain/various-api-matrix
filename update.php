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

//Create and initialize global array
$global_cur = array();
while($row=$result->fetch_assoc()){
    $global_cur[$row['currency']] = array($row['mintpal_last'],$row['cryptsy_last'],$row['bter_last'],$row['btce_last'],$row['vircurex_last'],$row['bittrex_last'],$row['poloniex_last'],$row['kraken_last'],$row['date_time'],$row['mintpal_vol'],$row['cryptsy_vol'],$row['bter_vol'],$row['btce_vol'],$row['vircurex_vol'],$row['bittrex_vol'],$row['poloniex_vol'],$row['kraken_vol']);
}

//Update mintpal values
$json = curl("https://api.mintpal.com/v1/market/summary/BTC");
$json = json_decode($json);
foreach($json as $market){
        $key = strtoupper($market->code);
        if(!array_key_exists($key, $global_cur))
        {
            $mysqli->query("insert into `rates` (`currency`) values('".$key."')");
            $global_cur[$key] = array('-','-','-','-','-','-','-','-','0000-00-00 00:00:00','-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][0]=$market->last_price;
        $array = (array)$market;
        $global_cur[$key][9]=$array['24hvol'];
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
            $global_cur[$key] = array('-','-','-','-','-','-','-','-','0000-00-00 00:00:00','-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][1] = $value->lasttradeprice;
        $global_cur[$key][10] = $value->volume*$value->lasttradeprice;
    }
}

//Update bter values
$json = null;
$json = curl("https://data.bter.com/api/1/tickers");
$json = json_decode($json);
foreach($json as $key=> $value){
    if(strpos($key,"btc") !== false){
        $val = "";
        if(strpos($key,"btc_")!==false){
            $key = strtoupper(substr($key,4,strlen($key)-4));
            $val = (1/$value->last);
        }
        else
        {
            $key = strtoupper(substr($key,0,strpos($key,"_")));
            $val = ($value->last);
        }
         if(!array_key_exists($key, $global_cur))
        {
            $mysqli->query("insert into `rates` (`currency`) values('".$key."')");
            $global_cur[$key] = array('-','-','-','-','-','-','-','-','0000-00-00 00:00:00','-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][2] = $val;
        $global_cur[$key][11] = $value->vol_btc;
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
$val = $value->ticker->last;
$vol = $value->ticker->vol_cur;
if(strpos($key,"btc_")!== false)
{
    $k = strtoupper(substr($key,4,strlen($key)-4));
    $global_cur[$k][3] = (1/$val);
    $global_cur[$k][12] = $vol*$global_cur[$k][3];
}
else{
    $k = strtoupper(substr($key,0,strpos($key,"_")));
    $global_cur[$k][3] = $val;
    $global_cur[$k][12] = $vol*$global_cur[$k][3];
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
$json = curl("https://api.vircurex.com/api/get_volume.json?alt=".$currency."&base=btc");
$json = json_decode($json);
$global_cur[$currency][13] = ($json->value == "")?0:$json->value;
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
            $global_cur[$key] = array('-','-','-','-','-','-','-','-','0000-00-00 00:00:00','-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][5] = $market->Last;
        $global_cur[$key][14] = $market->BaseVolume;
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
            $global_cur[$key] = array('-','-','-','-','-','-','-','-','0000-00-00 00:00:00','-','-','-','-','-','-','-','-');
        }
        $global_cur[$key][6] = $value->last;
        $global_cur[$key][15] = $value->baseVolume;
    }
}

//Update kraken values
$json = null;
$markets = null;
$json = curl("https://api.kraken.com/0/public/Ticker?pair=XXBTXLTC,XXBTXNMC,XXBTXXDG,XXBTXXRP,XXBTXXVN");
$json = json_decode($json);
$markets=$json->result;
foreach($markets as $key=>$value){
        $k = strtoupper(substr($key,5));
        $global_cur[$k][7] = (1/$value->c[0]);
        $global_cur[$k][16] = ($value->v[1]);
}

//Now update the database using the global array
$queries = "insert into history select * from rates; ";
foreach($global_cur as $key=>$value){
    $queries.= "update `rates` set `mintpal_last` = '".$value[0]."', `cryptsy_last` = '".$value[1]."', `bter_last` = '".$value[2]."', `btce_last` = '".$value[3]
    ."', `vircurex_last` = '".$value[4]."', `bittrex_last` = '".$value[5]."', `poloniex_last` = '".$value[6]."', `kraken_last` = '".$value[7]."', `date_time`=now(), `mintpal_vol` = '".$value[9]."', `cryptsy_vol` = '".$value[10]."', `bter_vol` = '".$value[11]."', `btce_vol` = '".$value[12]
    ."', `vircurex_vol` = '".$value[13]."', `bittrex_vol` = '".$value[14]."', `poloniex_vol` = '".$value[15]."', `kraken_vol` = '".$value[16]."' where `currency` = '".$key."'; ";
}
$queries.="update `last_updated` set `ts` = now() where 1;";
if(!$result=$mysqli->multi_query($queries)){
    echo "Error executing multiple queries";
    echo $mysqli->error;
    exit;
}
$mysqli->close();
$total = microtime(true) - $start;
echo $total;
?>