<!DOCTYPE html>
<html lang="en">

<head>

    <!-- Styles -->
    <link href="css/jquery.filer.css" rel="stylesheet">
    <link href="css/themes/jquery.filer-dragdropbox-theme.css" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <!-- Tether for Bootstrap -->
    <script src="https://www.atlasestateagents.co.uk/javascript/tether.min.js"></script>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <title>Penetration Report Generator</title>
    <meta name="generator" content="Bootply" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    
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

 <input id="reloadValue" type="hidden" name="reloadValue" value="" />
    <div class="row">
        <div class="col-md-4"></div>
        <div class="col-md-4">
            <!-- Server Name field -->
            <div class="input-group col-md-10 col-md-offset-1">
                <span class="input-group-addon">Server Name</span>
                <input id="serverNameInput" type="text" class="form-control" placeholder="Server Name">
            </div>
            <br>
            <br>
            <!-- Website URL field -->
            <div class="input-group col-md-10 col-md-offset-1">
                <span class="input-group-addon">Website URL</span>
                <input id="webURLInput" type="text" class="form-control" placeholder="www.example.org">
            </div>
            <br>
            <br>
            <!-- Date field -->
            <div class="input-group col-md-10 col-md-offset-1">
                <span class="input-group-addon">Date</span>
                <input id="dateInput" type="text" class="form-control" placeholder="DD/MM/YYYY">
            </div>

            <br>
            <br>
            </form>
        </div>
    </div>


    <!-- Upload form container -->
    <div id="content" style="padding-top: 3%">

        <input type="file" name="files[]" id="filer_input2" multiple="multiple">

    </div>
 
  
    <script src="http://code.jquery.com/jquery-3.1.0.min.js" crossorigin="anonymous"></script>
    <script src="js/jquery.filer.min.js" type="text/javascript"></script>
    <script src="js/custom.js" type="text/javascript"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
      <script type="text/javascript">
        jQuery(document).ready(function()
        {
                var d = new Date();
                d = d.getTime();
                if (jQuery('#reloadValue').val().length == 0)
                {
                        jQuery('#reloadValue').val(d);
                        jQuery('body').show();
                }
                else
                {
                        jQuery('#reloadValue').val('');
                        location.reload();
                }
        });
    </script>
</body>

</html>