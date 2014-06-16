<?php
$config = array();
$config['host'] = "localhost";
$config['user'] = "root";
$config['pwd'] = "";
$config['db'] = "api";
$currency_list = array(
	"365","AC","AUR","BC","BTCS","C2","CAIx","CINNI","COMM","CRYPT","DGB","DOGE","DOPE","DRK","EMC2","ENC","FAC","FLT","GRS","HIRO","HVC","ITC","IVC","KDC","LTC","METH","MINT","MRS","MYR","MZC","NAUT","NOBL","OLY","PLC","PND","POT","Q2C","RIC","SAT","SC","SPA","SUN","SYNC","TAK","TES","UNO","USDe","UTC","VRC","VTC","WC","XC","XLB","YC","ZED","ZET",
"42","AC","ALF","AMC","ANC","ARG","AUR","BC","BCX","BEN","BET","BQC","BTB","BTE","BTG","BUK","CACH","CAIx","CAP","CASH","CAT","CGB","CINNI","CLR","CMC","CNC","COMM","CRC","CRYPT","CSC","DEM","DGB","DGC","DMD","DOGE","DRK","DVC","EAC","ELC","EMC2","EMD","EXE","EZC","FFC","FLAP","FLT","FRC","FRK","FST","FTC","GDC","GLC","GLD","GLX","HBN","HVC","IFC","IXC","JKC","KDC","KGC","LEAF","LGD","LK7","LKY","LOT","LTB","LTC","LYC","MAX","MEC","MEOW","MINT","MN1","MN2","MNC","MRY","MYR","MZC","NAN","NAUT","NBL","NEC","NET","NMC","NRB","NRS","NVC","NXT","NYAN","ORB","OSC","PHS","Points","POT","PPC","PTS","PXC","PYC","QRK","RDD","RPC","RYC","SAT","SBC","SC","SMC","SPA","SPT","SRC","STR","SXC","TAG","TAK","TEK","TES","TGC","TRC","UNB","UNO","USDe","UTC","VRC","VTC","WC","WDC","XC","XJO","XLB","XPM","YAC","YBC","ZCC","ZED","ZET",
"CNY","USD","LTC","AC","AUR","BC","BQC","BTB","BUK","C2","CDC","COMM","CMC","CNC","DGC","DOGE","DRK","DTC","EXC","FLT","FRC","FTC","KDC","MAX","MEC","MINT","MMC","NEC","NMC","NXT","PPC","PRT","PTS","QRK","SLM","SRC","TAG","YAC","VRC","VTC","WDC","XC","XCP","XPM","ZCC","ZET",
"USD","RUR","EUR","CNH","GBP","LTC","NMC","NVC","TRC","PPC","FTC","XPM",
'ANC','AUR','BC','DGC','DOGE','DVC','FLT','FRC','FTC','I0C','IXC','LTC','NMC','NVC','NXT','PPC','QRK','TRC','VTC','WC','WDC','XPM','ZET',
"888","A3C","AAA","ACC","ATH","AV","AXIS","BANK","BBR","BC","BCAT","BCH","BCT","BELA","BITS","BLC","BLU","BOST","BTCX","BTL","BURN","BWC","CAIX","CCN","CESC","CFC","CGB","CHCC","CINNI","CLOAK","COIN2","COOL","CRT","CRYPT","CT","CTZ","CURE","DB","DCM","DIG","DIS","DOGE","DRK","DRM","DVK","EFL","ELT","ENC","ENRG","ERC","FBC","FCN","FLASH","FLT","FRAC","FTC","FUEL","GAC","GDN","GIVE","GLC","GLYPH","GOAL","GRK","GRN","GRS","H2O","H5C","HC","HIC","HIRO","HKC","HMY","HPY","HYPER","ISR","ITC","JPC","KIWI","KTK","LGD","LIMX","LOL","LTC","LTCX","MAMM","MARU","MAST","MHYC","MINT","MMXIV","MON","MPL","MUGA","MUN","MYC","MYR","MZC","NAUT","NJA","NL","NLG","NOBL","NTC","NTR","OC","OPC","PC","PER","PIGGY","POT","PPC","PTC","PURE","QBC","QCN","QTM","RBY","RDD","ROM","RT2","SC","SFR","SHARE","SHIBE","SHOP","SLR","SNCX","SPC","START","SUM2","SUPER","SYNC","TAC","THC","UNVC","URO","UVC","VMC","VOOT","VRC","VTC","WATER","WC","WEST","WIFI","WIN","X13C","XC","XDQ","XHC","XLB","XLC","XMR","XSI","YC","YMC","ZIP","ZS",
"LTC","NXT","NMC","XPM","MMC","NOBL","USDE","SOC","DOGE","GLB","Q2C","FOX","PMC","CACH","VTC","FZ","FRQ","CASH","WIKI","PRC","HUC","DRK","PTS","CNOTE","XCP","RIC","CGA","MEC","WOLF","MINT","AUR","IXC","REDD","MYR","BC","GRC","NRS","YIN","YANG","EBT","HVC","AIR","MRC","EXE","WDC","EMC2","BTCS","FLT","HIRO","PAWN","BONES","EFL","FAC","XSV","H2O","BNS","HOT","NOTE","GOLD","SRG","XXC","NC2","YACC","PPC","GRS","XBC","ADN","CAI","CC","LGC","COMM","WC","AC","METH","GIAR","ITC","CINNI","BITS","YC","MNS1","BLU","DIEM","BCC","JUG","GDN","NAS","LC","1CR","PRT","SRC","SUM","BDG","SHIBE","UVC","PLX","MUN","SC","NAUT","ABY","LCL","MON","DRM","BELA","BDC","VRC","CURE","XLB","CHA","XSI","SYNC","BANK","XMR","BCN","XC","QCN","CYC","FCN","QORA","BOST","MAST","DVK","LTCX","GLC","CRYPT","MIL","NL","BBR","NHZ","X13","ENC","DIS","PC","XHC","AXIS","JPC","LOL","PIGGY","TAC",	
"LTC","NMC","XDG","XRP","XVN");
echo count($currency_list).'<br/>';
asort($currency_list);
echo '<pre>';
var_dump($currency_list);
echo '</pre>';
$currency=array_unique($currency_list);
asort($currency);
echo count($currency).'<br/>';
echo '<pre>';
var_dump($currency);
echo '</pre>';
var_dump(implode("\"),(\"", $currency));
?>