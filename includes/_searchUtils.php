<?php

function get_ucname ($comp,$tag){

    trim($comp);
 
    $num_results = 0;
    $search_type = $_POST['search_type'];
    if (!$search_type){
	$search_type = $_GET['sl'];
    }
    if (!$search_type){
	$search_type = $_GET['search_type'];
    }

    $query = "SELECT name from UNICODE_MAPPING WHERE unicode=\"$comp\" order by name limit 1;";
/*
    if ($search_type == 1){
    $query = "SELECT name from UNICODE_MAPPING WHERE unicode like \"%$comp%\";";
    }
*/
    mysql_query("SET NAMES utf8");
    $result      = mysql_query($query);
    if ($result) {
	$num_results = mysql_num_rows($result);
	$i=0;
	while ($i < $num_results){
	    $ustr   = mysql_result($result,$i,"name");
	    $i++;
	}
    }

    if (!$ustr){
	$ustr = "";
    }

    if ($ustr == "" && $search_type == 1) {
	$comp = ltrim(rtrim($comp));
	$comp = str_replace('  ',' ',$comp);
	$comps = explode (' ',$comp); 
	$comp2 = end ($comps);
	$query = "SELECT name from UNICODE_MAPPING WHERE ( (unicode like \"$comp\") or (unicode like \"%$comp%\") or (REPLACE(unicode, ' ', '') like REPLACE(\"%$comp%\", ' ', '')) or (unicode like \"$comp2\") or (unicode like \"$comps[0]\")) and (unicode not like \"%,%\") ORDER BY name limit 1";
//	echo $query;
	mysql_query("SET NAMES utf8");
	$result      = mysql_query($query);
	if ($result) {
	    $num_results = mysql_num_rows($result);
	    $i=0;
	    while ($i < $num_results){
		$ustr   = mysql_result($result,$i,"name");
		$i++;
	    }
	}
    }

    if (!$ustr){
	$ustr = "";
    }
    $ustr = str_replace("<br>","",$ustr);
    if (!$ustr) {
	$ustr = $comp;
    }
    return $ustr;
}
function addPicture($path, $root, $val,$category){

    global $_Master_profile_script;
    $val1  = ucfirst($val);
    $val2  = ucwords($val);
    $val3a = explode(' ',$val);
    if ($val3a[2] != ''){
	$val3 = strtoupper($val3a[0]) . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);
	$val5 = strtoupper($val3a[0]) . ' ' . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);

    }
    if (strlen($val3a[0]) == 2){
	$val4 = strtoupper($val3a[0]) . ' ' . ucfirst ($val3a[1]);
    }
    $vals = array ("$val","$val1","$val2","$val3","$val4","$val5");
    foreach ($vals as $valx){
	$cate = substr($category,0,-1);
	$categories = runQuery("SELECT category from MARTISTS where category like \"%,%\" and category like \"%$cate%\" and name = \"$valx\"",'category');
	if ($_GET['debug4'] == 1 ) { 
	    echo   "SELECT category from MARTISTS where category like \"%,%\" and category like \"%$category%\" and name = \"$valx\"<BR>";
	    print_r($categories); echo "****<BR>";
	}



	if (!$categories){
	    if (file_exists("$path/$root/${valx}.jpg")){
		$pic = "$path/$root/${valx}.jpg";
		$link = "$_Master_profile_script?artist=${valx}&category=$category";
		$art  = "$val";
		return array("$pic","$link","$art");
	    }
	    else {
		return array("$pic","$link","$art");
	    }
	}
	else {
	    $cats = explode (',',$categories);
	    foreach ($cats as $cat){
		$cat = ltrim(rtrim($cat));
		if (file_exists("$path/$cat/${valx}.jpg")){
		    $pic = "$path/$cat/${valx}.jpg";
		    $link = "$_Master_profile_script?artist=${valx}&category=$category";
		    $art  = "$val";
		    return array("$pic","$link","$art");
		}
		else if (file_exists("$path/${cat}s/${valx}.jpg")){
		    $pic = "$path/${cat}s/${valx}.jpg";
		    $link = "$_Master_profile_script?artist=${valx}&category=$category";
		    $art  = "$val";
		    return array("$pic","$link","$art");
		}
	    }
	    return array("$pic","$link","$art");
	}

    }
}

