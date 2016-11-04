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
	// print_r($xmlDoc);
	foreach($xmlDoc->hosts->host->audit as $au) {
		$data[]=$au;
	}

	if (count($data)<=0) {
		echo "Please enter only retina file.";
		die;
	}

	foreach ($data as $c) {
		switch ($c->risk) {
			case 'Information':
				$c->riskNum = 4;
				break;
			case 'Low':
				$c->riskNum = 3;
				break;
			case 'Medium':
				$c->riskNum = 2;
				break;
			case 'High':
				$c->riskNum = 1;
				break;
			default:
				$c->riskNum = 'x';
		}
	}

	// echo "<pre>";
	// print_r($data);
	// echo "</pre>";

	usort($data, function ($first, $second) {
		if (intval($first->riskNum) === intval($second->riskNum)) {
			return (strval($first->name) > strval($second->name)) ? 1 : -1;
		}

		// echo $first->riskNum . " First > Second " . $second->riskNum . "<br>";

		// echo gettype(intval($first->riskNum)) . "<br>";
		// var_dump($first->riskNum);
		// if (intval($first->riskNum) > intval($second->riskNum)) {
		// 	echo "1>2<br>";
		// 	return 1;
		// } else {
		// 	echo "2>1<br>";
		// 	return -1;
		// }
		return (intval($first->riskNum) > intval($second->riskNum)) ? 1 : -1;
	});
	// asort($data->riskNum);

	// echo "<pre>";
	// print_r($data);
	// echo "</pre>";

	for ($i=0; $i < count($data) ; $i++) {
		$checkcount=$i+1;
		$risk = "";
		switch ($data[$i]->risk ) {
			case 'Information':
				$risk = '<span class="label label-info">' . $data[$i]->risk . '</span>';
				break;
			case 'Low':
				$risk = '<span class="label label-success">' . $data[$i]->risk . '</span>';
				break;
			case 'Medium':
				$risk = '<span class="label label-warning">' . $data[$i]->risk . '</span>';
				break;
			case 'High':
				$risk = '<span class="label label-danger">' . $data[$i]->risk . '</span>';
				break;
			default:

		}
		$cve = (strval($data[$i]->cve) != "N/A") ? '<li class="list-group-item"><input type="checkbox" style="display:none;" name="result['.$i.'][cve]" value="'.$data[$i]->cve.'"><strong>CVE:</strong> <br>'.$data[$i]->cve.'</li>' : '';


		?>
		<div class="nodedata">
			<div class="panel-group">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 class="panel-title">
							<span>
								<input id="supplied" class="checkrecord" type="checkbox" value="Record:<?php echo $checkcount ?>" name="Record:<?php echo $checkcount ?>">
								<span style="margin-left: 30px;"><?php echo $data[$i]->name ?></span>
							</span>
							<span class="pull-right">
								<?php echo $risk ?>
								<a data-toggle="collapse" href="#collapse<?php echo $checkcount ?>">Expand</a>
							</span>
						</h4>
					</div>
					<input type="checkbox" style="display:none;" name="result[<?php echo $i ?>][name]" value="<?php echo $data[$i]->name ?>">
					<input type="checkbox" style="display:none;" name="result[<?php echo $i ?>][risk]" value="<?php echo $data[$i]->risk ?>">
					<div id="collapse<?php echo $checkcount ?>" class="panel-collapse collapse">
						<ul class="list-group">
							<li class="list-group-item">
								<input type="checkbox" style="display:none;" name="result[<?php echo $i ?>][description]" value="<?php echo $data[$i]->description ?>"><strong>Description:</strong><br><?php echo $data[$i]->description ?>
							</li>
							<li class="list-group-item">
								<input type="checkbox" style="display:none;" name="result[<?php echo $i ?>][fixInformation]" value="<?php echo $data[$i]->fixInformation ?>"><strong>Fix Information:</strong><br><?php echo $data[$i]->fixInformation ?>
							</li>
							<?php echo $cve ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<?php

		// echo '<span><input id="supplied" class="checkrecord" type="checkbox" value="Record:'.$checkcount.'" name="Record:'.$checkcount.'"> - ' . $data[$i]->name . ' - ' . $data[$i]->risk . '</span>';
		// $html='<div class="nodedata">';

		// $html=$html . "Description: " . $data[$i]->description . "<br>";
		// $html=$html . "Fix Information: " . $data[$i]->fixInformation . "<br>";

		// $html=$html."<input type='checkbox' style='display:none;' name='result[".$i."][description]' value='".$data[$i]->description."'>description: ".$data[$i]->description."<br>";
		// $html=$html."<input type='checkbox' style='display:none;' name='result[".$i."][fixInformation]' value='".$data[$i]->fixInformation."'>fixInformation: ".$data[$i]->fixInformation."<br>";

		// foreach ($data[$i] as $key=>$au) {
		// 	//echo $key;
		// 	$html=$html."<input type='checkbox' style='display:none;' name='result[".$i."][".$key."]' value='$au' >".$key.": ".$au."<br>";
		// }
		// $html=$html.'</div><br>';
		// echo $html;
	}
?>