<?php


$inputFields = $_POST['input'];
//echo json_encode($inputFields);

$uploaded_files = $_POST['files_map'];

$global_array = array();
$issuesNumber =0;

/*$uploaded_files = array();
array_push($uploaded_files,"../test/testphp.vulnweb_retina.xml");
array_push($uploaded_files,"../test/testphp_vulnweb_nessus.xml");
array_push($uploaded_files,"../test/testphp.vulnweb_acunetix.xml");*/

for ($i = 0; $i < count($uploaded_files); ++$i) {
  $uploaded_files[$i] = "../uploads/" . $uploaded_files[$i];
}


//echo json_encode($uploaded_files);

$risk_priorities = array(
  'Critical',
  'High',
  'Medium',
  'Low',
  'Information'
  );
$badge_collors = array(
  'label label-info',
  'label label-warning',
  'label label-default',
  'label label-primary',
  'label label-success'
  );


$risk_counters = array();

main();


function main()
{

  global $global_array;
  global $uploaded_files;
  global $risk_counters;



    //extract xml files content
  foreach ($uploaded_files as $key => $value) {
    process_xml_input_file($value);
  }

    //remove duplicates
  $processed_array = removeDuplicates($global_array);

    //sort items
  $sorted_array = sortItemsByRisk($processed_array);

    //generate html report output
  buildHTMLPageWithContent($sorted_array);

    //console_log($risk_counters);
}

function buildHTMLPageWithContent($global_array)
{
  global $inputFields;
  global $issuesNumber;

  $htmlDocument = new DOMDocument();

  $html_input_path  = "../output_html/generated_output.html";
  $html_output_path = "output_html/generated_output2.html";

    //load the index.php file without displaying warnings
  libxml_use_internal_errors(true);
  $htmlDocument->loadHTMLFile($html_input_path);
  libxml_clear_errors();
  $server_name_span = $htmlDocument->getElementById('server_name');
  $site_name_span = $htmlDocument->getElementById('site_name');
  $date_span = $htmlDocument->getElementById('date_field');
  $issues_number_span=$htmlDocument->getElementById('issues_number');
  $inputFields=json_decode($inputFields);


  $server_name = $inputFields->serverNameInput;
  $site_name = $inputFields->webURLInput;
  $date = $inputFields->dateAndTime;


  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($server_name)) {
    $server_name_span->appendChild($fragment);
  } else {
            //error      
  }

  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($site_name)) {
    $site_name_span->appendChild($fragment);
  } else {
            //error      
  }

  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($date)) {
    $date_span->appendChild($fragment);
  } else {
            //error      
  }



  $divElement = $htmlDocument->getElementById('container');


  $htmlContent = buildHTMLString($htmlDocument, $divElement, $global_array);
  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($issuesNumber)) {
    $issues_number_span->appendChild($fragment);
  } else {
            //error      
  }

    //////echo $htmlContent;
    //$divElement->documentElement->appendChild()
    //$htmlDocument->saveHTMLFile("../" + $html_output_path); 
  $htmlDocument->saveHTMLFile("../" . $html_output_path);

  echo json_encode($html_output_path);
    //////echo json_encode($_SERVER['HTTP_HOST']);
}

