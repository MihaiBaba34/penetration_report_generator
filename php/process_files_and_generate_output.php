<?php
$inputFields    = $_POST['input'];
//get the list of files uploaded
$uploaded_files = $_POST['files_map'];

//data container of parsed xml files
$global_array        = array();
$issuesNumber        = 0;
//the php response used to transmit all the processed data
//and the url to the generated output html
$php_response        = array();
//preprocessed data container of xml files
$sorted_global_array = array();

$risk_priorities = array(
    'Critical',
    'High',
    'Medium',
    'Low',
    'Information'
);

//aserting every risk priority to a specific color
$badge_collors = array(
    'label label-info',
    'label label-warning',
    'label label-default',
    'label label-primary',
    'label label-success'
);

$risk_counters = array();

//global point of access to all processing functions
main();

function main()
{
    global $global_array;
    global $uploaded_files;
    global $risk_counters;
    global $php_response;
    global $sorted_global_array;
    
    //relative path to the input html template
    $html_input_path  = '../output_html/report_output_template.html';
    //relative path to the output html report
    $html_output_path = 'output_html/first_report_output.html';
    
    //append the relative path to the list of files 
    for ($i = 0; $i < count($uploaded_files); ++$i) {
        $uploaded_files[$i] = '../uploads/' . $uploaded_files[$i];
    }
    
    //extract xml files content
    foreach ($uploaded_files as $key => $value) {
        process_xml_input_file($value);
    }
    
    //remove duplicate elements
    $processed_array = removeDuplicates($global_array);
    
    //sort items by their risk
    $sorted_array = sortItemsByRisk($processed_array);
    
    //generate html report output and return its url
    $url_to_html_output = buildHTMLOutput($html_input_path, $html_output_path, $sorted_array);
    
    //build the response with the url to the generated html output 
    //and the global processed data
    $php_response["url_to_html_output"] = $url_to_html_output;
    $php_response["global_array"]       = $sorted_global_array;
    
    echo json_encode($php_response);
}

/**
 * global xml processor function
 *
 * @param  String $file input file name
 */
function process_xml_input_file($file)
{
    $uploadfile = $file;
    
    //load coresponding xml file
    $xmlDoc = simplexml_load_file($uploadfile);
    if (!$xmlDoc) {
        die('Error while reading XML file ' . $uploadfile);
    }
    //check type of uploaded XML, and calling its parser
    if (isset($xmlDoc->hosts->host->audit)) {
        //Call retina XML file parser if file Retina app output
        retinaXMLFileParser($uploadfile);
        
    } elseif (isset($xmlDoc->Report->ReportHost->ReportItem)) {
        //Call retina XML file parser if file Nessus app output
        nessusXMLFileParser($uploadfile);
        
    } elseif (isset($xmlDoc->Scan->ReportItems->ReportItem)) {
        //Call retina XML file parser if file Acunetix app output
        acunetixXMLFileParser($uploadfile);
    }
}


/**
 *
 * Sorts items from the given array by 
 * the risk of its components
 * 
 * @param  array $vector array to sort
 * @return array         output sorted array
 */
