<?php

function printContents($url){

  
    $fh = fopen($url, "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    echo $lx;
	}
	fclose($fh);
    }
    
}
function printContentsWithBreaks($url){

    $fh = fopen($url, "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    echo "$lx<p>";
	}
	fclose($fh);
    }
    
}
function readFileToArray($url) {
    $fc = array();
    $fh = fopen($url, "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    if ($lx != $pl) {
		array_push($fc, $lx);
	    }
	    $pl = $lx;
	}
	fclose($fh);
    }
    return $fc;
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

   if ($_GET["debug"] == 1){
	       echo "$comp<br>";
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
function checkRagaName($raga)
{
    $file = "php/data/ragas.txt";
    $raga_found = 0;
    $fh = fopen("$file", "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $raga = ucfirst(strtolower($raga));
	    if ($lx == $raga){
		$raga = $lx;
		$raga_found = 1;
		break;
	    }
	    else if (soundex($lx) == soundex ($raga)){
		$raga = $lx;
		$raga_found = 1;
		break;
	    }
	}
    }
    if (!$raga_found) { $raga = ''; }
    return $raga;
}
function getRagaList($raga){

    $readmore = "See Songs in This Raga";
    if ($_GET['lang'] != 'E') { $readmore = get_uc($readmore,''); }
    $tag = ucfirst(ltrim(rtrim($raga)));
    $firstlet = strtoupper(substr($raga,0,1));
    $url = "displayProfile.php?category=raga&artist=$raga";
    printLongHeaders ('','Ragas');

    if (file_exists("Ragas101/$firstlet/${tag}.txt")){
	if ($_GET['lang'] != 'E') { $tagx = get_uc($tag,''); } else { $tagx = $tag; }
	$starget = strtolower($target);
	echo "<div class=pcellheads><b><a href=\"$url\">$tagx</a></b></div>\n";
	if (file_exists("Ragas101/$firstlet/${tag}.jpg")){
	    echo "<div align=center><img src=\"Ragas101/$firstlet/${tag}.jpg\" height=200></div><br>";
	}
	echo "<div class=pcells> ", substr (file_get_contents("Ragas101/$firstlet/${tag}.txt"), 0, 10000), "  <P> <a href=\"$url\">$readmore</a> </div>";
    }
    else {
	if (file_exists("Ragas101/$firstlet/${tag}.jpg")){
	    echo "<div align=center><img src=\"Ragas101/$firstlet/${tag}.jpg\" height=300></div><br>";
	}
	if ($_GET['lang'] != 'E') { $tagx = get_uc($raga,''); } else { $tagx = $raga; }
	echo "<div class=pcellheads><b><a href=\"$url\">$tagx</a></b></div>\n";
	echo "<div class=pcells> <P> <a href=\"$url\">$readmore</a> </div>";
    }
}
function checkYoutubeId($id) {
    $ch = curl_init();
    $timeout = 5; // set to zero for no timeout
    curl_setopt ($ch, CURLOPT_URL, "$id");
    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
    $data = curl_exec($ch);
    curl_close($ch);
    if (!$data) return false;
    if ($data == "Video not found") return false;
    if ($data == "Invalid id") return false;
    if ($data == "Private video") return false;
    return true;
}
function url_exists($url) {
  $hdrs = @get_headers($url);
  return is_array($hdrs) ? preg_match("/^HTTP\\/\\d+\\.\\d+\\s+2\\d\\d\\s+.*$/",$hdrs[0]) : false;
}
function dRoot ($sid,$type,$mode){

    $pathdir = "";
    $ext = "";
    global  $_GDMasterRootofMSI ;
    $masterPath = $_GDMasterRootofMSI;


    if ($type == "Lyrics"){
	    $ext = "html";
	      if ($mode == "Movies"){
	        $pathdir = $masterPath . "/" . "Lyrics";
       	}
	    else {
	      $pathdir = $masterPath . "/" . "AlbumLyrics";
      	}
    }
    else if ($type == "Audio"){
	   $ext = "mp3";
	   if ($mode == "Movies"){
	      $pathdir = $masterPath . "/" . "Audio";
	   }
	   else {
	    $pathdir = $masterPath . "/" . "AlbumAudio";
	  }
   }
    if ($_GET["debug"] == 1) { echo "Root of Path for $type/$mode is $pathdir<BR>"; }
   
   $pvs = array ("0","1000","2000","3000","4000","5000","6000","7000","8000","9000","10000","11000","12000","13000","140000","15000","16000","17000","18000","19000","20000","187000","188000","189000","190000","191000","192000","193000","194000","195000","196000","197000","198000","199000","200000");
    $goodpath = '';
    foreach ($pvs as $pv){
   	   if (url_exists("$pathdir/$pv/${sid}.${ext}")){
	       if ($_GET["debug"] == 1) { echo "Root of Path for $type/$mode is $pathdir/$pv/${sid}.${ext}<BR>"; }
	      $goodpath = "$pathdir/$pv";
	   }
    }
	
	return $goodpath;
}

function writeNavigation ($page_num,$page1,$num_pages,$start,$tot_matches,$page_size,$url,$lang){

    $_colspan = 5;
    if ($_GET['missing'] == 'ulv' || $_GET['missing'] == 'mlv'){
	$_colspan = 7;
    }

    if ($num_pages > 6) {
	writeLimitedNavigation ($page_num,$page1,$num_pages,$start,$tot_matches,$page_size,$url,$lang);
    }
    else {
    if ($start==0) { $start=1;}

    echo "<tr><td align=center colspan=$_colspan>\n";

    $end = $start+24;
    if ($end > $tot_matches) { $end = $tot_matches; }

    $str = displaySongTags($start, $end , $tot_matches);
    echo $str;

    echo "<div class=paginate> ";
    if ($page_num > 1){
	$prevpage = $page_num - 1 ;
	echo "<a href=\"${url}&page_num=$prevpage\"><< Prev</a>  "; 
    }

    while ($page1 < $num_pages){
	if ($page1 == $page_num){
	    echo "<a href=\"${url}&page_num=$page1\" class=active>$page1</a>  "; 
	}
	else {
	    echo "<a href=\"${url}&page_num=$page1\">$page1</a>  "; 
	}
	$page1++;
    }

    if ($page_num == $num_pages){
	echo "<a href=\"${url}&page_num=$num_pages\" class=active>$num_pages</a>  ";
    }
    else {
	echo "<a href=\"${url}&page_num=$num_pages\">$num_pages</a>  ";
    }


    if ($page_num < $num_pages){
	$nextpage = $page_num + 1 ;
	echo "<a href=\"${url}&page_num=$nextpage\">Next >></a> ";
    }
    echo "</div><P><P></td></tr>";
    }
}

