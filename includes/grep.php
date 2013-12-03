<?php
    
    error_reporting(0);  // turn off error reporting
  

    function php_grep($search, $path){            
	set_time_limit(0);                         
	global $total;        
	global $occurance;
	global $filesearched;
	global $dirsearched;   
    
	$fp = opendir($path);
	while($f = readdir($fp)){
	    if( preg_match("#^\.+$#", $f) ) continue; // ignore symbolic links
		$file_full_path = $path.SLASH.$f;         // insert win/unix slash in proper direction
		$filesearched++;                          // assume path is a file for stat count
      
		if(is_dir($file_full_path)) { 
		    $ret .= php_grep($search, $file_full_path);
		    $filesearched--;            
		    $dirsearched++;         
	    } else if( stristr(file_get_contents($file_full_path), $search) ) { 
		$fh = fopen("$file_full_path", "r");
		$linect = 0;
		while (!feof($fh)) {                  
		    $line = trim(fgets($fh));
		    $pattern = "/$search/";
                    if(preg_match($pattern,$line)) {      
			$ret .= "<span style='color:acf;'>$file_full_path</span> <span style='color:#ffc;'>[$linect]</span> <i><xmp>$line</xmp></i>\n"; 
			$occurance++;
		    }
		    $linect++;
		}
		fclose($file_handle);
		$total++;
	    }
	}
	return $ret;
  }
  

?>
