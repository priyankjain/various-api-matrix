<?php
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
var_dump($value);
$value = $value->ticker->last;
$cur = "";
if(strpos($key,"btc_")!== false)
{
    $cur = strtoupper(substr($key,4,strlen($key)-4));
    $currency_pair[] = $cur;
    echo $cur.'/BTC'. " " . (1/$value);
}
else{
    $cur = strtoupper(substr($key,0,strpos($key,"_")));
    $currency_pair[] = $cur;
    echo $cur.'/BTC'. " " . $value;
}
echo '<br/>';
}
var_dump(implode("\",\"",$currency_pair));
?>