function correctPopularNames($path, $root, $val){

    $corrected_val = $val;
    $val = str_replace("."," ",$val);
    $val0 = getPreciseArtName($val,$root);
    if ($val0 == ''){
	$val0 = findSuperPopularArtists($val,$root);
    }
    $val1  = ucfirst($val);
    $val2  = ucwords($val);
    $val3a = explode(' ',$val);
    if ($val3a[2] != ''){
	$val3 = strtoupper($val3a[0]) . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);
	$val5 = strtoupper($val3a[0]) . ' ' . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);

    }
    if (strlen($val3a[0]) == 2){
	$val4 = strtoupper($val3a[0]) . ' ' . ucfirst ($val3a[1]);
    }

    
    $val7 = ucfirst(str_replace(" ","",$val));
    if (strlen($val) > 4 && $val3a[1] == '') {
	$val8 = runQuery("SELECT name from MARTISTS WHERE (name like \"$val1 %\" or name like \"%$val1\") and category like \"%$root%\"",'name');
    }
    $val9x = str_replace(' ','',$val);
    $val9 = runQuery("SELECT name from MARTISTS WHERE SOUNDEX(REPLACE(name,' ',''))=SOUNDEX(\"$val9x\") and category like \"%$root%\" LIMIT 1",'name');



    $vals = array ("$val0","$val","$val1","$val2","$val3","$val4","$val5","$val7","$val8","$val9");

    $val6_array = getCorrectArtName($val,$root);
    $found_a_match  = 0;
    foreach ($val6_array as $v6){
	foreach ($val6_array as $v6){
	    similar_text($val,$v6,$pct);
	    if ($pct  >= 70) {
		array_push ($vals, $v6);
	    }
	}
    }
    if ($_GET['debug2013'] == 1) { 
    print_r($vals);
    }

    $value_corrected = 0;
    foreach ($vals as $valx){
	if (file_exists("$path/$root/${valx}.jpg")){
	    $corrected_val = $valx;
	    $value_corrected = 1;
	    break;
	}
    }

    if (!$value_corrected){
	foreach ($vals as $valx){
	    $qry  = "SELECT name from MARTISTS where name regexp \"^$valx$\"";
            $cval = runQuery($qry,'name');
            if ($cval != ''){
  	      $corrected_val = $valx;
	      break;
            } 
	}
    }

    return $corrected_val;
    
}


