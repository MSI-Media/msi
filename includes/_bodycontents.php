<?php

$_RootDir     = "/home/msidbo6/public_html";
$_MasterRootofMSI  = "http://malayalasangeetham.info";

function bodyBlock(){
	 echo "<aside class=\"body_wrapper\">\n";
	 echo "      <div class=\"main\">\n";
	 echo "        <div class=\"mid_block\">\n";
	 echo "             <div class=\"banner_wrapper\">\n";
	 find3RandomMovies();
	 echo "              </div>\n";
	 randomsongDetails('');
	 echo "<hr class=faded>\n";
	 echo "             <div class=\"banner_wrapper\">\n";
	 find3RandomAlbums();
	 echo "              </div>\n";
	 randomsongDetails('Album');
	 echo "<hr class=faded>\n";
	 printSliders();
	 echo "</aside>\n";
	 echo "<hr class=faded>\n";
}

function printCurrentMonthMovieReleases($events,$title){
	 
	 global $_Master_movie_script;
    $count = 1;
    $datefile = "php/data/MovieDates.txt";
    $fh = fopen($datefile, "r");
    $vals = array ();
    $data = array ();
    if (!$title) { $title ='Notable Dates';}
    if (!$events) { $events = 5; }
    if ($fh){  
	while (!feof($fh)){
	    $ds = fgets($fh,1048576);
	    $ds = ltrim(rtrim($ds));
	    $lx = explode(':',$ds);
	    $month = $lx[0];
	    $date = $lx[1];
	    $year = $lx[2];
	    $mid  = $lx[3];
	    $movie = $lx[4];
	    $_date = date("d") + 1; 
	    $_month = date("m");
	    $_year  = date("y");
	    $years_past = date("Y") - $year;
	    if ($_GET['debug1'] == 1) { echo "$_date  $_month $_year <BR>";  }
	    if ( (($_date >= $date && $_month == $month) || (($_date-$date) == 31 && ($_month+1) == $month) 
		  || ($_date < $date && $_month > $month) ) && $year < 2010) {
		$ms = GetMonthString($month);
		if ($_GET['lang'] != 'E') { $movie = get_uc($movie,''); }
		echo "<a href=\"$_Master_movie_script?$mid\">$ms $date, $year: $movie ($years_past)</a> <br>\n";
		$cnt++;
	    }
	    if ($cnt == $events)  {
		break;
	    }
	    
	}
	echo "</ul>";
    }
}
function GetMonthString($n)
{
    $timestamp = mktime(0, 0, 0, $n, 1, 2005);
    
    return date("M", $timestamp);
}
function askTheExperts(){
    $experts = 'Ask The Experts';
    $exp_file = "Writeups/asktheexperts.txt";
    if ($_GET['lang'] != 'E'){
	$experts = get_uc($experts);
	$exp_file = "Writeups/asktheexperts_malayalam.txt";
    }
    echo "      <h2 class=\"title\">$experts</h2>\n";
    printHtmlContents("$exp_file");
    echo "<hr class=faded>\n";
}
function find3RandomMovies(){
	 global $_Master_movie_script;
    mt_srand(make_seed());
    $r   = mt_rand(0,8000);
    $cnt = 0;
    $pic_array = array();
    while ($cnt < 2) {
	if (file_exists("moviepics/${r}.jpg")){
	    if (!in_array("$r",$pic_array)){
		array_push($pic_array, $r);
		$cnt++;
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,8000);
    }

    $info1 = 'Selected Movie Details';
    if ($_GET['lang'] != 'E') {
	$info1 = get_uc($info1,'');
    }
//  echo "          <div class=\"panel\" id=\"memory\" style=\"float: left; position: relative;\"><h2>$info1</h2></div><p>\n";
    echo "<h2 class=\"leftheader\">$info1</h2>\n";
//  echo "  <div class=\"right-banner-text\">$info1</div>\n";
    $cnt=1;
    echo "<table width=100%><tr>";
    foreach ($pic_array as $p){
//      $movstr = getMovieDetailString ($p);
	$movrat = '';
	$pic = "moviepics/${p}.jpg";
	$rating = runQuery("SELECT rating from RATINGS WHERE mid=$p",'rating');
	if ($rating != ''){
	    $movrat = "<BR><span class=\"rating-static rating-${rating}\"></span>";
	}
	echo "<td width=50% ><a href=\"$_Master_movie_script?$p\"><img style=\"max-width:150px;width:150px;max-height:150px;height:150px\" src=\"$pic\"  border=0></a></td>\n";
	$cnt++;
    }
    echo "<tr></table>";

}


function find3RandomAlbums(){
	 global $_Master_album_script;
    mt_srand(make_seed());
    $r   = mt_rand(0,8000);
    $cnt = 0;
    $pic_array = array();
    while ($cnt < 2) {
	if (file_exists("albumpics/${r}.jpg")){
	    $aid = runQuery("SELECT M_ID FROM ALBUMS WHERE M_ID=$r",'M_ID');
	    if (!in_array("$r",$pic_array) && $aid > 0){
		array_push($pic_array, $r);
		$cnt++;
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,8000);
    }

    $info1 = 'Selected Album Details';
    if ($_GET['lang'] != 'E') {
	$info1 = get_uc($info1,'');
    }
    echo "<h2 class=\"leftheader\">$info1</h2>\n";
    $cnt=1;
    echo "<table width=100%><tr>";
    foreach ($pic_array as $p){
	$pic = "albumpics/${p}.jpg";
	echo "<td width=50% ><a  href=\"$_Master_album_script?$p\"><img src=\"$pic\"  height=\"150\" width=150 border=0></a></td>\n";
	$cnt++;
    }
    echo "<tr></table>";

}


function get_uc ($comp,$tag){

    trim($comp);
 
    $num_results = 0;
	$query = "SELECT unicode from UNICODE_MAPPING WHERE name=\"$comp\";";
	mysql_query("SET NAMES utf8");
	$result      = mysql_query($query);
	if ($result) {
	    $num_results = mysql_num_rows($result);
	    $i=0;
	    while ($i < $num_results){
		$ustr   = mysql_result($result,$i,"unicode");
		$i++;
	    }
	}

	if (!$ustr){
	    $ustr = "";
	}

	$ustr = str_replace("<br>","",$ustr);
	if (!$ustr) {
	    $ustr = $comp;
	}

      
    if (strpos($comp,",") === false) {
    }

    else if ($num_results == 0) {
    
   
        if (strpos($comp,"Raagamalika")) {
               echo "$comp<BR>";
        $rmtag = get_uc("Raagamalika",'') . "(";
        $comp  = str_replace("Raagamalika","",$comp);
        $comp  = str_replace("(","",$comp);
        $comp  = str_replace(")","",$comp);
        $strings = explode(",",$comp);
	$malstrings = array();
	foreach ($strings as $str){
	    $str = ltrim(rtrim($str));
	    $mal_str = runQuery("SELECT unicode from UNICODE_MAPPING where name=\"$str\"","unicode");
	    if (!$mal_str) { $mal_str = $str; }
	    array_push($malstrings,"$mal_str");
	}
	$ustr = $rmtag . implode (",",$malstrings) . ")";
       }
     else {
    
    
	$strings = explode(",",$comp);
	$malstrings = array();
	foreach ($strings as $str){
	    $str = ltrim(rtrim($str));
	    $mal_str = runQuery("SELECT unicode from UNICODE_MAPPING where name=\"$str\"","unicode");
	    if (!$mal_str) { $mal_str = $str; }
	    array_push($malstrings,"$mal_str");
	}
	$ustr = implode (",",$malstrings);
     }
    }
    return $ustr;
}

function runQuery($query,$tag){

    $res_funcQry = mysql_query($query);

    $num_funcQry = mysql_num_rows($res_funcQry);

    $i = 0;

    while ($i < $num_funcQry){

	$val = mysql_result($res_funcQry, $i, "$tag");

	$i++;

    }

    return $val;

}

function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}

function printSliders()
{

    $info1 = 'In Memoriam';
    $info1_1 = 'Milestones';
    $info4 = 'Latest Articles';
    $info2 = 'Media Stream';
    $info3 = 'Down The Memory Lane';

    if ($_GET['lang'] != 'E') {
	$info1 = get_uc($info1,'');
	$info1_1 = get_uc($info1_1,'');
	$info2 = get_uc($info2,'');
	$info3 = get_uc($info3,'');
	$info4 = get_uc($info4,'');
	$info5 = get_uc($info5,'');
    }
    $r   = rand(0,7);

    if ($_GET['debug2013'] == 1) { echo "Random Slider Count : $r<BR>"; }


    echo "<P><div class=\"liquid-slider\" style=\"border-bottom-left-radius:15px;border-bottom-right-radius:15px;border-top-right-radius:15px;\" id=\"slider-id\">\n";
    if ($r == 1) {
	echo " <div>\n";
	echo "      <h2 class=\"title\"> $info1</h2>\n";
	displayObituaries();
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info1_1</h2>\n";
	printCurrentMonthMovieReleases(7,'Milestones');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info3</h2>\n";
	printXLastEvents(6,'Down The Memory Lane');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info2</h2>\n";
	printArticleList(5);
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info4</h2>\n";
	displayArticles();
	echo " </div>\n";
//	printBestWishes();
    }
    else if ($r == 2) {
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info3</h2>\n";
	printXLastEvents(6,'Down The Memory Lane');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\"> $info1</h2>\n";
	displayObituaries();
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info1_1</h2>\n";
	printCurrentMonthMovieReleases(7,'Milestones');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info2</h2>\n";
	printArticleList(5);
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info4</h2>\n";
	displayArticles();
	echo " </div>\n";
//	printBestWishes();
    }
    else if ($r == 3) {
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info2</h2>\n";
	printArticleList(5);
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info3</h2>\n";
	printXLastEvents(6,'Down The Memory Lane');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\"> $info1</h2>\n";
	displayObituaries();
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info1_1</h2>\n";
	printCurrentMonthMovieReleases(7,'Milestones');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info4</h2>\n";
	displayArticles();
	echo " </div>\n";
//	printBestWishes();
    }
    else if ($r == 4) {
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info4</h2>\n";
	displayArticles();
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info2</h2>\n";
	printArticleList(5);
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info3</h2>\n";
	printXLastEvents(6,'Down The Memory Lane');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\"> $info1</h2>\n";
	displayObituaries();
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info1_1</h2>\n";
	printCurrentMonthMovieReleases(7,'Milestones');
	echo " </div>\n";
//	printBestWishes();

    }
    else if ($r == 5){
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info1_1</h2>\n";
	printCurrentMonthMovieReleases(7,'Milestones');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\"> $info1</h2>\n";
	displayObituaries();
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info3</h2>\n";
	printXLastEvents(6,'Down The Memory Lane');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info2</h2>\n";
	printArticleList(5);
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info4</h2>\n";
	displayArticles();
	echo " </div>\n";
//	printBestWishes();
    }
    else {
//	printBestWishes();
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info1_1</h2>\n";
	printCurrentMonthMovieReleases(7,'Milestones');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\"> $info1</h2>\n";
	displayObituaries();
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info3</h2>\n";
	printXLastEvents(6,'Down The Memory Lane');
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info2</h2>\n";
	printArticleList(5);
	echo " </div>\n";
	echo " <div>\n";
	echo "      <h2 class=\"title\">$info4</h2>\n";
	displayArticles();
	echo " </div>\n";

    }
    echo "</div>\n";
}


function printBestWishes()
{

//    if ($_GET['wishes'] == 1) { 
	$info5 = 'Best Wishes';


	$leg   = 'P Susheela - The Legend';
	$wat   = 'Watch Susheela Hits';
	$ytube_img = "images/homepage/Susheela-HomePage.png";
	$ytube     = "ShowWishes.php?artist=P%20Susheela";
//	$ytube     = "http://www.youtube.com/embed/Bhkr4lJust8";
	$link1 = "displayProfile.php?artist=P%20Susheela&category=singers";
	$link2 = "vidsongs.php?tag=Search&singers=P%20Susheela&videos=1&limit=410&alimit=14";
	if ($_GET['lang'] != 'E') { 
	    $info5 = get_uc($info5,'');
	    $leg = get_uc($leg,'');
	    $wat = get_uc($wat,'');
	}

	$leg2   = 'Kavalam Sreekumar';
	$wat2   = 'Listen To Sreekumar Songs';
	$ytube2_img = "images/homepage/Kavalam-HomePage.png";
//	$ytube2 = "http://www.youtube.com/embed/7N0F530dh4I";
	$ytube2 = "ShowWishes.php?artist=Kavalam%20Sreekumar";
	$link12 = "displayProfile.php?artist=Kavalam Sreekumar&category=singers";
	$link22 = "processSearch.php?db=moviesongs&singers=Kavalam%20Sreekumar";
	if ($_GET['lang'] != 'E') { 
	    $leg2 = get_uc($leg2,'');
	    $wat2 = get_uc($wat2,'');
	}

	echo " <div>\n";

	echo "      <h2 class=\"title\"> $info5</h2>\n";
	echo "<table width=100%><tr><td>";
//	echo "<div align=center><iframe width=\"320\" height=\"215\" src=\"$ytube\" frameborder=\"0\"></iframe></div>\n";
	echo "<div align=center><a href=\"$ytube\"><img src=\"$ytube_img\"></div>\n";
        echo "<div class=psubtitlebg><a href=\"$link1\">$leg</a> | <a href=\"$link2\">$wat</a></div>\n";
	echo "</td><td valign=top>\n";
//	echo "<div align=center><iframe width=\"320\" height=\"215\" src=\"$ytube2\" frameborder=\"0\"></iframe></div>\n";
	echo "<div align=center><a href=\"$ytube2\"><img src=\"$ytube2_img\"></div>\n";
        echo "<div class=psubtitlebg><a href=\"$link12\">$leg2</a> | <a href=\"$link22\">$wat2</a></div>\n";
	echo "</td></tr></table>";
	echo " </div>\n";
//    }
}
function printOldSliders()
{

	global $_Master_index;
    echo "<div class=\"clear\"></div><br>	\n";
    echo "  <div class=\"main\">\n";
    echo "     <div id=\"slider\">    \n";
    echo "       <ul class=\"navigation\">\n";

    $info1 = 'In Memoriam';
    $info4 = 'Latest Articles';
    $info2 = 'Media Stream';
    $info3 = 'Down The Memory Lane';
    $info5 = 'Quiz';

    if ($_GET['lang'] != 'E') {
	$info1 = get_uc($info1,'');
	$info2 = get_uc($info2,'');
	$info3 = get_uc($info3,'');
	$info4 = get_uc($info4,'');
	$info5 = get_uc($info5,'');
    }


    echo "<li><a href=\"${_Master_index}#memory\" class=\"selected\">$info1 I</a></li>\n";
    echo "<li><a href=\"${_Master_index}#sites\" class=\"selected\">$info2 I</a></li>\n";
    echo "<li><a href=\"${_Master_index}#files\" class=\"\">$info3 I</a></li>\n";
    echo "<li><a href=\"${_Master_index}#editor\" class=\"\">$info4 </a></li>\n";
    echo "<li><a href=\"${_Master_index}#quiz\" class=\"\">$info5 </a></li>\n";

    echo "      </ul>\n";
    echo "      <img class=\"scrollButtons left\" src=\"./msi2013_setup/scroll_left.png\"><div class=\"scroll\" style=\"overflow: hidden;\">\n";
    echo "      <div class=\"scrollContainer\" style=\"width: 4340px;\">\n";
    echo "          <div class=\"panel\" id=\"memory\" style=\"float: left; position: relative;\"><div class=\"leftheaderinside\">$info1</div><p>\n";
    displayObituaries();
    echo " </p></div>\n";
    echo "          <div class=\"panel\" id=\"sites\" style=\"float: left; position: relative;\"><div class=\"leftheaderinside\">$info2</div><p>\n";
    printArticleList(5);
    echo "</p></div>\n";
    echo "          <div class=\"panel\" id=\"files\" style=\"float: left; position: relative;\"><div class=\"leftheaderinside\">$info3</div><p>\n";
    printXLastEvents(6,'Down The Memory Lane');
    echo "</p></div>\n";
    echo "          <div class=\"panel\" id=\"editor\" style=\"float: left; position: relative;\"><div class=\"leftheaderinside\">$info4</div><p>\n";
    displayArticles();
//    echo "</p></div>\n";
//    echo "          <div class=\"panel\" id=\"quiz\" style=\"float: left; position: relative;\"><div class=\"leftheaderinside\">$info5</div><p>\n";
//    displayDailyQuiz();
    echo "      </div>\n";
    echo "    </div><img class=\"scrollButtons right\" src=\"./msi2013_setup/scroll_right.png\">\n";
    echo "  </div>\n";
    echo "</div>\n";
}    



function randomsongDetails($mode){

    echo "  <div class=\"right-banner-text\">\n";
    if (!$mode){
	$info1 = 'Movie Song Details Being Viewed';
    }
    else {
	$info1 = 'Album Song Details Being Viewed';
    }
    if ($_GET['lang'] != 'E'){
	$info1 = get_uc($info1,'');
    }
    echo "  	<h2 class=\"right_header\" style=\"width:300px;\">$info1\n";
//  echo "       <img style=\"position:absolute; left:1080px;\" src=\"./msi2013_setup/border-img.png\" alt=\"\" height=\"14\" width=\"72\">\n";
    echo "      </h2>\n";
    echo "      <ul class=\"right-banner\">\n";
    if (!$mode){
	find4RandomSongs();
    }
    else {
	find4RandomAlbumSongs();
    }
    echo "      </ul>\n";
//    echo "    	<a class=\"button\" href=\"$_Master_index\"></a>\n";
    echo "  </div>\n";
}




function find4RandomSongs(){
    global $_Master_song_script;
    mt_srand(make_seed());
    $r   = mt_rand(0,19000);
    $cnt = 0;
    $song_array = array();



    while ($cnt < 7) {
	if (!in_array("$r",$song_array)){
	    array_push($song_array, "$r");
	    $songdet = getSongString ($r,'');
	    if ($songdet != ''){
		echo "              <li><a href=\"$_Master_song_script?$r\">$songdet</a></li>\n";
		$cnt++;
                //if ($cnt == 2) { echo "</td><td class=pcells valign=top>\n"; }
               
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,19000);
    }
}
function find4RandomAlbumSongs(){
	 global $_Master_albumsong_script;
    mt_srand(make_seed());
    $r   = mt_rand(0,19000);
    $cnt = 0;
    $song_array = array();


    while ($cnt < 7) {
	if (!in_array("$r",$song_array)){
	    array_push($song_array, "$r");
	    $songdet = getSongString ($r,'Album');
	    if ($songdet != ''){
		echo "              <li><a href=\"$_Master_albumsong_script?$r\">$songdet</a></li>\n";
		$cnt++;
                //if ($cnt == 2) { echo "</td><td class=pcells valign=top>\n"; }
               
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,19000);
    }
}
function getSongString ($sid, $mode)
{
    $table = 'SONGS';
    if ($mode == 'Album') { $table = 'ASONGS'; }
    $q = "SELECT S_SONG,S_MOVIE, S_YEAR from $table WHERE S_ID=$sid";
    $res_funcQry = mysql_query($q);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    while ($i < $num_funcQry){
	$val1 = mysql_result($res_funcQry, $i, "S_SONG");
	$val2 = mysql_result($res_funcQry, $i, "S_MOVIE");
	$val3 = mysql_result($res_funcQry, $i, "S_YEAR");
	if ($_GET['lang'] != 'E'){
	    $val1 = runQuery("SELECT unicode from UNICODE_MAPPING where name = \"$val1\"",'unicode');
	    $val2 = runQuery("SELECT unicode from UNICODE_MAPPING where name = \"$val2\"", 'unicode');
	}
	$i++;
    }
    if ($val1 != ""){
	if ($val2 != "") {
//	    return "<img src=\"images/playicon.gif\" height=18 width=19>$val1 ($val2)";
//	    return "<img src=\"images/redarrow.gif\" >$val1 ($val2)";
	    return "$val1 ($val2)";
	}
//	else {	    return "<img src=\"images/playicon.gif\" height=18 width=19>$val1"; }
//	else {	    return "<img src=\"images/redarrow.gif\">$val1"; }
	else {	    return "$val1"; }
    }
    else {
	return '';
    }

}

function displayObituaries(){
    $artfile = "php/data/memories.txt";

    $fh = fopen($artfile, "r");
    echo "<P><table width=100% border=0><tr align=center>\n";
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != '' && $vals[2] != '' && $vals[3] != '' && $vals[4] != ''){
		if ($_GET['lang'] != 'E') { $vals[1] = get_uc($vals[1],'');}
		echo "<td style=\"font-size:13px\" valign=middle align=center><a href=\"$vals[4]\"><img style=\"-moz-box-shadow: -5px -5px 5px 5px #888;-webkit-box-shadow: -5px -5px 5px 5px#888;box-shadow: -5px -5px 5px 5px #888; border-radius:5px 5px 5px 5px; \" src=\"$vals[3]\" height=100><br>$vals[1] <br>($vals[2])</a></td>\n";
	    }
	}
    }
    echo "</tr></table>";
}
function showArticlesPage() {
    $artfile = "php/data/articles_list.txt";
    $fh = fopen($artfile, "r");
    $author_cnt = 0;
    if ($fh){  
	echo "<table width=80%>\n";
//	echo "<ul>\n";
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $tags = explode (':',$lx);
	    if ($tags[0] == 'AUTHOR'){
		$author_cnt++;
		if ($author_cnt == 3 || $author_cnt == 5){
		    echo "</div></td></tr><tr><td colspan=2></td></tr>";
		}
		else if ($author_cnt == 2 || $author_cnt == 4){
		    echo "</div></td><td></td>";
		}
		echo "<td valign=top><div class=pboxlines>\n";
		$pic = "images/Authors/${tags[1]}.jpg";
		if (file_exists("$pic")){
		    echo "<div align=left><a href=\"#\"><img src=\"$pic\" border=0 height=100 onclick=\"javascript:return false;\" onmousedown=\"if(event.button==2){return false;}\"></a></div><br>\n"; 
		}
		if ($_GET['lang'] != 'E') { $tags[1] = get_uc($tags[1],''); }
		echo "<div class=ptextsmaller>$tags[1]</div>";
	    }
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != ''){
		if ($_GET['lang'] != 'E') { $vals[0] = get_uc($vals[0],''); }
		if ($vals[2] == 'e'){
		    echo "<img src=\"images/arrow.gif\"> <a href=\"$vals[1]\" target=\"_new\">$vals[0] </a><br>\n";
		}
		else {
		    echo "<img src=\"images/arrow.gif\"> <a href=\"$vals[1]\">$vals[0] </a><br>\n";
		}
	    }
	}
	echo "</td></tr></table>";
