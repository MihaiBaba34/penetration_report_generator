<?php
	define('FPDF_FONTPATH','font/');
	require('fpdf.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Penetration Report Generator</title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
	<!-- Optional theme -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<!-- Latest compiled and minified JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	<script type="text/javascript">
	$(document).ready(function() {
		$("#uploadfile").hide();
			$('#file').change(function(){
			var file = this.files[0];
			name = file.name;
			size = file.size;
			type = file.type;
			//Your validation
			if(type=="text/xml") {
				$("#uploadfile").show();
			} else {
				$("#uploadfile").hide();
				alert("upload valid xml file");
			}
		});

		$('#uploadfile').click(function(){
			var formData = new FormData($('form')[0]);

			alert("request");

			// alert(JSON.stringify(formData));
			$.ajax({
				url: 'xml_uploader.php',  //Server script to process data
				type: 'POST',
				success: function(data){
					alert(data);
					/*$('#xml_result').html(data);
					$('#xmlname').val(name);
					$('.nodedata input[type=checkbox]').prop('checked', true);
					$('span input[type=checkbox]').prop('checked', true);*/
				},
				error: function(){},
				data: formData,
				//Options to tell jQuery not to process data or worry about content-type.
				cache: false,
				contentType: false,
				processData: false
			});
			/*$('#nodelist').show();
			$('#upload').hide();*/
		});

		$(document).on("click", "#supplied", function(){
			var on = $(this).addClass("on");
			if ( $(this).is(':checked') ) {
				$(this).parent("span").nextAll('div:first').removeClass('inactive').addClass('active');
				$('.active input[type=checkbox]').each(function() {
					this.checked = true;
				});
				$(this).each(function() {
					this.checked = true;
				});
			} else {
				$(this).parent("span").nextAll('div:first').removeClass('active').addClass('inactive');
				$('.inactive input[type=checkbox]').each(function() {
					this.checked = false;
				});
				$(this).each(function() {
					this.checked = false;
				});
			}
		});

		$('#convert').click(function(){
			services1="";
			services=$('input:checkbox[name=node]:checked').map(function() {
			services1+=this.value+",";
			}).get();
			// alert(services1);
			var formdata= $('#nodelist').serialize();
			/*$.ajax({
				url: 'convert.php',
				type: 'POST',
				data: formdata,
				success: function(data){
					window.open(
							'data:application/pdf,'+encodeURIComponent(data),
							'Batch Print',
							'width=600,height=600,location=_newtab'
						);
					  //  window.open('', '_blank');
				},
				error: function(xhr) {
					alert(xhr+"error");
				}
			})*/
			$('#nodelist').submit();
		});
	});
	</script>
</head>
<body>
	<div id="upload-wrapper">
		<h3>Penetration Report Generator</h3><br>
		<form enctype="multipart/form-data" action="" method="POST" id="upload">
			<input type="hidden" name="MAX_FILE_SIZE" value="1230000" />
			<input name="userfile" type="file" id="file"  /><br><br>
			<div class="result"></div>
			<input type="button" value="Upload" id="uploadfile" />
		</form>
		<form enctype="multipart/form-data" action="convert.php" method="POST" style="display:none;" id="nodelist">
			<input type="hidden" name="filename" value="" id="xmlname"/>
			<input type="submit" value="Convert as PDF" id="convert" />
			<input type="hidden" name="htmlcode" value="" />
			<div id="xmltohtml"></div>
			<div id="xml_result"></div>
		</form>
	</div>
</body>
</html>