function correctPopularProfileNames($path, $root, $val){

    $corrected_val = $val;
    $val = str_replace("."," ",$val);
    $val0 = getPreciseArtName($val,$root);
    if ($val0 == ''){
	$val0 = findSuperPopularArtists($val,$root);
    }
    $val1  = ucfirst($val);
    $val2  = ucwords($val);
    $val3a = explode(' ',$val);
    if ($val3a[2] != ''){
	$val3 = strtoupper($val3a[0]) . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);
	$val5 = strtoupper($val3a[0]) . ' ' . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);

    }
    if (strlen($val3a[0]) == 2){
	$val4 = strtoupper($val3a[0]) . ' ' . ucfirst ($val3a[1]);
    }

    
    $val7 = ucfirst(str_replace(" ","",$val));
    if (strlen($val) > 4 && $val3a[1] == '') {
	$val8 = runQuery("SELECT name from MARTISTS WHERE (name like \"$val1 %\" or name like \"%$val1\") and category like \"%$root%\"",'name');
    }
    $val9x = str_replace(' ','',$val);
    $val9 = runQuery("SELECT name from MARTISTS WHERE SOUNDEX(REPLACE(name,' ',''))=SOUNDEX(\"$val9x\") and category like \"%$root%\" LIMIT 1",'name');



    $vals = array ("$val0","$val","$val1","$val2","$val3","$val4","$val5","$val7","$val8","$val9");

    $val6_array = getCorrectArtName($val,$root);
    $found_a_match  = 0;
    foreach ($val6_array as $v6){
	foreach ($val6_array as $v6){
	    similar_text($val,$v6,$pct);
	    if ($pct  >= 70) {
		array_push ($vals, $v6);
	    }
	}
    }
    if ($_GET['debug2013'] == 1) { 
    print_r($vals);
    }

    $value_corrected = 0;
    foreach ($vals as $valx){
	if (file_exists("$path/$root/${valx}.jpg")){
	    $corrected_val = $valx;
	    $value_corrected = 1;
	    break;
	}
    }

    if (!$value_corrected){
	foreach ($vals as $valx){
	    $qry  = "SELECT name from MARTISTS where name regexp \"^$valx$\"";
            $cval = runQuery($qry,'name');
            if ($cval != ''){
  	      $corrected_val = $valx;
	      break;
            } 
	}
    }

    return $corrected_val;
    
}

function correctProfileNames($path, $root, $val){

    $val    = processString($val);
    $corrected_val = '';
    $val = str_replace("."," ",$val);
    $val0 = getPreciseArtName($val,$root);
    if ($val0 == ''){
	$val0 = findSuperPopularArtists($val,$root);
    }

    $val1  = ucfirst($val);
    $val2  = ucwords($val);
    $val3a = explode(' ',$val);
    if ($val3a[2] != ''){
	$val3 = strtoupper($val3a[0]) . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);
	$val5 = strtoupper($val3a[0]) . ' ' . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);

    }
    if (strlen($val3a[0]) == 2){
	$val4 = strtoupper($val3a[0]) . ' ' . ucfirst ($val3a[1]);
    }

    
    $val7 = ucfirst(str_replace(" ","",$val));
    if (strlen($val) > 4 && $val3a[1] == '') {
        $category = substr("$root", 0, -1);  
	$val8_array = runQuery("SELECT name from MARTISTS WHERE (name like \"$val1 %\" or name like \"%$val1\") and category like \"%$category%\" ",'name');
    }
    $vals = array ("$val0","$val","$val1","$val2","$val3","$val4","$val5","$val7");

    $val6_array = getCorrectArtName($val,$root);
    $found_a_match  = 0;
    foreach ($val6_array as $v6){
	foreach ($val6_array as $v6){
	    similar_text($val,$v6,$pct);
	    if ($pct  >= 70) {
	    if (!in_array($v6,$vals)){
		array_push ($vals, $v6);
		}
	    }
	}
    }
    if ($val8_array[0] != ''){
    foreach ($val8_array as $v6){
	foreach ($val6_array as $v6){
	    similar_text($val,$v6,$pct);
	    if ($pct  >= 70) {
	    if (!in_array($v6,$vals)){
		array_push ($vals, $v6);
		}
	    }
	}
    }
    }


    $value_corrected = 0;
    foreach ($vals as $valx){
	if (file_exists("$path/$root/${valx}.jpg")){
	    $corrected_val = $valx;
	    break;
	}
    }


   if ($_GET['debug2013'] == 1) { echo $corrected_val, "**<BR>"; }
   return $corrected_val;
    
}