//	echo "</ul>";
    }
}

function printArticleList($count)
{

	global $_Master_profile_script;
	global $_Master_SubmitArticles_script,$_Master_Articles;
       if (!$count) { $count = 100;
             echo "<table width=100%>\n";
            $t_title = 'Articles';
            $t_source = 'Source';
            $t_user = 'Contributor';
            $t_time = 'Added';
            $t_tags = 'Artists';
            if ($_GET['lang'] != 'E'){ 
               $t_title = get_uc($t_title,'');
               $t_source = get_uc($t_source,'');
               $t_user = get_uc($t_user,'');
               $t_time = get_uc($t_time,'');
               $t_tags = get_uc ($t_tags,'');
            }
            echo "<tr><td class=pleftsubheading valign=top>$t_title</td><td class=pleftsubheading valign=top> $t_tags</td></tr>\n";
 }
       else { $count = $count ; }
       $qry = "SELECT * FROM ARTICLES WHERE status='Y' ORDER BY submitted desc limit $count";
        $res_funcQry = mysql_query($qry); 
        $num_funcQry = mysql_num_rows($res_funcQry); 
        if ($num_funcQry > 1) { 

        $submsg = "Submit an article";
        $complist = "Complete List of articles";
        $i = 0;
        if ($_GET['lang'] != 'E') {
            $submsg = get_uc($submsg,'');
            $complist = get_uc($complist,'');
        }
//	$picicon = "icons/articles.jpg";

        while ($i < $num_funcQry){
           $_title = mysql_result($res_funcQry, $i, "title");
           $_source = mysql_result($res_funcQry, $i, "source");
           $_url = mysql_result($res_funcQry, $i, "url");
           $_user = mysql_result($res_funcQry, $i, "submitter");
           $_stime = mysql_result($res_funcQry, $i, "submitted");
           $_tags = mysql_result($res_funcQry, $i, "tags");
            if ($_GET['lang'] != 'E') {
                $_source = get_uc($_source,'');
                $_user = get_uc($_user,'');
            }
           if ($count > 5) {
              
              $thumblist = "";
              $thumbarray = array();
              $folderlist = array('Actors','Directors','Singers','Musicians','Lyricists');
              foreach ($folderlist as $fldr){
                  $taglist = explode(',',$_tags);
                  //array_push($taglist,$_user);
                  foreach ($taglist as $tl){
                     $tl = ltrim(rtrim($tl));
                     if (file_exists("pics/${fldr}/TN/${tl}.jpg")){
                          if (!in_array("$tl",$thumbarray)){
                              array_push ($thumbarray,"$tl");
                              $catname = strtolower($fldr);
			      if ($catname != 'singers' && $catname != 'actors'){
				  $catname = substr("$catname", 0, -1);	
			      }
                              $thumblist .=  "<a href=\"$_Master_profile_script?artist=$tl&category=$catname\"><img src=\"pics/${fldr}/TN/${tl}.jpg\" height=50 border=0></a>";  
                          }
                            
                     }
                  }
              }

               if ( $i&1 ) {
	            $odd = 'odd';
               }
              //echo "<tr ><td class=\"pcells${odd}\"><a href=\"$_url\" target=\"_new\">$_title</a> <br><i>$t_user : $_user</i> <br><i>$t_source : $_source</i></td><td class=\"pcells${odd}\"> $thumblist</td></tr>\n";
   echo "<tr bgcolor=#eeeeee><td ><a href=\"$_url\" target=\"_new\">$_title</a> <br><i>$t_user : $_user</i> <br><i>$t_source : $_source</i></td><td > $thumblist</td></tr>\n";
              $odd ='';
           }
           else {
//              echo "<img src=\"images/playicon.gif\" height=18 width=19><a href=\"$_url\" target=\"_new\">$_title ($_source) </a><br>\n";
//              echo "<img src=\"images/redarrow.gif\"><a href=\"$_url\" target=\"_new\">$_title ($_source) </a><br>\n";
              echo "<a href=\"$_url\" target=\"_new\">$_title ($_source) </a><br>\n";
         
           }
	   $i++;
        }

         if ($count > 5) {
           echo "</table>";
           echo "<P><P><div align=right><img src=\"images/green_flag.gif\"  border=0> <a href=\"$_Master_SubmitArticles_script\">$submsg</a> ...</div><br>";
         }
         else {
                   echo  " <P><P><div align=right> <a href=\"$_Master_Articles\"> $complist </a> | <a href=\"$_Master_SubmitArticles_script\">$submsg</a> ... </div>";
          }
        }
}
function displayArticles() {
    $artfile = "php/data/articles.txt";


    $info    = "Latest Articles";
    if ($_GET['lang'] != 'E') {
	$info = get_uc($info,'');
    }
/*
    $picicon = "icons/Notebook.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }
*/
    //echo "<P><div class=tableheader> $info </div><P>\n";
    $fh = fopen($artfile, "r");
    if ($fh){  
	echo "<ul>\n";
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != '' && $vals[2] != '' && $vals[3] != ''){
		if ($_GET['lang'] != 'E') { $vals[1] = get_uc($vals[1],''); $vals[3] = get_uc($vals[3],''); }
		//echo "$vals[0] : <a href=\"$vals[2]\" target=\"_new\">$vals[1] ($vals[3])</a><br>\n";
//                echo "$vals[0] : <a href=\"$vals[2]\">$vals[1] </a><br>\n";
                echo "<a href=\"$vals[2]\">$vals[1] </a><br>\n";
	    }
	}
	echo "</ul>";
    }
}

