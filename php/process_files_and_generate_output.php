<?php


//$uploaded_files = $_POST['files_map'];
$global_array = array();

$uploaded_files = array();
array_push($uploaded_files,"../uploads/testphp.vulnweb_retina.xml");
array_push($uploaded_files,"../test/testphp_vulnweb_nessus.xml");

//echo json_encode($uploaded_files);

main();


function main()
{

	global $global_array;
	global $uploaded_files;
	//extract xml files content
	foreach ($uploaded_files as $key => $value) {

	# code...
		process_xml_input_file($value);	
	}


	//remove duplicates
	removeDuplicates($global_array);


	//sort items


	//generate html report output
	//buildHTMLPageWithContent($global_array);
}

function console_log( $data ){
  echo '<script>';
  echo 'console.log('. json_encode( $data ) .')';
  echo '</script>';
}



function removeDuplicates($global_array)
{
	$result = array();

	//remove duplicates for nessus and retina 
	foreach ($global_array['nessus'] as $key_n => $nessus) {
		# code...
		foreach ($global_array['retina'] as $key_r => $retina) {
			# code...
		
			$pattern = '/\s+/';
			$first_cve = preg_replace($pattern,'',strtolower($nessus['cve']));
			$second_cve = preg_replace($pattern,'',strtolower($retina['cve']));

			$first_desc = preg_replace($pattern,'',strtolower($nessus['description']));
			$second_desc = preg_replace($pattern,'',strtolower($retina['description']));


			if(!(strcmp($first_desc , $second_desc) == 0 && strcmp($first_cve , $second_cve) == 0))
			{

				//array_push($result,);

/*				echo "<br>";
				echo "nessus -> ".$nessus['description']."<br>";
				echo "retina -> ".$retina['description']."<br>";

				echo "nessus -> ".$nessus['cve']."<br>";
				echo "retina -> ".$retina['cve']."<br>";
				echo "<br>";*/
			}
		}

	}



	//console_log($global_array);

	//remove duplicates for acunetix
}


function buildHTMLPageWithContent($global_array)
{

	$htmlDocument = new DOMDocument();

	$html_input_path = "../output_html/generated_output.html";
	$html_output_path = "output_html/generated_output2.html";

    //load the index.php file without displaying warnings 
	libxml_use_internal_errors(true);
	$htmlDocument->loadHTMLFile($html_input_path);
	libxml_clear_errors();    

	$divElement = $htmlDocument->getElementById('container');

	$htmlContent = buildHTMLString($htmlDocument, $divElement, $global_array);

    //echo $htmlContent;

    //$divElement->documentElement->appendChild()

    //$htmlDocument->saveHTMLFile("../" + $html_output_path); 
	$htmlDocument->saveHTMLFile("../".$html_output_path);

    //echo json_encode($html_output_path);
    //echo json_encode($_SERVER['HTTP_HOST']);
}




function preprocessOutputString($output)
{
	$result = str_replace('"',"'",$output);
	$result = htmlspecialchars($result);

	return $result;
}