function getAlternateProfiles($path, $root, $val)
{
    // Even if Picture and Profile is missing, see if its a proper artist

    $val    = processString($val);
    $corrected_val = '';
    $val = str_replace("."," ",$val);
    $val0 = getPreciseArtName($val,$root);
    if ($val0 == ''){
	$val0 = findSuperPopularArtists($val,$root);
    }

    $val1  = ucfirst($val);
    $val2  = ucwords($val);
    $val3a = explode(' ',$val);
    if ($val3a[2] != ''){
	$val3 = strtoupper($val3a[0]) . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);
	$val5 = strtoupper($val3a[0]) . ' ' . ucfirst($val3a[1]) . ' ' . ucfirst ($val3a[2]);

    }
    if (strlen($val3a[0]) == 2){
	$val4 = strtoupper($val3a[0]) . ' ' . ucfirst ($val3a[1]);
    }

    
    $val7 = ucfirst(str_replace(" ","",$val));
    if (strlen($val) > 4 && $val3a[1] == '') {
        $category = substr("$root", 0, -1);  
	$val8_array = runQuery("SELECT name from MARTISTS WHERE (name like \"$val1 %\" or name like \"%$val1\") and category like \"%$category%\" ",'name');
    }

    $val9x = str_replace(' ','',$val);
    $val9 = runQuery("SELECT name from MARTISTS WHERE SOUNDEX(REPLACE(name,' ',''))=SOUNDEX(\"$val9x\") and category like \"%$root%\" LIMIT 1",'name');

    $vals = array ("$val0","$val","$val1","$val2","$val3","$val4","$val5","$val7","$val9");


    $songs_array = array('Singers' => 'S_SINGERS');

    $movies_array = array('Directors' => 'M_DIRECTOR' ,
                          'Lyricists' => 'M_WRITERS',
                          'Musicians' => 'M_MUSICIAN');

    $dets_array = array('Actors' => 'M_CAST',
               'Editors'      => 'M_EDITOR',
               'Screenplay'   => 'M_SCREENPLAY',
               'Story'        => 'M_STORY',
               'Dialogs'      => 'M_DIALOG',
               'Art'          => 'M_ART',
               'Design'       => 'M_DESIGN',
               'Producers'    => 'M_PRODUCER',
    );

 if ($_GET['debug2013'] == 1) {   print_r($vals);}
//              if ($_GET['debug2013'] == 1) { echo $gvqry, "**<BR>"; }

$good_val = '';
    if ($corrected_val == ''){
       foreach ($vals as $valx){
          foreach ($movies_array as $k=>$v){
            if ($k == $root && $valx != '') {
               $gvqry = "SELECT $v from MOVIES WHERE $v like \"$valx\"  limit 1";
	       $good_val = runQuery("$gvqry",$v);
               if ($good_val != '') { 
	           $corrected_val = $valx;
  	           break;
               }
	       if ($corrected_val == ''){
		   $gvqry = "SELECT $v from ALBUMS WHERE $v like \"$valx\"  limit 1";
		   $good_val = runQuery("$gvqry",$v);
		   if ($good_val != '') { 
		       $corrected_val = $valx;
		       break;
		   }
	       }
            }
  	  }
       }
    }
$good_val = '';
    if ($corrected_val == ''){
       foreach ($vals as $valx){
          foreach ($songs_array as $k=>$v){
            if ($k == $root  && $valx != '') {
               $gvqry = "SELECT $v from SONGS WHERE $v like \"$valx\"  limit 1";
	       $good_val = runQuery("$gvqry",$v);
               if ($good_val != '') { 
	           $corrected_val = $valx;
  	           break;
               }
	       if ($corrected_val == ''){
		   $gvqry = "SELECT $v from ASONGS WHERE $v like \"$valx\"  limit 1";
		   $good_val = runQuery("$gvqry",$v);
		   if ($good_val != '') { 
		       $corrected_val = $valx;
		       break;
		   }
	       }
            }
  	  }
       }
    }

$good_val = '';               
    if ($corrected_val == ''){
       foreach ($vals as $valx){
          foreach ($dets_array as $k=>$v){
            if ($k == $root  && $valx != '') {
               $gvqry = "SELECT $v from MDETAILS WHERE $v like \"$valx\" or $v like \"%,$valx\" or $v like \"%,$valx,%\" or $v like \"$valx,%\"  limit 1";
	       $good_val = runQuery("$gvqry",$v);
               if ($good_val != '') { 
               if ($_GET['debug2013'] == 1) { echo "$good_val: $gvqry<BR>"; }          
	           $corrected_val = $valx;
  	           break;
               }
            }
  	  }
       }
    }
    return $corrected_val;
}

