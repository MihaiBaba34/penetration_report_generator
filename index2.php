<?php
  define('FPDF_FONTPATH','font/');
  require('fpdf.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>

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
    </head>
    <body>
      <div class="navbar navbar-default navbar-static-top">
        <div class="container">
          <div class="navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Brand</a>
          </div>
          <div class="collapse navbar-collapse">
            <ul class="nav navbar-nav">
              <li class="active"><a href="#">Home</a></li>
              <li><a href="#contact">Contact</a></li>
            </ul>
            <ul class="nav navbar-nav navbar-right">
              <li><a href="#about">About</a></li>
            </ul>
          </div><!--/.nav-collapse -->
        </div>
      </div>

      <div class="container">

        <div class="text-center">
        </div>

      </div><!-- /.container -->
      <!-- script references -->


      <div id="upload-wrapper">
    <h3>Penetration Report Generator</h3><br>
    <form enctype="multipart/form-data" action="" method="POST" id="upload">
      <input type="hidden" name="MAX_FILE_SIZE" value="1230000" />
      <input name="userfile" type="file" id="file"  /><br><br>
      <div class="result"></div>
      <input type="button" value="Upload" id="uploadfile" />
    </form>
    <form enctype="multipart/form-data" action="convert.php" method="POST" style="display:none;" id="nodelist">
      <input type="hidden" name="filename" value="" id="xmlname"/>
      <input type="submit" value="Convert as PDF" id="convert" />
      <input type="hidden" name="htmlcode" value="" />
      <div id="xmltohtml"></div>
      <div id="xml_result"></div>
    </form>
  </div>




      <script type="text/javascript" src="js/Upload_js.js"></script>
      <script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.2/jquery.min.js"></script>
      <script src="js/bootstrap.min.js"></script>
      </body>
    </html>