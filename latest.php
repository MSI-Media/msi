<?php session_start();

{
   error_reporting (E_ERROR);    
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("includes/cache.php");
    $_GET['encode']='utf';
//    $ch = new cache($_GET['lang'],'');

    $cLink = msi_dbconnect();
    printXHeader('Popup');
    $optag = $_GET['open'];

    if ($optag != 'code'){
	if ($_GET['lang'] == 'E'){
	    printContents("Writeups/LatestMsg.html");
	}
	else {
	    printContents("Writeups/LatestMsg_Malayalam.html");
	}
    }
    else {

  
    $msgFile = 'Writeups/Last10';
    if ($_GET['lang'] == 'E') { $msgFile .= '_E'; }
    printContents("${msgFile}.txt");
	// Movie Reviews
	echo "<table class=ptables><tr><td width=50% valign=top>\n";
	$q1 = "SELECT DISTINCT M_ID from MD_LINKS ORDER BY M_ATS DESC LIMIT 20";
	$movies = _buildArrayFromQuery($q1,'M_ID');
	buildMovieTables($movies,'Reviews','icons/Notebook.png');
	echo "</td><td valign=top>";

	// Profiles
	$q2 = "SELECT DISTINCT P_ARTIST from PROFILES ORDER BY P_ID DESC LIMIT 20";
	$profiles = _buildArrayFromQuery($q2,'P_ARTIST');
	buildPeopleTables($profiles,'Profiles','icons/Movie.png');
	// Posters
	echo "</td></tr><tr><td valign=top>";
	$q3 = "SELECT DISTINCT M_ID from PICTURES where P_STATUS='Y' ORDER BY P_ATS DESC LIMIT 20";
	$pictures = _buildArrayFromQuery($q3,'M_ID');
	buildMovieTables($pictures,'Movie Posters','icons/Picture.png');
	echo "</td><td valign=top>";
	// Profile Pictures
/*	
	$prof_pics = dirCrawl("pics/Actors/TN",'actors','Actor Pictures');
*/
	$q5 = "SELECT DISTINCT UT_ID from UTUBE where UT_STAT='Published' ORDER BY UT_ATS DESC LIMIT 20";
	$videos = _buildArrayFromQuery($q5,'UT_ID');
	buildSongTables($videos,'Videos','images/youtube.png','SONGS','');
	echo "</td></tr><tr><td valign=top>";
	// Songs

	$q6 = "SELECT DISTINCT S_ID from SONGS ORDER BY S_ATS DESC LIMIT 20";
	$songs = _buildArrayFromQuery($q6,'S_ID');
        buildSongTables($songs,'Song Details','icons/Music.png','SONGS','');
	echo "</td><td valign=top>";

	// Movie Details

	$q7 = "SELECT DISTINCT M_ID from MOVIES  ORDER BY M_ATS DESC LIMIT 20";
	$movies = _buildArrayFromQuery($q7,'M_ID');
	buildMovieTables($movies,'Movie Details','icons/Movie.png');
	echo "</tr><tr><td valign=top>";

	$q8 = "SELECT DISTINCT S_ID from SONGS WHERE  S_CLIP='Y' ORDER BY S_ID DESC LIMIT 20";
	$songs = _buildArrayFromQuery($q8,'S_ID');
        buildSongTables($songs,'Latest Audio','icons/Music.png','SONGS',1);
	echo "</td><td valign=top>";

	$q9 = "SELECT DISTINCT S_ID from ASONGS WHERE  S_CLIP='Y' ORDER BY S_ID  DESC LIMIT 20";
	$songs = _buildArrayFromQuery($q9,'S_ID');
        buildSongTables($songs,'Latest Album Audio','icons/Music.png','ASONGS',1);

        echo "</td></tr>";
	echo "</table>\n";
}      
        $regenMsg = "Regenerate This Page";
        if ($_GET['lang'] != 'E') { $regenMsg = get_uc($regenMsg,''); }
        $today = date("F j, Y");
        echo( "<div style=\"font-size:9px;margin 0 auto;\">This page was generated on $today | <a href=\"RemoveLatest.php?type=latest.php\">$regenMsg</a></div>");
       mysql_close($cLink);
       printFancyFooters();
//       $ch->close();
}

