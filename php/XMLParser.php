<?php

$uploaddir = $_POST['upload_path'];
$filename = $_POST['filename'];
$uploadfile = "../".$uploaddir."/".$filename;


    //load coresponding xml file
    $xmlDoc = simplexml_load_file($uploadfile);
    if(!$xmlDoc) {
        //die('Error while reading XML file '.$uploadfile);  
        die('Error while reading XML file '.$uploadfile);  
    }

    //check type of uploaded XML, and calling its parser
    if(isset($xmlDoc->hosts->host->audit))
    {
        //Call retina XML file parser if file Retina app output
        retinaXMLFileParser($uploadfile);
    }
    else
        if(isset($xmlDoc->Report->ReportHost->ReportItem))
        {
          //Call retina XML file parser if file Nessus app output
            nessusXMLFileParser($uploadfile);
        }
        else
            if(isset($xmlDoc->Scan->ReportItems->ReportItem))
            {
                 //Call retina XML file parser if file Acunetix app output
                acunetixXMLFileParser($uploadfile);
            }
            else
            {
                //not a valid xml
                $parsed_data_array = array();
                $parsed_data_array["report_type"] = "Not a valid xml file!";
                echo json_encode($parsed_data_array);
            }

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

    $parsed_data_array["report_type"] = "Nessus";
    
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

            //inserting data about each Report into main array
        array_push($parsed_data_array,$parsed_data_line_format);
    }

    //passing data to Javascript  
    echo json_encode($parsed_data_array);

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

    $parsed_data_array["report_type"] = "Retina";
    
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
            //setting Exploit
        $parsed_data_line_format["exploit"] = strip_tags((string)$x->exploit);
            //setting Description
        $parsed_data_line_format["description"] = strip_tags((string)$x->description);
        
        // echo "Data: <br> Name: ".$parsed_data_line_format["plugin_name"]."<br>Risk: ".$parsed_data_line_format["risk_factor"]."<br>Cve: ".$parsed_data_line_format["cve"]."<br>Exploit: ".$parsed_data_line_format["exploit"]."<br>Description: ".$parsed_data_line_format["description"]."<br><br><br>"  ;

            //inserting data about each Report into main array
        array_push($parsed_data_array,$parsed_data_line_format);
    }
    //passing data to Javascript  
    echo json_encode($parsed_data_array);
        
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

    $parsed_data_array["report_type"] = "Acunetix";
    
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
    
        
    echo json_encode($parsed_data_array);
}

?>