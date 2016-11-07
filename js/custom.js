
var uploaded_files_map = [];


$(document).ready(function(){

	$("#filer_input2").filer({
		limit: null,
		maxSize: null,
		extensions: null,
		changeInput: '<div class="jFiler-input-dragDrop"><div class="jFiler-input-inner"><div class="jFiler-input-icon"><i class="icon-jfi-cloud-up-o"></i></div><div class="jFiler-input-text"><h3>Drag&Drop files here</h3> <span style="display:inline-block; margin: 15px 0">or</span></div><a class="jFiler-input-choose-btn blue">Browse Files</a></div></div>',
		showThumbs: true,
		theme: "dragdropbox",
		templates: {
			box: '<ul class="jFiler-items-list jFiler-items-grid"></ul>',
			item: '<li class="jFiler-item">\
			<div class="jFiler-item-container">\
			<div class="jFiler-item-inner">\
			<div class="jFiler-item-thumb">\
			<div class="jFiler-item-status"></div>\
			<div class="jFiler-item-thumb-overlay">\
			<div class="jFiler-item-info">\
			<div style="display:table-cell;vertical-align: middle;">\
			<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
			<span class="jFiler-item-others">{{fi-size2}}</span>\
			</div>\
			</div>\
			</div>\
			{{fi-image}}\
			</div>\
			<div class="jFiler-item-assets jFiler-row">\
			<ul class="list-inline pull-left">\
			<li>{{fi-progressBar}}</li>\
			</ul>\
			<ul class="list-inline pull-right">\
			<li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
			</ul>\
			</div>\
			</div>\
			</div>\
			</li>',
			itemAppend: '<li class="jFiler-item">\
			<div class="jFiler-item-container">\
			<div class="jFiler-item-inner">\
			<div class="jFiler-item-thumb">\
			<div class="jFiler-item-status"></div>\
			<div class="jFiler-item-thumb-overlay">\
			<div class="jFiler-item-info">\
			<div style="display:table-cell;vertical-align: middle;">\
			<span class="jFiler-item-title"><b title="{{fi-name}}">{{fi-name}}</b></span>\
			<span class="jFiler-item-others">{{fi-size2}}</span>\
			</div>\
			</div>\
			</div>\
			{{fi-image}}\
			</div>\
			<div class="jFiler-item-assets jFiler-row">\
			<ul class="list-inline pull-left">\
			<li><span class="jFiler-item-others">{{fi-icon}}</span></li>\
			</ul>\
			<ul class="list-inline pull-right">\
			<li><a class="icon-jfi-trash jFiler-item-trash-action"></a></li>\
			</ul>\
			</div>\
			</div>\
			</div>\
			</li>',
			progressBar: '<div class="bar"></div>',
			itemAppendToEnd: false,
			canvasImage: true,
			removeConfirmation: true,
			_selectors: {
				list: '.jFiler-items-list',
				item: '.jFiler-item',
				progressBar: '.bar',
				remove: '.jFiler-item-trash-action'
			}
		},
		dragDrop: {
			dragEnter: null,
			dragLeave: null,
			drop: null,
			dragContainer: null,
		},
		uploadFile: {
			url: "./php/ajax_upload_file.php",
			data: null,
			type: 'POST',
			enctype: 'multipart/form-data',
			synchron: true,
			beforeSend: function(){},
			success: function(data, itemEl, listEl, boxEl, newInputEl, inputEl, id){

				var parent = itemEl.find(".jFiler-jProgressBar").parent(),
				new_file_name = JSON.parse(data),
				filerKit = inputEl.prop("jFiler");

				filerKit.files_list[id].name = new_file_name;

				itemEl.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-success\"><i class=\"icon-jfi-check-circle\"></i> Success</div>").hide().appendTo(parent).fadeIn("slow");
				});

				//call an ajax function after files are uploaded
				get_corresponding_output_for_xml_file(new_file_name);

				
				
<<<<<<< HEAD
=======


				
>>>>>>> developer
			},
			error: function(el){
				var parent = el.find(".jFiler-jProgressBar").parent();
				el.find(".jFiler-jProgressBar").fadeOut("slow", function(){
					$("<div class=\"jFiler-item-others text-error\"><i class=\"icon-jfi-minus-circle\"></i> Error</div>").hide().appendTo(parent).fadeIn("slow");
				});
			},
			statusCode: null,
			onProgress: null,
			onComplete: null
		},
		files: null,
		addMore: false,
		allowDuplicates: true,
		clipBoardPaste: true,
		excludeName: null,
		beforeRender: null,
		afterRender: null,
		beforeShow: null,
		beforeSelect: null,
		onSelect: null,
		afterShow: null,
		onRemove: function(itemEl, file, id, listEl, boxEl, newInputEl, inputEl){
			var filerKit = inputEl.prop("jFiler"),
			file_name = filerKit.files_list[id].name;

			$.post('./php/ajax_remove_file.php', {file: file_name});
		},
		onEmpty: null,
		options: null,
		dialogs: {
			alert: function(text) {
				return alert(text);
			},
			confirm: function (text, callback) {
				confirm(text) ? callback() : null;
			}
		},
		captions: {
			button: "Choose Files",
			feedback: "Choose files To Upload",
			feedback2: "files were chosen",
			drop: "Drop file here to Upload",
			removeConfirmation: "Are you sure you want to remove this file?",
			errors: {
				filesLimit: "Only {{fi-limit}} files are allowed to be uploaded.",
				filesType: "Only Images are allowed to be uploaded.",
				filesSize: "{{fi-name}} is too large! Please upload file up to {{fi-maxSize}} MB.",
				filesSizeAll: "Files you've choosed are too large! Please upload files up to {{fi-maxSize}} MB."
			}
		}
	});
})


function get_corresponding_output_for_xml_file(data)
{

<<<<<<< HEAD


	var dataInContainer="<pre>********************************************************************************************************";
=======
	uploaded_files_map.push(data);
	
	console.log(uploaded_files_map);

/*var dataInContainer="<pre>********************************************************************************************************";
>>>>>>> developer
	$.ajax({
		type: "POST",
		url: "./php/XMLParser.php",
		data: {upload_path:"uploads", filename:data}
	})
	.fail(function() {
		alert( "error" );
	})
	.done(function( msg ) {

		var dataContainer=document.getElementById("parseResults");    
		var objects;
		var isOk = true;

		try
		{
			objects = JSON.parse(msg);
			isOk = true;

		



   }
   catch(e)
   {
   	console.log("************");
   	console.log("Not a valid xml file!");
   	console.log("************");

   	//TODO:
   	alert("Not a valid xml file!");

   	isOk = false;

   	//var matches = msg.match(/Error while reading XML file ..\/uploads\/(.*)/);
   	//console.log(matches[1]);

	//$.post('./php/ajax_remove_file.php', {file: matches[1]});

   }   	


   console.log(isOk);

   if(isOk === true)
   {
   	dataInContainer += "<br>Report type: " + objects["report_type"] + "<br>";

    	//iterating trough all objects
    	for (object in objects)
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

<<<<<<< HEAD
    	}
       //appdending data to main report container {temporar}
       dataContainer.innerHTML =dataInContainer+"</pre><br><br><br>" + dataContainer.innerHTML;	
   }


=======
}


       //appdending data to main report container {temporar}
		dataContainer.innerHTML=dataInContainer+"</pre><br><br><br>"+dataContainer.innerHTML;
		
>>>>>>> developer

});*/



}