function getlatest20files() {
    $files = array();
    foreach (glob("/*.*", GLOB_BRACE) as $filename) {
        $files[$filename] = filectime($filename);
    }
    arsort($files);

    $newest = array_slice($files, 0, 5);
    return $newest;  
}

function dirCrawl($path,$profpath,$title){

	 global $_Master_profile_script;

    $ids = array();
   $ids = ListByDate($path);


   $pclass = '';
	$printstyle='';
	echo "<table class=ptables>\n";
        printDetailHeadingRowsWithIcons ("icons/Star.png","$title");
	foreach ($ids as $mid){
	$smid = $mid;
	    $cnt++;
        if ( $cnt&1 ) { $printstyle = 'odd'; } else { $printstyle = ''; }
            if ($_SESSION['lang'] != 'E') { $mid = get_uc($mid,''); }
	    echo "<tr><td class=\"pcells${printstyle}\"><a href=\"$_Master_profile_script?category=$profpath&artist=$smid\">$mid</a></td></tr>";
	}
	echo "</table>";

}
function buildMovieTables($ids,$title,$icon){

    $pclass = '';
	$printstyle='';
	echo "<table class=ptables>\n";
        printDetailHeadingRowsWithIcons ($icon,$title);
	foreach ($ids as $mid){
	    $cnt++;
        if ( $cnt&1 ) { $printstyle = 'odd'; } else { $printstyle = ''; }
	    echo "<tr><td class=\"pcells${printstyle}\">", getMovieString($mid), "</td></tr>";
	}
	echo "</table>";
}


function buildSongTables($ids,$title,$icon,$mode,$pl){

    $pclass = '';
	$printstyle='';
	echo "<table class=ptables>\n";
	printDetailHeadingRowsWithIcons ($icon,$title);
	foreach ($ids as $sid){
	    $cnt++;
            if ( $cnt&1 ) { $printstyle = 'odd'; } else { $printstyle = ''; }
	    echo "<tr><td class=\"pcells${printstyle}\">", getQSongString($sid,$mode,$pl), "</td></tr>";
	}
	echo "</table>";
}
function buildPeopleTables($art,$title,$icon){
	 global $_Master_profile_script;
    $possibleWriteups = array('Actors','Singers','Directors','Composers','Lyricists','Camera','Editors','Producers','Screenplay','Critics');
	$wu_maps = array ('Actors' => 'actors', 'Composers' => 'musician', 'Camera' => 'camera', 'Editors' => 'editor', 'Directors' => 'director', 'Producers' => 'producer', 'Screenplay' => 'screenplay', 'Lyricists' => 'lyricist','Singers'=>'singers','Critics'=> 'critics');
    $pclass = '';

	$printstyle='';
	echo "<table class=ptables>\n";
	printDetailHeadingRowsWithIcons ($icon,$title);
	foreach ($art as $artist){
	
	    $category = '';
	    $link = '';
	    foreach ($possibleWriteups as $pw){
		if (file_exists("Writeups/$pw/$artist.txt")){
		    $category = $pw;
		    $link = "$_Master_profile_script?category=$wu_maps[$pw]&artist=$artist";
		    break;
		}
	    }
	    $cnt++;
	    if ($_SESSION['lang'] != 'E') { $artist = get_uc($artist,''); $pw = get_uc($pw,''); }
	    if ( $cnt&1 ) { $printstyle = 'odd'; } else { $printstyle = ''; }
            if ($link == '') {
		echo "<tr><td class=\"pcells${printstyle}\">$artist ( $pw )</td></tr>";
            }
            else {
		echo "<tr><td class=\"pcells${printstyle}\"><a href=\"$link\">$artist ( $pw )</a></td></tr>";
	    }
	    $pw = '';
	}
    echo "</table>";
}
function _buildArrayFromQuery($qry, $tag){
    $rarray = array();
    $res_funcQry = mysql_query($qry);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    while ($i < $num_funcQry){
	  array_push ($rarray,mysql_result($res_funcQry, $i, "$tag"));
	  $i++;
    }

    return $rarray;
}

