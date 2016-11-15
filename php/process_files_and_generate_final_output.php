<?php

//array with all reports from XML
$global_array = $_POST['global_array'];
//array with data from index page
$inputFields = $_POST['input_fields'];
//array with data from acunetix XML
$acunetix=array();
if(isset($_POST['acunetix']))
{
  $acunetix=$_POST['acunetix'];
}
//array with data from combined XML (retina+nessus)
$combined=array();
if(isset($_POST['combined']))
{
  $combined=$_POST['combined'];
}
//response from HTML generator (URL to new HTML)
$php_response = array();

//total number of issues found
$total_issues=0;

//array with risk badge names
$risk_priorities = array(
  'Critical',
  'High',
  'Medium',
  'Low',
  'Information'
  );
//array with risk badge collors
$badge_collors   = array(
  'label label-info',
  'label label-warning',
  'label label-default',
  'label label-primary',
  'label label-success'
  );
//array with number of each type of report 
$risk_counters = array();
$text_output_path = '../downloads/TextReport.txt';

main();
//main function that starts HTML generator and return a link to output file
function main()
{
  global $global_array;
  global $risk_counters;
  global $php_response;
  global $inputFields;
  global $text_output_path;

  if(file_exists($text_output_path))
  {
  	unlink($text_output_path);
  }

  $url_to_html_output = buildHTMLPageWithContent($global_array);



  echo json_encode($url_to_html_output);
}

function writeToFile($filename, $textContent)
{
	//$myfile = fopen($filename, "w");

	$myfile = file_put_contents($filename, $textContent.PHP_EOL , FILE_APPEND | LOCK_EX);

	//fwrite($myfile, $textContent);

	//fclose($myfile);
}

//function that builds html content out of global array
function buildHTMLPageWithContent($global_array)
{
  global $inputFields;
  global $issuesNumber;
  global $total_issues;
  global $text_output_path;

  $htmlDocument = new DOMDocument();

  $html_input_path  = '../output_html/simpleHTMLReportStructure.html';
  $html_output_path = '../downloads/HTMLReport.html';
  
    //load the index.php file without displaying warnings
  libxml_use_internal_errors(true);
  $htmlDocument->loadHTMLFile($html_input_path);
  libxml_clear_errors();
  $server_name_span   = $htmlDocument->getElementById('server_name');
  $site_name_span     = $htmlDocument->getElementById('site_name');
  $date_span          = $htmlDocument->getElementById('date_field');
  $issues_number_span = $htmlDocument->getElementById('issues_number');

  $inputFields = json_encode($inputFields);
  $inputFields = json_decode($inputFields);

  $server_name = $inputFields->serverNameInput;
  $site_name   = $inputFields->webURLInput;
  $date        = $inputFields->dateAndTime;

  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($server_name)) {
    $server_name_span->appendChild($fragment);        
  } 

  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($site_name)) {
    $site_name_span->appendChild($fragment);
  } 

  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($date)) {
    $date_span->appendChild($fragment);
  }

  $divElement = $htmlDocument->getElementById('container');

//Inserting acunetix selected reports into final HTML file
  if(isset($_POST['acunetix']))
  {
    $htmlContent = buildAcunetixHTMLString($htmlDocument, $divElement, $global_array, $text_output_path);
    $fragment    = $htmlDocument->createDocumentFragment();
    if ($fragment->appendXML($htmlContent)) {
      $divElement->appendChild($fragment);
    } 
  }

//Inserting combined(retina+nessus) selected reports into final HTML file
  if(isset($_POST['combined']))
  {
    $htmlContent = buildCombinedHTMLString($htmlDocument, $divElement, $global_array, $text_output_path);
    $fragment    = $htmlDocument->createDocumentFragment();
    if ($fragment->appendXML($htmlContent)) {
      $divElement->appendChild($fragment);
    } 
  }

$divElement=$htmlDocument->getElementById("issues_number");
$fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($total_issues)) {
    $divElement->appendChild($fragment);
  } 

//writing all data to new file
  $htmlDocument->saveHTMLFile('../downloads/' . $html_output_path);

  return $html_output_path;
}