function printXLastEvents($events,$title){

	 global $_Master_profile_script;
    $count = 1;
    $datefile = "php/data/Dates.txt";
    $fh = fopen($datefile, "r");
    $vals = array ();
    $data = array ();
    if (!$title) { $title ='Notable Dates';}
    if ($_GET['lang'] != 'E') { $title = get_uc($title,''); }
    if (!$events) { $events = 5; }
    if ($fh){  
/*

	$picicon = "icons/memorychest.jpg";
	if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	    echo "<div class=tableheader> $title </div>\n";
	}
	else {
	    echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $title </div>\n";
	}
*/
	echo "<ul>\n";
	while (!feof($fh)){
	    $ds = fgets($fh,1048576);
	    $ds = ltrim(rtrim($ds));
	    $lx = explode(':',$ds);
	    $month = $lx[0];
	    $date = $lx[1];
	    $year = $lx[2];
	    $folder = $lx[3];
	    $category = $lx[4];
	    $artist = $lx[5];
	    $type = $lx[6];
			
	    $_date = date("d") + 1; 
	    $_month = date("m");
	    $_year  = date("y");
	    if (($_date >= $date && $_month == $month) || (($_date-$date) == 31 && ($_month+1) == $month) 
		|| ($_date < $date && $_month > $month)) {
		
		$ms = GetMonthString($month);
		if ($_GET['debug1'] == 1) { echo "$_date $date $_month $month <BR>";  }
		if ($_GET['lang'] != 'E') { $artistname = get_uc($artist,''); $folder = get_uc($folder,''); $type = get_uc($type,''); 
		//$ms = get_uc($ms); 
		}
		else { $artistname = $artist; $folder = $folder; $type = $type; $ms = $ms; }
		if ($category == 'singer') { $category = 'singers'; }
		echo "$ms $date,$year: <a href=\"$_Master_profile_script?category=$category&artist=$artist\">$artistname ($folder) </a> - $type<br>\n";
		
		$cnt++;
	    }
	    if ($cnt == $events)  {
		break;
	    }
	    
	}
	echo "</ul>";
    }
}

