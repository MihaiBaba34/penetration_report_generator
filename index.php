
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
      <link href="css/styles.css" rel="stylesheet">


    <style>
        body {
            font-family: 'Roboto Condensed', sans-serif;
            font-size: 14px;
            line-height: 1.42857143;
            color: #47525d;
            background-color: #fff;

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
     
    <div id="content">


      <input type="file" name="files[]" id="filer_input2" multiple="multiple">
   

    </div>








<div id="parseResults">
	

</div>




<button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#formularModal">
  Start Processing
</button>

<!-- Formular with report info modal -->
<div class="modal" id="formularModal" tabindex="-1" role="dialog"
     aria-labelledby="myModalLabel1" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title " id="myModalLabel1">Modal title</h4>
      </div>
      <div class="modal-body " >
<div class="row">
  <div class="col-lg-8 col-lg-offset-2">
        <form>

  <div class="form-group">
    <input type="text" class="form-control" id="formGroupExampleInput" placeholder="Server Name">
  </div>
  <div class="form-group">
    <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
  </div>
  <div class="form-group">
      <input type="text" class="form-control" id="formGroupExampleInput2" placeholder="Another input">
  </div>
</form>
</div>
</div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>






 
  <script src="http://code.jquery.com/jquery-3.1.0.min.js" crossorigin="anonymous"></script>
  <script src="js/jquery.filer.min.js" type="text/javascript"></script>
  <script src="js/custom.js" type="text/javascript"></script>
   <script type="text/javascript" src="js/Upload_js.js"></script>
  <script src="js/bootstrap.min.js"></script>
      </body>
    </html>