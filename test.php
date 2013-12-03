<?php
include_once('_includes/_data.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Untitled Document</title>
<link rel="stylesheet" type="text/css" href="http://msidb.org/css/search/style.css">
<style type="text/css">
.main_display
{
	float:left;
	height:1000px;
	width:100%;
}
.search
{
	float:left;
	height:30px;
	width:510px;
	margin:50px;
}
.Search_input
{
	width:500px;
	padding:5px;
	border:solid 1px #CCCCCC;
	color:#000000;
	background-position:490px center;
	background-repeat:no-repeat;
}
.suggestionsBox
{
	margin-top:2px;
	width:800px;
	z-index:100000;
	position:absolute;
	border:solid 1px #CCCCCC;
	border-top:none;
	box-shadow:0 5px 8px 0 rgba(0, 0, 0, 0.5);
}
.breedSearch_hov_error
{
	border-top: 1px solid #DBD9D1;
	color: #FFFFFF;
	cursor: pointer;
	font-family: "Myriad Pro";
	font-size: 14px;
	font-weight: bold;
	line-height: 18px;
	list-style-position: inside;
	list-style-type: none;
	padding: 5px 20px;
	text-align: justify;
	background:#FF0000;
}
</style>
<!---- Mother Jquery Library --->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.0/jquery.min.js"></script>
<!---- Mother Jquery Library --->
<script type="text/JavaScript">
function lookup(inputString) 
{
	if(inputString.length == 0) 
	{
		// Hide the suggestion box.
		$('#suggestions').hide();
	} 
	else 
	{
		var element = document.getElementById("inputString");
		element.style.backgroundImage = "url('icons/search_loading.gif')";
		$.post("_rpc_search.php", {queryString: ""+inputString+""}, function(data)
		{
			if(data.length >0)
			{
				document.getElementById("inputString").style.backgroundImage = "";
				$('#suggestions').show();
				$('#autoSuggestionsList').html(data);
			}
			else
			{
				document.getElementById("inputString").style.backgroundImage = "";
			}
		});
	}
} // lookup	
</script>
</head>
<body>
<div class="main_display">
	<div class="search">
		<input type="text" name="txtCat" id="inputString" autocomplete="off" class="Search_input" value="" placeholder="Start typing to find movies, songs & more ..." onKeyUp="lookup(this.value);" />										
		<div class="suggestionsBox" id="suggestions" style="display: none;">
			<div class="suggestionList" id="autoSuggestionsList" style="overflow:auto;">&nbsp;</div>
		</div>
	</div>
</div>
</body>
</html>