function getMovieString ($mid)
{
	global $_Master_movie_script;
    $q = "SELECT M_MOVIE,M_YEAR from MOVIES WHERE M_ID=$mid";
    $res_funcQry = mysql_query($q);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;

    while ($i < $num_funcQry){
		$movie = mysql_result($res_funcQry, $i, "M_MOVIE");
		$yr = mysql_result($res_funcQry, $i, "M_YEAR");
		$o1 = $movie;

		if ($_GET['lang'] != 'E'){
			$movie = get_uc($movie,'');
		}
		$i++;
	}
    if ($movie != ""){
          return "<a href=\"$_Master_movie_script?$mid\">$movie($yr)</a>";
	}
    else {
		return '';
    }

}

function getSongModFile  ($sid)
{
    global $_MasterRootDir;
    global $_RootofMSI,$_RootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $songFile  = findRoot($sid,'Audio',$mode) . '/' . "${sid}.mp3";
    $songLoc   = str_replace("$_RootofMSI","$_RootDir",$songFile);
    if (filesize($songLoc) < 1) {
	$songFile   = str_replace("$_RootofMSI","$_MasterRootDir",$songFile);
	$songFile   = str_replace("http://en.msidb.org","$_MasterRootDir",$songFile);
	$songFile   = str_replace("http://ml.msidb.org","$_MasterRootDir",$songFile);
    }
    $c = filemtime("$songFile");
    if ($c){
	return date("F j, Y, g:i a T", $c);
    }
}

function getQSongString ($sid, $table, $pl)
{
	global $_Master_song_script, $_Master_albumsong_script, $_Master_audioplayer;

	$song_script  = $_Master_song_script;
	$typemode = 'm';
	if ($table == 'ASONGS'){
	     $song_script = $_Master_albumsong_script;
	     $typemode = 'a';
	}
    $q = "SELECT S_SONG,S_MOVIE from $table WHERE S_ID=$sid";
    $res_funcQry = mysql_query($q);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;

    while ($i < $num_funcQry){
		$movie = mysql_result($res_funcQry, $i, "S_MOVIE");
		$song = mysql_result($res_funcQry, $i, "S_SONG");
		$o1 = $movie;

		if ($_GET['lang'] != 'E'){
			$movie = get_uc($movie,'');
			$song = get_uc($song,'');
		}
		$i++;
	}
    if ($movie != ""){
    if ($pl == 1) {
	$user = runQuery("SELECT S_CLIPOWN FROM $table where S_ID=$sid",'S_CLIPOWN');
	if ($_GET['lang'] != 'E') { $user = get_uc($user,''); }
      $listen_str = " <a href=\"$_Master_audioplayer?type=$typemode&id=$sid\" toptions=\"group=links,type = iframe,effect=appear,modal=1,width = 400,height = 125\" title=\"Playing Audio..\"><img src=\"images/listen.jpg\" style=\"opacity:0.3;filter:alpha(opacity=30);\" alt=\"Listen\" border=0> </a> ($user)"; 
    }
    return "<a href=\"$song_script?$sid\">$song ( $movie ) $listen_str</a>";
    }
    else {
		return '';
    }

}



function ListbyDate($directory){

  $list = array();
  $sortOrder="newestFirst";

   $results = array();
   $handler = opendir($directory);
   
   while ($file = readdir($handler)) { 
       if ($file != '.' && $file != '..' && $file != "robots.txt" && $file != ".htaccess"){
           $currentModified = filectime($directory."/".$file);
           $file_names[] = $file;
           $file_dates[] = $currentModified;
       }   
   }
       closedir($handler);

   //Sort the date array by preferred order
   if ($sortOrder == "newestFirst"){
       arsort($file_dates);
   }else{
       asort($file_dates);
   }
   
   //Match file_names array to file_dates array
   $file_names_Array = array_keys($file_dates);
   foreach ($file_names_Array as $idx => $name) $name=$file_names[$name];
   $file_dates = array_merge($file_dates);
   
   $i = 0;

   //Loop through dates array and then echo the list
   foreach ($file_dates as $$file_dates){
       $date = $file_dates;
       $j = $file_names_Array[$i];
       $file = $file_names[$j];
       $i++;
       if ($i > 20) { break; }
       $file = str_replace('.jpg','',$file);
       array_push ($list, "$file");     
   } 
   return $list;
}
function printDetailHeadingRowsWithIcons ($icon,$val){
    if ($_GET['lang'] != 'E') {
	    $val = get_uc("$val","");
	}
    echo "<tr class=tableheader><td style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" colspan=$span><img src=\"$icon\" height=20>&nbsp;$val</td></tr>";
}

?>