//function to create html for header content out of Acunetix reports
function appendAcunetixTableHeader($htmlDocument, $divElement, $risk_counters)
{

  $total_risks = array_sum($risk_counters);


  $HTMLAcunetixAntet = '
  <div class="alert alert-info" role="alert" style="background: #ECEEEF; border:0;">
    <h3 class="text-center"><strong>Web Application Vulnerabilities</strong></h3>
  </div>
  <br />
  <table class="table ">
    <thead>
      <tr>
        <th style="background: #FF4D00;">High</th>
        <th style="background: #E99F54;">Medium</th>
        <th style="background: #008800;">Low</th>
        <th style="background: #337AB7;">Info</th>
        <th class="table-active">Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td id="High" style="background: #FF4D00;  font-weight: bold; font-size: 160%;">' .$risk_counters['High'].'</td>
        <td id="Medium" style="background: #E99F54; font-weight: bold; font-size: 160%;">' .$risk_counters['Medium'].'</td>
        <td id="Low" style="background: #008800;  font-weight: bold; font-size: 160%;">' .$risk_counters['Low'].'</td>
        <td id="Informational" style="background: #337AB7; font-weight: bold; font-size: 160%;">' .$risk_counters['Information'].'</td>
        <td id="Total" class="table-active" style="font-size: 160%; font-weight: bold;" >' .$total_risks.'</td>
      </tr>

    </tbody>
  </table>
  ';
        //create a fragment (a html fragment) 
  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($HTMLAcunetixAntet)) {
    $divElement->appendChild($fragment);
  } 
}

//function to create html for header content out of combined reports(retina+nessus)
function appendCombinedTableHeader($htmlDocument, $divElement, $risk_counters)
{

  $total_risks = array_sum($risk_counters);
  $HTMLCombinedAntet = '
  <div class="alert alert-info" role="alert" style="background: #ECEEEF; border:0;">
    <h3 class="text-center"><strong>Web Application Vulnerabilities</strong></h3>
  </div>
  <br />
  <table class="table ">
    <thead>
      <tr>
        <th style="background: #DF0803;">Critical</th>
        <th style="background: #FF4D00;">High</th>
        <th style="background: #E99F54;">Medium</th>
        <th style="background: #008800;">Low</th>
        <th style="background: #337AB7;">Info</th>
        <th class="table-active">Total</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td id="Critical" style="background: #DF0803;  font-weight: bold; font-size: 160%;">' .$risk_counters['Critical'].'</td>
        <td id="High" style="background: #FF4D00;  font-weight: bold; font-size: 160%;">' .$risk_counters['High'].'</td>
        <td id="Medium" style="background: #E99F54; font-weight: bold; font-size: 160%;">' .$risk_counters['Medium'].'</td>
        <td id="Low" style="background: #008800;  font-weight: bold; font-size: 160%;">' .$risk_counters['Low'].'</td>
        <td id="Informational" style="background: #337AB7; font-weight: bold; font-size: 160%;">' .$risk_counters['Information'].'</td>
        <td id="Total" class="table-active" style="font-size: 160%; font-weight: bold;" >' .$total_risks.'</td>
      </tr>
    </tbody>
  </table>
  ';



  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($HTMLCombinedAntet)) {
    $divElement->appendChild($fragment);
  } 

}



//initialize couters variables with 0
function setCountersToZero()
{

if(!isset($risk_counters['Critical']))
{
  $risk_counters['Critical']=0;
}
if(!isset($risk_counters['High']))
{
  $risk_counters['High']=0;
}
if(!isset($risk_counters['Medium']))
{
  $risk_counters['Medium']=0;
}
if(!isset($risk_counters['Low']))
{
  $risk_counters['Low']=0;
}
if(!isset($risk_counters['Information']))
{
  $risk_counters['Information']=0;
}
return $risk_counters;
}