function sortItemsByRisk($vector)
{
    $result = array();
    
    //building sorted result for the given parameter
    //'combined' coresponds to the data from all xml files other than acunetix
    //'acunetix' coresponts to the data from acunetix xml files
    $result['combined'] = array();
    $result['acunetix'] = array();
    
    if (count($vector['combined']) > 0) {
        
        //initializing every risk type field 
        if (!isset($result['combined']['High'])) {
            $result['combined']['High'] = array();
        }
        
        if (!isset($result['combined']['Medium'])) {
            $result['combined']['Medium'] = array();
        }
        
        if (!isset($result['combined']['Low'])) {
            $result['combined']['Low'] = array();
        }
        
        if (!isset($result['combined']['Information'])) {
            $result['combined']['Information'] = array();
        }
        
        if (!isset($result['combined']['Critical'])) {
            $result['combined']['Critical'] = array();
        }
        
        //split array elements by their risk factor
        foreach ($vector['combined'] as $key => $value) {
            if (isset($value['risk_factor'])) {
                
                switch ($value['risk_factor']) {
                    case 'Critical':
                        array_push($result['combined']['Critical'], $value);
                        break;
                    
                    case 'High':
                        array_push($result['combined']['High'], $value);
                        break;
                    
                    case 'Medium':
                        array_push($result['combined']['Medium'], $value);
                        break;
                    
                    case 'Low':
                        array_push($result['combined']['Low'], $value);
                        break;
                    
                    case 'Information':
                        array_push($result['combined']['Information'], $value);
                        break;
                    
                    case 'None':
                        array_push($result['combined']['Information'], $value);
                        break;
                }
            }
        }
    }
    
    if (count($vector['acunetix']) > 0) {
        //acunetix
        if (!isset($result['acunetix']['High'])) {
            $result['acunetix']['High'] = array();
        }
        
        if (!isset($result['acunetix']['Medium'])) {
            $result['acunetix']['Medium'] = array();
        }
        
        if (!isset($result['acunetix']['Low'])) {
            $result['acunetix']['Low'] = array();
        }
        
        if (!isset($result['acunetix']['Information'])) {
            $result['acunetix']['Information'] = array();
        }
        
        if (!isset($result['acunetix']['Critical'])) {
            $result['acunetix']['Critical'] = array();
        }
        
        foreach ($vector['acunetix'] as $key => $value) {
            if (isset($value['risk_factor'])) {
                
                switch ($value['risk_factor']) {
                    
                    case 'high':
                        array_push($result['acunetix']['High'], $value);
                        break;
                    
                    case 'medium':
                        array_push($result['acunetix']['Medium'], $value);
                        break;
                    
                    case 'low':
                        array_push($result['acunetix']['Low'], $value);
                        break;
                    
                    case 'info':
                        array_push($result['acunetix']['Information'], $value);
                        break;
                }
            }
        }
    }
    
    return $result;
}

function buildHTMLOutput($html_input_path, $html_output_path, $input_array)
{
    
    $htmlDocument = new DOMDocument();
    
    //load the input file without displaying warnings
    libxml_use_internal_errors(true);
    $htmlDocument->loadHTMLFile($html_input_path);
    libxml_clear_errors();
    
    //insert completed fields from the upload page into the output html report
    insertInputFieldsIntoHTMLOutput($htmlDocument);
    
    //insert each report line into the html output
    insertReportLinesIntoHTMLOutput($htmlDocument, $input_array);
    
    //insert the total number of issues found in the report
    insertIssuesNumberIntoHTMLOutput($htmlDocument);
    
    $htmlDocument->saveHTMLFile('../' . $html_output_path);
    
    return $html_output_path;
}

/**
 *
 * Insert the total number of issues found in the report
 * 
 * @param  DOMDocument $htmlDocument handle to the 
 *                                   parsed html DOM structure
 */
function insertIssuesNumberIntoHTMLOutput($htmlDocument)
{
    global $issuesNumber;
    
    $issues_number_span = $htmlDocument->getElementById('issues_number');
    $fragment           = $htmlDocument->createDocumentFragment();
    if ($fragment->appendXML($issuesNumber)) {
        $issues_number_span->appendChild($fragment);
    }
}

/**
 *
 * Insert form input fields from upload page 
 * into the generated html output
 * 
 * @param  DOMDocument $htmlDocument handle to the 
 *                                   parsed html DOM structure
 */
function insertInputFieldsIntoHTMLOutput($htmlDocument)
{
    
    global $inputFields;
    $inputFields = json_decode($inputFields);
    
    //get specified fields where input data will be inserted
    $server_name_span = $htmlDocument->getElementById('server_name');
    $site_name_span   = $htmlDocument->getElementById('site_name');
    $date_span        = $htmlDocument->getElementById('date_field');
    
    //extract server name, web URL and date from upload page
    $server_name = $inputFields->serverNameInput;
    $site_name   = $inputFields->webURLInput;
    $date        = $inputFields->dateAndTime;
    
    //populate each html field with extracted data
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
}


/**
 * Build html output content with acunetix xml file and 
 * others xml files combined
 * 
 * @param  DOMDocument $htmlDocument handle to the 
 *                                   parsed html DOM structure
 * @param  array $global_array input data saved as 
 *                             an associative array
 */
function insertReportLinesIntoHTMLOutput($htmlDocument, $global_array)
{
    //insert acunetix file report lines
    insertAcunetix($htmlDocument, $global_array);
    
    //insert combined xml files report lines
    insertCombined($htmlDocument, $global_array);   
}


/**
 * Insert acunetix content into the html output
 * 
 * @param  DOMDocument $htmlDocument handle to the 
 *                                   parsed html DOM structure
 * @param  array $global_array input data saved as 
 *                             an associative array
 */
