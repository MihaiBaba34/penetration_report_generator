<?php



$uploaddir = 'uploads/';
$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
	
	$filename = $_FILES['userfile']['name'];

} else {
	echo "File is not valid!\n";
}







$time_pre = microtime(true);

//nessusXMLFileParser($uploadfile);
//retinaXMLFileParser($uploadfile);
acunetixXMLFileParser($uploadfile);
$time_post = microtime(true);

$exec_time = $time_post - $time_pre;
echo "<br>***********";
echo "Execution time :".$exec_time;
echo "<br>***********";

function nessusXMLFileParser($uploadfile)
{

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
		$parsed_data_line_format["plugin_name"] = $x->plugin_name;
			//setting Risk Factor
		$parsed_data_line_format["risk_factor"] = $x->risk_factor;
			//setting CVE
		$parsed_data_line_format["cve"] = $x->cve;
			//setting Exploit Available
		$parsed_data_line_format["exploit_available"] = $x->exploit_available;
			//setting Exploitability Ease
		$parsed_data_line_format["exploitability_ease"] = $x->exploitability_ease;
			//setting Description
		$parsed_data_line_format["description"] = $x->description;
		
		//creating a new array for cve entries from description string
		$parsed_cve_array_output_per_line = array();	

		
		$lastPos = 0;
		$positions = array();

		//finding all CVE entries in description string and pushing them into an array 
		while (($lastPos = strpos($x->description, $cveInitials, $lastPos))!== false) {
			$positions[] = $lastPos;
			$lastPos = $lastPos + strlen($cveInitials);
		}

		foreach ($positions as $value) 
		{   
			$cveOutput = ""; 
			$posOfCveStartOffset = $value;
			$posOfCveStopOffset = 0;

			$cveOutput = substr($x->description, $posOfCveStartOffset);
			$cveOutput = substr($cveOutput, 0, $cveLength);
			//pushing cve into array
			array_push($parsed_cve_array_output_per_line, $cveOutput);

		}
			//creating a cve array for each description
		$parsed_data_line_format["cve_array"] = array();
			//inserting values into cve array
		$parsed_data_line_format["cve_array"] = $parsed_cve_array_output_per_line;

			//inserting data about each Report into main array
		array_push($parsed_data_array,$parsed_data_line_format);
	}
print_r($parsed_data_array);
}

function retinaXMLFileParser($uploadfile)
{
	//load coresponding xml file
	$xmlDoc = simplexml_load_file($uploadfile);
	if(!$xmlDoc) {
		die('Error while reading retina XML file '.$uploadfile);
	}

	
	//array where are stored items from xml file
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
		$parsed_data_line_format["plugin_name"] = $x->name;
			//setting Risk Factor
		$parsed_data_line_format["risk_factor"] = $x->risk;
			//setting CVE
		$parsed_data_line_format["cve"] = $x->cve;
			//setting Exploit
		$parsed_data_line_format["exploit"] = $x->exploit;
			//setting Description
		$parsed_data_line_format["description"] = $x->description;
		
		// echo "Data: <br> Name: ".$parsed_data_line_format["plugin_name"]."<br>Risk: ".$parsed_data_line_format["risk_factor"]."<br>Cve: ".$parsed_data_line_format["cve"]."<br>Exploit: ".$parsed_data_line_format["exploit"]."<br>Description: ".$parsed_data_line_format["description"]."<br><br><br>"  ;

			//inserting data about each Report into main array
		array_push($parsed_data_array,$parsed_data_line_format);
	}
	
		

}

function acunetixXMLFileParser($uploadfile)
{
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
	
		

}








//OTHER ELEMENTS

/*		echo "<br>****************************************************<br>";
		echo "<pre>";
		echo "Port: ".$x["port"]."/".$x["protocol"]."/".$x["svc_name"];
	

		echo "<br>****************************************************<br>";
		echo "pluginFamily: 	".$x["pluginFamily"];
		echo "<br>****************************************************<br>";


		echo "Risk Factor: ".$x->risk_factor ;
		echo "<br>****************************************************<br>";
		echo "Solution: ".$x->solution ;


		echo "<br>****************************************************<br>";
		echo "Plugin Output: ".$x->plugin_output;
		echo "<br>****************************************************<br>";


		echo "<br>****************************************************<br>";
		echo "See Also: ".$x->see_also;
		echo "<br>****************************************************<br>";


		echo "Description: ".$x->description ;
		echo "<br>****************************************************<br>";
		echo "</pre>";

		echo "*******************************************";*/
		/*

		echo "Plugin Name: ".$x->plugin_name;
		echo "<br>"	;
		echo "Risk Factor: ".$x->risk_factor;
		echo "<br>"	;
		echo "Cve: ".$x->cve;
		echo "<br>"	;
		echo "Exploit: ".$x->exploit_available."<br>*******<br>".$x->exploitability_ease;
		echo "<br>"	;
		echo "Descriprion	: ".$x->description;*/


?>