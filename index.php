 
<!DOCTYPE html>
<html lang="en">
<head>

	<!-- Styles -->
	<link href="css/jquery.filer.css" rel="stylesheet">
	<link href="css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet">

	<!-- Google Fonts -->

	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta charset="utf-8">
	<title>Navbar Static top Template</title>
	<meta name="generator" content="Bootply" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	<link href="css/bootstrap.min.css" rel="stylesheet">
    <!--[if lt IE 9]>
      <script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
      <![endif]-->
      <style>
      	body {

      		font-family: 'Roboto Condensed', sans-serif;
      		font-size: 14px;
      		line-height: 1.42857143;
      		color: #47525d;
      		background-color: #FFFFFF;

      		margin: 0;
      		padding: 20px;
      	}

      	hr {
      		margin-top: 20px;
      		margin-bottom: 20px;
      		border: 0;
      		border-top: 1px solid #eee;
      	}

      	.jFiler {
      		font-family: inherit;
      	}
      </style>

    </head>

    <body>

     <div id="content" style="padding-top: 10%">


      <input type="file" name="files[]" id="filer_input2" multiple="multiple">


      <style type="text/css">
       .table td {
         text-align: center;   
       }
       .table th {
         text-align: center;   
       }
     </style>
   </div>


   <!-- Formular with report info modal -->
   <div class="modal" id="formularModal" tabindex="-1" role="dialog"
   aria-labelledby="myModalLabel1" aria-hidden="true">
   <div class="modal-dialog" role="document">
    <div class="modal-content">
     <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
       <span aria-hidden="true">&times;</span>
     </button>
     <h4 class="modal-title" style="margin-left: 30%;" id="myModalLabel1">Please Insert Client Data</h4>
   </div>
   <div class="modal-body " >


    <form class = "" role = "form">

     <div class = "input-group col-md-10 col-md-offset-1">
      <span class = "input-group-addon">Server Name</span>
      <input id="serverNameInput" type = "text" class = "form-control" placeholder = "Server Name">
    </div>

    <br>
    <br>



    <div class = "input-group col-md-10 col-md-offset-1">
      <span class = "input-group-addon">Website URL</span>
      <input id="webURLInput" type = "text" class = "form-control" placeholder = "www.example.org">
    </div>

    <br>
    <br>

    <div class = "input-group col-md-10 col-md-offset-1">
      <span class = "input-group-addon">Date</span>
      <input id="dateInput" type = "text" class = "form-control" placeholder = "DD/MM/YYYY">
    </div>
    <br>
    <br>
  </form>


</div>
<div class="modal-footer">
  <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
  <button type="button" class="btn btn-primary"  onclick=" ValidateForm();" >Generate Report</button>
</div>
</div>
</div>
</div>


<script type="text/javascript">
/**
 * DHTML date validation script for dd/mm/yyyy. Courtesy of SmartWebby.com (http://www.smartwebby.com/dhtml/datevalidation.asp)
 */
// Declaring valid date character, minimum year and maximum year
var dtCh= "/";
var minYear=1900;
var maxYear=2100;

function isInteger(s){
	var i;
	for (i = 0; i < s.length; i++){   
        // Check that current character is number.
        var c = s.charAt(i);
        if (((c < "0") || (c > "9"))) return false;
      }
    // All characters are numbers.
    return true;
  }

  function stripCharsInBag(s, bag){
   var i;
   var returnString = "";
    // Search through string's characters one by one.
    // If character is not in bag, append to returnString.
    for (i = 0; i < s.length; i++){   
    	var c = s.charAt(i);
    	if (bag.indexOf(c) == -1) returnString += c;
    }
    return returnString;
  }

  function daysInFebruary (year){
  // February has 29 days in any year evenly divisible by four,
    // EXCEPT for centurial years which are not also divisible by 400.
    return (((year % 4 == 0) && ( (!(year % 100 == 0)) || (year % 400 == 0))) ? 29 : 28 );
  }
  function DaysArray(n) {
   for (var i = 1; i <= n; i++) {
    this[i] = 31
    if (i==4 || i==6 || i==9 || i==11) {this[i] = 30}
     if (i==2) {this[i] = 29}
   } 
 return this
}

function isDate(dtStr){
	var daysInMonth = DaysArray(12)
	var pos1=dtStr.indexOf(dtCh)
	var pos2=dtStr.indexOf(dtCh,pos1+1)
	var strDay=dtStr.substring(0,pos1)
	var strMonth=dtStr.substring(pos1+1,pos2)
	var strYear=dtStr.substring(pos2+1)
	strYr=strYear
	if (strDay.charAt(0)=="0" && strDay.length>1) strDay=strDay.substring(1)
		if (strMonth.charAt(0)=="0" && strMonth.length>1) strMonth=strMonth.substring(1)
			for (var i = 1; i <= 3; i++) {
				if (strYr.charAt(0)=="0" && strYr.length>1) strYr=strYr.substring(1)
			}
		month=parseInt(strMonth)
		day=parseInt(strDay)
		year=parseInt(strYr)
		if (pos1==-1 || pos2==-1){
			alert("The date format should be : dd/mm/yyyy")
			return false
		}
		if (strMonth.length<1 || month<1 || month>12){
			alert("Please enter a valid month")
			return false
		}
		if (strDay.length<1 || day<1 || day>31 || (month==2 && day>daysInFebruary(year)) || day > daysInMonth[month]){
			alert("Please enter a valid day")
			return false
		}
		if (strYear.length != 4 || year==0 || year<minYear || year>maxYear){
			alert("Please enter a valid 4 digit year between "+minYear+" and "+maxYear)
			return false
		}
		if (dtStr.indexOf(dtCh,pos2+1)!=-1 || isInteger(stripCharsInBag(dtStr, dtCh))==false){
			alert("Please enter a valid date")
			return false
		}
		return true
	}

	function ValidateForm(){


    var inputFields = new Array();

    var dateAndTimeField=document.getElementById("dateInput");
    var serverNameField= document.getElementById("serverNameInput");
    var webURLField= document.getElementById("webURLInput");
    
    if(serverNameField.value=="" || serverNameField.value.length<3)
    {
      alert("Server Name must be at least 3 characters long");
      serverNameField.focus();
      return false;
    }
    else
    {
      inputFields["serverNameInput"]=serverNameField.value;
    }

    if(webURLField.value=="" || webURLField.value.length<3)
    {
      alert("Web URL must be at least 3 characters long");
      webURLField.focus();
      return false;
    }
    else
    {
      inputFields["webURLInput"]=webURLField.value;
    }
    
    if (isDate(dateAndTimeField.value)==false){
     dateAndTimeField.focus();
     return false;
   }
   else
   {
    inputFields["dateAndTime"]=dateAndTimeField.value;
  }

  startProcessing(inputFields);
  return true
}


</script>



<script src="http://code.jquery.com/jquery-3.1.0.min.js" crossorigin="anonymous"></script>
<script src="js/jquery.filer.js" type="text/javascript"></script>
<script src="js/custom.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/main.js"></script>
</body>
</html>