function insertAcunetix($htmlDocument, $global_array)
{
    
    global $risk_priorities, $risk_counters, $badge_collors, $sorted_global_array;
    
    $html                = '';
    $HTMLAcunetixContent = '';
    $reportNumber        = 0;
    
    //get the container div element in which will be inserted the acunetix html content
    $divElement    = $htmlDocument->getElementById('container');
    $nr_priorities = count($risk_priorities);
    
    if (count($global_array['acunetix']) > 0) {
        $risk_counters = array();
        $reportNumber  = 0;
        
        //iterate through all priorities in order from highest($i==0) to lowest($i==$nr_priorities)
        for ($i = 0; $i < $nr_priorities; ++$i) {
            //sorting by alphabet order in each priority type
            $reportPositionInVector = 0;
            $report_title           = array();
            foreach ($global_array['acunetix'][$risk_priorities[$i]] as $key => $row) {
                $report_title[$key] = $row['plugin_name'];
            }
            array_multisort($report_title, SORT_ASC | SORT_NATURAL | SORT_FLAG_CASE, $global_array['acunetix'][$risk_priorities[$i]]);
            
            //save the sorted global array data
            $sorted_global_array = $global_array;
            
            //iterate through global data array by risk priorities ordering
            foreach ($global_array['acunetix'][$risk_priorities[$i]] as $key_2 => $report) {
                
            	//count each existing risk from global data and store it to
            	//the risk_counters array
                if (!isset($risk_counters[$risk_priorities[$i]])) {
                    $risk_counters[$risk_priorities[$i]] = 1;
                } else {
                    ++$risk_counters[$risk_priorities[$i]];
                }
                
                //get and preprocess interesting fields from the report
                $fixInformation = '';
                if (isset($report['information'])) {
                    $fixInformation = $report['information'];
                    $fixInformation = preprocessOutputString($fixInformation);
                }
                                
                $plugin_name = '';
                if (isset($report['plugin_name'])) {
                    $plugin_name = $report['plugin_name'];
                    $plugin_name = preprocessOutputString($plugin_name);
                }
                
                $description = '';
                if (isset($report['description'])) {
                    $description = $report['description'];
                    $description = preprocessOutputString($description);
                }
                
                $risk_factor = '';
                if (isset($report['risk_factor'])) {
                    
                    $risk_factor = $risk_priorities[$i];
                    if ($risk_priorities[$i] === 'Information') {
                        $risk_factor = $risk_priorities[$i] . 'al';
                    }
                    $risk_factor = preprocessOutputString($risk_factor);
                }
                
                $cve_strings = '';
                if (isset($report['cve'])) {
                    $cve_strings = $report['cve'];
                    $cve_strings = preprocessOutputString($cve_strings);
                }
                
                $mandatory_item = '';
                if ($risk_factor == 'Critical' || $risk_factor == 'High' || $risk_factor == 'Medium') {
                    $mandatory_item = 'checked="checked"';
                }
                
                //compute position and report number in the global array to store it to the final report
                ++$reportNumber;
                ++$reportPositionInVector;
                $reportId = 'acunetix' . $reportNumber;
                
                /**
                 * generating acunetix report line
                 */
                include 'html_templates/acunetix_report_line_template.php';
                                
                $HTMLAcunetixContent = $HTMLAcunetixContent . $html;
            }
        }
        
        //reset risk counters before displaying them to html output
        if (!isset($risk_counters['Critical'])) {
            $risk_counters['Critical'] = 0;
        }
        
        if (!isset($risk_counters['High'])) {
            $risk_counters['High'] = 0;
        }
        
        if (!isset($risk_counters['Medium'])) {
            $risk_counters['Medium'] = 0;
        }
        
        if (!isset($risk_counters['Low'])) {
            $risk_counters['Low'] = 0;
        }
        
        if (!isset($risk_counters['Information'])) {
            $risk_counters['Information'] = 0;
        }

        //compute the total number of issues by adding each number of risks
        $totalNumberOfIssues = $risk_counters['Critical'] + $risk_counters['High'] + $risk_counters['Medium'] + $risk_counters['Low'] + $risk_counters['Information'];
        global $issuesNumber;
        $issuesNumber = $issuesNumber + $totalNumberOfIssues;
        
        /**        
         * generating acunetix report header
         * 
         */
        include 'html_templates/acunetix_report_header.php';
        
        //append the acunetix header content to the html output file
        $fragment = $htmlDocument->createDocumentFragment();
        if ($fragment->appendXML($HTMLAcunetixAntet)) {            
            $divElement->appendChild($fragment);
        }
        
        //append the acunetix report content to the html output file
        $fragment = $htmlDocument->createDocumentFragment();
        if ($fragment->appendXML($HTMLAcunetixContent)) {
            
            $divElement->appendChild($fragment);
        }
    }
}

