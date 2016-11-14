function startProcessing(inputArgument)
{

    var input_fields= JSON.stringify(inputArgument);
    localStorage.setItem("input_fields",input_fields);

	$.ajax({
		type: "POST",
		url: "./php/process_files_and_generate_output.php",
		data: {

            files_map:uploaded_files_map,
            input: input_fields
        }
	})
	.done(function( msg ) {
				


		var received_data = JSON.parse(msg);
        var url = received_data.url_to_html_output;

        //save the global array received from php in order
        //to access it globally
        var storage_array = JSON.stringify(received_data.global_array);
        localStorage.setItem("global_array",storage_array);

		window.location.href = url;

	});
 }

function populatePageWithParsedData(parsedData)
{
	var accordion = document.getElementById("accordion");
	var html = "";

	for(report in parsedData)
	{
		html += 
	"<div class='card'>" +  
    "<div class='card-header' role='tab' id='headingOne'>" + 
      "<h5 class='mb-0'>" + 
        "<a data-toggle='collapse' data-parent='#accordion' href='#collapseOne' aria-expanded='true' aria-controls='collapseOne'>" + 
          "Collapsible Group Item #1" + 
        "</a>" + 
      "</h5>" + 
    "</div>" + 
    "<div id='collapseOne' class='collapse in' role='tabpanel' aria-labelledby='headingOne'>" + 
      "<div class='card-block'>" + 
        "sustainable VHS." + 
      "</div>" + 
    "</div>" + 
  "</div>";
	}

	

  accordion.innerHTML = html;

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


// prevent Start Processing button from triggering upload file window
function preventDefaultFunction(event)
{   
    clickOnMainAreaFlag=true; 
    event.preventDefault();

    if(uploaded_files_map.length > 0)
    {
        ValidateForm();
    }
    else
    {
        alert("Please add at least one XML file!");
    }
        
}