function findSuperPopularArtists($val,$root)
{

    $val = strtolower($val);
    $val = ltrim(rtrim($val));
    $r = runQuery("SELECT val from SLOOKUP where name=\"$val\" and (category=\"$root\" or category = \"\")",'val');
//    echo "$val $r<BR>";
    return $r;
}

function getCorrectArtName($val,$root){
    $len  = strlen($val);
    $vals = str_split($val);
    $category = substr("$root", 0, -1); 
    $qry  = "SELECT name from MARTISTS where category like \"%$category%\" and name regexp ";
    //  $qelems = '^';
    $qelems = '';

    $lenstart = 0;
    while ($lenstart < $len){
	if ($vals[$lenstart] != $prev){
	    $astr    = getAlternateLetters($vals[$lenstart]);
	    if (($len - $lenstart) > 1){
		$qelems .= $astr . '.*';
	    }
	    else { $qelems .= $astr;}
	    $prev = $vals[$lenstart];
	}
	$lenstart++;
    }
    //    $qry .= "'" . $qelems . "$'";
    $qry .= "'" . $qelems . "'";
   //    echo $qry;
   //    $valx = runQuery($qry, 'name');
    $valx_array = buildArrayFromQuery($qry,'name');
    return $valx_array;
//  return $valx;
}

function getPreciseArtName($val,$root){
    $len  = strlen($val);
    $vals = str_split($val);
    $category = substr("$root", 0, -1); 
    $qry  = "SELECT name from MARTISTS where category like \"%$category%\" and name regexp \"^$val$\"";
    $valx = runQuery($qry, 'name');
    return $valx;
}

function getPreciseMovieSongName($val,$db){
$table1 = 'MOVIES';
$table2 = 'UMOVIES';
$tag1 = 'M_MOVIE';
$tag2 = 'M_MOVIE';
if ($db == 'albums'){
$table1 = 'ALBUMS';
$table2 = 'UALBUMS';
$tag1 = 'M_MOVIE';
$tag2 = 'M_MOVIE';
}
else if ($db == 'moviesongs'){
$table1 = 'SONGS';
$table2 = 'USONGS';
$tag1 = 'S_SONG';
$tag2 = 'U_SONG';
}
else if ($db == 'albumsongs'){
$table1 = 'ASONGS';
$table2 = 'UASONGS';
$tag1 = 'S_SONG';
$tag2 = 'S_SONG';
}
    $qry  = "SELECT $tag1 from $table1 where $tag1 like \"$val\"";
    $valx = runQuery($qry, "$tag1");
    if ($valx == ''){
       $valr = addRepetitionCounts($val);
       $qry  = "SELECT $tag1 from $table1 where $tag1 regexp \"$valr\"";
       $multivals = buildArrayFromQuery($qry, "$tag1");	
       if ($multvals[1] != ''){
          $valx = runQuery($qry, "$tag1");
       }
    }
    if ($valx == ''){
       $qry  = "SELECT $tag2 from $table2 where $tag2 like \"$val\"";
       $valx = runQuery($qry, "$tag2");
    }

    if ($valx == ''){
       $valnospace = str_replace(" ","",$val);
       $valr = addRepetitionCounts($valnospace);
       $qry  = "SELECT $tag1 from $table1 where REPLACE($tag1,' ','') regexp \"$valr\"";
       $multivals = buildArrayFromQuery($qry, "$tag1");	
       if ($multvals[1] != ''){
       $valx = runQuery($qry, "$tag1");
       }
    }

    if ($valx == ''){
       $valnospace = str_replace(" ","",$val);
       $valr = addRepetitionCounts($valnospace);
       $qry  = "SELECT $tag2 from $table2 where $tag2 like \"$valr\"";
       $valx = runQuery($qry, "$tag2");
    }
    return $valx;
}

