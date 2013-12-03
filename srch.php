<?php
include_once('_includes/_data.php');
require_once("_includes/_xtemplate_header.php");
require_once("_includes/_bodycontents.php");
require_once("_includes/_data.php");
require_once("_includes/_moviePageUtils.php");
    $cLink = msi_dbconnect();	
printXHeader('');
?>


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
<?php
printFancyFooters();
    mysql_close($cLink);
?>