function buildHTMLString($htmlDocument, $divElement, $global_array)
{

  global $risk_priorities, $risk_counters, $badge_collors;

  $html                = "";
  $HTMLCombinedContent = "";
  $HTMLAcunetixContent = "";
  $reportNumber        = 0;

  $nr_priorities = count($risk_priorities);
  if (count($global_array["acunetix"]) > 0) {
    $risk_counters = array();
    $reportNumber  = 0;





        //treat acunetix values 
    for ($i = 0; $i < $nr_priorities; $i++) {
 //sorting by alphabet order in each priority type
      $report_title = array();
      foreach ($global_array["acunetix"][$risk_priorities[$i]] as $key => $row)
      {

        $report_title[$key] = $row['plugin_name'];
      }
      array_multisort($report_title, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $global_array["acunetix"][$risk_priorities[$i]]);


            //treat all combined values from applications: nessus, retina, nmap
      foreach ($global_array["acunetix"][$risk_priorities[$i]] as $key_2 => $report) {


        if (!isset($risk_counters[$risk_priorities[$i]])) {

          $risk_counters[$risk_priorities[$i]] = 1;
        } else {
          $risk_counters[$risk_priorities[$i]]++;
        }

        $fixInformation = "";
        if (isset($report["information"])) {
          $fixInformation = $report["information"];
          $fixInformation = preprocessOutputString($fixInformation);
        }
      //      $exploit = "";
      // if (isset($report["exploit"])) {
      //   $exploit = $report["exploit"];

      //   $exploit = preprocessOutputString($exploit);

      // }
      // if($exploit=="")
      // {
      //   $exploit= "---"; 
      // }

        $plugin_name = "";
        if (isset($report["plugin_name"])) {
          $plugin_name = $report["plugin_name"];
          $plugin_name = preprocessOutputString($plugin_name);
        }

        $description = "";
        if (isset($report["description"])) {
          $description = $report["description"];
          $description = preprocessOutputString($description);
        }

        $risk_factor = "";
        if (isset($report["risk_factor"])) {
                    //$risk_factor = $report["risk_factor"];
          $risk_factor = $risk_priorities[$i];
          if ($risk_priorities[$i] === "Information") {
            $risk_factor = $risk_priorities[$i] . "al";
          }
          $risk_factor = preprocessOutputString($risk_factor);
        }

        $cve_strings = "";
        if (isset($report["cve"])) {
          $cve_strings = $report["cve"];
          $cve_strings = preprocessOutputString($cve_strings);
        }

        $mandatory_item="";
        if($risk_factor=="Critical"||$risk_factor=="High"||$risk_factor=="Medium")
        {
          $mandatory_item="checked=\"checked\"";
        }

        $reportNumber++;
        $reportId = "acunetix" . $reportNumber;

        $html = "
        <div class='nodedata'>
         <div class='panel-group'>
          <div class='panel panel-default'>
           <div class='panel-heading'>
            <h4 class='panel-title'>
             <span>
              <input id='supplied' class='checkrecord' type='checkbox' value='Record:1' name='Record:1' checked=\"checked\" /> 
              <span style='margin-left: 30px;'>" . $plugin_name . "
              </span>
            </span>
            <span class='pull-right'>
              <span class=\"".$badge_collors[$i]."\">" . $risk_factor . "
              </span>
              <a style=\"color:black\" data-toggle='collapse' href='#" . $reportId . "' class='' aria-expanded='true'> &#160;Extend  &#160;<span style=\"color:black\" class=\"glyphicon glyphicon-menu-hamburger\"></span> </a>
              <input id='supplied' class='checkrecord' type='checkbox' value='Record:1' name='Record:1' ".$mandatory_item." /> 
            </span>       

          </h4>
        </div>

        
        <div id='" . $reportId . "' class='panel-collapse collapse ' aria-expanded='false'>
          <ul class='list-group'>
           <li class='list-group-item'>
            <input type='checkbox' style='display:none;' name='result[0][description]' value=\"" . $description . "\" /><strong>Description: </strong> " . $description . " </li>
            <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value=\"" . $fixInformation . "\" /><strong>
             Information:&#160;  </strong>" . $fixInformation . "<br />  </li>

             <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $cve_strings . "\" /><strong>CVE:&#160;</strong>" . $cve_strings . "
             </li>
           </ul>
         </div>
       </div>
     </div>
   </div>";

                //////echo $html;
   $HTMLAcunetixContent = $HTMLAcunetixContent . $html;
 }
}

if (!isset($risk_counters["Critical"])) {
  $risk_counters["Critical"] = 0;
}

if (!isset($risk_counters["High"])) {
  $risk_counters["High"] = 0;
}

if (!isset($risk_counters["Medium"])) {
  $risk_counters["Medium"] = 0;
}

if (!isset($risk_counters["Low"])) {
  $risk_counters["Low"] = 0;
}

if (!isset($risk_counters["Information"])) {
  $risk_counters["Information"] = 0;
}
$totalNumberOfIssues=$risk_counters["Critical"]+$risk_counters["High"]+$risk_counters["Medium"]+$risk_counters["Low"]+$risk_counters["Information"];
global $issuesNumber;
$issuesNumber=$issuesNumber+$totalNumberOfIssues;
$HTMLAcunetixAntet = "
<div class=\"alert alert-info\" role=\"alert\" style=\"background: #ECEEEF; border:0;\">
  <h3 class=\"text-center\"><strong>Web Application Vulnerabilities</strong></h3> 
</div>
<br />
<table class=\"table \">
  <thead >
    <tr>
      <th style=\"background: #FF4D00;\">High</th>
      <th style=\"background: #E99F54;\">Medium</th>
      <th style=\"background: #008800;\">Low</th>
      <th style=\"background: #337AB7;\">Info</th>
      <th class=\"table-active\">Total</th>


    </tr>
  </thead>
  <tbody>
    <tr>
      <td id=\"High\" style=\"background: #FF4D00;  font-weight: bold; font-size: 160%;\">" . $risk_counters["High"] . "</td>
      <td id=\"Medium\" style=\"background: #E99F54; font-weight: bold; font-size: 160%;\">" . $risk_counters["Medium"] . "</td>
      <td id=\"Low\" style=\"background: #008800;  font-weight: bold; font-size: 160%;\">" . $risk_counters["Low"] . "</td>
      <td id=\"Informational\" style=\"background: #337AB7; font-weight: bold; font-size: 160%;\">" . $risk_counters["Information"] . "</td>
      <td id=\"Total\" class=\"table-active\" style=\"font-size: 160%; font-weight: bold;\" >" . $totalNumberOfIssues . "</td>


    </tr>

  </tbody>
</table>
";
//DF0803- red
        //create a fragment (a html fragment) with changed parameters such as 
$fragment          = $htmlDocument->createDocumentFragment();
if ($fragment->appendXML($HTMLAcunetixAntet)) {
  /*$divElement->insertBefore($fragment, $divElement->firstChild);*/
  $divElement->appendChild($fragment);
} else {
            ////echo $HTMLAcunetixAntet;        
            ////echo $plugin_name."<br>";      
}
        //create a fragment (a html fragment) with changed parameters such as 
$fragment = $htmlDocument->createDocumentFragment();
if ($fragment->appendXML($HTMLAcunetixContent)) {
  /*$divElement->insertBefore($fragment, $divElement->firstChild);*/
  $divElement->appendChild($fragment);
} else {
            ////echo $HTMLAcunetixContent;        
            ////echo $plugin_name."<br>";      
}






console_log($risk_counters);
}


if(count($global_array["combined"]))
{

  $risk_counters = array();
      //display values after the defined risk priorities 
  for ($i = 0; $i < $nr_priorities; $i++) {
 //sorting by alphabet order in each priority type
    $report_title = array();
    foreach ($global_array["combined"][$risk_priorities[$i]] as $key => $row)
    {
      $report_title[$key] = $row['plugin_name'];
    }
    array_multisort($report_title, SORT_ASC|SORT_NATURAL|SORT_FLAG_CASE, $global_array["combined"][$risk_priorities[$i]]);


        //treat all combined values from applications: nessus, retina, nmap
    foreach ($global_array["combined"][$risk_priorities[$i]] as $key_2 => $report) {

      if (!isset($risk_counters[$risk_priorities[$i]])) {
        $risk_counters[$risk_priorities[$i]] = 1;
      } else {
        $risk_counters[$risk_priorities[$i]]++;
      }

      $fixInformation = "";
      if (isset($report["information"])) {
        $fixInformation = $report["information"];
        $fixInformation = preprocessOutputString($fixInformation);
      }
      $exploit = "";
      if (isset($report["exploit"])) {
        $exploit = $report["exploit"];

        $exploit = preprocessOutputString($exploit);

      }
      if($exploit=="")
      {
        $exploit= "---"; 
      }
      if($fixInformation=="")
      {
        if (isset($report["solution"])) {
          $fixInformation=$report["solution"];
          $fixInformation = preprocessOutputString($fixInformation);
        }
      }

      $plugin_name = "";
      if (isset($report["plugin_name"])) {
        $plugin_name = $report["plugin_name"];
        $plugin_name = preprocessOutputString($plugin_name);
      }

      $description = "";
      if (isset($report["description"])) {
        $description = $report["description"];
        $description = preprocessOutputString($description);
      }

      $risk_factor = "";
      if (isset($report["risk_factor"])) {
                //$risk_factor = $report["risk_factor"];
        $risk_factor = $risk_priorities[$i];
        if ($risk_priorities[$i] === "Information") {
          $risk_factor = $risk_priorities[$i] . "al";
        }
        $risk_factor = preprocessOutputString($risk_factor);
      }

      $cve_strings = "";
      if (isset($report["cve"])) {
        $cve_strings = $report["cve"];
        $cve_strings = preprocessOutputString($cve_strings);
      }
      $reportNumber++;
      $mandatory_item="";
      if($risk_factor=="Critical"||$risk_factor=="High"||$risk_factor=="Medium")
      {
        $mandatory_item="checked=\"checked\"";
      }
    
      $reportId            = "combined" . $reportNumber;
      $html                = "
      <div class='nodedata'>
       <div class='panel-group'>
        <div class='panel panel-default'>
         <div class='panel-heading' style=\"background: #ECEEEF\">
          <h4 class='panel-title'>
           <span>
            <input id='supplied' class='checkrecord' type='checkbox' value='Record:1' name='Record:1' checked=\"checked\" /> 
            <span style='margin-left: 30px;'>" . $plugin_name . "
            </span>
          </span>
          <span class='pull-right'>
            <span class=\"".$badge_collors[$i]."\">" . $risk_factor . "
            </span>
            <a style=\"color:black\" data-toggle='collapse' href='#" . $reportId . "' class='' aria-expanded='true'> &#160; Extend    &#160;<span style=\"color:black\" class=\"glyphicon glyphicon-menu-hamburger\"></span></a>
            <input id='supplied' class='checkrecord' type='checkbox' value='Record:1' name='Record:1' ".$mandatory_item." /> 

          </span>       
        </h4>
      </div>

      <div id='" . $reportId . "' class='panel-collapse collapse ' aria-expanded='false'>
        <ul class='list-group'>
         <li class='list-group-item'>
          <input type='checkbox' style='display:none;' name='result[0][description]' value=\"" . $description . "\" /><strong>Description: </strong> " . $description . " </li>
          <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value=\"" . $fixInformation . "\" /><strong>Fix 
           Information:&#160;  </strong>" . $fixInformation . "<br />  </li>
           <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $exploit . "\" /><strong>Exploit: &#160;</strong>" . $exploit . "
           </li>
           <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"" . $cve_strings . "\" /><strong>CVE: &#160;</strong>" . $cve_strings . "
           </li>
         </ul>
       </div>
     </div>
   </div>
 </div>";
 $HTMLCombinedContent = $HTMLCombinedContent . $html;
            //////echo $html;

}

}

if (!isset($risk_counters["Critical"])) {
  $risk_counters["Critical"] = 0;
}

if (!isset($risk_counters["High"])) {
  $risk_counters["High"] = 0;
}

if (!isset($risk_counters["Medium"])) {
  $risk_counters["Medium"] = 0;
}

if (!isset($risk_counters["Low"])) {
  $risk_counters["Low"] = 0;
}

if (!isset($risk_counters["Information"])) {
  $risk_counters["Information"] = 0;
}

$totalNumberOfIssues=$risk_counters["Critical"]+$risk_counters["High"]+$risk_counters["Medium"]+$risk_counters["Low"]+$risk_counters["Information"];
global $issuesNumber;
$issuesNumber=$issuesNumber+$totalNumberOfIssues;

if(count($global_array["combined"]) > 0)
{
  $HTMLCombinedAntet = "
  <div class=\"alert alert-info\" role=\"alert\" style=\"background: #ECEEEF; border:0;\">
    <h3 class=\"text-center\"><strong>Infrastructure Vulnerabilities</strong></h3> 
  </div>
  <br />
  <table class=\"table \">
    <thead >
      <tr>
        <th style=\"background: #DF0803;\">Critical</th>
        <th style=\"background: #FF4D00;\">High</th>
        <th style=\"background: #E99F54;\">Medium</th>
        <th style=\"background: #008800;\">Low</th>
        <th style=\"background: #337AB7;\">Info</th>
        <th class=\"table-active\">Total</th>


      </tr>
    </thead>
    <tbody>
      <tr>
        <td id=\"Critical\" style=\"background: #DF0803;  font-weight: bold; font-size: 160%;\">" . $risk_counters["Critical"] . "</td>
        <td id=\"High\" style=\"background: #FF4D00;  font-weight: bold; font-size: 160%;\">" . $risk_counters["High"] . "</td>
        <td id=\"Medium\" style=\"background: #E99F54; font-weight: bold; font-size: 160%;\">" . $risk_counters["Medium"] . "</td>
        <td id=\"Low\" style=\"background: #008800;  font-weight: bold; font-size: 160%;\">" . $risk_counters["Low"] . "</td>
        <td id=\"Informational\" style=\"background: #337AB7; font-weight: bold; font-size: 160%;\">" . $risk_counters["Information"] . "</td>
        <td id=\"Total\" class=\"table-active\" style=\"font-size: 160%; font-weight: bold;\" >" . $totalNumberOfIssues . "</td>


      </tr>

    </tbody>
  </table>
  ";
        //create a fragment (a html fragment) with changed parameters such as 
  $fragment          = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($HTMLCombinedAntet)) {

    $divElement->appendChild($fragment);

  } else {

  }
        //create a fragment (a html fragment) with changed parameters such as 
  $fragment = $htmlDocument->createDocumentFragment();
  if ($fragment->appendXML($HTMLCombinedContent)) {

    $divElement->appendChild($fragment);

  } else {

  }
}





    //display the risk counters
console_log($risk_counters);
}








