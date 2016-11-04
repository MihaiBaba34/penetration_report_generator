<?php 

echo "xml_uploader response";

$target_dir = "uploads/";
$uploadfile = $target_dir . basename($_FILES["userfile"]["name"]);
$uploadOk = 0;
$fileType = pathinfo($uploadfile,PATHINFO_EXTENSION);


if (move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile))
{	 
	echo "File is uploaded successfully.\n";
	$filename = $_FILES['userfile']['name'];
	$uploadOk = 1;
} 
else 
{
	echo "File is not valid!\n";
	$uploadOk = 0;
}




if($uploadOk == 1)
{
	echo "Success!";

	$xmlDoc = simplexml_load_file($uploadfile);
	if(!$xmlDoc) {		
		die('Upload XML file.');
	}


}






?>