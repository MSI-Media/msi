	if($search){	// if search is set, we process a search request 
    $results = php_grep($search, $path);
    }

  if ($search == '') { $search = 'enter text to search for'; } 

	echo "
    <html>
    <head>
      <title>$title - $ver</title>
      <style>
      body  { margin: 100px; background-color: #123; color: #fff; font-family: arial;}
      input { background-color: #9c9; font-size: 105%;}
      h1    { text-align: center; color: #ea7; display: inline;}
      h3    { text-align: center; color: #ea7; display: inline;}
      xmp   { display: inline;}
      </style>
    </head>
    <body>
	  <form method=post>
    <table>
	  	<tr align=center><td>
        <h1>$title</h1>
        <br>
        <h3>ver $ver</h2>
        <br>
        </td></tr>
        <tr><td>
        <input name=path size=100 value=\"$path\" /><br>
        Path<br><br>
	  	  <input name=search size=100 value=\"$search\" /><br>
        Search<br>
	  	  <center><input type=submit></center><br>
      </td></tr>
      <tr><td
        <span style='color: #ea7;'>
          Dirs  searched = $dirsearched<br> 
          Files searched = $filesearched<br>   
          Files matched  = $total<br>
          Occurances     = $occurance<br>
        </span>
      </td></tr>  
    </table>
	  </form> ";

	  echo "<pre>$results</pre>";
    echo "<span style='color: #355;'>By Jan Zumwalt - net-wrench.com </span>"