function buildHTMLString($htmlDocument, $divElement, $global_array)
{

	$html = "";
	foreach ($global_array as $key => $reports) 
	{

		switch($key)
		{
			case "nessus":


			
			break;

			case "retina":
			
				//generateRetinaReport($reports);

			break;

			case "acunetix":
			
			break;

		}
		foreach ($reports as $key_2 => $report) 
		{


			$fixInformation = "";
			if(isset($report["information"]))
			{
				$fixInformation = $report["information"];				
				$fixInformation = preprocessOutputString($fixInformation);
			}


			$plugin_name = "";
			if(isset($report["plugin_name"]))
			{
				//$plugin_name = 	"PHP &lt; 5.5.12 / 5.4.28 privilege escalation";
				$plugin_name = $report["plugin_name"];
				$plugin_name = preprocessOutputString($plugin_name);
			}

			$description = "";
			if(isset($report["description"]))
			{
				$description = $report["description"];
				$description = preprocessOutputString($description);					
			}

			$risk_factor = "";
			if(isset($report["risk_factor"]))
			{
				$risk_factor = $report["risk_factor"];
				$risk_factor = preprocessOutputString($risk_factor);
			}

			$cve_strings = "";
			if(isset($report["cve"]))
			{
				$cve_strings = $report["cve"];
				$cve_strings = preprocessOutputString($cve_strings);
			}


			$html = "
			<div class='nodedata'>
				<div class='panel-group'>
					<div class='panel panel-default'>
						<div class='panel-heading'>
							<h4 class='panel-title'>
								<span>
									<input id='supplied' class='checkrecord' type='checkbox' value='Record:1' name='Record:1' /> 
									<span style='margin-left: 30px;'>".$plugin_name."
									</span>
								</span>
								<span class='pull-right'>
									<span class='label label-danger'>".$risk_factor."
									</span>
									<a data-toggle='collapse' href='#collapse1' class='' aria-expanded='true'>Expand </a>
								</span>       
							</h4>
						</div>
						<input type='checkbox' style='display:none;' name='result[0][name]' value=\"".$plugin_name."\" /> 
						<input type='checkbox' style='display:none;' name='result[0][risk]' value=\"".$report["risk_factor"]."\" />
						<div id='collapse1' class='panel-collapse collapse ' aria-expanded='false'>
							<ul class='list-group'>
								<li class='list-group-item'>
									<input type='checkbox' style='display:none;' name='result[0][description]' value=\"".$description."\" /><strong>Description: </strong><br /> ".$description." </li>
									<li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value=\"".$fixInformation."\" /><strong>Fix 
										Information:  </strong>".$fixInformation."<br />  </li>
										<li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value=\"".$cve_strings."\" /><strong>CVE:</strong><br />".$cve_strings."
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>";

				//echo $html;

        //create a fragment (a html fragment) with changed parameters such as 
					$fragment = $htmlDocument->createDocumentFragment();
					if($fragment -> appendXML($html))
					{
						$divElement->insertBefore($fragment, $divElement->firstChild);
					}
					else
					{
						echo $html;		

						echo $plugin_name."<br>";		

						/*echo $fixInformation."<br>".$plugin_name."<br>".$description."<br>".$risk_factor."<br>".$cve_strings."<br><br>";*/
					}

    //$divElement->insertBefore($fragment);  


				}



			}       

    /*$html = "<div class='panel panel-default'>
    <div class='panel-heading'>
        <h4 class='panel-title'>
        <span>
                <input id='supplied' class='checkrecord' type='checkbox' value='Record:1' name='Record:1' /> 
                <span style='margin-left: 30px;'>OpenSSL DROWN Vulnerability Detected
                </span>
        </span>
        <span class='pull-right'>
            <span class='label label-danger'>High
            </span>
            <a data-toggle='collapse' href='#collapse1' class='' aria-expanded='true'>Expand </a>
        </span>       
        </h4>
        </div>
        <input type='checkbox' style='display:none;' name='result[0][name]' value='OpenSSL DROWN Vulnerability Detected' /> 
        <input type='checkbox' style='display:none;' name='result[0][risk]' value='High' />
        <div id='collapse1' class='panel-collapse collapse in' aria-expanded='true'>
            <ul class='list-group'>
                <li class='list-group-item'>
                <input type='checkbox' style='display:none;' name='result[0][description]' value='Retina has detected that the targeted service accepts connections for SSLv2 which can allow remote attackers to perform a Decrypting RSA with Obsolete and Weakened eNcryption (DROWN) attack.' /><strong>Description: </strong><br /> Retina has detected that the targeted service accepts connections for SSLv2 which can allow remote attackers to perform a 'Decrypting RSA with Obsolete and Weakened eNcryption' (DROWN) attack. </li>
                <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][fixInformation]' value='Disable SSLv2 support on the affected target.' /><strong>Fix 
                    Information:php </strong><br /> Disable SSLv2 support on the affected target. </li>
                <li class='list-group-item'><input type='checkbox' style='display:none;' name='result[0][cve]' value='CVE-2016-0703,CVE-2016-0800,CVE-2016-0798,CVE-2016-0797,CVE-2016-0704,CVE-2016-0705,CVE-2016-0702,CVE-2016-0799' /><strong>CVE:</strong><br /> CVE-2016-0703,CVE-2016-0800,CVE-2016-0798,CVE-2016-0797,CVE-2016-0704,CVE-2016-0705,CVE-2016-0702,CVE-2016-0799
                </li>
            </ul>
        </div>
    </div>";*/

    
    return $html;

}