function getMovieString ($mid)
{
	global $_Master_movie_script;
    $q = "SELECT M_MOVIE,M_YEAR from MOVIES WHERE M_ID=$mid";
    $res_funcQry = mysql_query($q);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    $yrtag  = 'Year';
    $movtag = 'Movie';
    if ($_GET['lang'] != 'E'){
          $movtag = get_uc($movtag,'');
     }
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

function showSmartPhones(){

	 global $_Master_index;
    echo "<P><table width=100%><tr>\n";
    echo "<td align=left><a href=\"$_Master_index?i=android\"><img src=\"ios/android-logo.jpg\" width=150></a></td><td align=right><a href=\"$_Master_index?i=ios\"><img src=\"ios/AppStoreLogo.jpg\" width=150></a></td></tr></table>\n";
}
function getMovieDetailString ($mid)
{

	global $_Master_movie_script;
    $q = "SELECT M_MOVIE,M_DIRECTOR,M_MUSICIAN,M_WRITERS,M_YEAR from MOVIES WHERE M_ID=$mid";
    $res_funcQry = mysql_query($q);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    $dirtag = 'Director';
    $yrtag  = 'Year';
    $mustag = 'Musician';
    $lyrtag = 'Lyricist';
    $movtag = 'Movie';
    if ($_GET['lang'] != 'E'){
          $movtag = get_uc($movtag,'');
          $dirtag = get_uc($dirtag,'');
          $mustag = get_uc($mustag,'');
          $lyrtag = get_uc($lyrtag,'');
     }
    while ($i < $num_funcQry){
	$movie = mysql_result($res_funcQry, $i, "M_MOVIE");
	$yr = mysql_result($res_funcQry, $i, "M_YEAR");
	$dirtr = mysql_result($res_funcQry, $i, "M_DIRECTOR");
	$musician = mysql_result($res_funcQry, $i, "M_MUSICIAN");
	$lyricist = mysql_result($res_funcQry, $i, "M_WRITERS");
	$o1 = $movie;
        $o2 = $dirtr;
        $o3 = $musician;
        $o4 = $lyricist;
	if ($_GET['lang'] != 'E'){
	   $movie = get_uc($movie,'');
	   $dirtr = get_uc($dirtr,'');
	   $musician = get_uc($musician,'');
	   $lyricist = get_uc($lyricist,'');
	}
	$i++;
    }
    $cast = runQuery("SELECT M_CAST FROM MDETAILS WHERE M_ID=$mid",'M_CAST');
    if ($_GET['lang'] != 'E'){ $cast = get_uc($cast,''); }
    $dets = array();
    if ($movie != ""){
	if ($yr > 0 && $musician != 'Uncategorized' && $lyricist != 'Uncategorized' && $dirtr != 'Uncategorized') {

           $movstr=$movie;
	   if ($yr != 'Uncategorized'){
                 $movstr .= "($yr)";
           }

           $movstr = "<P><a href=\"$_Master_movie_script?$mid\">$movstr</a><P>";
           return "<P>$movstr";
	}
	else {	    return "<P><a href=\"$_Master_movie_script?$mid\"><a href=\"$_Master_movie_script?$mid\">$movie($yr)</a></a>";	
 }
     echo "</table>\n";

    }
    else {
	return '';
    }

}

function doGaugeCharts ()
{

    echo "<script type='text/javascript' src='https://www.google.com/jsapi'></script>\n";
    echo "<script type='text/javascript'>\n";
    echo "  google.load('visualization', '1', {packages:['gauge']});\n";
    echo "  google.setOnLoadCallback(drawChart);\n";
    echo "  function drawChart() {\n";
    echo "    var data = new google.visualization.DataTable();\n";
    echo "    data.addColumn('string', 'Label');\n";
    echo "    data.addColumn('number', 'Value');\n";
    echo "    data.addRows([\n";

    $gaugefile = "php/data/gauge_data_malayalam.txt";
    if ($_GET['lang'] == 'E'){
	$gaugefile = "php/data/gauge_data.txt";	
    }
    $fh = fopen($gaugefile, "r");
    $vals = array ();
    $data = array ();
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $vals = explode (':',$lx);
	    if ($vals[0] != '' && $vals[1] != ''){
		$data["$vals[0]"] = $vals[1];
	    }
	}
    }
    $data_elements = array();
    foreach ($data as $k=>$v){
	//echo "['",$k,"', $v],\n";
        array_push ($data_elements,"['",$k,"', $v]");
    }
    echo implode (',',$data_elements);
    echo "    ]);\n";
    echo "    var options = {\n";
    echo "      width: 800, height: 340,\n";
    echo "      redFrom: 90, redTo: 100,\n";
    echo "      yellowFrom:75, yellowTo: 90,\n";
    echo "      minorTicks: 5\n";
    echo "    };\n";

    echo "    var chart = new google.visualization.Gauge(document.getElementById('chart_1'));\n";
    echo "    chart.draw(data, options);\n";
    echo "  }\n";
    echo "</script>\n";
    echo "<div align=center id='chart_1'></div>\n";

}