function writeLimitedNavigation ($page_num,$page1,$num_pages,$start,$tot_matches,$page_size,$url,$lang){


    if ($page_num == "") { $page_num = 1 ; }
    if ($start==0) { $start=1;}

    $_colspan = 5;
    if ($_GET['missing'] == 'ulv' || $_GET['missing'] == 'mlv'){
	$_colspan = 7;
    }

    echo "<tr><td align=center colspan=$_colspan>\n";

    $end = $start+24;
    if ($end > $tot_matches) { $end = $tot_matches; }

    if ($_GET["lang"] == "E"){
	echo "Displaying Songs $start to $end of $tot_matches <BR>";
    }
    else {
	$str = displaySongTags($start, $end , $tot_matches);
	echo $str;
    }
    echo "<div class=paginate> ";

    //-------- << Prev
    if ($page_num > 1){
	$prevpage = $page_num - 1 ;
	echo "<a href=\"${url}&page_num=$prevpage\"><< Prev</a>  "; 
    }


    if ($page_num == 1){
	$current_page = 1;
	$next_page = 2;
	$next2_page = 3;
	echo " <a href=\"${url}&page_num=$prev_page\" class=active>$current_page</a>  "; 
	echo "<a href=\"${url}&page_num=$next_page\" >$next_page</a>  ";	
	echo "<a href=\"${url}&page_num=$next2_page\">$next2_page</a>  ..."; 
       echo "<a href=\"${url}&page_num=$num_pages\">$num_pages</a>  "; 

    }
    else if ($page_num == $num_pages){
	$prev_page = $page_num - 1;
	$prev2_page = $page_num - 2;
	echo " <a href=\"${url}&page_num=1\">1</a> ... "; 
       echo " <a href=\"${url}&page_num=$prev2_page\">$prev2_page</a>  "; 
       echo " <a href=\"${url}&page_num=$prev_page\">$prev_page</a>  "; 
       echo "<a href=\"${url}&page_num=$page_num\" class=active>$page_num</a>  "; 
    }
    else {
       $prev_page     = $page_num - 1;
       $current_page  = $page_num;
       $next_page     = $page_num + 1;
       if ($prev_page != 1) { 
	   echo " <a href=\"${url}&page_num=1\">1</a> ... "; 
       }
       echo " <a href=\"${url}&page_num=$prev_page\">$prev_page</a>  "; 
       echo "<a href=\"${url}&page_num=$current_page\" class=active>$current_page</a>  ";	
       echo "<a href=\"${url}&page_num=$next_page\">$next_page</a>  "; 
       if ($next_page != $num_pages) 
       { 
	   echo " ... "; 
	   echo "<a href=\"${url}&page_num=$num_pages\">$num_pages</a>  "; 
       }
    }

    //------ >> Next
    if ($page_num < $num_pages){
	$nextpage = $page_num + 1 ;
	echo "<a href=\"${url}&page_num=$nextpage\">Next >></a> ";
    }
    echo "</div><P><P></td></tr>";
}



function printLyricsContentsLanguage($url,$lang){
    echo "<div class=ptextleft>\n";
    $fh = fopen($url, "r");
    $adduni=0;
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $origlx = $lx;
	    $lx = stripslashes($lx);

	    $newlx = explode(' ', $origlx);
	    $bar = $newlx[0];
	    if (!$bar) { $bar = $origlx; }
	    if ($lang == "E"){
		if (preg_match("/---/",$lx)){
//		    echo "<div style=\"border-bottom: 1px dotted #000000; width: 100%;\"></div>\n";
		    next;
		}
		else if ($lx == '<br>'){
		    next;
		}
		else if (strlen($bar) != strlen(utf8_decode($bar))){
		    next;
		}
		else if (preg_match("/\&/",$lx)){
		     next;
		}
		else if (preg_match("/Added by/",$lx)){
		    $result = preg_replace("/\<i\>Added (.+)\<\/i\>/", "", $lx);	
		    echo "$result";
		}
		else if (preg_match("/[^a-zA-Z0-9]/", $lx)){
		    echo "$lx";
		}
	    }
	    else {
		if (preg_match("/Added by/",$lx)){
		    $result = preg_replace("/\<i\>Added (.+)\<\/i\>/", "", $lx);	
		    echo "$result";
		    $adduni=1;
		    next;
		}
		else if (preg_match("/a|e|i|o|u|m|s/",$lx) ){
		    if ($adduni == 1) { $adduni = 0; }
		    next;	
		}
		else {
		    if ($adduni == 1){
			echo "$lx";			    
		    }

		}
	    }
	}
	fclose($fh);
    }
    echo "</div>\n";
}