function addRepetitionCounts($val){
    $len  = strlen($val);
    $vals = str_split($val);
    $qelems = '';

    $lenstart = 0;
    while ($lenstart < $len){
	if ($vals[$lenstart] != $prev){
	    $astr    = $vals[$lenstart];
	    if (($len - $lenstart) > 1){
		$qelems .= $astr . '{1,}';
	    }
	    else { $qelems .= $astr;}
	    $prev = $vals[$lenstart];
	}
	$lenstart++;
    }
    return $qelems;
}

function getRegexpName($val){
    $len  = strlen($val);
    $vals = str_split($val);
    $qelems = '';

    $lenstart = 0;
    while ($lenstart < $len){
	if ($vals[$lenstart] != $prev && $vals[$lenstart] != 'h'){
	    $astr    = getAlternateLetters($vals[$lenstart]);
	    if (($len - $lenstart) > 1){
		$qelems .= $astr . '.*';
	    }
	    else { $qelems .= $astr;}
	    $prev = $vals[$lenstart];
	}
	$lenstart++;
    }
    return $qelems . '*' ;
}




function getAlternateLetters($let){
    if ($let == "s" || $let == "z"){
	return '(s|sh|z)';
    }
    else if ($let == "t" || $let == "d"){
	return '(t|d)';
    }
    else if ($let == "b" || $let == "p"){
	return '(b|p)';
    }
    else if ($let == "k" || $let == "g"){
	return '(k|g)';
    }
    else if ($let == "o" || $let == "u"){
	return '(o|u)';
    }
    else if ($let == "e" || $let == "y" || $let == "i" || $let == "a"){
	return '(y|i|e|a)*';
    }
    else if ($let == "a" || $let == "e"){
	return '(a|e)';
    }
    else if ($let == "l" || $let == "t"){
	return '(l|t)';
    }
    else {
	return $let;
    }
}