function doBars($barfile)
{

    echo "    <script type='text/javascript' src='https://www.google.com/jsapi'></script>\n";
    echo "    <script type='text/javascript'>\n";
    echo "    google.load('visualization', '1', {packages:['corechart']});\n";
    echo "google.setOnLoadCallback(drawChart);\n";
    echo "function drawChart() {\n";
    echo "    var data = new google.visualization.DataTable();\n";
    $movies = 'Movies';
    $directors = 'Directors';
    $musicians = 'Musicians';
    $lyricists = 'Lyricists';
    $year = 'Year';
    $title = "Malayalam Movies and Music Through The Decades";
    if ($_GET['lang']!= 'E'){
	$movies = get_uc($movies,'');
	$directors = get_uc($directors,'');
	$musicians = get_uc($musicians,'');
	$lyricists = get_uc($lyricists,'');
	$year = get_uc($year,'');
	$title = get_uc($title,'');
    }
    echo "    data.addColumn('string', \"$year\");\n";
    echo "    data.addColumn('number', \"$movies\");\n";
    echo "    data.addColumn('number', \"$directors\");\n";
    echo "    data.addColumn('number', \"$musicians\");\n";
    echo "    data.addColumn('number', \"$lyricists\");\n";
    echo "        data.addRows([\n";
    $data_elements = array();
    if ($barfile == '') { 
      $barfile = "php/data/bar_data.txt";
    }
    $fh = fopen($barfile, "r");
    $vals = array ();
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    if ($lx != ''){
                array_push($data_elements,"[$lx]");
		//echo "[$lx],\n";
	    }
	}
    }
    echo implode (',',$data_elements);
    echo "		     ]);\n";

    echo "    var options = {\n";
    echo "      title: '$title',\n";
    echo "      hAxis: {title: '$year', titleTextStyle: {color: 'red'}}\n";
    echo "    };\n";
    
    echo "    var chart = new google.visualization.ColumnChart(document.getElementById('chart_2'));\n";
    echo "    chart.draw(data, options);\n";
    echo "}\n";
    echo "    </script>\n";
    echo "    <div align=center id='chart_2' style='width: 100%; height: 500px;'></div>\n";


}