/**
 * Insert combined xml files content into the html output
 * 
 * @param  DOMDocument $htmlDocument handle to the 
 *                                   parsed html DOM structure
 * @param  array $global_array input data saved as 
 *                             an associative array
 */
function insertCombined($htmlDocument, $global_array)
{
    global $risk_priorities, $risk_counters, $badge_collors, $sorted_global_array;

    //get the container div element in which will be inserted the acunetix html content
    $divElement = $htmlDocument->getElementById('container');
    
    $html                = '';
    $HTMLCombinedContent = '';
    $reportNumber        = 0;
    
    $nr_priorities = count($risk_priorities);
    if (count($global_array['combined'])) {
        $risk_counters = array();

        //iterate through all priorities in order from highest($i==0) to lowest($i==$nr_priorities)
        for ($i = 0; $i < $nr_priorities; ++$i) {
            //sorting by alphabet order in each priority type
            $reportPositionInVector = 0;
            $report_title           = array();
            foreach ($global_array['combined'][$risk_priorities[$i]] as $key => $row) {
                $report_title[$key] = $row['plugin_name'];
            }
            array_multisort($report_title, SORT_ASC | SORT_NATURAL | SORT_FLAG_CASE, $global_array['combined'][$risk_priorities[$i]]);
            
            //save the sorted global array data
            $sorted_global_array = $global_array;
            
            //iterate through global data array by risk priorities ordering
            foreach ($global_array['combined'][$risk_priorities[$i]] as $key_2 => $report) {
             	//count each existing risk from global data and store it to
            	//the risk_counters array
                if (!isset($risk_counters[$risk_priorities[$i]])) {
                    $risk_counters[$risk_priorities[$i]] = 1;
                } else {
                    ++$risk_counters[$risk_priorities[$i]];
                }
                
                //get and preprocess interesting fields from the report
                $fixInformation = '';
                if (isset($report['information'])) {
                    $fixInformation = $report['information'];
                    $fixInformation = preprocessOutputString($fixInformation);
                }
                
                
                $exploit = '';
                if (isset($report['exploit'])) {
                    $exploit = $report['exploit'];
                    
                    $exploit = preprocessOutputString($exploit);
                }
                if ($exploit == '') {
                    $exploit = '---';
                }
                
                if ($fixInformation == '') {
                    if (isset($report['solution'])) {
                        $fixInformation = $report['solution'];
                        $fixInformation = preprocessOutputString($fixInformation);
                    }
                }
                
                $plugin_name = '';
                if (isset($report['plugin_name'])) {
                    $plugin_name = $report['plugin_name'];
                    $plugin_name = preprocessOutputString($plugin_name);
                }
                
                $description = '';
                if (isset($report['description'])) {
                    $description = $report['description'];
                    $description = preprocessOutputString($description);
                }
                
                $risk_factor = '';
                if (isset($report['risk_factor'])) {
                    //$risk_factor = $report["risk_factor"];
                    $risk_factor = $risk_priorities[$i];
                    if ($risk_priorities[$i] === 'Information') {
                        $risk_factor = $risk_priorities[$i] . 'al';
                    }
                    $risk_factor = preprocessOutputString($risk_factor);
                }
                
                $cve_strings = '';
                if (isset($report['cve'])) {
                    $cve_strings = $report['cve'];
                    $cve_strings = preprocessOutputString($cve_strings);
                }

                //compute position and report number in the global array to store it to the final report
                ++$reportNumber;
                ++$reportPositionInVector;
                $mandatory_item = '';
                if ($risk_factor == 'Critical' || $risk_factor == 'High' || $risk_factor == 'Medium') {
                    $mandatory_item = 'checked="checked"';
                }                
                
                $reportId = 'combined' . $reportNumber;
                
                /**
                 * generating combined report line
                 * 
                 */
                include 'html_templates/combined_report_line_template.php';
                
                $HTMLCombinedContent = $HTMLCombinedContent . $html;
                
            }
        }
        
        //reset risk counters before displaying them to html output
        if (!isset($risk_counters['Critical'])) {
            $risk_counters['Critical'] = 0;
        }
        
        if (!isset($risk_counters['High'])) {
            $risk_counters['High'] = 0;
        }
        
        if (!isset($risk_counters['Medium'])) {
            $risk_counters['Medium'] = 0;
        }
        
        if (!isset($risk_counters['Low'])) {
            $risk_counters['Low'] = 0;
        }
        
        if (!isset($risk_counters['Information'])) {
            $risk_counters['Information'] = 0;
        }
        
        $totalNumberOfIssues = $risk_counters['Critical'] + $risk_counters['High'] + $risk_counters['Medium'] + $risk_counters['Low'] + $risk_counters['Information'];
        global $issuesNumber;
        $issuesNumber = $issuesNumber + $totalNumberOfIssues;
        
        if (count($global_array['combined']) > 0) 
        {
                        
            /**
             * generating combined report header
             * 
             */
            include 'html_templates/combined_report_header.php';
                        
            //append the combined xml files header content to the html output file
            $fragment = $htmlDocument->createDocumentFragment();
            if ($fragment->appendXML($HTMLCombinedAntet)) {
                $divElement->appendChild($fragment);
            }
            
            //append the combined xml files content to the html output file
            $fragment = $htmlDocument->createDocumentFragment();
            if ($fragment->appendXML($HTMLCombinedContent)) {
                $divElement->appendChild($fragment);
            }
        }        
    }
}


