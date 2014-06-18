<!DOCTYPE html>
<html>
<head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">function formsubmit(){
    document.getElementById("currency").submit();}
    </script>
</head>
<body>
<?php
require_once("config.php");
ini_set('precision', $config['precision']);
if(!isset($_POST) || count($_POST)<0){
	$uri = "http://".$_SERVER['HTTP_HOST'].str_replace("result.php", "", $_SERVER['REQUEST_URI']);
	echo '<script type="text/javascript">';
	echo 'window.location.href="'.$uri.'"';
	echo '</script>';
	exit;
}

$sql = "select * from rates where currency in ";
$array = "('0',";
foreach($config['currencies'] as $currency){
	if(isset($_POST[$currency]) || $_POST['options'] == "yes"){
		$array.="'".$currency."',";
	}
}
$array = substr($array,0,-1);
$array.=")";
$sql = $sql.$array;
$mysqli = new mysqli($config['host'],$config['user'],$config['pwd'],$config['db']);
if($mysqli->connect_errno > 0){
	echo "Error connecting to database";
	exit;
}
if(!$result=$mysqli->query($sql)){
	echo "Error executing query";
	exit;
}
?>
    <form name="currency" id="currency" action="result.php" method="POST">
        <?php
            foreach($_POST as $key=>$value){
                if($key!="submit")
        echo '<input type="hidden" name="'.$key.'" value="'.$value.'"/>';
    }
    ?>

    </form>
<div class="container">
    <div class="row">&nbsp;</div>
    
    <div class="alert alert-info alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <strong>Last Updated: </strong> <?php 
    if(!$ts=$mysqli->query("select ts from last_updated limit 1")){
        echo "Error fetching last updated time";
        exit;
    }
    $times = $ts->fetch_assoc();
    echo $times['ts'];
    $mysqli->close();
  ?>
</div>
<div class="row">
    <?php if($_POST['refresh-options'] == "manual") 
    echo '<div class="btn-group"><button class="btn btn-info btn-sm dropdown-toggle" type="button" onclick="formsubmit();">Refresh</button></div>';
    else
        echo '<script type="text/javascript">setTimeout(function(){formsubmit();},'.($_POST['seconds']*10000).');</script>';
    ?>
</div>
    <div class="row">
    	<table style="table-layout: fixed;" class="table">
    		<thead><th>Currency</th><th>Mintpal</th><th>Cryptsy</th><th>Bter</th><th>Btc-e</th><th>Vircurex</th><th>Bittrex</th><th>Poloniex</th><th>Kraken</th></thead>
    		<tbody>
    			<?php
    				while($row=$result->fetch_assoc()){
    					echo '<tr><th>'.$row['currency'].'</th>
    					   <td>';
                        if($row['mintpal']=='-') echo '-'; else echo number_format((float)$row['bittrex'],$config['precision'],'.',''); echo '</td>
    					   <td>';
                        if($row['cryptsy']=='-') echo '-'; else echo number_format((float)$row['cryptsy'],$config['precision'],'.',''); echo '</td>
                            <td>';
                        if($row['bter']=='-') echo '-'; else echo number_format((float)$row['bter'],$config['precision'],'.',''); echo '</td>
                            <td>';
                        if($row['btce']=='-') echo '-'; else echo number_format((float)$row['btce'],$config['precision'],'.',''); echo '</td>
                        <td>';
                        if($row['vircurex']=='-') echo '-'; else echo number_format((float)$row['vircurex'],$config['precision'],'.',''); echo '</td>
    					<td>';
                        if($row['bittrex']=='-') echo '-'; else echo number_format((float)$row['bittrex'],$config['precision'],'.',''); echo '</td>
                        <td>';
                        if($row['poloniex']=='-') echo '-'; else echo number_format((float)$row['poloniex'],$config['precision'],'.',''); echo '</td>
                        <td>';
                        if($row['kraken']=='-') echo '-'; else echo number_format((float)$row['kraken'],$config['precision'],'.',''); echo '</td>
    					</tr>';
    				}
    			?>
    			<tr></tr>
    		<tbody>
    	</table>
    </div>
</div>

</body>
</html>
    <?php if($_POST['refresh-options'] == "auto") 
        {
            $seconds = 120;
            if(isset($_POST['seconds']) && is_numeric($_POST['seconds'])) $seconds = $_POST['seconds'];
            echo '<script type="text/javascript">setTimeout(function(){formsubmit();},'.($seconds*1000).');</script>';
        }
    ?>