//TODO
//echo the path to built html file
//echo json_encode($global_array);	

function process_xml_input_file($file)
{

	
	$uploadfile = "../uploads/".$file;

/*$uploaddir = $_POST['upload_path'];
$filename = $_POST['filename'];
$uploadfile = "../".$uploaddir."/".$filename;*/


    //load coresponding xml file
$xmlDoc = simplexml_load_file($uploadfile);
if(!$xmlDoc) {
	die('Error while reading XML file '.$uploadfile);   
}

    //check type of uploaded XML, and calling its parser
if(isset($xmlDoc->hosts->host->audit))
{
        //Call retina XML file parser if file Retina app output
	retinaXMLFileParser($uploadfile);
        //$global_array[]
}
else
	if(isset($xmlDoc->Report->ReportHost->ReportItem))
	{
          //Call retina XML file parser if file Nessus app output
		nessusXMLFileParser($uploadfile);
        //array_push($global_array, "nessus");
	}
	else
		if(isset($xmlDoc->Scan->ReportItems->ReportItem))
		{
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
		if(!$xmlDoc) {
			die('Error while reading nessus XML file '.$uploadfile);

		}
    //array where are stored items from xml file
		$data=array();   
		$parsed_data_array = array();

    //Cve form from description script
		$cveInitials="CVE-";
    //Cve string length
		$cveLength=13;
    //iterating through nessus XML file structure
		foreach($xmlDoc->Report->ReportHost->ReportItem as $reportItemElement) {
			$data[]=$reportItemElement;
		}   

    //iterate through all the data loaded from xml file
    //and retain the necessary info
		foreach ($data as $x ) {

    //creating a new array for each Report property
			$parsed_data_line_format = array();

            //setting Plugin Name
			$parsed_data_line_format["plugin_name"] = strip_tags((string)$x->plugin_name);
            //setting Risk Factor
			$parsed_data_line_format["risk_factor"] = strip_tags((string)$x->risk_factor);
            //setting Exploitability Ease
			$parsed_data_line_format["exploit"] = strip_tags((string)$x->exploitability_ease);
            //setting Description
			$parsed_data_line_format["description"] = strip_tags((string)$x->description);

			$lastPos = 0;
			$positions = array();

        //finding all CVE entries in description string and pushing them into an array 
			while (($lastPos = strpos($x->description, $cveInitials, $lastPos))!== false) {
				$positions[] = $lastPos;
				$lastPos = $lastPos + strlen($cveInitials);
			}
			$finalCVE="";
			foreach ($positions as $value) 
			{   
				$cveOutput = ""; 
				$posOfCveStartOffset = $value;
				$posOfCveStopOffset = 0;

				$cveOutput = substr($x->description, $posOfCveStartOffset);
				$cveOutput = substr($cveOutput, 0, $cveLength);
            //pushing cve into array
				$finalCVE=$finalCVE." ".$cveOutput;

			}
            //Setting Cve
			$parsed_data_line_format["cve"] =  $finalCVE;
			if($parsed_data_line_format["cve"] == "N/A" || $parsed_data_line_format["cve"] == "")
			{
				$parsed_data_line_format["cve"] = "None";
			}

            //inserting data about each Report into main array
			array_push($parsed_data_array,$parsed_data_line_format);
		}

    //passing data to Javascript  
    //echo json_encode($parsed_data_array);
		//array_push($global_array,$parsed_data_array);

		$global_array["nessus"] = $parsed_data_array;

	}

	function retinaXMLFileParser($uploadfile)
	{
		global $global_array;

    //load coresponding xml file
		$xmlDoc = simplexml_load_file($uploadfile);
		if(!$xmlDoc) {
			die('Error while reading retina XML file '.$uploadfile);
		}


    //array where are stored items from xml file(filename)
		$data=array();   
		$parsed_data_array = array();

    //iterating through retina XML file structure
		foreach($xmlDoc->hosts->host->audit as $auditElement) {
			$data[]=$auditElement;
		}   

    //iterate through all the data loaded from xml file
    //and retain the necessary info
		foreach ($data as $x ) {

    //creating a new array for each Report property
			$parsed_data_line_format = array();

            //setting Plugin Name
			$parsed_data_line_format["plugin_name"] = strip_tags((string)$x->name);
            //setting Risk Factor
			$parsed_data_line_format["risk_factor"] = strip_tags((string)$x->risk);
            //setting CVE


			$parsed_data_line_format["cve"] = strip_tags((string)$x->cve);
			if($parsed_data_line_format["cve"] == "N/A" || $parsed_data_line_format["cve"] == "")
			{
				$parsed_data_line_format["cve"] = "None";
			}
            //setting Exploit
			$parsed_data_line_format["exploit"] = strip_tags((string)$x->exploit);
            //setting Description
			$parsed_data_line_format["description"] = strip_tags((string)$x->description);
			//setting Fix information
			$parsed_data_line_format["information"] = strip_tags((string)$x->fixInformation);
			

        // echo "Data: <br> Name: ".$parsed_data_line_format["plugin_name"]."<br>Risk: ".$parsed_data_line_format["risk_factor"]."<br>Cve: ".$parsed_data_line_format["cve"]."<br>Exploit: ".$parsed_data_line_format["exploit"]."<br>Description: ".$parsed_data_line_format["description"]."<br><br><br>"  ;

            //inserting data about each Report into main array
			array_push($parsed_data_array,$parsed_data_line_format);
		}
    //passing data to Javascript  

    //echo json_encode($parsed_data_array);
		//array_push($global_array,$parsed_data_array);

		$global_array["retina"] = $parsed_data_array;
	}

	function acunetixXMLFileParser($uploadfile)
	{

		global $global_array;

    //load coresponding xml file
		$xmlDoc = simplexml_load_file($uploadfile);
		if(!$xmlDoc) {
			die('Error while reading acunetix XML file '.$uploadfile);
		}


    //array where are stored items from xml file
		$data=array();   
		$parsed_data_array = array();

    //iterating through retina XML file structure
		foreach($xmlDoc->Scan->ReportItems->ReportItem as $auditElement) {
			$data[]=$auditElement;
		}   

    //iterate through all the data loaded from xml file
    //and retain the necessary info
		foreach ($data as $x ) {

    //creating a new array for each Report property
			$parsed_data_line_format = array();

            //setting Plugin Name
			$parsed_data_line_format["plugin_name"] = strip_tags((string)$x->Name);
            //setting Risk Factor
			$parsed_data_line_format["risk_factor"] = strip_tags((string)$x->Severity);
            //setting CVE
			$parsed_data_line_format["cve"] = strip_tags((string)$x->CWE);
            //setting Information
			$parsed_data_line_format["information"] = strip_tags((string)$x->Impact);
            //setting Description
			$parsed_data_line_format["description"] = strip_tags((string)$x->Description);

        // //echo gettype($parsed_data_line_format["plugin_name"]);
        // echo "Data: <br> Name: ".$parsed_data_line_format["plugin_name"]."<br>Risk: ".$parsed_data_line_format["risk_factor"]."<br>Cve: ".$parsed_data_line_format["cve"]."<br>Information: ".$parsed_data_line_format["information"]."<br>Description: ".$parsed_data_line_format["description"]."<br><br><br>"  ;

            //inserting data about each Report into main array
			array_push($parsed_data_array,$parsed_data_line_format);
		}



    //echo json_encode($parsed_data_array);
		//array_push($global_array,$parsed_data_array);
		$global_array["acunetix"] = $parsed_data_array;
	}







	?>