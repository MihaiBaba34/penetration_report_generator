
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
   

    </div>

<div id="parseResults">
	

</div>




<<<<<<< HEAD
<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#formularModal" onclick = "startProcessing()">
  Start Processing
</button>
=======
>>>>>>> bea3e3f174663ebf4dca1092a60ff68b0b5fbe58

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

       <div class = "input-group col-md-10 col-md-offset-1">
         <span class = "input-group-addon">Website URL</span>
         <input id="webURLInput" type = "text" class = "form-control" placeholder = "www.example.org">
      </div>
    
      <br>
     <div class = "input-group col-md-10 col-md-offset-1">
         <span class = "input-group-addon">Date</span>
         <input id="dateInput" type = "text" class = "form-control" placeholder = "MM/DD/YYYY">
      </div>

   </form>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" >Cancel</button>
        <button type="button" class="btn btn-primary" data-dismiss="modal" >Generate Report</button>
      </div>
    </div>
  </div>
</div>






 
  <script src="http://code.jquery.com/jquery-3.1.0.min.js" crossorigin="anonymous"></script>
<<<<<<< HEAD
  <!-- <script src="js/jquery.filer.min.js" type="text/javascript"></script> -->
=======
>>>>>>> bea3e3f174663ebf4dca1092a60ff68b0b5fbe58
  <script src="js/jquery.filer.js" type="text/javascript"></script>
  <script src="js/custom.js" type="text/javascript"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
      </body>
    </html>