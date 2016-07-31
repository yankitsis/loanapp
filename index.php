<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Forms Complete Example</title>
	<style type="text/css">
body {
	margin: 2em 5em;
	font-family:Georgia, "Times New Roman", Times, serif;
}
h1, legend {
	font-family:Arial, Helvetica, sans-serif;
}
label, input, select {
	display:block;
}
input, select {
	margin-bottom: 1em;
}
fieldset {
	margin-bottom: 2em;
	padding: 1em;
}
fieldset fieldset {
	margin-top: 1em;
	margin-bottom: 1em;
}
.loan_id_stuff {
	float: left; 
	margin-right: 20px;
}
.loan_id_div{
	display:none;
}
	</style>	
	
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>


<script>

 function send_info(){
        $.post("https://www.webbizconcept.com/testapp/reg_app.php",
        {
          property_value: $("#property_value" ).val(),
          loan_amount: $("#loan_amount" ).val(),
          ssn: $("#ssn" ).val()
        },
        function(data,status){
			var xml = data,
			xmlDoc = $.parseXML( xml ),
			$xml = $( xmlDoc ),
			$error = $xml.find( "error" );
			var buf = $error.text();
			$loan_id = $xml.find( "loan_id" );
			if(buf.length>0){
				$('#result').css('color','red')
				$('#result').html('Error: ' + $error.text());	
				
			}else{
				$('#result').css('color','green')
				$('#result').html('Success: You successfully applied for a loan.  The loan application ID  is   ' + $loan_id.text());	
			
			}		

			
			
        });

 }
 
 function check_loan_status(){
	 $('#check_loan').hide();
	 $('#check_status').show();
	 
 }
 
 function get_status_value(){
        $.post("https://www.webbizconcept.com/testapp/get_loan_status.php",
        {
          loan_id: $("#loand_id_input" ).val()
        },
        function(data,status){
			var xml = data,
			xmlDoc = $.parseXML( xml ),
			$xml = $( xmlDoc ),
			$error = $xml.find( "error" );
			var buf = $error.text();
			$loan_id = $xml.find( "loan_id" );
			$loan_status = $xml.find( "loan_status" );
			if(buf.length>0){
				$('#result').css('color','red')
				$('#result').html('Error: ' + $error.text());	
				
			}else{
				$('#result').css('color','green')
				$('#result').html('The status of your application is:  ' + $loan_status.text());	
			
			}		

			
			
        });

	 
 }
</script>
</head>
<body>
<form id="register" name="register" method="post" action="javascript:send_info()" );">
<h1>Apply For a Loan</h1>
  <fieldset> 
    <legend>Application details</legend> 
    <div> 
        <label>Property value
	   <input id="property_value" name="property_value" type="text"  pattern="[0-9]{4,8}" placeholder="Your property value" required autofocus>

		</label>
    </div>
    <div> 
        <label>Loan amount
			<input id="loan_amount" name="loan_amount" type="text"  pattern="[0-9]{4,8}" placeholder="Enter loan amount" required autofocus>
		</label>
    </div>
    <div> 
        <label>Social security number
			<input id="ssn" name="ssn" pattern="\d{3}-?\d{2}-?\d{4}"  title="Expected pattern is ###-##-####" placeholder="555-55-5555" required autofocus>

		</label>
    </div>

  </fieldset>
  <div id="but_div">
	<input type="Submit" value="Submit Application" class="loan_id_stuff"> 	
	<input type="button" value="Check Your Loan Status" id="check_loan" onClick="check_loan_status()" class="loan_id_stuff">
	<div id="check_status"  class="loan_id_div"> 
		<div class="loan_id_stuff" style="display: none" >Enter Loan ID:</div> 
		<input type="text" name="loand_id_input"  id="loand_id_input" class="loan_id_stuff"> 
		<input type="button" id="get_status" value="Get Status" onClick="get_status_value()" class="loan_id_stuff">
	</div>
  </div>
  <div id="result"  name="result" style="clear:both; float: none;">
  </div>
  <span></span>
</form> 
</form> 
</body>
</html>