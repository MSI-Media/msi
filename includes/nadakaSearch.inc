<?


function get_unicode_movie ($comp){
    $query = "SELECT unicode from UNICODE_MAPPING WHERE name=\"$comp\" LIMIT 1";  	
    $result      = mysql_query($query);
    $num_results = mysql_num_rows($result);
    $i=0;
    $ucomposer = '';
    while ($i < $num_results){
	$ucomposer   = mysql_result($result,$i,"unicode");
	$i++;
    }

    if ($ucomposer == ''){
	$comps = explode (',',$comp);
	$ucomposer = '';
	foreach ($comps as $cmp){
	    $query1 = "SELECT unicode from UNICODE_MAPPING WHERE name=\"$cmp\" LIMIT 1";  	
	    $result1      = mysql_query($query1);
	    $num_results1 = mysql_num_rows($result1);
	    $ii=0;
	    while ($ii < $num_results1){
		if ($ucomposer != ""){
		    $ucomposer .= ',';
		}
		$ucomposer   .= mysql_result($result1,$ii,"unicode");
		$ii++;
	    }
	}
    }
    if (!$ucomposer){
	$ucomposer = $comp;
    }
    return $ucomposer;
}


function assignUrl($fldname, $fldheads, $filterUrl){
    $url = "";
    foreach ($fldheads as $key => $val){
	if ($val == $fldname && !$url){
	    $url = $filterUrl[$key];
	}
    }
    return $url;
}

function printMenu($tag, $firstval)
{
  $query = "SELECT distinct $tag FROM ALBUMS order by $tag ";

  $result     = mysql_query($query);
  $num_results=mysql_num_rows($result);
  $i=0;


  $acnt = 0;
  while ($i < $num_results){
     $sname = mysql_result($result,$i,"$tag");
     trim($sname);
     if ($sname){
       $ar = explode(',',$sname);
       trim($ar);
       foreach ($ar as $arelems) {
	   $master[$acnt++] = trim("$arelems");
       }
    }
   $i++;
 }
  echo "<SELECT name=$tag>\n";
  if ($firstval) {
    echo "<OPTION value=\"$firstval\" class=fixedsmall>$firstval </OPTION>\n";
  }
  echo "<OPTION value=\"Select One\" class=fixedsmall>Select One </OPTION>\n";
  sort($master);
  $unique = array_unique($master);

  foreach ($unique as $uelems) {
      echo "<OPTION value=\"$uelems\" class=fixedsmall>$uelems</OPTION>\n";
  }

  echo "</SELECT>";
    echo "<tr>\n";

}




function returnLabels ($query, $label){

  $labels = array();

  $result     = mysql_query($query);
  $num_results=mysql_num_rows($result);
  $i=0;

  while ($i < $num_results){
    $labeltxt       = mysql_result($result,$i,"$label");
    if (!$labeltxt){
      $labeltxt = "Uncategorized";
    }
    array_push($labels, $labeltxt);
    $i++;

  }
  if ($num_results > 4){
    array_push($labels, "Rest");
  }
  return $labels;
}

function returnData ($tquery, $query, $label){

  $labels = array();
  $top5 = 0;
  $result     = mysql_query($query);
  $num_results=mysql_num_rows($result);
  $i=0;
  while ($i < $num_results){
    $labeltxt       = mysql_result($result,$i,"$label");
    if (!$labeltxt){
      $labeltxt = "Uncategorized";
    }
    array_push($labels, $labeltxt);
    $top5 += $labeltxt;
    $i++;

  }

  if ($num_results > 4){
    $tresult     = mysql_query($tquery);
    $num_results=mysql_num_rows($tresult);
    $i=0;
    while ($i < $num_results){
      $labeltxt       = mysql_result($tresult,$i,"$label");
      $labeltxt      -= $top5;
      array_push($labels, $labeltxt);
      $i++;
    }
  }

  return $labels;
}