function findRoot ($sid,$type,$mode){

    $pathdir = "";
    global $_MasterRootDir, $_MasterRootofMSI ;

    $masterPath = $_MasterRootDir;
    if ($type == 'Audio') {
	$masterPath = $_MasterRootofMSI;
	$typetag = $type;
    }
    if ($type == 'Lyrics'){
        $ext = "html";
	$typetag = $type;
	if ($mode == 'Movies'){
	    $pathdir = $masterPath . "/" . "Lyrics";
	}
	else {
	    $typetag = "AlbumLyrics";
	    $pathdir = $masterPath . "/" . "AlbumLyrics";
	}
    }
    else if ($type == 'Audio'){
	$ext = 'mp3';
	if ($mode == 'Movies'){
	    $pathdir = $masterPath . "/" . "Audio";
	}
	else {
	    $typetag = "AlbumAudio";
	    $pathdir = $masterPath . "/" . "AlbumAudio";
	}
    }

    $spathval = $sid / 1000;
    if ($spathval < 1){
	$spath = "0";
    }
    else if ($spathval < 2 && $spathval > 1){
	$spath = "1000";
    }
    else if ($spathval < 3 && $spathval > 2){
	$spath = "2000";
    }
    else if ($spathval < 4 && $spathval > 3){
	$spath = "3000";
    }
    else if ($spathval < 5 && $spathval > 4){
	$spath = "4000";
    }
    else if ($spathval < 5 && $spathval > 4){
	$spath = "5000";
    }
    else if ($spathval < 6 && $spathval > 5){
	$spath = "6000";
    }
    else if ($spathval < 7 && $spathval > 6){
	$spath = "7000";
    }
    else if ($spathval < 8 && $spathval > 7){
	$spath = "8000";
    }
    else if ($spathval < 9 && $spathval > 8){
	$spath = "9000";
    }
    else if ($spathval < 10 && $spathval > 9){
	$spath = "10000";
    }
    else if ($spathval < 11 && $spathval > 10){
	$spath = "11000";
    }
    else if ($spathval < 12 && $spathval > 11){
	$spath = "12000";
    }
    else if ($spathval < 13 && $spathval > 12){
	$spath = "13000";
    }
    else if ($spathval < 14 && $spathval > 13){
	$spath = "14000";
    }
    else if ($spathval < 15 && $spathval > 14){
	$spath = "15000";
    }
    else if ($spathval < 16 && $spathval > 15){
	$spath = "16000";
    }
    else if ($spathval < 17 && $spathval > 16){
	$spath = "17000";
    }
    else if ($spathval < 18 && $spathval > 17){
	$spath = "18000";
    }
    else if ($spathval < 19 && $spathval > 18){
	$spath = "19000";
    }
    else if ($spathval < 20 && $spathval > 19){
	$spath = "19000";
    }
    else if ($spathval < 190 && $spathval > 189){
	$spath = "190000";
    }
    else if ($spathval < 191 && $spathval > 190){
	$spath = "191000";
    }
    else if ($spathval < 192 && $spathval > 191){
	$spath = "192000";
    }
    else if ($spathval < 193 && $spathval > 192){
	$spath = "193000";
    }
    else if ($spathval < 194 && $spathval > 193){
	$spath = "194000";
    }
    else if ($spathval < 195 && $spathval > 194){
	$spath = "195000";
    }
    else if ($spathval < 196 && $spathval > 195){
	$spath = "196000";
    }
    else {
	$spath = "20000";
    }

    if (! file_exists("$_MasterRootDir/$spath/${sid}.${ext}")){
	$patharray = array ('0','1000','2000','3000','4000','5000','6000','7000','8000','9000','10000','11000','12000','13000','14000','15000','16000','17000','18000','19000','20000','188000','189000','190000','191000','192000','193000','194000','195000','196000','197000','198000','199000','200000');
	foreach ($patharray as $pa){
	    if (file_exists("$_MasterRootDir/$typetag/$pa/${sid}.${ext}")){
		$spath = $pa;
		break;
	    }
	}
/*
	if ($_GET['debug'] == 1) {
	    if ( !$spath){
		echo "Missing spath since $sid not in $spath<BR>";
	    }
	    else {
		echo "Found file $_MasterRootDir/$typetag/$pa/${sid}.${ext}<BR>";
	    }
	}
*/
    }
    $pathdir = str_replace('en.','',$pathdir);
    $pathdir = str_replace('ml.','',$pathdir);

    if ($_GET['debug4'] == 1){ 
	echo "Returning $pathdir/$spath<BR>";
    }
    
    return $pathdir . "/" . $spath;
}



function getLatestCount($mode){

$counts = array();

$t1 = "MOVIES";
$t2 = "SONGS";
if ($mode == "ALBUM") {
   $t1 = "ALBUMS";
   $t2 = "ASONGS";
}


$query1 =  "SELECT COUNT(S_SONG) FROM $t2;"; 

$query2 =  "SELECT COUNT(M_MOVIE) FROM $t1;"; 

$query3 =  "SELECT COUNT(DISTINCT M_MUSICIAN) FROM $t1;"; 

$query4 =  "SELECT COUNT(DISTINCT M_WRITERS) FROM $t1;"; 

$query5 =  "SELECT COUNT(S_ID) FROM $t2 WHERE S_LYR=\"Y\" or S_MLYR=\"Y\"";

$query6 =  "SELECT COUNT(S_ID) FROM $t2 WHERE S_MLYR=\"Y\"";



array_push($counts,runQuery($query1,"COUNT(S_SONG)"));

array_push($counts,runQuery($query2,"COUNT(M_MOVIE)"));

array_push($counts,runQuery($query3,"COUNT(DISTINCT M_MUSICIAN)"));

array_push($counts,runQuery($query4,"COUNT(DISTINCT M_WRITERS)"));

array_push($counts,runQuery($query5,"COUNT(S_ID)"));

array_push($counts,runQuery($query6,"COUNT(S_ID)"));



return $counts;



}



function getLatestDetailedCount($mode){

$counts = array();

$t1 = "MOVIES";
$t2 = "SONGS";
$t3 = "UTUBE";
$t4 ="PICTURES";

if ($mode == "ALBUM") {
   $t1 = "ALBUMS";
   $t2 = "ASONGS";
   $t3 = "ALBUM_UTUBE";
   $t4 = "APICTURES";
}


$query1 =  "SELECT COUNT(S_SONG) FROM $t2 WHERE M_STATE=\"U\";"; 

$query2 =  "SELECT COUNT(M_MOVIE) FROM $t1 WHERE M_COMMENTS=\"*\";"; 



$query3 =  "SELECT COUNT(S_SONG) FROM $t2 WHERE M_STATE=\"D\";"; 

$query4 =  "SELECT COUNT(M_MOVIE) FROM $t1 WHERE M_COMMENTS=\"Dubbed\";"; 



$query5 =  "SELECT COUNT(DISTINCT M_MOVIE) FROM $t1 WHERE M_MUSICIAN!=\"Uncategorized\";"; 

$query6 =  "SELECT COUNT(DISTINCT M_MOVIE) FROM $t1 WHERE M_WRITERS!=\"Uncategorized\";"; 



$query7 =  "SELECT COUNT(DISTINCT M_MOVIE) FROM $t1 WHERE M_YEAR!=\"Uncategorized\";"; 

$query8 =  "SELECT COUNT(DISTINCT M_MOVIE) FROM $t1 WHERE M_DIRECTOR!=\"Uncategorized\";"; 

$query9 =  "SELECT COUNT(S_ID) FROM $t2 WHERE S_CLIP=\"Y\"";

$query10 =  "SELECT COUNT(DISTINCT S_CLIPOWN) FROM $t2 WHERE S_CLIP=\"Y\" ORDER BY S_CLIPOWN";



$query11 = "SELECT COUNT(UT_ID) FROM $t3 WHERE UT_STAT=\"Published\"";  

$query12 =  "SELECT COUNT(DISTINCT UT_OWN) FROM $t3 ORDER BY UT_OWN";

$query13 =  "SELECT COUNT(S_ID) FROM $t2 WHERE S_KCLIP=\"Y\"";


$query14 = "SELECT COUNT(DISTINCT M_ID) from $t4 WHERE P_STATUS=\"Y\"";

if ($mode != "ALBUM"){
    $query15 = "SELECT COUNT(DISTINCT M_ID) from MD_LINKS";
    $query16 = "SELECT COUNT(P_ID) from PPUSTHAKAM ";
}

array_push($counts,runQuery($query1,"COUNT(S_SONG)"));

array_push($counts,runQuery($query2,"COUNT(M_MOVIE)"));

array_push($counts,runQuery($query3,"COUNT(S_SONG)"));

array_push($counts,runQuery($query4,"COUNT(M_MOVIE)"));

array_push($counts,runQuery($query5,"COUNT(DISTINCT M_MOVIE)"));

array_push($counts,runQuery($query6,"COUNT(DISTINCT M_MOVIE)"));

array_push($counts,runQuery($query7,"COUNT(DISTINCT M_MOVIE)"));

array_push($counts,runQuery($query8,"COUNT(DISTINCT M_MOVIE)"));

array_push($counts,runQuery($query9,"COUNT(S_ID)"));

array_push($counts,runQuery($query10,"COUNT(DISTINCT S_CLIPOWN)"));

array_push($counts,runQuery($query11,"COUNT(UT_ID)"));

array_push($counts,runQuery($query12,"COUNT(DISTINCT UT_OWN)"));

array_push($counts,runQuery($query13,"COUNT(S_ID)"));

array_push($counts,runQuery($query14,"COUNT(DISTINCT M_ID)"));

if ($mode != "ALBUM"){
    array_push($counts,runQuery($query15,"COUNT(DISTINCT M_ID)"));
    array_push($counts,runQuery($query16,"COUNT(P_ID)"));
}



return $counts;



}