return $html;
}

function sortItemsByRisk($vector)
{
  $result = array();

  $result["combined"] = array();
  $result["acunetix"] = array();

  if(count($vector["combined"]) > 0)
  {
   if (!isset($result["combined"]["High"])) {
    $result["combined"]["High"] = array();
  }

  if (!isset($result["combined"]["Medium"])) {
    $result["combined"]["Medium"] = array();
  }

  if (!isset($result["combined"]["Low"])) {
    $result["combined"]["Low"] = array();
  }

  if (!isset($result["combined"]["Information"])) {
    $result["combined"]["Information"] = array();
  }

  if (!isset($result["combined"]["Critical"])) {
    $result["combined"]["Critical"] = array();
  }

  foreach ($vector["combined"] as $key => $value) {

    if (isset($value["risk_factor"])) {
            //////echo $value["risk_factor"]."<br>";
      switch ($value["risk_factor"]) {
        case "Critical":
        array_push($result["combined"]["Critical"], $value);
        break;

        case "High":
        array_push($result["combined"]["High"], $value);
        break;

        case "Medium":
        array_push($result["combined"]["Medium"], $value);
        break;

        case "Low":
        array_push($result["combined"]["Low"], $value);
        break;

        case "Information":
        array_push($result["combined"]["Information"], $value);
        break;

        case "None":
        array_push($result["combined"]["Information"], $value);
        break;
      }
    }
  }

}



if (count($vector["acunetix"]) > 0) {
        //acunetix
  if (!isset($result["acunetix"]["High"])) {
    $result["acunetix"]["High"] = array();
  }

  if (!isset($result["acunetix"]["Medium"])) {
    $result["acunetix"]["Medium"] = array();
  }

  if (!isset($result["acunetix"]["Low"])) {
    $result["acunetix"]["Low"] = array();
  }

  if (!isset($result["acunetix"]["Information"])) {
    $result["acunetix"]["Information"] = array();
  }

  if (!isset($result["acunetix"]["Critical"])) {
    $result["acunetix"]["Critical"] = array();
  }

  foreach ($vector["acunetix"] as $key => $value) {

    if (isset($value["risk_factor"])) {
                ////echo $value["risk_factor"]."<br>";
      switch ($value["risk_factor"]) {

        case "high":
        array_push($result["acunetix"]["High"], $value);
        break;

        case "medium":
        array_push($result["acunetix"]["Medium"], $value);
        break;

        case "low":
        array_push($result["acunetix"]["Low"], $value);
        break;

        case "info":
        array_push($result["acunetix"]["Information"], $value);
        break;
      }
    }
  }
}



    //console_log($vector);

return $result;
}