function buildAcunetixHTMLString($htmlDocument, $divElement, $global_array, $filename)
{
  global $risk_priorities, $risk_counters, $badge_collors, $acunetix, $total_issues;

  $html                = '';
  $text = '';
  $HTMLAcunetixContent = '';
  $AcunetixTextContent = '';
  $reportNumber        = 0;

  foreach ($acunetix as $key => $value) 
  {
    if ($value["left"]["checked"] == "checked" || $value["right"]["checked"] == "checked") 
    {           
            //display element with the current checkbox checked
      $report                       = $global_array['acunetix'];
      $report_with_risk_priority    = $report[$risk_priorities[$value["left"]["riskLevel"]]];
      $element_from_specific_report = $report_with_risk_priority[$value["left"]["positionInRiskVector"] - 1];


      if (!isset($risk_counters[$risk_priorities[$value["left"]["riskLevel"]]])) {
       $risk_counters[$risk_priorities[$value["left"]["riskLevel"]]] = 1;
     } else {
      ++ $risk_counters[$risk_priorities[$value["left"]["riskLevel"]]];
    }            

    $fixInformation = '';
    $fixInformation = $element_from_specific_report['information'];

    $plugin_name = '';
    $plugin_name = $element_from_specific_report['plugin_name'];
    $description = $element_from_specific_report['description'];

    $risk_factor = '';
    $risk_factor = $risk_priorities[$value["left"]["riskLevel"]];
    if ($risk_priorities[$value["left"]["riskLevel"]] === 'Information') {
      $risk_factor = $risk_priorities[$value["left"]["riskLevel"]] . 'al';
    }

    $cve_strings = '';
    $cve_strings = $element_from_specific_report['cve'];

    $mandatory = "";
    if($value["right"]["checked"] == "checked")
    {
      $mandatory = "[--MANDATORY--]";
    }

    ++$total_issues;
    ++$reportNumber;
    $reportId = 'acunetix' . $reportNumber;



	$text = "Plugin name: ".$plugin_name.$mandatory." \n";
	$text = $text. "Risk factor: ".$risk_factor."\n";
	$text = $text. "Description: ".$description."\n";
	$text = $text. "Fix Information: ".$fixInformation."\n";
	$text = $text. "CVE: ".$cve_strings."\n\n";


    $html = "
    <div class='nodedata'>
     <div class='panel-group'>
      <div class='panel panel-default'>
       <div class='panel-heading'>
        <h4 class='panel-title'>
         <span>
          <span style='margin-left: 30px;'>" . $plugin_name ."&#160;&#160;&#160;<font color=\"red\">".$mandatory."</font>
          </span>
        </span>
        <span class='pull-right'>
          <span class=\"" . $badge_collors[$value["left"]["riskLevel"]] . '">' . $risk_factor . "
          </span>
          <a style=\"color:black\" data-toggle='collapse' href='#" . $reportId . "' class='' aria-expanded='true'> &#160;Extend  &#160;<span style=\"color:black\" class=\"glyphicon glyphicon-menu-hamburger\"></span> </a>
        </span>

      </h4>
    </div>


    <div id='" . $reportId . "' class='panel-collapse collapse ' aria-expanded='false'>
      <ul class='list-group'>
       <li class='list-group-item'>
        <input type='checkbox' style='display:none;' name='result[0][description]' value=\"" . $description . '" /><strong>Description: </strong> ' . $description . " </li>
        <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value=\"" . $fixInformation . '" /><strong>
         Information:&#160;  </strong>' . $fixInformation . "<br />  </li>

         <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $cve_strings . '" /><strong>CVE:&#160;</strong>' . $cve_strings . '
         </li>
       </ul>
     </div>
   </div>
 </div>
</div>';

$HTMLAcunetixContent = $HTMLAcunetixContent.$html;
$AcunetixTextContent = $AcunetixTextContent.$text;

}
}   
appendAcunetixTableHeader($htmlDocument, $divElement, $risk_counters);

$total_risks = array_sum($risk_counters);

//append header text to the output text file
$header_text_info = "High: ".$risk_counters['High']." || ";
$header_text_info = $header_text_info."Medium: ".$risk_counters['Medium']." || ";
$header_text_info = $header_text_info."Low: ".$risk_counters['Low']." || ";
$header_text_info = $header_text_info."Information: ".$risk_counters['Information']." || ";
$header_text_info = $header_text_info."Total risks: ".$total_risks." ||\n\n";

$AcunetixTextContent = $header_text_info .$AcunetixTextContent;
writeToFile($filename,$AcunetixTextContent);

return $HTMLAcunetixContent;
}