function printGoogleFriendConnect() 
{


echo "<script type=\"text/javascript\" src=\"http://www.google.com/friendconnect/script/friendconnect.js\"></script>\n";
echo "<div id=\"div-6121187966292221975\" style=\"width:350px;border:1px solid #cccccc;\"></div>\n";
echo "<script type=\"text/javascript\">\n";
echo "var skin = {};\n";
echo "skin[\"FONT_FAMILY\"] = \"tahoma,sans-serif\";\n";
echo "skin[\"BORDER_COLOR\"] = \"#cccccc\";\n";
echo "skin[\"ENDCAP_BG_COLOR\"] = \"#e0ecff\";\n";
echo "skin[\"ENDCAP_TEXT_COLOR\"] = \"#333333\";\n";
echo "skin[\"ENDCAP_LINK_COLOR\"] = \"#0000cc\";\n";
echo "skin[\"ALTERNATE_BG_COLOR\"] = \"#ffffff\";\n";
echo "skin[\"CONTENT_BG_COLOR\"] = \"#ffffff\";\n";
echo "skin[\"CONTENT_LINK_COLOR\"] = \"#0000cc\";\n";
echo "skin[\"CONTENT_TEXT_COLOR\"] = \"#333333\";\n";
echo "skin[\"CONTENT_SECONDARY_LINK_COLOR\"] = \"#7777cc\";\n";
echo "skin[\"CONTENT_SECONDARY_TEXT_COLOR\"] = \"#666666\";\n";
echo "skin[\"CONTENT_HEADLINE_COLOR\"] = \"#333333\";\n";
echo "skin[\"NUMBER_ROWS\"] = \"4\";\n";
echo "google.friendconnect.container.setParentUrl(\"/\" /* location of rpc_relay.html and canvas.html */);\n";
echo "google.friendconnect.container.renderMembersGadget(\n";
echo " { id: \"div-6121187966292221975\",\n";
if ($_GET["lang"] != "E"){
   echo "  locale: \"ml\",\n";
}
echo "   site: \"05216516822682914964\" },\n";
echo "  skin);\n";
echo "</script>\n";

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
//	    return "<img src=\"images/playicon.gif\" height=18 width=19>&nbsp;$val1 ($val2)";
//	    return "<img src=\"images/redarrow.gif\" >&nbsp;$val1 ($val2)";
	    return "&nbsp;$val1 ($val2)";
	}
//	else {	    return "<img src=\"images/playicon.gif\" height=18 width=19>&nbsp;$val1"; }
//	else {	    return "<img src=\"images/redarrow.gif\">&nbsp;$val1"; }
	else {	    return "&nbsp;$val1"; }
    }
    else {
	return '';
    }

}
function getSongDetailString ($sid)
{
    $q = "SELECT S_SONG,S_MOVIE , S_YEAR from SONGS WHERE S_ID=$sid";
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
    if ($val1 != "" && $val2 != ""){
	if ($val3 > 0) {
	    return "$val1 ( $val2 $val3)";
	}
	else {	    return "$val1 ( $val2 )"; }
    }
    else {
	return '';
    }

}

