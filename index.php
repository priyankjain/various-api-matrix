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
<div class="container">
    <div class="row">&nbsp;</div>
<div class="panel panel-info">
  <div class="panel-heading">
    <h3 class="panel-title"><center>Exchange Rates</center></h3>
    </div>
  <div class="panel-body">
    <form name="currency" action="result.php" method="POST">
    <div class="form-group">
            <h3>
                <small>Currency Pairs</small>
            </h3>
    </div>
<?php
require_once("config.php");
    $cnt = 0;
    for($i=0; $i < count($config['currencies']) ; $i++){
        if($cnt == 0) echo '<div class="form-group"><div class="row">';
        echo '<div class="col-xs-2"><span class="button-checkbox"><button type="button" class="btn" data-color="primary">'.$config['currencies'][$i].
        '</button><input type="checkbox" class="hidden" name="'.$config['currencies'][$i].'"/></span></div>';
        if($cnt == 5 || $i == count($config['currencies'])-1) {
            echo '</div></div>';
            $cnt = -1;
        }
        $cnt++;
    }   
?>
    <div class="form-group">
    <h3><br />
        <small>Display Options</small>
    </h3>
    </div>
    <div class="form-group">
        <span class="button-checkbox">
            <button type="button" class="btn" data-color="primary">Auto Refresh</button>
            <input type="checkbox" class="hidden" name = "autorefresh"/>
        </span>
    </div>

    <div class="form-group">
     <button type="submit" class="btn btn-info" name="submit">Submit</button>
    </div>
</form>
 
  </div>
</div>
   
</div>
</body>
</html>