function doLines($category,$data_array, $artist){
    ksort($data_array);
    echo "    <script type='text/javascript' src='https://www.google.com/jsapi'></script>\n";
    echo "    <script type='text/javascript'>\n";
    echo "    google.load('visualization', '1', {packages:['corechart']});\n";
    echo "google.setOnLoadCallback(drawChart);\n";
    echo "function drawChart() {\n";
    echo "    var data = new google.visualization.DataTable();\n";
    $year = 'Year';
    $title = 'Over the Years';
    if ($category != 'singers' && $category != 'musician' && $category != 'lyricist') {
    $movies = 'Movies';
    }
    else {
    $movies = 'Songs';
    }
    if ($_GET['lang'] != 'E'){
	$year = get_uc($year,'');
	$category = get_uc($category,'');
	$artist = get_uc($artist,'');
	$title = get_uc($title,'');
        $movies = get_uc($movies,'');
    }
    echo "    data.addColumn('string', '$year');\n";

    echo "    data.addColumn('number', '$movies');\n";

    echo "        data.addRows([\n";
    $data_elements = array();
    foreach ($data_array as $x => $y){
	//echo "          ['$x', $y],\n";
        array_push($data_elements,"['$x',$y]");
    }
    echo implode (',',$data_elements);
    echo "		     ]);\n";
    echo "\n";
    echo "    var options = {\n";
    echo "      title: '$title'\n";
    echo "    };\n";
    echo "\n";
    $time = time();
    echo "    var chart = new google.visualization.LineChart(document.getElementById('chart_${time}'));\n";
    echo "    chart.draw(data, options);\n";
    echo "}\n";
    echo "    </script>\n";
    echo "   <div align=center id='chart_${time}' style='width: 100%; height: 300px;'></div>\n";



}