function printArticleList($count)
{

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
        $info = "Media Stream";
        $submsg = "Submit an article";
        $complist = "Complete List of articles";
        $i = 0;
        if ($_GET['lang'] != 'E') {
            $info = get_uc($info,'');
            $submsg = get_uc($submsg,'');
            $complist = get_uc($complist,'');
        }
	$picicon = "icons/articles.jpg";
	if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	    echo "<div class=tableheader> $info </div>\n";
	}
	else {
	    echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
	}
        while ($i < $num_funcQry){
           $_title = mysql_result($res_funcQry, $i, "title");
           $_source = mysql_result($res_funcQry, $i, "source");
           $_url = mysql_result($res_funcQry, $i, "url");
           $_user = mysql_result($res_funcQry, $i, "submitter");
           $_stime = mysql_result($res_funcQry, $i, "submitted");
           $_tags = mysql_result($res_funcQry, $i, "tags");
            if ($_GET['lang'] != 'E') {
                $_source = get_uc($_source);
                $_user = get_uc($_user);
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
                              $thumblist .=  "<a href=\"displayProfile.php?artist=$tl&category=$catname\"><img src=\"pics/${fldr}/TN/${tl}.jpg\" height=50 border=0></a>";  
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
//              echo "<img src=\"images/playicon.gif\" height=18 width=19>&nbsp;<a href=\"$_url\" target=\"_new\">$_title ($_source) </a><br>\n";
//              echo "<img src=\"images/redarrow.gif\">&nbsp;<a href=\"$_url\" target=\"_new\">$_title ($_source) </a><br>\n";
              echo "&nbsp;<a href=\"$_url\" target=\"_new\">$_title ($_source) </a><br>\n";
         
           }
	   $i++;
        }
         if ($count > 5) {
           echo "</table>";
           echo "<P><P><div align=right><img src=\"images/green_flag.gif\"  border=0> &nbsp;<a href=\"submitArticles.php\">$submsg</a> ...</div><br>";
         }
         else {
                   echo  " <P><P><div align=right> <a href=\"articles.php\"> $complist </a> | <a href=\"submitArticles.php\">$submsg</a> ... </div>";
          }
        }
}
function getAlbumDetailString ($mid)
{
    $q = "SELECT M_MOVIE,M_DIRECTOR,M_MUSICIAN,M_WRITERS,M_YEAR from ALBUMS WHERE M_ID=$mid";
    $res_funcQry = mysql_query($q);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    $dirtag = 'Label';
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
    if ($movie != ""){
	if ($yr > 0 && $musician != 'Uncategorized' && $lyricist != 'Uncategorized' && $dirtr != 'Uncategorized') {
          return "$movtag:<a href=\"a.php?$mid\">$movie($yr)</a><br>$dirtag:$dirtr<br>";
	}
	else {	     return "$movtag:<a href=\"m.php?$mid\">$movie($yr)</a>"; }
    }
    else {
	return '';
    }

}
function getMovieDetailString ($mid)
{
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
    if ($movie != ""){
	if ($yr > 0 && $musician != 'Uncategorized' && $lyricist != 'Uncategorized' && $dirtr != 'Uncategorized') {
	   // return "$movtag:<a href=\"m.php?$mid\">$movie($yr)</a><br>$dirtag:<a href=\"displayProfile.php?category=director&artist=$o2\">$dirtr</a><br>$mustag:<a href=\"displayProfile.php?category=musician&artist=$o3\">$musician</a><br>$lyrtag:<a href=\"displayProfile.php?category=lyricist&artist=$o4\">$lyricist</a><BR>";
          return "$movtag:<a href=\"m.php?$mid\">$movie($yr)</a><br>$dirtag:<a href=\"displayProfile.php?category=director&artist=$o2\">$dirtr</a><br>";
	}
	else {	     return "$movtag:<a href=\"m.php?$mid\">$movie($yr)</a>"; }
    }
    else {
	return '';
    }

}
function getMovieNameForTitle ($mid, $mode)
{
    $mid = str_replace("mid=","",$mid);
    if ($mid > 0) {
	$query       = "SELECT M_MOVIE,M_YEAR FROM $mode WHERE M_ID=$mid";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $movie_name = mysql_result($res_funcQry, $i, "M_MOVIE");
	    $year       = mysql_result($res_funcQry, $i, "M_YEAR");
	    if ($_GET['lang'] != 'E'){
		$movie_name = get_uc($movie_name,'');
	    }
	    $i++;
	}
	return "$movie_name [$year]";
    }
    else {
	return "";
    }

}
function printNewAlbumsList($cnt){

    if ($cnt > 0) {
	$query       = "SELECT M_ID,M_MOVIE,M_YEAR,M_MUSICIAN,M_WRITERS FROM ALBUMS ORDER BY M_ID DESC LIMIT $cnt";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$info = 'Latest Albums';
	if ($_GET['lang'] != 'E'){
	    $info = get_uc($info,'');
	}
	$picicon = "icons/Movie.png";
	if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){	
	    echo "<P><div class=tableheader> $info </div><P>\n";
	}
	else {
	    echo "<P><div class=tableheadernobg><img src=\"$picicon\" align=middle height=20> $info </div><P>\n";
	}
    }
    $i = 0;
    while ($i < $num_funcQry){
	$alb = mysql_result($res_funcQry, $i, "M_MOVIE");
	$yr  = mysql_result($res_funcQry, $i, "M_YEAR");
	$mus  = mysql_result($res_funcQry, $i, "M_MUSICIAN");
	$lyr  = mysql_result($res_funcQry, $i, "M_WRITERS");
	$mid  = mysql_result($res_funcQry, $i, "M_ID");
	$_url = "a.php?$mid";
	if ($_GET['lang'] != 'E')
	{ 
	    $alb = get_uc($alb,''); 
	}
	if ($yr < 1) { 
	    $yr = ''; 
	} 
	else { 
	    $yr = "($yr)"; 
	}
	echo "<img src=\"images/playicon.gif\" height=18 width=19>&nbsp;<a href=\"$_url\">$alb $yr </a><br>\n";
	$i++;
    }
}

function getSongNameForTitle ($sid, $mode)
{

    if ($sid > 0) {
	$query       = "SELECT S_SONG,S_MOVIE,S_YEAR FROM $mode WHERE S_ID=$sid";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $song_name  = mysql_result($res_funcQry, $i, "S_SONG");
	    $movie_name = mysql_result($res_funcQry, $i, "S_MOVIE");
	    if ($_GET['lang'] != 'E'){
		$movie_name = get_uc($movie_name,'');
		$song_name = get_uc($song_name,'');
	    }
	    $year       = mysql_result($res_funcQry, $i, "S_YEAR");
	    $i++;
	}
    if ($_GET['debug3'] == 1) { echo "$song_name $movie_name $year<BR>";}

	return "$song_name ($movie_name [$year])";
    }
    else {
	return "";
    }

}
function find3RandomMovies(){
    mt_srand(make_seed());
    $r   = mt_rand(0,6945);
    $cnt = 0;
    $pic_array = array();
    while ($cnt < 2) {
	if (file_exists("moviepics/TNs/pic_${r}.jpg")){
	    if (!in_array("$r",$pic_array)){
		array_push($pic_array, $r);
		$cnt++;
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,6945);
    }
    $info = 'Movie Details Being Viewed';

    if ($_GET['lang'] != 'E'){
	$info = get_uc($info,'');
    }
    $picicon = "icons/Movie.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }
    echo "<div align=left>\n";
    echo "<table class=ptables><tr>\n";
    foreach ($pic_array as $p){
        $movstr = getMovieDetailString ($p);
	$pic = "moviepics/TNs/pic_${p}.jpg";
	echo "<td valign=top align=center><a href=\"m.php?$p\"><img class=shadow src=\"$pic\" border=0 height=75></a><td valign=top style=\"padding-left:5px;font-style:italic\"><br>$movstr</td></td>\n";
    }
    echo "</tr></table>";
    echo "</div>\n";
}

function find3RandomAlbums(){
    mt_srand(make_seed());
    $r   = mt_rand(0,6945);
    $cnt = 0;
    $pic_array = array();
    while ($cnt < 2) {
	if (file_exists("albumpics/TNs/pic_${r}.jpg") && file_exists("albumpics/${r}.jpg")){
	    if (!in_array("$r",$pic_array)){
		array_push($pic_array, $r);
		$cnt++;
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,6945);
    }
    $info = 'Album Details Being Viewed';
    if ($_GET['lang'] != 'E'){
	$info = get_uc($info,'');
    }
    $picicon = "icons/Picture.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){	
	echo "<P><div class=tableheader> $info </div><P>\n";
    }
    else {
	echo "<P><div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div><P>\n";
    }
    echo "<div align=left>\n";
    echo "<table class=ptables><tr>\n";
    foreach ($pic_array as $p){
        $movstr = getAlbumDetailString ($p);
	$pic = "albumpics/TNs/pic_${p}.jpg";
	echo "<td valign=top align=center><a href=\"a.php?$p\"><img class=shadow src=\"$pic\" border=0 height=75></a><td valign=top style=\"padding-left:5px;font-style:italic\"><br>$movstr</td></td>\n";
    }
    echo "</tr></table>";
    echo "</div>\n";
}

function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return (float) $sec + ((float) $usec * 100000);
}
function displayArticles() {
    $artfile = "php/data/articles.txt";


    $info    = "Latest Articles";
    if ($_GET['lang'] != 'E') {
	$info = get_uc($info,'');
    }
    $picicon = "icons/Notebook.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }

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
                echo "$vals[0] : <a href=\"$vals[2]\">$vals[1] </a><br>\n";
	    }
	}
	echo "</ul>";
    }
}
function showArticlesPage() {
    $artfile = "php/data/articles_list.txt";
    $info    = "Selected List of Articles";
    if ($_GET['lang'] != 'E') {
	$info = get_uc($info,'');
    }
    echo "<P><div class=tableheader> $info </div><P>\n";
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
		    echo "</div></td></tr><tr><td colspan=2>&nbsp;</td></tr>";
		}
		else if ($author_cnt == 2 || $author_cnt == 4){
		    echo "</div></td><td>&nbsp;</td>";
		}
		echo "<td valign=top>&nbsp;&nbsp;<div class=pboxlines>\n";
		$pic = "images/Authors/${tags[1]}.jpg";
		if (file_exists("$pic")){
		    echo "<div align=left><a href=\"#\" class=\"shadow\"><img src=\"$pic\" border=0 height=100 onclick=\"javascript:return false;\" onmousedown=\"if(event.button==2){return false;}\"></a></div><br>\n"; 
		}
		if ($_GET['lang'] != 'E') { $tags[1] = get_uc($tags[1],''); }
		echo "<div class=ptextsmaller>$tags[1]</div>";
	    }
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != ''){
		if ($_GET['lang'] != 'E') { $vals[0] = get_uc($vals[0],''); }
		if ($vals[2] == 'e'){
		    echo "<img src=\"images/arrow.gif\">&nbsp; <a href=\"$vals[1]\" target=\"_new\">$vals[0] </a><br>\n";
		}
		else {
		    echo "<img src=\"images/arrow.gif\">&nbsp; <a href=\"$vals[1]\">$vals[0] </a><br>\n";
		}
	    }
	}
	echo "</td></tr></table>";