function console_log($data)
{
    //echo '<script>';
    //echo 'console.log('. json_encode( $data ) .')';
    //echo '</script>';
}

function test_diff($item)
{
  foreach ($secondArray as $key => $value) {

    $pattern    = '/\s+/';
    $first_cve  = preg_replace($pattern, '', strtolower($item['cve']));
    $second_cve = preg_replace($pattern, '', strtolower($value['cve']));

    $first_desc  = preg_replace($pattern, '', strtolower($item['description']));
    $second_desc = preg_replace($pattern, '', strtolower($value['description']));


    if ((strcmp($first_desc, $second_desc) == 0 && strcmp($first_cve, $second_cve) == 0)) {
      return false;
    }
  }
}

function isInsertedAlready($vector, $val)
{


  foreach ($vector as $key => $item) {
        # code...
    $pattern    = '/\s+/';
    $first_cve  = preg_replace($pattern, '', strtolower($val['cve']));
    $second_cve = preg_replace($pattern, '', strtolower($item['cve']));

    $first_desc  = preg_replace($pattern, '', strtolower($val['description']));
    $second_desc = preg_replace($pattern, '', strtolower($item['description']));

    if (strcmp($first_desc, $second_desc) == 0 && strcmp($first_cve, $second_cve) == 0) {

            //echo "<br>";
            /*
            //echo $val['plugin_name']."<br>";
            //echo $first_desc."<br>";
            //echo $second_desc."<br>";
            
            //echo $first_cve."<br>";
            //echo $second_cve."<br>";*/
            //echo "<br>";
            return true;
          }
        }


        return false;
      }
      function reunion2($firstArray, $secondArray)
      {
        $result              = array();
        $unique_elements_map = array();

        if (count($firstArray) >= count($secondArray)) {
        //echo "count(firstArray) >= count(secondArray) <br>";
          foreach ($firstArray as $key => $value) {

            $pattern    = '/\s+/';
            $first_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $first_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $first_cve . "_" . $first_desc;
            if (!isset($unique_elements_map[$entry_key])) {
              $unique_elements_map[$entry_key] = $value;
            }
          }

          foreach ($secondArray as $key => $value) {

            $pattern     = '/\s+/';
            $second_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $second_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $second_cve . "_" . $second_desc;
            if (!isset($unique_elements_map[$entry_key])) {
              $unique_elements_map[$entry_key] = $value;
            }
          }
        } else {
        //echo "count(firstArray) < count(secondArray) <br>";
          foreach ($secondArray as $key => $value) {

            $pattern    = '/\s+/';
            $first_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $first_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $first_cve . "_" . $first_desc;
            
            if (!isset($unique_elements_map[$entry_key])) {

              $unique_elements_map[$entry_key] = $value;
            }
          }


          foreach ($firstArray as $key => $value) {

            $pattern     = '/\s+/';
            $second_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $second_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $second_cve + "_" + $second_desc;
            if (!isset($unique_elements_map[$entry_key])) {
              $unique_elements_map[$entry_key] = $value;
            }
          }
        }

    /*  console_log("unique_elements_map");
    console_log($unique_elements_map);
    console_log("unique_elements_map");*/
    
    //copying elements from unique_elements_map to result array
    foreach ($unique_elements_map as $key => $value) {

      array_push($result, $value);

    }
    
    
    return $result;
  }



  function reunion($firstArray, $secondArray)
  {
    $result = array();
    
    
    if (count($firstArray) >= count($secondArray)) {
      $result = $firstArray;
      foreach ($secondArray as $key => $value) {

        if (!isInsertedAlready($result, $value)) {
          array_push($result, $value);
        }
      }
    } else {
      $result = $secondArray;
      foreach ($firstArray as $key => $value) {

        if (!isInsertedAlready($result, $value)) {
          array_push($result, $value);
        }
      }
    }
    
    return $result;
  }

  function removeDuplicatesFromArray($vector)
  {
    $result = array();
    
    foreach ($vector as $key => $value) {

      if (!isInsertedAlready($result, $value)) {
        array_push($result, $value);
      }
    }
    
    return $result;
  }


  function removeDuplicates($global_array)
  {
    $result             = array();
    $result["combined"] = array();
    

    
    //remove duplicates for nessus and retina by making a reunion 
    //between two of them
    $unique_elements_map_combined = array();
    //$result["combined"] = reunion2($result["combined"], $global_array['nessus']); 
    foreach ($global_array as $key => $value) {
      if ($key !== "acunetix") {
        $result["combined"] = reunion2($result["combined"], $value);
      }
    }
    
    //remove duplicates for acunetix
    $result['acunetix'] = array();
    $duplicates         = array();
    
    if (isset($global_array['acunetix'])) {
      $duplicates = removeDuplicatesFromArray($global_array['acunetix']);
    }
    
    $result['acunetix'] = $duplicates;
    
    console_log("removeDuplicates");
    console_log($result);
    console_log("removeDuplicates");
    
    return $result;
  }



  function preprocessOutputString($output)
  {
    $result = str_replace('"', "'", $output);
    $result = htmlspecialchars($result);
    
    return $result;
  }


