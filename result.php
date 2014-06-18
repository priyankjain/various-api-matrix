<!DOCTYPE html>
<html>
<head>
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta charset="utf-8">

    <script src="http://code.jquery.com/jquery-1.11.0.min.js"></script>
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap-theme.min.css">
    <script src="http://netdna.bootstrapcdn.com/bootstrap/3.1.1/js/bootstrap.min.js"></script>
    <script type="text/javascript">
                $(function () {
            $('.button-checkbox').each(function () {

                // Settings
                var $widget = $(this),
                    $button = $widget.find('button'),
                    $checkbox = $widget.find('input:checkbox'),
                    color = $button.data('color'),
                    settings = {
                        on: {
                            icon: 'glyphicon glyphicon-check'
                        },
                        off: {
                            icon: 'glyphicon glyphicon-unchecked'
                        }
                    };

                // Event Handlers
                $button.on('click', function () {
                    $checkbox.prop('checked', !$checkbox.is(':checked'));
                    $checkbox.triggerHandler('change');
                    updateDisplay();
                });
                $checkbox.on('change', function () {
                    updateDisplay();
                });

                // Actions
                function updateDisplay() {
                    var isChecked = $checkbox.is(':checked');

                    // Set the button's state
                    $button.data('state', (isChecked) ? "on" : "off");

                    // Set the button's icon
                    $button.find('.state-icon')
                        .removeClass()
                        .addClass('state-icon ' + settings[$button.data('state')].icon);

                    // Update the button's color
                    if (isChecked) {
                        $button
                            .removeClass('btn-default')
                            .addClass('btn-' + color + ' active');
                    }
                    else {
                        $button
                            .removeClass('btn-' + color + ' active')
                            .addClass('btn-default');
                    }
                }

                // Initialization
                function init() {

                    updateDisplay();

                    // Inject the icon if applicable
                    if ($button.find('.state-icon').length == 0) {
                        $button.prepend('<i class="state-icon ' + settings[$button.data('state')].icon + '"></i> ');
                    }
                }
                init();
            });
        });
    </script>
</head>
<body>
<?php
if(!isset($_POST) || count($_POST)<0 || !isset($_POST['submit'])){
	$uri = "http://".$_SERVER['HTTP_HOST'].str_replace("result.php", "", $_SERVER['REQUEST_URI']);
	echo '<script type="text/javascript">';
	echo 'window.location.href="'.$uri.'"';
	echo '</script>';
	exit;
}
require_once("config.php");
$sql = "select * from rates where currency in ";
$array = "('0',";
foreach($config['currencies'] as $currency){
	if(isset($_POST[$currency])){
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
	echo "Error executiong query";
	exit;
}
$mysqli->close();
?>
<div class="container">
    <div class="row">&nbsp;</div>
    <div class="row">
    	<table class="table">
    		<thead><th>Currency</th><th>Mintpal</th><th>Cryptsy</th><th>Bter</th><th>Btc-e</th><th>Vircurex</th><th>Bittrex</th><th>Poloniex</th><th>Kraken</th></thead>
    		<tbody>
    			<?php
    				while($row=$result->fetch_assoc()){
    					echo '<tr><th>'.$row['currency'].'</th>
    					<td>'.$row['mintpal'].'</td>
    					<td>'.$row['cryptsy'].'</td>
    					<td>'.$row['bter'].'</td>
    					<td>'.$row['btce'].'</td>
    					<td>'.$row['vircurex'].'</td>
    					<td>'.$row['bittrex'].'</td>
    					<td>'.$row['poloniex'].'</td>
    					<td>'.$row['kraken'].'</td>
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