function displayDailyQuiz()

{

	global $_Master_quiz_register;
    echo "             <div class=\"banner_wrapper_extended\">\n";    

    $str = time();
    $today = date("d/m/y", $str);
    $quizfile = "php/data/Quiz.txt";
    $placemsg  = 'Your Email';
    $submit   = 'Submit Answer';
    $quizname = 'MSI Daily Quiz';
    $quizlink = 'Participate and Win';
    $quizlink2 = 'MSI Quiz Winners';
    if ($_GET['lang'] != 'E'){
	$quizfile = "php/data/Quiz_Malayalam.txt";
	$placemsg = get_uc($placemsg,'');
	$submit = get_uc($submit,'');
	$quizname = get_uc($quizname);
	$quizlink = get_uc($quizlink);
	$quizlink2 = get_uc($quizlink2,''); 
    }


    $fh = fopen($quizfile, "r");
    $found_day = 0;
    if ($fh){  
	echo "<table class=ptables><tr><td valign=top width=80%>\n";
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $lexus = explode('|',$lx);
	    if ($today == $lexus[0]){
		$found_day = 1;
		echo "      <h2 class=\"title\">$quizname ($lexus[0])</h2>\n";
		echo "      <a href=\"$_Master_quiz_register\">$quizlink,</a> &nbsp;&nbsp;<a href=\"$_Master_quiz_register\">$quizlink2</a> <BR>\n";
		echo "<P>",$lexus[1],"<P>";
		if ($lexus[6] != ''){
		    $comp = $lexus[6];
		    $pos = strpos($comp,".jpg");
		    if ($pos !== false){
			echo "<img src=\"$lexus[6]\" alt=\"Quiz Picture\">\n";
		    }
		    else {
			$pos = strpos($comp,".mp3");
			if ($pos !== false){
			    addQuizAudio($comp);
			}
		    }
		}
	    }
	    if ($found_day){
		echo "<form name=\"myForm\" method=post action=\"$_Master_quiz_register\" onsubmit='javascript:return validateForm();'>\n";
		echo "<input type=hidden name=date value=$today>\n";
		foreach (range(2,5) as $rv){
		    $rval = $rv - 1;
		    echo "<input type=radio name=answers value=\"$rval\">&nbsp;&nbsp;$lexus[$rv]<br>\n";
		}
		echo "<P><input type=text size=30 placeholder=\"$placemsg\" name=email required>\n";
		echo "<P><input type=submit  value=\"$submit\">\n";
		echo "</form>";
		break;
	    }
	}
	echo "</td><td valign=top align=center>\n";
	echo "<a href=\"$_Master_quiz_register\"><img src=\"images/MSI.png\" height=100></a><p>\n";
	echo "</td></tr></table>";
    }
    echo "</div>";
}

