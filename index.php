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
function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
}
function validate(){
    var error = '';
    var vol = document.currency.threshold.value;
    var seconds = document.currency.seconds.value;
    console.log(seconds);
    console.log(vol);
    if(document.getElementById('yes').checked && document.getElementById('manual').checked){
        console.log(0);
        error = '';
    }
    else if(document.getElementById('yes').checked && document.getElementById('auto').checked && (seconds == null || isNaN(parseFloat(seconds)))){ 
        console.log(1);
        error = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> Please numeric interval value in seconds</div>';  
    }
    else if(document.getElementById('manual').checked && document.getElementById('no').checked && (vol == null || isNaN(parseFloat(vol)))){
        console.log(2);
        error = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> Please enter numeric threshold volume</div>';
    }         
    else if(document.getElementById('auto').checked && document.getElementById('no').checked && (vol == null || isNaN(parseFloat(vol))) && !(seconds == null || isNaN(parseFloat(seconds)))){
        console.log(4);
        error = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> Please enter numeric threshold volume</div>';
    }
    else if(document.getElementById('no').checked && document.getElementById('auto').checked  && !(vol == null || isNaN(parseFloat(vol))) && (seconds == null || isNaN(parseFloat(seconds)))){
        console.log(5);
        error = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> Please numeric interval value in seconds</div>';  
    }
    else if(document.getElementById('no').checked && document.getElementById('auto').checked  && (vol == null || isNaN(parseFloat(vol))) && (seconds == null || isNaN(parseFloat(seconds)))){
    console.log(3);
    error = '<div class="alert alert-danger alert-dismissable"><button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button><strong>Error!</strong> Please numeric threshold volume & numeric interval value in seconds</div>';     
    }
    else{
        console.log(6);
        error = '';
    }
    document.getElementById("error").innerHTML = error;
    if(error == ''){
        document.getElementById('error').innerHTML = '';
        return true;
    }
    else{
        document.getElementById('error').scrollIntoView();
        return false;
    }
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
<div class="panel panel-primary">
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
    <div class="col-xs-6 form-group">
            <label class="col-sm-6 col-md-6"><h4>Select All Currencies?</h4></label>
            <div class="col-sm-6 col-md-6">
               <div class="btn-group" data-toggle="buttons">

      <label class="btn btn-primary">
        <input type="radio" name="options" id="yes" value="yes" class="yes-no">All
      </label>
      
      <label class="btn btn-primary active">
        <input type="radio" name="options" id="no" value="no" class="yes-no" checked>Threshold volume
      </label> 
</div>
            </div>
    </div>

    <div class="col-xs-6 form-group" id="cur-pair">
    <input type="text"  id="threshold" class="form-control" name="threshold" placeholder="Enter threshold volume">
    </div>

</div>
<div class="row">&nbsp;</div>

    <div class="form-group">
    <h3><br />
        <small>Display Options</small>
    </h3>
    </div>

       <div class="col-xs-6 form-group" >
            <label class="col-sm-6 col-md-6"><h4>Auto Refresh?</h4></label>
            <div class="col-sm-6 col-md-6">
               <div class="btn-group" data-toggle="buttons">
                      <label class="btn btn-primary">
        <input type="radio" name="refresh-options" id="auto" value="auto" class="refresh"> Yes
      </label>
      
      <label class="btn btn-primary active">
        <input type="radio" name="refresh-options" id="manual" value="manual" class="refresh" checked> No
      </label> 
</div>
            </div>
    </div>

     <div class="col-xs-6 form-group" id="refresh">
    <input type="text"  id="seconds" class="form-control" name="seconds" placeholder="Enter interval in seconds">
    </div>
<div class="row"></div>
    <div class="form-group">
     <button type="submit" class="btn btn-info" name="submit">Submit</button>
    </div>

</form>
 
  </div>
</div>
   
</div>
</body>
</html>