//TODO
//////echo the path to built html file


  function process_xml_input_file($file)
  {
    $uploadfile = $file;
    
    /*$uploaddir = $_POST['upload_path'];
    $filename = $_POST['filename'];
    $uploadfile = "../".$uploaddir."/".$filename;*/
    
    
    //load coresponding xml file
    $xmlDoc = simplexml_load_file($uploadfile);
    if (!$xmlDoc) {
      die('Error while reading XML file ' . $uploadfile);
    }
    
    //check type of uploaded XML, and calling its parser
    if (isset($xmlDoc->hosts->host->audit)) {
        //Call retina XML file parser if file Retina app output
      retinaXMLFileParser($uploadfile);
        //$global_array[]
    } else if (isset($xmlDoc->Report->ReportHost->ReportItem)) {
        //Call retina XML file parser if file Nessus app output
      nessusXMLFileParser($uploadfile);
        //array_push($global_array, "nessus");
    } else if (isset($xmlDoc->Scan->ReportItems->ReportItem)) {
        //Call retina XML file parser if file Acunetix app output
      acunetixXMLFileParser($uploadfile);
        //array_push($global_array, "acunetix");
    }
  }


  function nessusXMLFileParser($uploadfile)
  {


    global $global_array;
    
    //load coresponding xml file
    $xmlDoc = simplexml_load_file($uploadfile);
    if (!$xmlDoc) {
      die('Error while reading nessus XML file ' . $uploadfile);

    }
    //array where are stored items from xml file
    $data              = array();
    $parsed_data_array = array();
    
    //Cve form from description script
    $cveInitials = "CVE-";
    //Cve string length
    $cveLength   = 13;
    //iterating through nessus XML file structure
    foreach ($xmlDoc->Report->ReportHost->ReportItem as $reportItemElement) {
      $data[] = $reportItemElement;
    }
    
    //iterate through all the data loaded from xml file
    //and retain the necessary info
    foreach ($data as $x) {

        //creating a new array for each Report property
      $parsed_data_line_format = array();

        //setting Plugin Name
      $parsed_data_line_format["plugin_name"] = strip_tags((string) $x->plugin_name);
        //setting Risk Factor
      $parsed_data_line_format["risk_factor"] = strip_tags((string) $x->risk_factor);
        //setting Exploitability Ease
      $parsed_data_line_format["exploit"]     = strip_tags((string) $x->exploitability_ease);
        //setting Description
      $parsed_data_line_format["description"] = strip_tags((string) $x->description);
        //setting Description
      $parsed_data_line_format["solution"] = strip_tags((string) $x->solution);


      $lastPos   = 0;
      $positions = array();

        //finding all CVE entries in description string and pushing them into an array 
      while (($lastPos = strpos($x->description, $cveInitials, $lastPos)) !== false) {
        $positions[] = $lastPos;
        $lastPos     = $lastPos + strlen($cveInitials);
      }
      $finalCVE = "";
      foreach ($positions as $value) {
        $cveOutput           = "";
        $posOfCveStartOffset = $value;
        $posOfCveStopOffset  = 0;

        $cveOutput = substr($x->description, $posOfCveStartOffset);
        $cveOutput = substr($cveOutput, 0, $cveLength);
            //pushing cve into array
        $finalCVE  = $finalCVE . " " . $cveOutput;

      }
        //Setting Cve
      $parsed_data_line_format["cve"] = $finalCVE;
      if ($parsed_data_line_format["cve"] == "N/A" || $parsed_data_line_format["cve"] == "") {
        $parsed_data_line_format["cve"] = "None";
      }

        //inserting data about each Report into main array
      array_push($parsed_data_array, $parsed_data_line_format);
    }
    
    //passing data to Javascript  
    //////echo json_encode($parsed_data_array);
    //array_push($global_array,$parsed_data_array);
    
    $global_array["nessus"] = $parsed_data_array;
    
  }

  function retinaXMLFileParser($uploadfile)
  {
    global $global_array;
    
    //load coresponding xml file
    $xmlDoc = simplexml_load_file($uploadfile);
    if (!$xmlDoc) {
      die('Error while reading retina XML file ' . $uploadfile);
    }
    
    
    //array where are stored items from xml file(filename)
    $data              = array();
    $parsed_data_array = array();
    
    //iterating through retina XML file structure
    foreach ($xmlDoc->hosts->host->audit as $auditElement) {
      $data[] = $auditElement;
    }
    
    //iterate through all the data loaded from xml file
    //and retain the necessary info
    foreach ($data as $x) {

        //creating a new array for each Report property
      $parsed_data_line_format = array();

        //setting Plugin Name
      $parsed_data_line_format["plugin_name"] = strip_tags((string) $x->name);
        //setting Risk Factor
      $parsed_data_line_format["risk_factor"] = strip_tags((string) $x->risk);
        //setting CVE


      $parsed_data_line_format["cve"] = strip_tags((string) $x->cve);
      if ($parsed_data_line_format["cve"] == "N/A" || $parsed_data_line_format["cve"] == "") {
        $parsed_data_line_format["cve"] = "None";
      }
        //setting Exploit
      $parsed_data_line_format["exploit"]     = strip_tags((string) $x->exploit);
        //setting Description
      $parsed_data_line_format["description"] = strip_tags((string) $x->description);
        //setting Fix information
      $parsed_data_line_format["information"] = strip_tags((string) $x->fixInformation);


        // ////echo "Data: <br> Name: ".$parsed_data_line_format["plugin_name"]."<br>Risk: ".$parsed_data_line_format["risk_factor"]."<br>Cve: ".$parsed_data_line_format["cve"]."<br>Exploit: ".$parsed_data_line_format["exploit"]."<br>Description: ".$parsed_data_line_format["description"]."<br><br><br>"  ;

        //inserting data about each Report into main array
      array_push($parsed_data_array, $parsed_data_line_format);
    }
    //passing data to Javascript  
    
    //////echo json_encode($parsed_data_array);
    //array_push($global_array,$parsed_data_array);
    
    $global_array["retina"] = $parsed_data_array;
  }

  function acunetixXMLFileParser($uploadfile)
  {

    global $global_array;
    
    //load coresponding xml file
    $xmlDoc = simplexml_load_file($uploadfile);
    if (!$xmlDoc) {
      die('Error while reading acunetix XML file ' . $uploadfile);
    }
    
    
    //array where are stored items from xml file
    $data              = array();
    $parsed_data_array = array();
    
    //iterating through retina XML file structure
    foreach ($xmlDoc->Scan->ReportItems->ReportItem as $auditElement) {
      $data[] = $auditElement;
    }
    
    //iterate through all the data loaded from xml file
    //and retain the necessary info
    foreach ($data as $x) {

        //creating a new array for each Report property
      $parsed_data_line_format = array();

        //setting Plugin Name
      $parsed_data_line_format["plugin_name"] = strip_tags((string) $x->Name);
        //setting Risk Factor
      $parsed_data_line_format["risk_factor"] = strip_tags((string) $x->Severity);
        //setting CVE
      $parsed_data_line_format["cve"]         = strip_tags((string) $x->CWE);
        //setting Information
      $parsed_data_line_format["information"] = strip_tags((string) $x->Impact);
        //setting Description
      $parsed_data_line_format["description"] = strip_tags((string) $x->Description);

        // //////echo gettype($parsed_data_line_format["plugin_name"]);
        // ////echo "Data: <br> Name: ".$parsed_data_line_format["plugin_name"]."<br>Risk: ".$parsed_data_line_format["risk_factor"]."<br>Cve: ".$parsed_data_line_format["cve"]."<br>Information: ".$parsed_data_line_format["information"]."<br>Description: ".$parsed_data_line_format["description"]."<br><br><br>"  ;

        //inserting data about each Report into main array
      array_push($parsed_data_array, $parsed_data_line_format);
    }
    
    
    
    //////echo json_encode($parsed_data_array);
    //array_push($global_array,$parsed_data_array);
    $global_array["acunetix"] = $parsed_data_array;
  }




//echo json_encode("output_html/generated_output2.html");


  ?>