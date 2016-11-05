<?php 


class Foo { 
    public $aMemberVar = 'aMemberVar Member Variable'; 
    private $aFuncName = 'aMemberFunc'; 
        
    function aMemberFunc() { 
        print 'aFuncName'; 
    } 
} 

$foo = new Foo; 
	
//echo $foo->aMemberVar;	
echo $foo->aMemberFunc();





/*


echo "xml_uploader response:\n";
ini_set('upload-max-filesize', '10M');
ini_set('post_max_size', '10M');
//echo ini_get("post_max_size");
$max_upload = (int)(ini_get('upload_max_filesize'));
$max_post = (int)(ini_get('post_max_size'));
$memory_limit = (int)(ini_get('memory_limit'));
$upload_mb = min($max_upload, $max_post, $memory_limit);
echo "min". $upload_mb;

$target_dir = "uploads/";
$uploadfile = $target_dir . basename($_FILES["userfile"]["name"]);
$uploadOk = 0;
$fileType = pathinfo($uploadfile,PATHINFO_EXTENSION);


$result = move_uploaded_file($_FILES['userfile']['tmp_name'], $uploadfile);

echo $result."\n";

if ($result)
{	 
	echo "File is uploaded successfully.\n";
	$filename = $_FILES['userfile']['name'];
	$uploadOk = 1;
} 
else 
{
	 switch ($_FILES['userfile'] ['error']) 
    {      case 1: 
            echo '<p> The file is bigger than this PHP installation allows</p>'; 
            break; 
        case 2: 
            echo '<p> 
             </p>';         
            break; 
        case 3: 
            echo '<p> Only part of the file was uploaded</p>'; 
            break; 
        case 4: 
            echo '<p> No file was uploaded</p>'; 
            break; 
        case 0: 
            echo '<p> file was uploaded successfully</p>'; 
            break; 
    } 
	//echo "File is not valid!\n";
	$uploadOk = 0;
}







if($uploadOk == 1)
{
	echo "Success!\n";

	$xmlDoc = simplexml_load_file($uploadfile);
	if(!$xmlDoc) {		
		die('Upload XML file.');
	}

	if(isset($xmlDoc->hosts->host->audit))
	{
		echo "retina file!\n";
	}
	else
		if(isset($xmlDoc->Report->ReportHost->ReportItem))
		{
			echo "Nessus file!\n";
		}
		else
			if(isset($xmlDoc->Scan->ReportItems->ReportItem))
			{
				echo "Acuntix file!\n";
			}
}


*/



?>