//function responsible to verify whether is the element '$val' 
//inserted in the array '$vector'
function isInsertedAlready($vector, $val)
{
    foreach ($vector as $key => $item) {
        $pattern    = '/\s+/';
        $first_cve  = preg_replace($pattern, '', strtolower($val['cve']));
        $second_cve = preg_replace($pattern, '', strtolower($item['cve']));
        
        $first_desc  = preg_replace($pattern, '', strtolower($val['description']));
        $second_desc = preg_replace($pattern, '', strtolower($item['description']));
        
        if (strcmp($first_desc, $second_desc) == 0 && strcmp($first_cve, $second_cve) == 0) {
            return true;
        }
    }
    
    return false;
}


//function responsible with generating the reunion of two arrays
//after their matching 'cve' and 'description' components
function reunion($firstArray, $secondArray)
{
    //the stored reunion result
    $result              = array();
    $unique_elements_map = array();
    
    if (count($firstArray) >= count($secondArray)) {
        
        //first array has more items than second array
        
        //adding all first array's elements into the final result, eliminating
        //progressively duplicates from it by making a unique key from the 
        //preprocessed cve and description fields
        foreach ($firstArray as $key => $value) {
            
            //building the unique key
            $pattern    = '/\s+/';
            $first_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $first_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $first_cve . '_' . $first_desc;
            
            //if built key does not exist, add coresponding value to the result
            //else do nothing (duplicate item)            
          if (!array_key_exists($entry_key,$unique_elements_map)) {
                $unique_elements_map[$entry_key] = $value;
            }
        }
        
        //add each element of second array to the result by the same technique described above
        foreach ($secondArray as $key => $value) {
            $pattern     = '/\s+/';
            $second_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $second_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $second_cve . '_' . $second_desc;
            
            //if current key already existed, it is not added to the result 
           if (!array_key_exists($entry_key,$unique_elements_map)) {
                $unique_elements_map[$entry_key] = $value;
            }
        }
    } else {
        
        //case when the second array has more elements than the first one
        foreach ($secondArray as $key => $value) {
            $pattern    = '/\s+/';
            $first_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $first_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $first_cve . '_' . $first_desc;
            
           if (!array_key_exists($entry_key,$unique_elements_map)) {
                $unique_elements_map[$entry_key] = $value;
            }
        }
        
        foreach ($firstArray as $key => $value) {
            $pattern     = '/\s+/';
            $second_cve  = preg_replace($pattern, '', strtolower($value['cve']));
            $second_desc = preg_replace($pattern, '', strtolower($value['description']));
            
            $entry_key = $second_cve . '_' . $second_desc;
       
            if (!array_key_exists($entry_key,$unique_elements_map)) {
                $unique_elements_map[$entry_key] = $value;
            }
        }	
    }
    
    //copying elements from unique_elements_map to result array
    foreach ($unique_elements_map as $key => $value) {
        array_push($result, $value);
    }
    
    return $result;
}

//function responsible with eliminating the duplicates from a single array
function removeDuplicatesFromArray($vector)
{
    $result = array();
    
    //iterates over all array elements and adds them into the result array
    //only if they were not already inserted
    foreach ($vector as $key => $value) {
        if (!isInsertedAlready($result, $value)) {
            array_push($result, $value);
        }
    }
    
    return $result;
}