//	echo "</ul>";
    }
}
function displayObituaries(){
    $artfile = "php/data/memories.txt";
    $info    = "In Memoriam";
    if ($_GET['lang'] != 'E') {
	$info = get_uc($info,'');
    }
    $picicon = "icons/Star.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }
    $fh = fopen($artfile, "r");
    echo "<table width=100% border=0><tr align=center>\n";
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != '' && $vals[2] != '' && $vals[3] != '' && $vals[4] != ''){
		if ($_GET['lang'] != 'E') { $vals[1] = get_uc($vals[1],'');}
		echo "<td align=center><a href=\"$vals[4]\"><img src=\"$vals[3]\" height=100><br>$vals[1] ($vals[2])</a>&nbsp;</td>\n";
	    }
	}
    }
    echo "</tr></table>";
}
function displayProfiles() {
    $artfile = "php/data/profiles.txt";
    $info    = "Up Close and Personal";
    if ($_GET['lang'] != 'E') {
	$info = get_uc($info,'');
    }
    $picicon = "icons/Star.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }

    $fh = fopen($artfile, "r");
    if ($fh){  
//	echo "<ul>\n";
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != '' && $vals[2] != '' && $vals[3] != ''){
		//echo "<li class=pcells><a href=\"$vals[1]\">$vals[0]</a></li>\n";
		if ($_GET['lang'] != 'E') { $vals[1] = get_uc($vals[1],''); $vals[3] = get_uc($vals[3],''); }
//		echo "<img src=\"images/redarrow.gif\">&nbsp;<a href=\"$vals[2]\" target=\"_new\">$vals[1] ($vals[3])</a><br>\n";
		echo "<a href=\"$vals[2]\">$vals[1] ($vals[3])</a><br>\n";
	    }
	}
//	echo "</ul>";
    }
}
function find4RandomSongs(){
    mt_srand(make_seed());
    $r   = mt_rand(0,19000);
    $cnt = 0;
    $song_array = array();
    $info = 'Movie Song Details Being Viewed';
    if ($_GET['lang'] != 'E'){
	$info = get_uc($info,'');
    }
    $picicon = "icons/Music.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }

//    echo "<P><div class=tableheader> $info </div><P>\n";
    echo "<table class=ptables><tr><td valign=top>\n";
    //echo "<div class=pcells>\n";
    while ($cnt < 6) {
	if (!in_array("$r",$song_array)){
	    array_push($song_array, "$r");
	    $songdet = getSongString ($r,'');
	    if ($songdet != ''){
		echo "<a href=\"s.php?$r\">$songdet</a><br>\n";
		$cnt++;
                //if ($cnt == 2) { echo "</td><td class=pcells valign=top>\n"; }
               
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,19000);
    }
/*
    echo "</td><td class=pcells valign=top>\n";
    $cnt=0;
    while ($cnt < 3) {
	if (!in_array("$r",$song_array)){
	    array_push($song_array, "$r");
	    $songdet = getSongString ($r,'Album');
	    if ($songdet != ''){
		echo "<a href=\"as.php?$r\">$songdet</a><br>\n";
		$cnt++;
                //if ($cnt == 2) { echo "</td><td class=pcells valign=top>\n"; }
               
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,19000);
    }
    //echo "</div>\n";
*/
    echo "</td></tr></table>\n";
}

function printPlaylists($titl){

    if ($_GET['lang'] != 'E') { $titl = get_uc($titl,''); }
    echo "<div class=tableheader> $titl </div>\n";
    $artfile = "php/data/playlists.txt";
    $fh = fopen($artfile, "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != '' ){
		if ($_GET['lang'] != 'E') { $vals[0] = get_uc($vals[0],''); }
		echo "<img src=\"images/redarrow.gif\">&nbsp;<a href=\"$vals[1]\">$vals[0]</a><br>\n";
	    }
	}
    }
}