function addQuizAudio($songFile)
{
    global $_playerLoc; 
    $browser = new Browser();
    if ($browser->getBrowser() == 'Firefox' && $browser->getVersion() < 19){
	$oldbrowser = 1;
    }
    else if ($browser->getBrowser() == 'MSIE' && $browser->getVersion() < 9){
	$oldbrowser = 1;
    }
    else if ($browser->getPlatform() == 'WinXP' || $browser->getPlatform() == 'Windows'){
	$oldbrowser = 1;
    }
    if ($_GET['debug2013'] ==1) { echo "Running on ", $browser->getPlatform(), ":", $browser->getBrowser(), ":", $browser->getVersion, "<BR>"; }

    if (!$oldbrowser){
	echo "<audio controls height=\"100\" width=\"100\">\n";
	echo "<source src=\"$songFile\" type=\"audio/mpeg\">\n";
	echo "<embed height=\"50\" width=\"100\" src=\"$songFile\">\n";
	echo "</audio>\n";
    }
    else {
	echo "<script language=\"JavaScript\" src=\"$_playerLoc/audio-player.js\"></script>\n";
	echo "<object type=\"application/x-shockwave-flash\" data=\"$_playerLoc/player.swf\" id=\"audioplayer1\" height=\"24\" width=\"400\">\n";
	echo "<param name=\"movie\" value=\"$_playerLoc/player.swf\">\n";
	echo "<param name=\"FlashVars\" value=\"playerID=1&amp;soundFile=$songFile\">\n";
	echo "<param name=\"quality\" value=\"high\">\n";
	echo "<param name=\"menu\" value=\"false\">\n";
	echo "<param name=\"wmode\" value=\"transparent\">\n";
	echo "</object>\n";
    }
}
function printTop3Winners()
{
    $leadmsg = "MSI Quiz Winners";
    $subleadmsg = "Complete List of Winners from Last Month";
    if ($_GET['lang'] != 'E') { 
	$leadmsg = get_uc($leadmsg,''); 
	$subleadmsg = get_uc($subleadmsg,''); 
    }
    echo "<div class=pheading>$leadmsg</div>\n";
    echo "<div class=psubheading><a href=\"registerquiz.php\">$subleadmsg</a></div>\n";

    $winners = "pics/Quiz/winners/112013";
    $sorted_pics = scandir("$winners/photos");
    echo "<table class=ptables>\n";
    echo "<tr>\n";
    $cnt = 0;
    foreach  ($sorted_pics as $p){
	if ($p != '.' && $p != '..') {
	    $pname = str_replace(".jpg",'',$p);
    	    echo "<td width=33% bgcolor=#ffffff valign=top>\n";
	    echo "<div align=center><img src=\"$winners/photos/$p\" height=100 width=100><br>";
	    $pxname = trim(str_replace(range(0,9),'',$pname));
	    if ($_GET['lang'] != 'E') { $pname4dis = get_uc($pxname); } else { $pname4dis = $pxname; }
	    echo "$pname4dis<br></div>";
	    echo "</td>\n";
	    $cnt++;
	    if ($cnt == 3) { break;}
	}
    }
    echo "</tr></table>";
}
?>