function addClauseToArray ($tag,$val){
    $url_str = "";
    $val8 = $val;
    $search_type = $_POST['search_type'];
    if (!$search_type){
	$search_type = $_GET['sl'];
    }
    if (!$search_type){
	$search_type = $_GET['search_type'];
    }

    if ($search_type == 1) {
	if ($val > 0){
	    $url_str = "  ($tag like \"$val%\") ";
	}
	else {
	$pos = strpos($val,",");
	$val8 =$val;
	if ($pos !== false) {
	    $val8 = $val;
	}
	else {
	    $len  = strlen($val);
	    if ($len > 8){
		$val8 = substr($val,0,$len-4);
	    }
	    else {
		$val8 = $val;
	    }

	}
	$val8 = str_replace("a","%a%",$val8);
	$val8 = str_replace("e","%e%",$val8);
	$val8 = str_replace(" ","%",$val8);
	$val8 = str_replace(",","%",$val8);
	$val8 = str_replace("t","t%",$val8);
	$url_str = " ( ($tag like \"$val\") or ($tag like \"%$val8%\") or (SOUNDEX($tag)=SOUNDEX(\"$val\")) or (SOUNDEX(SUBSTRING($tag,1,8)) = SOUNDEX (\"$val8\"))  or (REPLACE($tag, ' ', '') like REPLACE(\"%$val%\", ' ', '')) ) ";
//	echo $url_str;
	if ($pos !== false && $search_type == 1){
	    $artists = array();
	    $artists = explode (',',$val);
	    $qry = '';
	    $qry_elems = array();
	    foreach ($artists as $art){
		$qelem = "\"%" . substr($art,0,3) . "%\"";
		array_push ($qry_elems,"$tag like $qelem");
 	    }
	    $qry = implode (" AND ", $qry_elems);
	    $qry = str_replace("%%","%",$qry);
	    $url_str = " ( ($tag like \"%$val8%\") or ( $qry ) ) ";
	}
	}
    }
    else {
	$pos = strpos($val,",");
	$url_str = '';
	if ($pos !== false) {
	    $valelems = explode(',',$val);
	    $valoutput = array();
	    foreach ($valelems as $valelem) {
		array_push ($valoutput,  "($tag like \"$valelem,%\" or $tag like \"%,$valelem\" or $tag like \"%,$valelem,%\" or $tag like \"$valelem\" or $tag like \" $valelem ,%\" or $tag like \"%, $valelem\" or $tag like \"%, $valelem,%\" or $tag like \"%, $valelem ,%\" or $tag like \"%,$valelem ,%\" or $tag like \"%, $valelem\" or $tag like \"%$valelem ,%\" ) ");
	    }
	    $url_str = implode (' AND ', $valoutput);
	}
	else { 
	    if ($tag == "S_SONG" || $tag == "S_MOVIE" || $tag == "M_MOVIE") {
		$valnospace = str_replace(" ","",$val);
		$valregexp  = getRegexpName($val);
		$url_str = "( (SOUNDEX($tag)=SOUNDEX(\"$val\")) or $tag regexp \"$valregexp\" or $tag like \"$valnospace%\" or $tag like \"$val%\" or $tag like \"%$val\" or $tag like \"%$val%\" or $tag like \"$val\" or $tag like \" $val %\" or $tag like \"% $val\" or $tag like \"% $val%\" or $tag like \"% $val %\" or $tag like \"%$val %\" or $tag like \"% $val\" or $tag like \"%$val %\" or $tag like \"$val%\" or $tag like \" $val%\") "; 
	    }
	    else {
		$url_str = "($tag like \"$val,%\" or $tag like \"%,$val\" or $tag like \"%,$val,%\" or $tag like \"$val\" or $tag like \" $val ,%\" or $tag like \"%, $val\" or $tag like \"%, $val,%\" or $tag like \"%, $val ,%\" or $tag like \"%,$val ,%\" or $tag like \"%, $val\" or $tag like \"%$val ,%\" ) "; 
	    }
	}
    }
    return $url_str;
}

function getProfiles($tags, $siz){
    global $_Master_profile_script;
    if (!$siz) { $siz = 1000; }
    $targets = array('Singers','Actors','Musicians','Lyricists','Story','Screenplay','Dialog','Directors','Producers','Art','Editors','Camera','Design','Makeup');
    $readmore = "Read More";
    if ($_GET['lang'] != 'E') { $readmore = get_uc($readmore,''); }
    foreach ($tags as $tag){
	$tag = ucfirst(ltrim(rtrim($tag)));

	foreach ($targets as $target){
	    if ($_GET['debug2013'] == 1) { echo "pics/$target/${tag}.jpg<BR>";}
	    if (file_exists("Writeups/$target/${tag}.txt")){
		if ($_GET['lang'] != 'E') { $tagx = get_uc($tag,''); } else { $tagx = $tag; }
		$starget = truncatePathElems(strtolower($target));
		$url = "$_Master_profile_script?category=$starget&artist=$tag";
		echo "<div class=pcellheads><b><a href=\"$url\">$tagx</a></b></div>\n";
		echo "<div class=pcellslong> ", substr (file_get_contents("Writeups/$target/${tag}.txt"), 0, $siz), "  ... <a href=\"$url\">$readmore</a> </div>";
		break;
	    }
	    else    if (file_exists("Writeups/$target/${tag}.html")){
		if ($_GET['lang'] != 'E') { $tagx = get_uc($tag,''); } else { $tagx = $tag; }
		$starget = truncatePathElems(strtolower($target));
		$url = "$_Master_profile_script?category=$starget&artist=$tag";
		echo "<div class=pcells><b><a href=\"$url\">$tagx</a></b></div>\n";
		echo "<div class=pcellslong> ", substr (file_get_contents("Writeups/$target/${tag}.html"), 0, $siz), "  ... <a href=\"$url\">$readmore</a></div>";
		break;
	    }
	}
    }


}
function truncatePathElems ($starget){
    if ($starget == 'lyricists') { $starget = 'lyricist'; } else if ($starget == 'composers') { $starget = 'musician'; } else if ($starget == 'directors') { $starget = 'director'; }
    return $starget;
}
function printDetailHeadersOneLine ($key,$val){
    if ($_GET['lang'] != 'E') {	
	$key = get_uc("$key","");
	$val = get_uc("$val","");
    }
    return "$key : $val";

}