function find4RandomAlbumSongs(){
    mt_srand(make_seed());
    $r   = mt_rand(0,19000);
    $cnt = 0;
    $song_array = array();
    $info = 'Album Song Details Being Viewed';
    if ($_GET['lang'] != 'E'){
	$info = get_uc($info,'');
    }
    $picicon = "icons/lightmusic.jpg";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }

    //echo "<P><div class=tableheader> $info </div><P>\n";
    echo "<table class=ptables><tr><td valign=top>\n";
    $cnt=0;
    while ($cnt < 5) {
	if (!in_array("$r",$song_array)){
	    array_push($song_array, "$r");
	    $songdet = getSongString ($r,'Album');
	    if ($songdet != ''){
		echo "<a href=\"as.php?$r\">$songdet</a><br>\n";
		$cnt++;
                //if ($cnt == 2) { echo "</td><td class=pcells valign=top>\n"; }
               
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,19000);
    }
    //echo "</div>\n";
    echo "</td></tr></table>\n";
}
function showSmartPhones(){
    $info = "MSI on Smart Phones";
    if ($_GET['lang']!='E') { $info = get_uc($info,'');}
    $aupldmsg = 'Downloading Now ...';
    $iupldmsg = 'Over 5000+ Downloads';
    if ($_GET['lang'] != 'E'){
	$aupldmsg = get_uc($aupldmsg,'');
	$iupldmsg = get_uc($iupldmsg,'');
    }
    $picicon = "icons/smartphone.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }
    echo "<table width=80%><tr>\n";
    echo "<td align=left><a href=\"index.php?i=android\"><img src=\"ios/android-logo.jpg\" width=150></a><br>$aupldmsg</td><td align=right><a href=\"index.php?i=ios\"><img src=\"ios/AppStoreLogo.jpg\" width=150></a><br>$iupldmsg</td></tr></table>\n";
    echo "</div>";
}
function find3RandomArtists(){

    mt_srand(make_seed());
    $r   = mt_rand(0,9954);
    $cnt = 0;
    $artist_array = array();
    $info = 'Learn More About These Artists';
    if ($_GET['lang'] != 'E'){
	$info = get_uc($info,'');
    }
//   echo "<div class=tableheader> $info </div><P>\n";
    $picicon = "icons/Picture.png";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $info </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $info </div>\n";
    }

    echo "<table class=ptables><tr>\n";
    while ($cnt < 2) {
	if (!in_array("$r",$artist_array)){
	    array_push($artist_array, "$r");
	    $artdet = getArtistDetailString ($r);
	    if ($artdet != ''){
		echo "$artdet\n";
		$cnt++;
	    }
	}
	mt_srand(make_seed());
	$r   = mt_rand(0,9954);
    }
    echo "</tr></table>\n";
/*
    $info = 'Our Rememberences';
    $art = 'D Vinayachandran';

   if ($_GET['lang'] != 'E') { 	$info = get_uc($info,''); $artname = get_uc($art,'');  } else {     $artname = $art ; }
    echo "<div class=tableheader> $info </div><P>\n";

   echo "<table class=ptables><tr>\n";
   echo "<td align=center><a href=\"displayProfile.php?category=lyricist&artist=$art\"><img src=\"pics/Lyricists/${art}.jpg\" height=100 width=100 class=shadow><BR>$artname (1946-2013)</a></td>\n";


   echo "</tr></table>\n";
*/


}
function getOGImagesArtist ($scr, $artist, $category) 
{
    $images = array();
    $mpic = "pics";
    if ($_GET['debug3'] == 1) { echo "$artist $category <BR>"; }
    $images = ogImageParser($images,$artist,$category);
    return array_unique($images);
}

function getOGImages ($scr, $sid) 
{

    $images = array();
    if ($scr == 's.php') { $table = 'SONGS'; $mpic  = 'moviepics'; $blogpics = "_PhotosfrmBlog";}
    else { $table = 'ASONGS'; $mpic = 'albumpics'; $blogpics = "_aPhotosfrmBlog";}

    $singers=runQuery("SELECT S_SINGERS FROM $table where S_ID=$sid",'S_SINGERS');
    $images = ogImageParser($images,$singers,'Singers');
    $music=runQuery("SELECT S_MUSICIAN FROM $table where S_ID=$sid",'S_MUSICIAN');
    $images =  ogImageParser($images,$music,'Musicians');
    $lyrics=runQuery("SELECT S_WRITERS FROM $table where S_ID=$sid",'S_WRITERS');
    $images = ogImageParser($images,$lyrics,'Lyricists');
    $movie=runQuery("SELECT DISTINCT M_ID FROM $table where S_ID=$sid",'M_ID');
    if (file_exists("$mpic/${movie}.jpg")){
	array_push ($images, "http://malayalasangeetham.info/$mpic/${movie}.jpg");
    }

    foreach (range (0,10) as $num){
	if (file_exists("$blogpics/${movie}_${num}.jpg")){
	    array_push ($images, "http://malayalasangeetham.info/$blogpics/${movie}_${num}.jpg");
	}
    }

    return array_unique($images);
}