function buildCombinedHTMLString($htmlDocument, $divElement, $global_array, $filename)
{

  global $risk_priorities, $risk_counters, $badge_collors, $combined, $total_issues;


  $risk_counters=setCountersToZero();
    $html                = '';
  $HTMLCombinedContent = '';
  $reportNumber        = 0;
  $text = '';
  $CombinedTextContent = '';

  //echo json_encode($combined);

  foreach ($combined as $key => $value) 
  {
    if ($value["left"]["checked"] == "checked" || $value["right"]["checked"] == "checked") 
    {   
            //display element with the current checkbox checked
      $report                       = $global_array['combined'];
      $report_with_risk_priority    = $report[$risk_priorities[$value["left"]["riskLevel"]]];
      $element_from_specific_report = $report_with_risk_priority[$value["left"]["positionInRiskVector"] - 1];


      if (!isset($risk_counters[$risk_priorities[$value["left"]["riskLevel"]]])) {
       $risk_counters[$risk_priorities[$value["left"]["riskLevel"]]] = 1;
     } else {
      ++ $risk_counters[$risk_priorities[$value["left"]["riskLevel"]]];
    }     


    $risk_factor = $risk_priorities[$value["left"]["riskLevel"]];     

    if ($risk_priorities[$value["left"]["riskLevel"]] === 'Information') {
      $risk_factor = $risk_priorities[$value["left"]["riskLevel"]] . 'al';
    }

    $mandatory = "";
    if($value["right"]["checked"] == "checked")
    {
      $mandatory = "[--MANDATORY--]";
    }

    $fixInformation = '';
    if (isset($element_from_specific_report['information']))
    {
      $fixInformation = $element_from_specific_report['information'];
      $fixInformation = preprocessOutputString($fixInformation);
    }

    if ($fixInformation == '') {

      if (isset($element_from_specific_report['solution'])) {
        $fixInformation = $element_from_specific_report['solution'];
        $fixInformation = preprocessOutputString($fixInformation);

      }
    }

    $exploit = '';
    if (isset($element_from_specific_report['exploit'])) {
      $exploit = $element_from_specific_report['exploit'];
      $exploit = preprocessOutputString($exploit);
    }
    if ($exploit == '') {
      $exploit = '---';
    }

    $plugin_name = '';
    if (isset($element_from_specific_report['plugin_name'])) {
      $plugin_name = $element_from_specific_report['plugin_name'];
      $plugin_name = preprocessOutputString($plugin_name);
    }

    $description = '';
    if (isset($element_from_specific_report['description'])) {
      $description = $element_from_specific_report['description'];
      $description = preprocessOutputString($description);
    }

    $cve_strings = '';
    if (isset($element_from_specific_report['cve'])) {
      $cve_strings = $element_from_specific_report['cve'];
      $cve_strings = preprocessOutputString($cve_strings);
    }
    ++$total_issues;
    ++$reportNumber;

    $reportId = 'combined' . $reportNumber;

    $text = "Plugin name: ".$plugin_name.$mandatory."\n";
	$text = $text. "Risk factor: ".$risk_factor."\n";
	$text = $text. "Description: ".$description."\n";
	$text = $text. "Fix Information: ".$fixInformation."\n";
	$text = $text. "Exploit: ".$exploit."\n";
	$text = $text. "CVE: ".$cve_strings."\n\n";


    $html = "
    <div class='nodedata'>
     <div class='panel-group'>
      <div class='panel panel-default'>
       <div class='panel-heading'>
        <h4 class='panel-title'>
         <span>
         <span style='margin-left: 30px;'>" . $plugin_name . "&#160;&#160;&#160;<font color=\"red\">".$mandatory."</font>
          </span>
        </span>
        <span class='pull-right'>
          <span class=\"" . $badge_collors[$value["left"]["riskLevel"]] . '">' . $risk_factor . "
          </span>
          <a style=\"color:black\" data-toggle='collapse' href='#" . $reportId . "' class='' aria-expanded='true'> &#160;Extend  &#160;<span style=\"color:black\" class=\"glyphicon glyphicon-menu-hamburger\"></span> </a>
        </span>

      </h4>
    </div>


    <div id='" . $reportId . "' class='panel-collapse collapse ' aria-expanded='false'>
      <ul class='list-group'>
       <li class='list-group-item'>
        <input type='checkbox' style='display:none;' name='result[0][description]' value=\"" . $description . '" /><strong>Description: </strong> ' . $description . " </li>
        <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value=\"" . $fixInformation . '" /><strong>
         Fix Information:&#160;  </strong>' . $fixInformation . "<br />  </li>
         <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" .$exploit.'" /><strong>Exploit: &#160;</strong>'.$exploit."
         </li>

         <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $cve_strings . '" /><strong>CVE:&#160;</strong>' . $cve_strings . '
         </li>
       </ul>
     </div>
   </div>
 </div>
</div>';

$HTMLCombinedContent = $HTMLCombinedContent.$html;
$CombinedTextContent = $CombinedTextContent.$text;

}
}   
appendCombinedTableHeader($htmlDocument, $divElement, $risk_counters);


$total_risks = array_sum($risk_counters);

//append header text to the output text file
$header_text_info = "Critical: ".$risk_counters['Critical']." || ";
$header_text_info = $header_text_info."High: ".$risk_counters['High']." || ";
$header_text_info = $header_text_info."Medium: ".$risk_counters['Medium']." || ";
$header_text_info = $header_text_info."Low: ".$risk_counters['Low']." || ";
$header_text_info = $header_text_info."Information: ".$risk_counters['Information']." || ";
$header_text_info = $header_text_info."Total risks: ".$total_risks." ||\n\n";

$CombinedTextContent = $header_text_info .$CombinedTextContent;
writeToFile($filename,$CombinedTextContent);

return $HTMLCombinedContent;
}

//remove special chars from string 
function preprocessOutputString($output)
{
  $result = str_replace('"', "'", $output);
  $result = htmlspecialchars($result);

  return $result;
}
