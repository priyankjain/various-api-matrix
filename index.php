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

function validate(){
    var i=$('input[type="checkbox"]:checked').filter(".currencies").size();
    if(i == 0){
        if(document.getElementById('yes').checked){
            document.getElementById("error").innerHTML = '';
            return true;
        }
        else 
            {   
                var c= '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> Please select atleast one currency</div>';
                document.getElementById("error").innerHTML = c;
                //window.location.hash ="#error";
                document.getElementById('error').scrollIntoView()
                return false;
            }
    }
    document.getElementById("error").innerHTML = '';
    return true;
}
                $(function () {
                    $("#refresh").hide();
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
$(function(){
  $('input[type="radio"].yes-no').change(function(){
    if ($(this).is(':checked'))
    {  
        if(document.getElementById("yes").checked)
            $("#cur-pair").hide();
        else
            $("#cur-pair").show();
    }
  });
});

$(function(){
  $('input[type="radio"].refresh').change(function(){
    if ($(this).is(':checked'))
    {  
        if(document.getElementById("manual").checked)
            $("#refresh").hide();
        else
            $("#refresh").show();
    }
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
    <div class="row"><div class="col-xs-3"></div><div class="col-xs-6" id="error"></div><div class="col-xs-3"></div></div>
    <form name="currency" action="result.php" method="POST" onsubmit="return validate()">
    <div class="form-group">
            <h3>
                <small>Currency Pairs</small>
            </h3>
    </div>
    <div class="row">
    <div class="form-group">
            <label class="col-sm-3 col-md-3"><h4>Select all currencies?</h4></label>
            <div class="col-sm-7 col-md-7">
               <div class="btn-group" data-toggle="buttons">

      <label class="btn btn-default">
        <input type="radio" name="refresh-options" id="yes" value="yes" class="yes-no"> Yes
      </label>
      
      <label class="btn btn-default active">
        <input type="radio" name="refresh-options" id="no" value="no" class="yes-no" checked> No
      </label> 
</div>
            </div>
    </div>
</div>
<div class="row">&nbsp;</div>
<div id="cur-pair">
<?php
require_once("config.php");
    $cnt = 0;

    for($i=0; $i < count($config['currencies']) ; $i++){
        if($cnt == 0) echo '<div class="form-group"><div class="row">';
        echo '<div class="col-xs-2"><span class="button-checkbox"><button type="button" class="btn" data-color="primary">'.$config['currencies'][$i].
        '</button><input type="checkbox" class="hidden currencies" name="'.$config['currencies'][$i].'"/></span></div>';
        if($cnt == 5 || $i == count($config['currencies'])-1) {
            echo '</div></div>';
            $cnt = -1;
        }
        $cnt++;
    }   
?>
</div>
    <div class="form-group">
    <h3><br />
        <small>Display Options</small>
    </h3>
    </div>
<div class="row">
       <div class="col-xs-6 form-group" >
            <label class="col-sm-6 col-md-6"><h4>Auto Refresh?</h4></label>
            <div class="col-sm-6 col-md-6">
               <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-default">
        <input type="radio" name="refresh-options" id="auto" value="auto" class="refresh"> Yes
      </label>
      
      <label class="btn btn-default active">
        <input type="radio" name="refresh-options" id="manual" value="manual" class="refresh" checked> No
      </label> 
</div>
            </div>
    </div>

     <div class="col-xs-6 form-group" id="refresh">
    <input type="text"  id="seconds" class="form-control" name="seconds" placeholder="Enter interval in seconds">
    </div>
</div>
<div class="row">
    <div class="form-group">
     <button type="submit" class="btn btn-info" name="submit">Submit</button>
    </div>
</div>
</form>
 
  </div>
</div>
   
</div>
</body>
</html>