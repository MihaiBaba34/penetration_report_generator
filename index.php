
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
        <button type="button" class="btn btn-primary" data-dismiss="modal" onclick = "startProcessing()" >Generate Report</button>
      </div>
    </div>
  </div>
</div>




<div id="accordion" role="tablist" aria-multiselectable="true">
  <!-- <div class="card">
    <div class="card-header" role="tab" id="headingOne">
      <h5 class="mb-0">
        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
          Collapsible Group Item #1
        </a>
      </h5>
    </div>

    <div id="collapseOne" class="collapse in" role="tabpanel" aria-labelledby="headingOne">
      <div class="card-block">
        Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.
      </div>
    </div>
  </div> -->
</div>

 
  <script src="http://code.jquery.com/jquery-3.1.0.min.js" crossorigin="anonymous"></script>
  <script src="js/jquery.filer.js" type="text/javascript"></script>
  <script src="js/custom.js" type="text/javascript"></script>
  <script src="js/bootstrap.min.js"></script>
  <script src="js/main.js"></script>
      </body>
    </html>