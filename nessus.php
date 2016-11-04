<?php
	// print_r($_POST);
	$uploaddir = 'uploads/';
	$uploadfile = $uploaddir . basename($_FILES['userfile']['name']);
	if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile)) {
		// echo $_FILES['userfile']['name'];
		// echo "File is uploaded.\n";
		$filename = $_FILES['userfile']['name'];
	} else {
		echo "File is not valid!\n";
	}

	$xmlDoc = simplexml_load_file($uploadfile);
	if(!$xmlDoc) {
		die('Upload XML file.');
	}

	$data=array();
	 //print_r($xmlDoc);
	foreach($xmlDoc->Report->ReportHost->ReportItem as $au) {
		$data[]=$au;
		//echo "<br>";
		// print_r($au) ;
		//echo "*******************************************";

	}	


	foreach ($data as $x ) {


		echo "<br>****************************************************<br>";
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

		// echo "*******************************************";


		# code...
	}

	
?>