function getArticles($tags){

    $relart = "Relevant Articles";

    if ($_GET['lang'] != 'E'){
	$relart = get_uc($relart,'');
    }

    $tagarray = array();
    foreach ($tags as $tag){
	if ($tag != ''){
  	      array_push ($tagarray, " tags like \"$tag\" or tags like \"%,$tag\" or tags like \"%,$tag,%\" or tags like \"$tag,%\" ");
//            array_push ($tagarray, " tags like \"%$tag%\" ");
	}
    }

    if ($tagarray[0] != ''){
	$query = "SELECT title,url from ARTICLES WHERE (" . implode (' or ',$tagarray) . ") ";
	$result        = mysql_query($query);
	$num_results   = mysql_num_rows($result);
    
	$i=0;
	if ($num_results > 0) {
	    echo "<div class=pcellheads><b>$relart</b></div><ul>\n";
	    while ($i < $num_results){
		$title = mysql_result($result, $i, "title");
		$url = mysql_result($result, $i, "url");
		echo "<li class=pcellslong><a href=\"$url\" target=\"_new\">$title</a></li>\n";
		$i++;
	    }
	    echo "</div>";
	}
    }
}

function getAudiofils($tags){

    $relart = "Relevant Audio";
    $glv_episode = "Gaanalokaveedhikal Episode";

    if ($_GET['lang'] != 'E'){
	$relart = get_uc($relart,'');
	$glv_episode = get_uc($glv_episode,'');
    }
    $url = "GLV.php";
    if ($_GET['lang'] != 'E'){
	$relart = get_uc($relart,'');
    }
    $tagarray = array();
    if ($tags[0] != ""){
	foreach ($tags as $tag){
	    if ($tag != ''){
		array_push ($tagarray, " tags like \"%$tag%\" ");
	    }
	}
	$query = "SELECT episode from GLV_TAGS WHERE (" . implode (' or ',$tagarray) . ") ";
	$episodes = buildArrayFromQuery($query, 'episode');
	if ($episodes[0] != ""){
	    echo "<div class=pcellslong><b>$relart</b><ul>\n";
	    foreach ($episodes as $episode){
		echo "<li class=pcellslong><a href=\"${url}?e=$episode\" target=\"_new\">$glv_episode $episode</a></li>\n";
	    }
	    echo "</div>";
	}
    }
}
function processString ($str){
    $str = str_replace("."," ",$str);
    $strs = explode(',',$str);
    $nstrs = array();
    foreach ($strs as $st){
	$st = ltrim(rtrim($st));
	array_push($nstrs,$st);
    }
    $str = implode(',',$nstrs);
    $str = str_replace("  "," ",$str);
    return $str;
}
function writeToCacheFile ($fil,$txt)
{
    $fh = fopen("$fil","a");
    $t = date('m/d/Y - H:ia');
    if ($fh){
	fwrite ($fh, "${t} : $txt\n");
	fclose ($fh);
    }
}
/*
function writeToCacheFile ($fil,$txt)
{
    if (file_exists("$fil")){
	$fh = fopen("$fil","a");
    }
    else {
	$fh = fopen("$fil","w");
    }
    $t = date('m/d/Y - H:ia');
    if ($fh){
	fwrite ($fh, "${t} : $txt\n");
	fclose ($fh)
    }
}
*/
?>