function ogImageParser($mstr, $dstr, $tag) 
{
    if (strpos($dstr,",") !== false){
	$_dstr = explode (',',$dstr);
	foreach ($_dstr as $_s){
	    $_s = ltrim(rtrim($_s));
	    if ($_GET['debug3'] == 1) { echo "Enlarged/$tag/${_s}_e.jpg<BR>";}
	    if (file_exists("Enlarged/$tag/${_s}.jpg")){
		array_push ($mstr, "http://malayalasangeetham.info/Enlarged/$tag/${_s}.jpg");
	    }
	    else if (file_exists("Enlarged/$tag/${_s}_e.jpg")){
		array_push ($mstr, "http://malayalasangeetham.info/Enlarged/$tag/${_s}_e.jpg");
	    }
	}
    }
    else {
         if ($_GET['debug3'] == 1) { echo "Enlarged/$tag/${dstr}_e.jpg<BR>";}
	if (file_exists("Enlarged/$tag/${dstr}.jpg")){
	    array_push ($mstr, "http://malayalasangeetham.info/Enlarged/$tag/${dstr}.jpg");
	}
	else   if (file_exists("Enlarged/$tag/${dstr}_e.jpg")){
	    array_push ($mstr, "http://malayalasangeetham.info/Enlarged/$tag/${dstr}_e.jpg");
	}
    }
    if ($_GET['debug3'] == 1) {  print_r($mstr); }    
    return $mstr;
}
function getArtistDetailString ($r){
	
	$art = runQuery("SELECT name from ARTISTS WHERE id=$r",'name');
	$cat = runQuery("SELECT category from ARTISTS WHERE id=$r",'category');
	if ($cat == 'Story') { $wp = 'Screenplay'; $pp = $wp; $linkcat = 'story'; }
	else if ($cat == 'Musicians') { $wp = 'Composers'; $pp = $cat; $linkcat = 'musician'; }
        else { $wp = $cat; $pp = $cat; $linkcat = strtolower($cat);  }
        if ($cat == 'Directors') { $linkcat = 'director'; }
        else if ($cat == 'Lyricists') { $linkcat = 'lyricist'; }
        else if ($cat == 'Producers') { $linkcat = 'producer'; }
        if (file_exists("pics/${pp}/${art}.jpg")){
	   $artname=$art;
	   if ($_GET['lang'] != 'E') { $artname = get_uc($artname,'');  }
	  
	   return "<td align=center><a href=\"displayProfile.php?category=$linkcat&artist=$art\"><img src=\"pics/${pp}/${art}.jpg\" height=75 class=shadow><BR>$artname</a></td>\n";
	}
	else {
	  return;
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
function printProfileHeaders($a1,$a2,$a3,$a4,$a5,$a6,$a7)
{
    if ($a1 == '') { $a1 = 'Artist';}
    if ($a2 == '') { $a2 = 'Movies'; }
    if ($_GET['lang'] != 'E') { $a1 = get_uc($a1,''); $a2 = get_uc($a2,'');  $a3 = get_uc($a3,''); $a4 = get_uc($a4,''); $a4 = get_uc($a4,''); $a5 = get_uc($a5,'');$a6 = get_uc($a6,''); $a7=get_uc($a7,'');}
    if ($a7) {
	echo "<tr><td class=tableheader>$a1</td><td class=tableheader>$a2</td><td class=tableheader>$a3</td><td class=tableheader>$a4</td><td class=tableheader>$a5</td><td class=tableheader>$a6</td><td class=tableheader>$a7</td></tr>\n";
    }	
    else if ($a6) {
	echo "<tr><td class=tableheader>$a1</td><td class=tableheader>$a2</td><td class=tableheader>$a3</td><td class=tableheader>$a4</td><td class=tableheader>$a5</td><td class=tableheader>$a6</td></tr>\n";
    }	
    else if ($a5) {
	echo "<tr><td class=tableheader>$a1</td><td class=tableheader>$a2</td><td class=tableheader>$a3</td><td class=tableheader>$a4</td><td class=tableheader>$a5</td></tr>\n";
    }	
    else if ($a4) {
	echo "<tr><td class=tableheader>$a1</td><td class=tableheader>$a2</td><td class=tableheader>$a3</td><td class=tableheader>$a4</td></tr>\n";
    }	
    else if ($a3) {
	echo "<tr><td class=tableheader>$a1</td><td class=tableheader>$a2</td><td class=tableheader>$a3</td></tr>\n";
    }
    else {
	echo "<tr><td class=tableheader>$a1</td><td class=tableheader>$a2</td></tr>\n";
    }
}
function printXLastEvents($events,$title){
    $count = 1;
    $datefile = "php/data/Dates.txt";
    $fh = fopen($datefile, "r");
    $vals = array ();
    $data = array ();
    if (!$title) { $title ='Notable Dates';}
    if ($_GET['lang'] != 'E') { $title = get_uc($title); }
    if (!$events) { $events = 5; }
    if ($fh){  
	$picicon = "icons/memorychest.jpg";
	if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	    echo "<div class=tableheader> $title </div>\n";
	}
	else {
	    echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $title </div>\n";
	}

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
		if ($_GET['lang'] != 'E') { $artistname = get_uc($artist); $folder = get_uc($folder); $type = get_uc($type); 
		//$ms = get_uc($ms); 
		}
		else { $artistname = $artist; $folder = $folder; $type = $type; $ms = $ms; }
		if ($category == 'singer') { $category = 'singers'; }
		echo "<li class=pcellsbgleft>$ms $date,$year: <a href=\"displayProfile.php?category=$category&artist=$artist\">$artistname ($folder) </a> - $type</li>\n";
		
		$cnt++;
	    }
	    if ($cnt == $events)  {
		break;
	    }
	    
	}
	echo "</ul>";
    }
}

function printCurrentMonthMovieReleases($events,$title){
    $count = 1;
    $datefile = "php/data/MovieDates.txt";
    $fh = fopen($datefile, "r");
    $vals = array ();
    $data = array ();
    if (!$title) { $title ='Notable Dates';}
    if ($_GET['lang'] != 'E') { $title = get_uc($title); }
    if (!$events) { $events = 5; }
    if ($fh){  
	$picicon = "icons/milestone.png";
	if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	    echo "<div class=tableheader> $title </div>\n";
	}
        else {
           echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $title </div>\n";
        }

	echo "<ul>\n";
	while (!feof($fh)){
	    $ds = fgets($fh,1048576);
	    $ds = ltrim(rtrim($ds));
	    $lx = explode(':',$ds);
	    $month = $lx[0];
	    $date = $lx[1];
	    $year = $lx[2];
	    $mid  = $lx[3];
	    $movie = $lx[4];
	    if ($_GET['debug1'] == 1) { echo "$date  $month $year <BR>";  }			
	    $_date = date("d") + 1; 
	    $_month = date("m");
	    $_year  = date("y");
	    $years_past = date("Y") - $year;
	    if ($_GET['debug1'] == 1) { echo "$_date  $_month $_year <BR>";  }
	    if ( (($_date >= $date && $_month == $month) || (($_date-$date) == 31 && ($_month+1) == $month) 
		  || ($_date < $date && $_month > $month) ) && $year < 2010) {
		$ms = GetMonthString($month);
//		if ($_GET['debug1'] == 1) { echo "$_date $date $_month $month <BR>";  }
		if ($_GET['lang'] != 'E') { $movie = get_uc($movie); }
		echo "<li class=pcellsbgleft>$ms $date,$year: <a href=\"m.php?$mid\">$movie </a> ($years_past)</li>\n";
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

function printArticleSeries($titl){
    if ($_GET['lang'] != 'E') { $titl = get_uc($titl,''); }
    $articles = buildArrayFromQuery("SELECT DISTINCT name from COLNAMES  ORDER BY name",'name');


    $picicon = "icons/articles.jpg";
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
	echo "<div class=tableheader> $titl </div>\n";
    }
    else {
	echo "<div class=tableheadernobg><img src=\"$picicon\" align=middle height=30> $titl </div>\n";
    }

    echo "<ul>\n";

    foreach ($articles as $art){
        $artid = runQuery("SELECT tag from COLNAMES where name=\"$art\"",'tag');
	if ($_GET['lang'] != 'E') { $art = get_uc($art,''); }
	echo "<li class=pcellsbgleft><a href=\"Columns.php?cn=$artid\">$art </a></li>\n";
    }
    echo "</ul>\n";
}
function isNonAdminUser() {

    $retstat = 1;
    global $_username;
    $_username = $_SERVER['PHP_AUTH_USER'];
    if ($_username == '') { $_username = $_SERVER['REMOTE_USER']; }
    if ($_GET['auth'] == 1) { echo "Logged in as $username<BR>"; }
    $nonAdminUsers = array ('ajay','anoop','vijay','sunny','jaya','dilip','kalyani','jija','sidhardh');
    foreach ($nonAdminUsers as $auname){
	if ($_username == $auname){
	    $retstat = 0;
	    break;
	}
    }
    
    return $retstat;
}

?>