function removeDuplicates($global_array)
{
    //the result map with eliminated duplicates
    $result             = array();
    //the coresponding result for all xml files combined without acunetix
    $result['combined'] = array();

    //remove duplicates for nessus and retina by making a reunion
    //between two of them    
    foreach ($global_array as $key => $value) {
        if ($key !== 'acunetix') {
            $result['combined'] = reunion($result['combined'], $value);

        }
    }
    
    //remove duplicates for acunetix
    $result['acunetix']          = array();
    $eliminated_duplicates_array = array();
    
    if (isset($global_array['acunetix'])) {
        $eliminated_duplicates_array = removeDuplicatesFromArray($global_array['acunetix']);
    }
    
    //append the accunetix data with removed duplicates to the resulted array
    $result['acunetix'] = $eliminated_duplicates_array;
    
    return $result;
}

function preprocessOutputString($output)
{
    $result = str_replace('"', "'", $output);
    $result = htmlspecialchars($result);
    
    return $result;
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
    $cveInitials = 'CVE-';
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
        $parsed_data_line_format['plugin_name'] = strip_tags((string) $x->plugin_name);
        //setting Risk Factor
        $parsed_data_line_format['risk_factor'] = strip_tags((string) $x->risk_factor);
        //setting Exploitability Ease
        $parsed_data_line_format['exploit']     = strip_tags((string) $x->exploitability_ease);
        //setting Description
        $parsed_data_line_format['description'] = strip_tags((string) $x->description);
        //setting Description
        $parsed_data_line_format['solution']    = strip_tags((string) $x->solution);
        
        $lastPos   = 0;
        $positions = array();
        
        //finding all CVE entries in description string and pushing them into an array
        while (($lastPos = strpos($x->description, $cveInitials, $lastPos)) !== false) {
            $positions[] = $lastPos;
            $lastPos     = $lastPos + strlen($cveInitials);
        }
        $finalCVE = '';
        foreach ($positions as $value) {
            $cveOutput           = '';
            $posOfCveStartOffset = $value;
            $posOfCveStopOffset  = 0;
            
            $cveOutput = substr($x->description, $posOfCveStartOffset);
            $cveOutput = substr($cveOutput, 0, $cveLength);
            //pushing cve into array
            $finalCVE  = $finalCVE . ' ' . $cveOutput;
        }
        //Setting Cve
        $parsed_data_line_format['cve'] = $finalCVE;
        if ($parsed_data_line_format['cve'] == 'N/A' || $parsed_data_line_format['cve'] == '') {
            $parsed_data_line_format['cve'] = 'None';
        }
        
        //inserting data about each Report into main array
        array_push($parsed_data_array, $parsed_data_line_format);
    }
    
    $global_array['nessus'] = $parsed_data_array;
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
        $parsed_data_line_format['plugin_name'] = strip_tags((string) $x->name);
        //setting Risk Factor
        $parsed_data_line_format['risk_factor'] = strip_tags((string) $x->risk);
        //setting CVE
        
        $parsed_data_line_format['cve'] = strip_tags((string) $x->cve);
        if ($parsed_data_line_format['cve'] == 'N/A' || $parsed_data_line_format['cve'] == '') {
            $parsed_data_line_format['cve'] = 'None';
        }
        //setting Exploit
        $parsed_data_line_format['exploit']     = strip_tags((string) $x->exploit);
        //setting Description
        $parsed_data_line_format['description'] = strip_tags((string) $x->description);
        //setting Fix information
        $parsed_data_line_format['information'] = strip_tags((string) $x->fixInformation);
        
        //inserting data about each Report into main array
        array_push($parsed_data_array, $parsed_data_line_format);
    }
    
    //passing data to Javascript
    $global_array['retina'] = $parsed_data_array;
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
        $parsed_data_line_format['plugin_name'] = strip_tags((string) $x->Name);
        //setting Risk Factor
        $parsed_data_line_format['risk_factor'] = strip_tags((string) $x->Severity);
        //setting CVE
        $parsed_data_line_format['cve']         = strip_tags((string) $x->CWE);
        //setting Information
        $parsed_data_line_format['information'] = strip_tags((string) $x->Impact);
        //setting Description
        $parsed_data_line_format['description'] = strip_tags((string) $x->Description);
        
        //inserting data about each Report into main array
        array_push($parsed_data_array, $parsed_data_line_format);
    }
    
    $global_array['acunetix'] = $parsed_data_array;
}
