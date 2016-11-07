function startProcessing()
{

	console.log("Processing...");
	console.log(uploaded_files_map);
	console.log("Processing...");

	$.ajax({
		type: "POST",
		url: "./php/process_files_and_generate_output.php",
		data: {files_map:uploaded_files_map}
	})
	.done(function( msg ) {

		var objects= JSON.parse(msg);

		console.log(objects);
		//displayContent(msg);

		//displayContent(msg);		
	});
 

 }

 function displayContent(msg)
 {

 	//var dataInContainer="<pre>********************************************************************************************************";

    	var dataContainer=document.getElementById("parseResults");
    	var objects= JSON.parse(msg);


    	//iterating trough all objects
    	for (obj in objects)
    	{
    		for(object in obj)
    		{
    			dataInContainer+="<br>Report number : "+object+"----------------------------------------------------------------------------------------<br>";
    		//Cve value from object
    		var objCVE="";
    		//Description value from object
    		var objDescription="";
    		//Exploit value from object
    		var objExploit="";
    		//Name value from object
    		var objName="";
    		//Risk Factor value from object
    		var objRisk="";	
    		//Information value from object
			var objInfo="";

			//setting Cve for current report
    		objCVE=objects[object].cve;
    		if(objCVE=="")
    			objCVE="none";

			//setting Description for current report
    		objDescription+=objects[object].description;
    		if(objDescription=="")
    			objDescription="none";

			//setting Exploit for current report
    		objExploit+=objects[object].exploit;
    		if(objExploit=="")
    			objExploit="none";
    		
			//setting Name for current report
    		objName+=objects[object].plugin_name;
    		if(objName=="")
    			objName="none";

			//setting Risk Factor for current report
    		objRisk+=objects[object].risk_factor;
    		if(objRisk=="")
    			objRisk="none";


			//setting Information for current report
    		objInfo=objects[object].information;
    		if(objInfo=="")
    			objInfo="none";


    		//temporar display
    		dataInContainer+="Name : "+objName+"<br>";
    		dataInContainer+="Risk : "+objRisk+"<br>";
    		dataInContainer+="Description : "+objDescription+"<br>";
    		dataInContainer+="Cve : "+objCVE+"<br>";
    		dataInContainer+="Exploit : "+objExploit+"<br>";
    		dataInContainer+="Information : "+objInfo+"<br>";
    		}
    	
		}

       //appdending data to main report container {temporar}
		dataContainer.innerHTML=dataInContainer+"</pre><br><br><br>"+dataContainer.innerHTML;
		
 }