function printBrowseTables($year, $distinction,$browseOrder){

    $displayLanguage = $_GET['lang'];

    echo "<div class=ptextleft>";
    if ($_GET['lang'] == 'E'){	
	printContents("Writeups/Nadakam_eng.txt");
    }
    else {
	printContents("Writeups/Nadakam.html");
    }

    $dirs = array ('AM Chakrapani Warrier','VS Andrews','Swami Brahmavrithan','TC Achuthamenon');

    echo "<table border=0 width=100%>\n";
    echo "<tr>";
    $acnt=0;

    foreach ($dirs as $artpic){
	if ($acnt == 0){ 
	    echo "</tr><tr>";
	}

	if ($displayLanguage!='E'){
	    $martpic = get_uc("$artpic","");
	    echo "<td class=fixedtiny align=center><img class=shadow src=\"pics/Screenplay/${artpic}.jpg\" width=100><br>$martpic</td>";
	}
	else {
	    echo "<td class=fixedtiny align=center><img class=shadow src=\"pics/Screenplay/${artpic}.jpg\" width=100><br>$artpic</td>";
	}
	$acnt++;
	if ($acnt == 4){
	    $acnt = 0;
	}
    } 
    echo "</tr>";
    echo "</table>";
     echo "</div>";
  $order_field = "muscnt";
  $order_style = "DESC";
//  $order_title = "by Number of Dramas & Songs";
  $order_title = "by Number of Dramas ";

  $whereClause = " WHERE M_DIRECTOR like '%Drama%' ";
  if ($year && $distinction){
    $whereClause .= " AND m_year like \"$year%\" ";
    $whereTitle  = " in ${year}0s ";
  }
  if ($distinction == "Dramas"){
    $distinction = "";
  }
  echo "<table width=100%>\n";
  echo "<tr>\n";

    echo "<td width=50% valign=top>";
    $bgcolor="#eeefff";

    $query1 = "SELECT DISTINCT m_musician, count(m_movie) muscnt FROM ALBUMS $whereClause group by m_musician order by $order_field $order_style";
    if ($_GET['show_sql'] == 1){
	echo "$query1<br>";
    }
    $result1     = mysql_query($query1);
    $num_results=mysql_num_rows($result1);
    $i=0;

    echo "<table width=100%>\n";
    echo "<tr bgcolor=#AA4433><td colspan=3 class=ptextleft><font color=#ffffff>All Composers Sorted $order_title $whereTitle </font></td></tr>";


    while ($i < $num_results){
      $musician       = mysql_result($result1,$i,"m_musician");
      if (!$musician && !$browseOrder){
	$musician="Uncategorized";
      }

      $albums         = mysql_result($result1,$i,"muscnt");
      $urlix = "listAlbums.php?tag=Search&dmusician=$musician&limit=$albums";

      if ($year){
	$urlix .= "&m_year=$year";
      }

      //------Asongs
      $query3       = "SELECT DISTINCT count(s_song) scnt FROM ASONGS,ALBUMS where s_musician=\"$musician\" and (ASONGS.M_ID=ALBUMS.M_ID and ALBUMS.M_DIRECTOR like \"%Drama%\")";
      $result3      = mysql_query($query3);
      $num_results3 = mysql_num_rows($result3);
      $asongs = "";
      $urlix2 = "";
      $ii=0;
      while ($ii < $num_results3){
	  $asongs         = mysql_result($result3,$ii,"scnt");
	  $urlix2 = "listAlbumSongs.php?tag=Search&musician=$musician&limit=$asongs";
	  $ii++;
      }
      //-----



      if ($displayLanguage != 'E'){
	  $rsltStrng1 = get_uc("$musician","");
      }
      if ($rsltStrng1 == ""){
            $rsltStrng1 = "$musician";	
      }

//      echo "<tr bgcolor=$bgcolor><td class=ptextleft>$rsltStrng1</td><td class=ptextleft><a href=\"$urlix\">$albums</td><td class=ptextleft><a href=\"$urlix2\">$asongs</a></tr>";
      echo "<tr bgcolor=$bgcolor><td class=ptextleft>$rsltStrng1</td><td class=ptextleft><a href=\"$urlix\">$albums</td></tr>\n"; //<td class=ptextleft><a href=\"$urlix2\">$asongs</a></tr>";
      $rsltStrng1="";
      $i++;
    }
    echo "</table>";
    echo "</td>";


  if (!$distinction || $distinction == "Lyricists"){

    if ($browseOrder){
      $order_field = "M_WRITERS";
      $order_style = "ASC";
      $order_title = "Alphabetically";
    }

    echo "<td valign=top>";

    $bgcolor="#eeefff";
  

    $query2 = "SELECT DISTINCT m_writers, count(m_movie) muscnt FROM ALBUMS $whereClause group by m_writers order by $order_field $order_style";

    $result2     = mysql_query($query2);
    $num_results=mysql_num_rows($result2);
    $i=0;

    echo "<table width=100%>\n";
    echo "<tr bgcolor=#AA4433><td colspan=3 class=ptextleft><font color=#ffffff>All Lyricists Sorted $order_title $whereTitle </font></td></tr>";
    $ii = 0;
    while ($i < $num_results){
      $writers       = mysql_result($result2,$i,"m_writers");
      if (!$writers && !$browseOrder){
	$writers="Uncategorized";
      }
    
      $albums         = mysql_result($result2,$i,"muscnt");
      $urlix          = "listAlbums.php?tag=Search&dlyricist=$writers&limit=$albums";


      //------Asongs
      $query3       = "SELECT DISTINCT count(s_song) scnt FROM ASONGS,ALBUMS where s_writers=\"$writers\" and (ASONGS.M_ID=ALBUMS.M_ID and ALBUMS.M_DIRECTOR like \"%Drama%\")";
      $result3      = mysql_query($query3);
      $num_results3 = mysql_num_rows($result3);
      $asongs = "";
      $urlix2 = "";
      $ii=0;
      while ($ii < $num_results3){
	  $asongs         = mysql_result($result3,$ii,"scnt");
	  $urlix2 = "listAlbumSongs.php?tag=Search&lyricist=$writers&limit=$albums";
	  $ii++;
      }
      //-----
      if ($year){
	$urlix .= "&m_year=$year";
      }

      if ($displayLanguage != 'E'){
	  $rsltStrng2 = get_uc("$writers","");
      }
      if ($rsltStrng2 == ""){
	  $rsltStrng2 = "$writers";	
      }
//      echo "<tr bgcolor=$bgcolor><td class=ptextleft>$rsltStrng2</td><td class=ptextleft><a href=\"$urlix\">$albums</td><td class=ptextleft><a href=\"$urlix2\">$asongs</a></td></tr>";
      echo "<tr bgcolor=$bgcolor><td class=ptextleft>$rsltStrng2</td><td class=ptextleft><a href=\"$urlix\">$albums</td></tr>\n";//<td class=ptextleft><a href=\"$urlix2\">$asongs</a></td></tr>";
      $rsltStrng2="";
      $i++;
}
    echo "</table>";
    echo "</td>";
  }

  if (!$distinction || $distinction == "Labels"){

    if ($browseOrder){
      $order_field = "M_DIRECTOR";
      $order_style = "ASC";
      $order_title = "Alphabetically";
    }

    if ($distinction == "Labels"){
      echo "<td valign=top>";
    }
    else {
      echo "<br>";
    }
    $bgcolor="#eeefff";
  
    $dirs = array ('Thoppil Bhasi','NN Pillai','KT Muhammad','Sundaran Kallayi');

    echo "<table border=0 width=100%>\n";
    echo "<tr>";
    $acnt=0;

    foreach ($dirs as $artpic){
	if ($acnt == 0){ 
	    echo "</tr><tr>";
	}

	if ($displayLanguage!='E'){
	    $martpic = get_uc("$artpic","");
	    echo "<td class=fixedtiny align=center><img class=shadow src=\"pics/Screenplay/${artpic}.jpg\" width=100><br>$martpic</td>";
	}
	else {
	    echo "<td class=fixedtiny align=center><img class=shadow src=\"pics/Screenplay/${artpic}.jpg\" width=100><br>$artpic</td>";
	}
	$acnt++;
	if ($acnt == 4){
	    $acnt = 0;
	}
    }
    echo "</tr>";
    echo "</table>";




    $query2 = "SELECT DISTINCT m_director, count(m_movie) muscnt FROM ALBUMS $whereClause group by m_director order by $order_field $order_style";

    $result2     = mysql_query($query2);
    $num_results=mysql_num_rows($result2);
    $i=0;

    echo "<table width=100%>\n";
    echo "<tr bgcolor=#AA4433><td colspan=2 class=ptextleft><font color=#ffffff>Drama Troupes (Companies) Sorted $order_title $whereTitle </font></td></tr>";
    while ($i < $num_results){
      $director       = mysql_result($result2,$i,"m_director");
      if (!$director && !$browseOrder){
	$director ="Uncategorized";
      }
    
      $albums         = mysql_result($result2,$i,"muscnt");

      $urlix = "listAlbums.php?tag=Search&ddirector=$director&limit=$albums";
      if ($year){
	$urlix .= "&m_year=$year";
      }
      $albname_comp = str_replace("[Drama]","",$director);
     
      if ($displayLanguage != 'E'){
	  $rsltStrng3 = get_uc("$albname_comp","");
      }
      if ($rsltStrng3 == ""){
	  $rsltStrng3 = "$albname_comp";	
      }
      echo "<tr bgcolor=$bgcolor><td class=ptextleft>$rsltStrng3</td><td class=ptextleft><a href=\"$urlix\">$albums</a></td></tr>";
      $rsltStrng3="";
      $i++;
    }
    echo "</table>";
    echo "</td>";
  }

  echo "</tr>";
  echo "</table>";
}




?>
