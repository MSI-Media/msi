<?



function printFB ($mid, $mode){

    global $_Master_movie_script;
    global $_Master_album_script;

    if ($mode == 'Albums'){
	echo "<div class=\"fb-like\" data-href=\"http://malayalasangeetham.info/Master_album_script?$mid\" data-send=\"true\" data-width=\"780\" data-show-faces=\"false\" data-font=\"lucida grande\"></div>\n";
    }
    else {
	echo "<div class=\"fb-like\" data-href=\"http://malayalasangeetham.info/Master_movie_script?$mid\" data-send=\"true\" data-width=\"780\" data-show-faces=\"false\" data-font=\"lucida grande\"></div>\n";
    }
}
function printSFB ($sid, $mode){

    global $_Master_song_script;
    global $_Master_albumsong_script;

    if ($mode == 'Albums'){
	echo "<div class=\"fb-like\" data-href=\"http://malayalasangeetham.info/$Master_albumsong_script?$sid\" data-send=\"true\" data-width=\"780\" data-show-faces=\"false\" data-font=\"lucida grande\"></div>\n";
    }
    else {
	echo "<div class=\"fb-like\" data-href=\"http://malayalasangeetham.info/Master_song_script?$sid\" data-send=\"true\" data-width=\"780\" data-show-faces=\"false\" data-font=\"lucida grande\"></div>\n";
    }

}
function cacheMessage($id,$type){
    global $_Master_cacheremove_script;
    $msg = 'We have Cached This Page for Faster Loading. If you see a wrong language used on this page or find data that is incorrect you need to click here to reload the latest entries from the database';
    if ($_GET['lang'] == 'E') { $msg = get_uc($msg,''); }
    echo "<div class=psubtitle><a href=\"$_Master_cacheremove_script?type=$type&id=$sid\">$msg</a></div>\n";
}
function printPicturesExtended ($mid,$mus,$lyr, $singer, $tag){

    $picPath1  = "moviepics";
    global $_Master_movie_script;
    global $_Master_album_script;
    global $_Master_profile_script ;

    $aurl = "$_Master_movie_script";
    if ($tag == 'Albums'){
        $picPath1  = "albumpics";	
	$aurl = "$_Master_album_script";
    }
    $picPath2 = "pics/Musicians";
    $picPath3 = "pics/Lyricists";
    $picPath4 = "pics/Singers";

    $pic_array = array();

    echo "<div class=pheadingleft>\n";
    if (file_exists("$picPath1/pic_${mid}.jpg")){
	$pics = "$picPath1/pic_${mid}.jpg";
	echo "<a href=\"${aurl}?$mid\"><img src=\"$pics\" class=preview onmousedown=\"if(event.button==2){return false;}\" border=0 height=100  ></a>\n";

	echo "<br>"; 
    }

    if (file_exists("$picPath2/${mus}.jpg")){
	array_push ($pic_array, "$mus");
	$pics = "$picPath2/${mus}.jpg";
	echo "<a href=\"$_Master_profile_script?category=musician&artist=$mus\"><img src=\"$pics\" class=preview onmousedown=\"if(event.button==2){return false;}\" border=0 height=100></a>\n";

    }

    // Lyricists can be in the traditional form too
    $lyr = str_replace("Traditional","",$lyr);
    $lyr = str_replace("(","",$lyr);
    $lyr = str_replace(")","",$lyr);
    $lyr = ltrim(rtrim($lyr));
    if ($_GET['debugx'] ==1) { echo "$picPath3/${lyr}.jpg"; }
    if (file_exists("$picPath3/${lyr}.jpg") && $mus != $lyr){
	if (!in_array("$lyr",$pic_array)){
	    $pics = "$picPath3/${lyr}.jpg";
	    echo "<a href=\"$_Master_profile_script?category=lyricist&artist=$lyr\"><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$pics\" border=0 height=100></a>\n";

	    array_push ($pic_array, "$lyr");
	}
    }
    if ($_GET['debug'] == 1 ) { print_r($pic_array);}
    $sings = explode(',',$singer);
    foreach ($sings as $sing){
	$sing=ltrim(rtrim($sing));
	if (file_exists("$picPath4/${sing}.jpg")){
	    if (!in_array("$sing",$pic_array)){
		$pics = "$picPath4/${sing}.jpg";
		echo "<a href=\"$_Master_profile_script?category=singers&artist=$sing\"><img src=\"$pics\" class=\"preview\" onmousedown=\"if(event.button==2){return false;}\"  border=0 height=100></a>\n";

		array_push ($pic_array, "$sing");
	    }
	}
    }
    echo "</div>";    


}
function printPictures ($mid,$tag,$mov,$mmov){

    global $_Master_Songbook_script ;
    $picPath1  = "moviepics";
    if ($tag == 'Albums'){
        $picPath1  = "albumpics";	
    }
    $pic_array = array();

    if (file_exists("$picPath1/${mid}.jpg")){
	array_push($pic_array,"$picPath1/${mid}.jpg");
    }
    else {
	array_push($pic_array,"pics/NoPhoto.jpg");
    }
    
    echo "<div class=pheading>\n";
    foreach ($pic_array as $pics){
//	echo "<a href=\"$pics\" class=\"preview\"><img src=\"$pics\" border=0 height=100 width=100 onclick=\"javascript:return false;\"></a>\n";
	echo "<a href=\"$pics\" class=\"preview\"><img src=\"$pics\" border=0 height=100 width=100 onclick=\"javascript:return false;\" onmousedown=\"if(event.button==2){return false;}\">\n";
    }

    echo "</div>";    

    if ($tag != 'Albums'){
	$sbook = "Song Book";	
	if ($_GET['lang'] != 'E'){
	    $sbook = get_uc($sbook,'');
	}

	$pmov = runQuery("SELECT P_MOVIE FROM PPUSTHAKAM where P_ID=$mid",'P_MOVIE');
	$_pmov = str_replace(" ","_",$pmov);
	if ($pmov != "" && file_exists("ppusthakam/$pmov")){
	    echo "<div class=pcellheadscenter><a href=\"$_Master_Songbook_script?movie=$pmov&movn=$mmov&mid=$mid\">$sbook</a></div>";
	}
	else if (file_exists("ppusthakam/$_pmov") && $_pmov != ""){
	    echo "<div class=pcellheadscenter><a href=\"$_Master_Songbook_script?movie=$_pmov&movn=$mmov&mid=$mid\">$sbook</a></div>";
	}
    }

}
function printXContents($url){

  
    $fh = fopen($url, "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    if (!$lx) { $lx = "<BR>";}
	    echo $lx;
	}
	fclose($fh);
    }
    
}
function printContributors ($mid,$tag)
{
    echo( "<table class=ptables>\n");
    printDetailHeadingRows ('Contributors','2');		
    if ($tag == "Movies"){
	$revtag = 'Reviews';
	if (file_exists("sreviews/${mid}.txt") || file_exists("sreviews/${mid}.html")){
	    if  (!file_exists("reviews/${mid}.txt") && !file_exists("reviews/${mid}.html")){
		$revtag = 'Song Reviews';
	    }
	    else {
		$revtag = 'Reviews,Song Reviews';
	    }
	}

	printDetailOwnerRows ('Poster',implode(' ,',buildArrayFromQuery("SELECT DISTINCT P_OWNER from PICTURES where M_ID=$mid",'P_OWNER')), 'UserPics',$icnt++);

	printDetailOwnerRows ('Promotions',implode(' ,',buildArrayFromQuery("SELECT DISTINCT P_USER from PROMOS where P_ID=$mid",'P_USER')), 'UserPics',$icnt++);

	printDetailOwnerRows ($revtag,implode(' ,',buildArrayFromQuery("SELECT DISTINCT M_USER from MD_LINKS where M_ID=$mid",'M_USER')), 'UserPics',$icnt++);
	$ppowner = runQuery("SELECT P_USER from PPUSTHAKAM where P_ID=$mid",'P_USER');
	if ($ppowner != ''){
	    printDetailOwnerRows ('Pattupusthakam',$ppowner, 'UserPics', $icnt++);
	}
    }
    else {
	printDetailOwnerRows ('Poster',implode(' ,',buildArrayFromQuery("SELECT DISTINCT P_OWNER from APICTURES where M_ID=$mid",'P_OWNER')), 'UserPics',$icnt++);
    }

    $alllyrics = findAllOwners($mid,'S_LYROWNER',$tag);
    $allclips  = findAllOwners($mid,'S_CLIPOWN', $tag);
    $allkars   = findAllOwners($mid,'S_KCLIPOWN', $tag);
    $allvids   = findAllOwners($mid,'UT_OWN', $tag);

    printDetailOwnerRows('Lyrics Contributors', $alllyrics ,'UserPics',$icnt++);
    printDetailOwnerRows('Audio Clip', $allclips ,'UserPics',$icnt++);
    printDetailOwnerRows ('Video Contributors',$allvids, 'UserPics',$icnt++);
    printDetailOwnerRows('Karaoke Contributors',$allkars,'UserPics',$icnt++);
    echo ("</table>");
}

function findUserFromComments($tag,$table,$stag,$id)
{
    $qry = "SELECT $tag FROM $table where $stag=$id";
    $com = runQuery($qry,$tag);
    $com1 = explode (' on ',$com);
    $com_email = str_replace('Added by','',$com1[0]);
    $com_email = str_replace('<br>','',$com_email);
    $com_name  = explode('@',$com_email);
    $retcom = ltrim(rtrim($com_name[0]));
    
    if ($retcom != 'Added'){
	return($retcom);
    }
    else {
	return;
    }
}

function findAllSongOwners ($sid, $tag, $table) {

    $t1 = "SONGS";
    $t2 = "UTUBE";
    if ($table == 'Albums'){
	$t1 = 'ASONGS';
	$t2 = 'ALBUM_UTUBE';
    }

    $owners = array();
    if ($tag != 'UT_OWN'){
	$own = runQuery("SELECT $tag from $t1 WHERE S_ID=$sid","$tag");
    }
    else {
	$own = runQuery("SELECT $tag from $t2 WHERE UT_ID=$sid","$tag");
    }
    $sns_array = explode(',',$own);
    foreach ($sns_array as $sn){
	$sn = ltrim(rtrim($sn));
	if ($sn != ""){
	    if (!in_array("$sn",$owners)){
		if ($_GET['debug'] == 1) { echo "Adding $sn to owners<br>" ; }
		array_push ($owners,"$sn");
	    }
	}
    }
    sort($owners);
    return implode (' ,',$owners);
}

function findAllOwners ($mid, $tag, $table) {

    $t1 = "SONGS";
    $t2 = "UTUBE";
    if ($table == 'Albums'){
	$t1 = 'ASONGS';
	$t2 = 'ALBUM_UTUBE';
    }

    $owners = array();
    $songs = buildArrayFromQuery("SELECT DISTINCT S_ID FROM $t1 WHERE M_ID=$mid",'S_ID');
    foreach ($songs as $sid){
	if ($tag != 'UT_OWN'){
	    $own = runQuery("SELECT $tag from $t1 WHERE S_ID=$sid","$tag");
	}
	else {
	    $own = runQuery("SELECT $tag from $t2 WHERE UT_ID=$sid","$tag");
	}
	$sns_array = explode(',',$own);
	foreach ($sns_array as $sn){
	    $sn = ltrim(rtrim($sn));
	    if ($sn != ""){
		if (!in_array("$sn",$owners)){
		if ($_GET['debug'] == 1) { echo "Adding $sn to owners<br>" ; }
		    array_push ($owners,"$sn");
		}
	    }
	}
    }
    sort($owners);
    return implode (' ,',$owners);
}

function printReviews ($mid){

    $revPath  = "reviews";
    $srevPath = "sreviews";
    global $_Master_SubmitReviews_script ;
    $rev_array = array();

    if (file_exists("$revPath/${mid}.html")){
	array_push($rev_array,"$revPath/${mid}.html");
    }
    if (file_exists("$revPath/${mid}.txt")){
	array_push($rev_array,"$revPath/${mid}.txt");
    }

    $hdr = "Storyline";
	if ($_GET['lang'] != 'E'){
	    $hdr = get_uc($hdr,'');
	}
    
    echo "<table class=ptables width=95%><tr class=tableheader><td style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" >$hdr</td></tr>";
    echo "<tr><td class=ptextleft>\n";

    foreach ($rev_array as $revs){
        echo "<P>";
	printContents("$revs");
	echo "<P><div style=\"border-bottom: 1px dotted #000000; width: 100%;\"></div><P>\n";
    }


    $oig_link = runQuery("SELECT M_URL FROM MD_LINKS WHERE M_URL like '%oldmalayalam%' and M_ID=$mid limit 1",'M_URL');

    if ($oig_link != ''){
	$old_is_gold_title = "Old is Gold by B Vijayakumar";
	if ($_GET['lang'] != 'E'){
	    $old_is_gold_title = get_uc($old_is_gold_title,'');
	}
	echo "<a href=\"$oig_link\" target=\"_new\"> $old_is_gold_title </a>";
    }


    echo "<P>";
    $revm = "Add or Update Review and Synopsis";
    if ($_GET['lang'] != "E"){
	$revm = get_uc("$revm",'');
    }
    echo "<div class=subtitle>\n";
    if ($rev_array[0] == ""){
	if ($_GET['lang'] != 'E'){
	    $hdr1 = get_uc("Review Not Available",'');
	}
	echo "$hdr1<P>";
    }
    echo "<a href=\"$_Master_SubmitReviews_script?mid=$mid\">$revm</a></div>\n";

    $exts = array('txt','html');
    if (file_exists("$srevPath/${mid}.txt") || file_exists("$srevPath/${mid}.html")){
        $shdr = "Songs Review";	
	if ($_GET['lang'] != 'E'){
	    $shdr = get_uc($shdr,'');
	}
	echo "<table class=ptables width=95%><tr class=tableheader><td style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" >$shdr</td></tr>";
	echo "<tr><td class=ptextleft>\n";
	foreach ($exts as $ext) {
	    echo "<div style=\"padding-left:50px;font-size:11pt;font-family:Lucida Sans; font-family:\"HelveticaNeue-Light\", \"Helvetica Neue Light\", \"Helvetica Neue\", Helvetica, Arial, \"Lucida Grande\", sans-serif;text-align:left;\"; >\n";
	    printContents("$srevPath/${mid}.${ext}");
	    echo "</div>\n";
	}
    }




    echo "</td></tr></table>";
}


function printSongs($mid,$tag) {
    global $_Master_albumsong_script;
    global $_Master_song_script   ;
    $table = 'SONGS';
    $songScript = "$_Master_song_script";
    if ($tag == 'Albums'){
	$table = 'ASONGS';
	$songScript = "$_Master_albumsong_script";
    }
    $raga_width=55;
    if ($_GET['lang'] == 'E') { $raga_width=20; }
    $songs_qry = "SELECT S_ID,S_SONG,S_MUSICIAN,S_SINGERS,S_WRITERS,S_RAGA,S_CLIP from $table WHERE M_ID=$mid order by S_SONG";
    $res_Qry = mysql_query($songs_qry);
    $num_Qry = mysql_num_rows($res_Qry);
    $i = 0;
    if ($num_Qry > 0) {
	echo "<table class=ptables>\n";
        printDetailHeadingRows ('Songs','6');		
	echo "<tr class=tableheader>\n";
	printDetailCellHeads ('Song');
	printDetailCellHeads ('Musician');
	printDetailCellHeads ('Lyricist');
	printDetailCellHeads ('Singers');
	printDetailCellHeads ('Raga');
	printDetailCellHeads ('Listen');
	echo "</tr>\n";
	while ($i < $num_Qry){
	    echo "<tr>\n";
	    $sid = mysql_result($res_Qry, $i, "S_ID");
	    printDetailCells (mysql_result($res_Qry, $i, "S_SONG"),"$songScript?$sid",$i);
	    printDetailCells (mysql_result($res_Qry, $i, "S_MUSICIAN"),'',$i);
	    printDetailCells (mysql_result($res_Qry, $i, "S_WRITERS"),'',$i);
	    printDetailCells (mysql_result($res_Qry, $i, "S_SINGERS"),'',$i);
	    printDetailCells (mysql_result($res_Qry, $i, "S_RAGA"),'',$i);
/*
	    $raga = mysql_result($res_Qry, $i, "S_RAGA");
	    if (strpos($raga,'Raagamalika') === false){
		printDetailCells ($raga,'','');
	    }
	    else {
		printDetailCells ('Raagamalika','','');
	    }
*/
	    printDetailCells (provideListenString($sid,mysql_result($res_Qry, $i, "S_CLIP"), $tag),'','');



	    echo "</tr>";
	    $i++;
	}
	echo "</table>\n";
    }
}

function provideListenString ($sid,$clip, $tag){


global $_Master_SubmitTrailers_script; 
global $_Master_Submitclips_script ;
global $_Master_Submitvids_script ;
global $_Master_SubmitPictures_script;
global $_Master_SubmitReviews_script ;
global $_Master_contribs_script;
global $_Master_cacheremove_script;
global $_Master_videoplayer ;
global $_Master_audioplayer ;


    global $_MasterRootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $listen_str = '';
    $video=false;
    $table      = 'UTUBE';

    global $_Master_song_script;
    global $_Master_albumsong_script;

    $songScript = "$_Master_song_script";

    if ($tag == 'Albums'){
	$table = 'ALBUM_UTUBE';
	$songScript = "$_Master_albumsong_script";
    }
    $typemode='m';
            if ($tag == 'Albums'){ $typemode='a';}

    $utube          = runQuery("SELECT UT_URL from $table WHERE UT_ID=$sid AND UT_STAT='Published'",'UT_URL');
    $utubevalsx = explode('&',$utube);
    $utubevals = explode ('=',$utubevalsx[0]);
    $utube_str  = "$songScript?$sid";
    $utube_lock = '';
    if (!$utube) {
	$utube_lock  = runQuery("SELECT UT_URL from $table WHERE UT_ID=$sid AND UT_STAT='Submitted'",'UT_URL');
    }
    if ($utubevals[1] != "") {
	$utlink  = "http://gdata.youtube.com/feeds/api/videos/$utubevals[1]";
	if ($_GET['debug'] == 1) { echo "$utlink<BR>";}
	if (checkYoutubeId("$utlink")){
//	    $avstring = "<iframe width=\"280\" height=\"170\" src=\"http://www.youtube.com/embed/$utubevals[1]?wmode=transparent\" frameborder=\"0\" allowfullscreen></iframe>";
//	    $listen_str = "<a href=\"$utube_str\"><img src=\"images/youtube.png\" height=20 alt=\"Watch\" border=0></a>";
  	    $listen_str = " <a href=\"$_Master_videoplayer?type=$typemode&id=$sid\" toptions=\"group=vlinks,type = iframe,effect=appear,modal=1,width = 420,height = 285\" title=\"Playing Video..\"><img src=\"images/youtube.png\" alt=\"Watch\" border=0></a>";
	    $video=true;
	}
	else if ($utube_lock != '') {
	    $listen_str = "<img src=\"images/video-music.png\" height=20 alt=\"Watch\" border=0></a>";
	}
	else {
	    $listen_str = "<img src=\"images/uploadv.png\" height=20 alt=\"Watch\" border=0></a>";
	}
    }
    else {
	if ($utube_lock != '') {
	    $listen_str = "<img src=\"images/video-music.png\" height=20 alt=\"Watch\" border=0>";
	}
	else {
	    if ($tag == 'Albums'){
		$listen_str = "<a href=\"$_Master_Submitvids_script?sid=$sid&mode=ALBUMS\"><img src=\"images/uploadv.png\" height=20 alt=\"Watch\" border=0></a>";
	    }
	    else {
		$listen_str = "<a href=\"$_Master_Submitvids_script?sid=$sid\"><img src=\"images/uploadv.png\" height=20 alt=\"Watch\" border=0></a>";
	    }
	}
    }

        if ($video == false){
          $alternateVideo = 0;
    	  $video_url      = "";
	  $url = $utubevals[1];

   	   if (file_exists("$_MasterRootDir/Videos/${url}.flv")) { 
//$listen_str = "<a href=\"$utube_str\"><img src=\"images/youtube.png\" height=20 alt=\"Watch\" border=0></a>"; 
  	    $listen_str = " <a href=\"$_Master_videoplayer?type=$typemode&id=$sid\" toptions=\"group=vlinks,type = iframe,effect=appear,modal=1,width = 420,height = 285\" title=\"Playing Video..\"><img src=\"images/youtube.png\" alt=\"Watch\" border=0></a>";
	   }
	   else if (file_exists("$_MasterRootDir/Videos/${sid}.flv")) { 
//$listen_str = "<a href=\"$utube_str\"><img src=\"images/youtube.png\" height=20 alt=\"Watch\" border=0></a>"; 
  	    $listen_str = " <a href=\"$_Master_videoplayer?type=$typemode&id=$sid\" toptions=\"group=vlinks,type = iframe,effect=appear,modal=1,width = 420,height = 285\" title=\"Playing Video..\"><img src=\"images/youtube.png\" alt=\"Watch\" border=0></a>";
	   }
	   else if (file_exists("$_MasterRootDir/Videos/${sid}.FLV")) { 
//$listen_str = "<a href=\"$utube_str\"><img src=\"images/youtube.png\" height=20 alt=\"Watch\" border=0></a>"; 
  	    $listen_str = " <a href=\"$_Master_videoplayer?type=$typemode&id=$sid\" toptions=\"group=vlinks,type = iframe,effect=appear,modal=1,width = 420,height = 285\" title=\"Playing Video..\"><img src=\"images/youtube.png\" alt=\"Watch\" border=0></a>";
	   }
       }

    $listen_str = "<table border=0 width=100%><tr><td width=50%>$listen_str</td><td>";

    if ($clip == 'Y'){
//	$listen_str .= " <a href=\"$songScript?$sid\"><img src=\"images/listen.jpg\" alt=\"Listen\" border=0></a>";
$typemode = 'm';
       if ($tag == 'Albums'){ $typemode='a';}
	$listen_str .= " <a href=\"$_Master_audioplayer?type=$typemode&id=$sid\" toptions=\"group=links,type = iframe,effect=appear,modal=1,width = 400,height = 125\" title=\"Playing Audio..\"><img src=\"images/listen.jpg\" alt=\"Listen\" border=0></a>";


    }
    else if ($clip == 'L'){
	$listen_str .= "   <img src=\"images/small_lock.jpg\" border=0 alt=\"Audio Submitted\">";
    }
    else {
        if ($tag == 'Albums'){
	    $listen_str .=  "   <a href=\"$_Master_Submitclips_script?sid=$sid&mode=ALBUMS\"><img src=\"images/upload.png\" border=0></a> ";
	}
	else {
	    $listen_str .=  "   <a href=\"$_Master_Submitclips_script?sid=$sid\"><img src=\"images/upload.png\" border=0></a> ";
	}
    }
    $listen_str = $listen_str . "</td></tr></table>";

    return $listen_str;
}
function printStills ($mid){
    $pic_array = array();
    global $_MasterRootDir;
    $picPath2 = "$_MasterRootDir/_PhotosfrmBlog";
    $picPath2URL = "$_MasterRootofMSI/_PhotosfrmBlog";

    if (file_exists("$picPath2/${mid}.jpg")){
	array_push($pic_array,"$picPath2URL/${mid}.jpg");
    }

    foreach (range(0,20) as $number) {
  
       if (file_exists("$picPath2/${mid}_$number.jpg")){
         array_push($pic_array,"$picPath2URL/${mid}_$number.jpg");
      } 
    }
    echo "<div class=pheading>\n";
    if ($pic_array[0] != ""){
	foreach ($pic_array as $pics){
	    echo "<a href=\"$pics\" class=\"preview\"><img src=\"$pics\" border=0 height=100 onclick=\"javascript:return false;\" onMouseOver=\"javascript:window.status=''\" onmousedown=\"if(event.button==2){return false;}\"></a>\n";
	}
	echo "</div>";
	echo "<div class=ptext>\n";
	if ($_GET['lang'] != 'E'){
	    printContents("Writeups/AboutAdditionalPictures.html");
	}
	else {
	    printContents("Writeups/AboutAdditionalPictures_eng.html");
	}
	echo "</div>";
    }
    echo "</div>\n";
}


function printAStills ($mid){
    $pic_array = array();
    global $_MasterRootDir;
    $picPath2 = "$_MasterRootDir/_aPhotosfrmBlog";
    $picPath2URL = "$_MasterRootofMSI/_aPhotosfrmBlog";

    if (file_exists("$picPath2/${mid}.jpg")){
	array_push($pic_array,"$picPath2URL/${mid}.jpg");
    }

    foreach (range(0,20) as $number) {
  
       if (file_exists("$picPath2/${mid}_$number.jpg")){
         array_push($pic_array,"$picPath2URL/${mid}_$number.jpg");
      } 
    }
    echo "<div class=pheading>\n";
    if ($pic_array[0] != ""){
	foreach ($pic_array as $pics){
	    echo "<a href=\"$pics\" class=\"preview\"><img src=\"$pics\" border=0 height=100 onclick=\"javascript:return false;\" onMouseOver=\"javascript:window.status=''\" onmousedown=\"if(event.button==2){return false;}\"></a>\n";
	}
	echo "</div>";
	echo "<div class=ptext>\n";
	if ($_GET['lang'] != 'E'){
	    printContents("Writeups/AboutAdditionalAlbumPictures.html");
	}
	else {
	    printContents("Writeups/AboutAdditionalAlbumPictures_eng.html");
	}
	echo "</div>";
    }
    echo "</div>\n";
}

function printDetailHeadingRows ($val,$span){
    if ($_GET['lang'] != 'E') {
	    $val = get_uc("$val","");
	}
    echo "<tr class=tableheader><td style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" colspan=$span>$val</td></tr>";
}
function printDetailMovieHeadingRows ($stat,$val,$span){
 
    if ($_GET['debug1'] == 1) { echo $stat; }
    if ($_GET['lang'] != 'E') {
	 $val = get_uc("$val","");
         $statval = get_uc("$stat",'');
    } else { $statval = $stat; }

    if ($stat == 'Dubbed'){
         echo "<tr class=tableheader><td colspan=2 style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" colspan=$span>$val [ $statval ]</td></tr>";
    }
    else if ($stat == 'Unreleased'){
         echo "<tr class=tableheader><td colspan=2 style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" colspan=$span>$val [ $statval ]</td></tr>";
    }
	else if ($stat == 'In Production'){
         echo "<tr class=tableheader><td colspan=2 style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" colspan=$span>$val [ $statval ]</td></tr>";
    }
    else {
      echo "<tr class=tableheader><td style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:left;\" colspan=$span>$val</td></tr>";
    }
}
function printDetailHeadingDivs ($val){
    if ($_GET['lang'] != 'E') {
	    $val = get_uc("$val","");
	}
    echo "<div class=psubheading>$val</div>\n";
}
function printRelationshipsList($artist, $category){

    $category = substr($category, 0, -1);
    $relative_list = array();
    global $_Master_profile_script ;
    $relatives = array();
    $artist = ltrim(rtrim($artist));
    $query = "SELECT * from RELATIVES where artist1=\"$artist\" or artist2=\"$artist\" order by artist1,artist2";
    $res_funcQry = mysql_query($query);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    while ($i < $num_funcQry){
	$valid = 0;
       $artist1 = mysql_result($res_funcQry, $i, "artist1");
       $artist2 = mysql_result($res_funcQry, $i, "artist2");
       if ($artist1 == $artist){
	   if ($_GET['debug4'] == 1) { echo "SELECT RELATIVES.id as ccn from RELATIVES,MARTISTS WHERE MARTISTS.name = \"$artist1\" and MARTISTS.category like \"%$category%\"<BR>";}
	   $related  = $artist2;
	   if (!in_array($related,$relative_list)){
	       $valid = runQuery("SELECT RELATIVES.id as ccn from RELATIVES,MARTISTS WHERE MARTISTS.name = \"$artist1\" and MARTISTS.category like \"%$category%\"",'ccn');
	       array_push ($relative_list,$related);
	       $field    = mysql_result($res_funcQry, $i, "field2");
	       $relation = mysql_result($res_funcQry, $i, "secondr");
	   }
       }
       else {

	   if ($_GET['debug4'] == 1) { echo "SELECT RELATIVES.id as ccn from RELATIVES,MARTISTS WHERE MARTISTS.name=\"$artist2\" and MARTISTS.category like \"%$category%\"<BR>";}
	   $related  = $artist1;
	   if (!in_array($related,$relative_list)){
	       $valid = runQuery("SELECT RELATIVES.id as ccn from RELATIVES,MARTISTS WHERE MARTISTS.name=\"$artist2\" and MARTISTS.category like \"%$category%\"",'ccn');
	       array_push ($relative_list,$related);
	       $field    = mysql_result($res_funcQry, $i, "field1");
	       $relation = mysql_result($res_funcQry, $i, "firstr");
	   }
       }
       if ($field == 'Actor' || $field == 'Singer'){
	   $url      = "${_Master_profile_script}?artist=$related&category=" . strtolower($field) . 's';
       }
       else if ($field != ''){
	   $url      = "${_Master_profile_script}?artist=$related&category=" . strtolower($field);
       }
       if ($_GET['lang'] != 'E'){
	   $related_str   = get_uc($related,'');
	   $relation_str  = get_uc($relation,'');
       }
       else {
	   $related_str   = $related;
	   $relation_str  = $relation;
       }
       if ($valid > 0){
	   array_push ($relatives,"<a href=\"$url\">$related_str ($relation_str)</a>");
       }
       $i++;
    }
    if ($relatives[0] != ''){
	$key = 'Related To';
	if ($_GET['lang'] != 'E'){
	    $key = get_uc($key,'');
	}
    
	echo "<div class=psubtitlebg><font color=#aa4433>$key</font> : ";
	echo implode (', ',$relatives);
	echo "</div><P>";
    }
}
function printArrayList ($artist,$key,$val) {
    global $_Master_profile_script ;
    $vale= array();
    if ($_GET['lang'] != 'E') {
    	$key = get_uc("$key","");
    }
    echo "<div class=psubtitlebg><font color=#aa4433>$key</font> : ";
    foreach (explode(',',$val) as $val_e){
	$val_e_save = $val_e;      
	$val_e_save = strtolower($val_e_save);
	if ($val_e_save == 'singer'){ $val_e_save = 'singers'; }
	if ($_GET['lang'] != 'E') {
	    $val_e = get_uc("$val_e","");
	}
	array_push ($vale, "<a href=\"$_Master_profile_script?category=$val_e_save&artist=$artist\">$val_e</a>");
    }
    echo implode (', ',$vale);
    echo "</div><P>\n";
}
function printDetailHeaders ($key,$val){
    if ($_GET['lang'] != 'E') {	
	$key = get_uc("$key","");
	$val = get_uc("$val","");
    }
    echo "<div style=\"font-size:13pt;font-family:Lucida Sans;font-weight:bold;text-align:center;\"> $key : $val</div>\n";
}

function printShortHeaders ($key,$val){
    if ($_GET['lang'] != 'E') {	
	$key = get_uc("$key","");
	$val = get_uc("$val","");
    }
   echo "&nbsp;<font color=#000033>$key</font> : <font color=#E3170D>$val</font>&nbsp;";

}

function printLinkedRows ($key, $link, $target,$cnt) 
{

    $printstyle='prowsshort';
    if ( $cnt&1 ) {
	$printstyle .= 'odd';
    }

    if ($_GET['lang'] != 'E'){
	$key = get_uc($key, '');
    }
    if ($target == ''){
       echo ( "<tr><td colspan=2 class=${printstyle}><a href=\"$link\">$key</td></tr>\n");
    }
    else {
            echo ( "<tr><td colspan=2 class=${printstyle}><a href=\"$link\" target=\"$target\">$key</td></tr>\n");
    }
}
function printDetailRows ($key, $val, $keyloc, $cnt){

    global $_Master_profile_script ;
    $profileScript     = $_Master_profile_script;

    $artistpicLocation = "pics/$keyloc/TN";
    if ($_GET['debug1'] == 1) { echo "HERE1: $key : $val<BR>"; }

    if ($val == "" || $val == "__" || $val == "Uncategorized" || $val == "___"){
	$val = "Not Available";
	$url = "";
    }
    else {
   	 if ($key == "background music") { $lkey = "bgm"; }
	else {$lkey = strtolower($key); }
	if ($keyloc != ""){
	    $url = "$profileScript?category=$lkey&artist=$val";
	}
    }

    $val_array = array();
    $pos = strpos($val, ",");
    $key_tag   = get_uc("$key",'');

    if ($pos === false){
	$val = ltrim(rtrim($val));
	if ($url != ""){
	    if ($keyloc != ""){

		if (file_exists("$artistpicLocation/${val}.jpg")){
		    $imgurl = "$artistpicLocation/${val}.jpg";
		}
		else {
		    $imgurl = "pics/tn_NoPhoto.jpg";
		}

	    }
	    if ($_GET['lang']=='E'){
		if ($imgurl != ""){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$val</a>");
		}
		else {
		    array_push($val_array,"<a href=\"$url\">$val</a>");
		}
	    }
	    else {
		$val_tag   = get_uc("$val",'');
		if ($imgurl != ""){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$val_tag</a>");
		}
		else {
		    array_push($val_array,"<a href=\"$url\">$val_tag</a>");
		}
	    }
	}
	else {
	    if ($_GET['lang']=='E'){
		array_push($val_array,"$val");
	    }
	    else {
		$val_tag   = get_uc("$val",'');
		array_push($val_array,"$val_tag");
	    }
	}
	$print_string = implode(' ,',$val_array);
    }
    else {
	$pos2 = strpos($val, "Raagamalika");
	if ($pos2 === false) {
	    $vals = explode(',',$val);
	    $key_tag   = get_uc("$key",'');
	    foreach ($vals as $valx){
		$valx = ltrim(rtrim($valx));
		if (file_exists("$artistpicLocation/${valx}.jpg")){
		    $imgurl = "$artistpicLocation/${valx}.jpg";
		}
		else {
		    $imgurl = "pics/tn_NoPhoto.jpg";
		}
		$url = "$profileScript?category=$lkey&artist=$valx";
		if ($_GET['lang']=='E'){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$valx</a>");
		}
		else {
		    $valx_tag   = get_uc("$valx",'');
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$valx_tag</a>");
		}	
	    }
	    $print_string = implode(' ,',$val_array);
	}
	else {
	    if ($_GET['debug1'] == 1) { echo "HERE2: $key : $val<BR>"; }
	    $val = str_replace("Raagamalika",'',$val);
	    $val = str_replace("(",'',$val);
	    $val = str_replace(")",'',$val);
	    $val = ltrim(rtrim($val));
	    if ($_GET['debug1'] == 1) { echo "HERE: $val<BR>"; }


	    $vals = explode(',',$val);
	    $key_tag   = get_uc("$key",'');
	    foreach ($vals as $valx){
		$valx = ltrim(rtrim($valx));
		if (file_exists("$artistpicLocation/${valx}.jpg")){
		    $imgurl = "$artistpicLocation/${valx}.jpg";
		}
		else {
		    $imgurl = "pics/tn_NoPhoto.jpg";
		}
		$url = "$profileScript?category=$lkey&artist=$valx";
		if ($_GET['lang']=='E'){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$valx</a>");
		    $rm = "Raagamalika (";
		}
		else {
		    $valx_tag   = get_uc("$valx",'');
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$valx_tag</a>");
		    $rm = get_uc("Raagamalika",'') . " (";
		}	
	    }
	    $print_string = $rm . implode(' ,',$val_array) . ")";
	}
    }

//    $print_string = implode(' ,',$val_array);

    $printstyle='';
    if ( $cnt&1 ) {
	$printstyle = 'odd';
    }


    if ($print_string != ""){ 
	if ($_GET['lang']=='E'){
	    echo ( "<tr><td class=\"prowsshort${printstyle}\">$key</td><td colspan=4 class=\"prows${printstyle}\">$print_string</td></tr>\n");
	}
	else {
	    echo ( "<tr><td class=\"prowsshort${printstyle}\">$key_tag</td><td colspan=4 class=\"prows${printstyle}\">$print_string</td></tr>\n");
	}	

    }
}


function printDetailRowsMerged ($key, $val, $keyloc, $cnt){
    global $_Master_profile_script ;
    $profileScript     = "$_Master_profile_script";

    $artistpicLocation = "pics/$keyloc/TN";
    if ($key == 'Raga'){
	$pos = strpos($val, 'Raagamalika');
	if ($pos !== false) {
	    $val = 'Raagamalika';
	    $keyloc = '';
	}		     
    }

    if ($val == "" || $val == "__" || $val == "Uncategorized" || $val == "___"){
	$val = "Not Available";
	$url = "";
    }
    else {
   	 if ($key == "background music") { $lkey = "bgm"; }
	else {$lkey = strtolower($key); }
	if ($keyloc != ""){
	    $url = "$profileScript?category=$lkey&artist=$val";
	}
    }

    $val_array = array();

    $key_tag   = get_uc("$key",'');

  
	$val = ltrim(rtrim($val));
	if ($url != ""){
	    if ($keyloc != ""){

		if (file_exists("$artistpicLocation/${val}.jpg")){
		    $imgurl = "$artistpicLocation/${val}.jpg";
		}
		else {
		    $imgurl = "pics/tn_NoPhoto.jpg";
		}

	    }
	    if ($_GET['lang']=='E'){
		if ($imgurl != ""){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$val</a>");
		}
		else {
		    array_push($val_array,"<a href=\"$url\">$val</a>");
		}
	    }
	    else {
		$val_tag   = get_uc("$val",'');
		if ($imgurl != ""){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$val_tag</a>");
		}
		else {
		    array_push($val_array,"<a href=\"$url\">$val_tag</a>");
		}
	    }
	}
	else {
	    if ($_GET['lang']=='E'){
		array_push($val_array,"$val");
	    }
	    else {
		$val_tag   = get_uc("$val",'');
		array_push($val_array,"$val_tag");
	    }
	}
   

    $print_string = implode(' ,',$val_array);

    $printstyle='';
    if ( $cnt&1 ) {
	$printstyle = 'odd';
    }


    if ($print_string != ""){ 
	if ($_GET['lang']=='E'){
	    echo ( "<tr><td class=\"prowsshort${printstyle}\">$key</td><td class=\"prows${printstyle}\">$print_string</td></tr>\n");
	}
	else {
	    echo ( "<tr><td class=\"prowsshort${printstyle}\">$key_tag</td><td class=\"prows${printstyle}\">$print_string</td></tr>\n");
	}	
    }
}



function printDetailOwnerRows ($key, $val, $keyloc,$cnt){

   global $_Master_contribs_script ;
    $profileScript     = "$_Master_contribs_script";
    $artistpicLocation = "pics/$keyloc/TN";


    $firstlet = strtoupper(substr($val,0,1));
    $url      = "${profileScript}?let=${firstlet}";

    $val_array = array();
    $pos = strpos($val, ",");
    $key_tag   = get_uc("$key",'');

    if ($pos === false){
	if ($url != ""){
	    if ($keyloc != ""){
		if (file_exists("$artistpicLocation/${val}.jpg")){
		    $imgurl = "$artistpicLocation/${val}.jpg";
		}
		else {
		    $imgurl = "pics/tn_NoPhoto.jpg";
		}
	    }
	    if ($_GET['lang']=='E'){
		if ($imgurl != ""){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$val</a>");
		}
		else {
		    array_push($val_array,"<a href=\"$url\">$val</a>");
		}
	    }
	    else {
		$val_tag   = get_uc("$val",'');
		if ($imgurl != ""){
		    array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$val_tag</a>");
		}
		else {
		    array_push($val_array,"<a href=\"$url\">$val_tag</a>");
		}
	    }
	}
	else {
	    if ($_GET['lang']=='E'){
		array_push($val_array,"$val");
	    }
	    else {
		$val_tag   = get_uc("$val",'');
		array_push($val_array,"$val_tag");
	    }
	}
    }
    else {
	$vals = explode(',',$val);
	$key_tag   = get_uc("$key",'');
	foreach ($vals as $valx){
	    $valx = ltrim(rtrim($valx));
	    $firstlet = strtoupper(substr($valx,0,1));
	    $url      = "${profileScript}?let=${firstlet}";
	    if (file_exists("$artistpicLocation/${valx}.jpg")){
		$imgurl = "$artistpicLocation/${valx}.jpg";
	    }
	    else {
		$imgurl = "pics/tn_NoPhoto.jpg";
	    }
	    if ($_GET['lang']=='E'){
		array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$valx</a>");
	    }
	    else {
		$valx_tag   = get_uc("$valx",'');
		array_push($val_array,"<a href=\"$url\" class=\"screenshot\" rel=\"$imgurl\">$valx_tag</a>");
	    }	
	}
    }

    $print_string = implode(' ,',$val_array);


    $printstyle='prowsshort';
    if ( $cnt&1 ) {
	$printstyle = 'prowsshortodd';
    }

    if ($print_string != ""){ 
	if ($_GET['lang']=='E'){
	    echo ( "<tr><td class=${printstyle}>$key</td><td colspan=4 class=${printstyle}>$print_string</td></tr>\n");
	}
	else {
	    echo ( "<tr><td class=${printstyle}>$key_tag</td><td colspan=4 class=${printstyle}>$print_string</td></tr>\n");
	}	
    }
}


function printPicList ($arr, $links, $names){
    $mnames = array();
    if ($_GET['lang'] != 'E'){
	if ($names[0] != ""){
	    foreach ($names as $n){
		if ($n != $prev){
		    array_push($mnames,get_uc("$n",''));
		}
		$prev = $n;
	    }
	    $names = $mnames;
	}
    }	
    if ($_GET['debug'] == 1) {
           print_r($arr);
           print_r($links);
           print_r($names);
    }
    echo "<tr><td colspan=6 align=center>\n";
//    echo "<tr><td align=center>\n";
//    echo "<table align=center>\n";
    echo "<table class=ptables>\n";
    echo "<tr>\n";
    $cnt=0;
    foreach ($arr as $arr_elem){
	if ($arr_elem != $prev){
	    echo "<td align=center><img class=preview src=\"$arr[$cnt]\" border=0 height=100 onmousedown=\"if(event.button==2){return false;}\">\n";
	    echo "<div class=psubtitle><a href=\"$links[$cnt]\">$names[$cnt]</div>";
	    echo "</td>";
	}
	$prev = $arr_elem;
	$cnt++;

    }
    echo "</tr></table>";
    echo "</td></tr>";

}
function printPicList2 ($arr, $links, $names){

    $mnames = array();
    if ($_GET['lang'] != 'E'){
	foreach ($names as $n){
	    array_push($mnames,get_uc("$n",''));
	}
	$names = $mnames;
    }
    echo "<tr>\n";
    if ($arr[1] == ""){
	if (file_exists("$arr[0]")){
	    echo "<td align=center colspan=6<img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[0]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[0]\">$names[0]</div>";
	    echo "</td>\n";
	}
    }
    else if ($arr[2] == "") {
	if (file_exists("$arr[0]")){
	    echo "<td align=center colspan=3><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[0]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[0]\">$names[0]</div>";
	    echo "</td>\n";
	}
	if (file_exists("$arr[1]")){
	    echo "<td align=center colspan=2><img class=preview src=\"$arr[1]\" onmousedown=\"if(event.button==2){return false;}\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[1]\">$names[1]</div>";
	    echo "</td>\n";
	}
    }
    else if ($arr[3] == "") {
	if (file_exists("$arr[0]")){
	    echo "<td align=center colspan=2><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[0]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[0]\">$names[0]</div>";
	    echo "</td>\n";
	}
	if (file_exists("$arr[1]")){
	    echo "<td align=center colspan=1><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[1]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[1]\">$names[1]</div>";
	    echo "</td>\n";
	}
	if (file_exists("$arr[2]")){
	    echo "<td align=center colspan=2><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[2]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[2]\">$names[2]</div>";
	    echo "</td>\n";
	}

    }
    else if ($arr[4] == "") {
	if (file_exists("$arr[0]")){
	    echo "<td align=center colspan=2><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[0]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[0]\">$names[0]</div>";
	    echo "</td>\n";
	}
	if (file_exists("$arr[1]")){
	    echo "<td align=center colspan=1><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[1]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[1]\">$names[1]</div>";
	    echo "</td>\n";
	}
	if (file_exists("$arr[2]")){
	    echo "<td align=center colspan=1><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[2]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[2]\">$names[2]</div>";
	    echo "</td>\n";
	}
	if (file_exists("$arr[3]")){
	    echo "<td align=center colspan=1><img class=preview onmousedown=\"if(event.button==2){return false;}\" src=\"$arr[3]\" border=0 height=100>\n";
	    echo "<div class=psubtitle><a href=\"$links[3]\">$names[3]</div>";
	    echo "</td>\n";
	}

    }
    echo "</tr>";
}

function printDetailCells ($val,$link,$cnt){

    $val_array = array();
    $key_tag   = get_uc("$key",'');
    $lang = $_SESSION['lang'];	

    if ($_GET['debug1'] == 1) { echo "HERE1: $val<BR>"; }
    $pos = strpos($val, ",");

    if ($pos === false) {
	if ($lang =='E'){	
	    array_push($val_array,"$val");
	}
	else {
	    $valx_tag   = get_uc("$val",'');
	    array_push($val_array,"$valx_tag");
	}	
	$print_string = implode(' ,',$val_array);
    }

    else {
	$pos2 = strpos ($val, "Raagamalika");
	if ($pos2 === false) {
	    $vals = explode (',',$val);
	    foreach ($vals as $vale){
		$vale = ltrim(rtrim($vale));
		if ($lang =='E'){	
		    array_push($val_array,"$vale");
		}
		else {
		    $valx_tag   = get_uc("$vale",'');
		    array_push($val_array,"$valx_tag");
		}	
	    }
	    $print_string = implode(' ,',$val_array);
	}
	else {
	    $val = str_replace("Raagamalika",'',$val);
	    $val = str_replace("(",'',$val);
	    $val = str_replace(")",'',$val);
	    $val = ltrim(rtrim($val));
	    if ($_GET['debug1'] == 1) { echo "HERE: $val<BR>"; }
	    $vals = explode (',',$val);
	    foreach ($vals as $vale){
		$vale = ltrim(rtrim($vale));
		if ($lang =='E'){	
		    array_push($val_array,"$vale");
		    $rm = "Raagamalika (";
		}
		else {
		    $valx_tag   = get_uc("$vale",'');
		    array_push($val_array,"$valx_tag");
		    $rm = get_uc("Raagamalika",'') . " (";
		}	
	    }
	    $print_string = $rm . implode(' ,',$val_array) . ")";
	}
    }
//  $print_string = implode(' ,',$val_array);


//    $printstyle='pcells';
    $printstyle='prowsshort';
    if ( $cnt&1 ) {
	$printstyle = 'prowsshortodd';
    }


    if ($link) {
	echo ( "<td class=$printstyle><a href=\"$link\">$print_string</a></td>\n");
    }
    else {
	echo ( "<td class=$printstyle>$print_string</td>\n");
    }
}
function printDetailCellsTruncated ($val,$link,$cnt,$width){

    $val_array = array();
    $key_tag   = get_uc("$key",'');
    $lang = $_SESSION['lang'];	

/*
    $pos = strpos($val, ",");

    if ($pos === false) {
	if ($lang =='E'){	
	    array_push($val_array,"$val");
	}
	else {
	    $valx_tag   = get_uc("$val",'');
	    array_push($val_array,"$valx_tag");
	}	
    }
    else {
	$vals = explode (',',$val);
	foreach ($vals as $vale){
	    $vale = ltrim(rtrim($vale));
	    if ($lang =='E'){	
		array_push($val_array,"$vale");
	    }
	    else {
		$valx_tag   = get_uc("$vale",'');
		array_push($val_array,"$valx_tag");
	    }	
	}
    }
    $print_string = substr(implode(' ,',$val_array),0,$width);
*/
$print_string = substr(get_uc($val,''),0,$width);

    $printstyle='pcells';
    if ( $cnt&1 ) {
	$printstyle = 'pcellsodd';
    }
  
    if ($link) {
	echo ( "<td class=$printstyle><a href=\"$link\">$print_string</a></td>\n");
    }
    else {
	echo ( "<td class=$printstyle>$print_string</td>\n");
    }
}
function printDetailCellsWithPreview ($val,$link){

    $val_array = array();
    $key_tag   = get_uc("$key",'');

    if ($_GET['lang']=='E'){
	array_push($val_array,"$val");
    }
    else {
	$valx_tag   = get_uc("$val",'');
	array_push($val_array,"$valx_tag");
    }	

    $print_string = implode(' ,',$val_array);

    if ($link) {
	echo ("<td class=pcells><a href=\"$link\" class=\"screenshot\" rel=\"$link\" onclick=\"javascript:return false;\">$print_string</a></td>");
    }
    else {
	echo ( "<td class=pcells>$print_string</td>\n");
    }
}


function printDetailCellHeads ($val){

    if ($_GET['lang']=='E'){
	echo ( "<th class=pcellheads>$val</th>\n");
    }
    else {
	$val_tag   = get_uc("$val",'');
	echo ( "<th class=pcellheads>$val_tag</th>\n");
    }
}



function printDetailCellHeadsSorts ($val,$order,$lnk){
    $vx = $val;
    if ($_GET['lang']!='E'){
	$val_tag   = get_uc("$val",'');
	$vx = $val_tag;
    }
    $lnk_a = $lnk . "&sortorder=$order&sorttype=1";
    $lnk_d = $lnk . "&sortorder=$order&sorttype=2";

    echo ( "<th class=pcellheads><a href=\"$lnk_a\"><img src=\"icons/icon_asc.gif\"></a> $vx<a href=\"$lnk_d\"><img src=\"icons/icon_desc.gif\"></a></th>\n"); 
}


function printDetailCellHeadsSortsSmall ($val,$order,$lnk){
    $vx = $val;
    if ($_GET['lang']!='E'){
	$val_tag   = get_uc("$val",'');
	$vx = $val_tag;
    }
    $lnk_a = $lnk . "&sortorder=$order&sorttype=1";
    $lnk_d = $lnk . "&sortorder=$order&sorttype=2";

    echo ( "<th class=pcellheadsveryshort><a href=\"$lnk_a\"><img src=\"icons/icon_asc.gif\"></a> $vx<a href=\"$lnk_d\"><img src=\"icons/icon_desc.gif\"></a></th>\n"); 
}



function  eliminateSortVals($val)
{
    $newelems = array();
    $elems = explode ('&', $val);
    foreach ($elems as $k => $v){
	$v2elems = explode ('=',$v);
	if ($v2elems[0] != 'sorttype' && $v2elems[0] != 'sortorder'){
	    array_push ($newelems, "$v2elems[0]=$v2elems[1]");
	}
    }
    return implode ('&',$newelems);
}
function printDetailCellSmallHeads ($val){

    if ($_GET['lang']=='E'){
	echo ( "<th bgcolor=#ffffff class=ptextsmaller>$val</th>\n");
    }
    else {
	$val_tag   = get_uc("$val",'');
	echo ( "<th bgcolor=#ffffff class=ptextsmaller>$val_tag</th>\n");
    }
}
function buildArrayFromQuery($qry,$field){

    $array  = array();
    $res_funcQry = mysql_query($qry);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    $functions = array();
    while ($i < $num_funcQry){
       $func_name = mysql_result($res_funcQry, $i, "$field");
       array_push($array,"$func_name");
       $i++;
    }
    return $array;
}

function printDetailListingRows ($key, $val, $url){

    if ($url != ""){
	$url .= "&limit=$val";
    }

    $val_array = array();
    $pos = strpos($val, ",");
    $key_tag   = get_uc("$key",'');

    if ($url != ""){
	if ($_GET['lang']=='E'){
	    array_push($val_array,"<a href=\"$url\">$val</a>");
	}
	else {
	    $val_tag   = get_uc("$val",'');
	    array_push($val_array,"<a href=\"$url\">$val_tag</a>");
	}
    }
    else {
	if ($_GET['lang']=='E'){
	    array_push($val_array,"$val");
	}
	else {
	    $val_tag   = get_uc("$val",'');
	    array_push($val_array,"$val_tag");
	}
    }

    $print_string = implode(' ,',$val_array);

    if ($print_string != ""){ 
	if ($_GET['lang']=='E'){
	    echo ( "<tr><td class=prows>$key</td><td class=prows>$print_string</td></tr>\n");
	}
	else {
	    echo ( "<tr><td class=prows>$key_tag</td><td class=prows>$print_string</td></tr>\n");
	}	
    }
}

function displaySongTags ($start, $end, $total){
    $current = 'Shown Above';
    $full    = 'Total';

    if ($_GET['lang'] != 'E') { $current = get_uc($current,''); $full = get_uc($full,''); }

    return "<BR> $current $start : $end | $full $total<br>";


}

function printDetailDivs ($val,$link,$cnt){

    $val_array = array();
    $key_tag   = get_uc("$key",'');
    $lang = $_SESSION['lang'];	

    $pos = strpos($val, ",");

    if ($pos === false) {
	if ($lang =='E'){	
	    array_push($val_array,"$val");
	}
	else {
	    $valx_tag   = get_uc("$val",'');
	    array_push($val_array,"$valx_tag");
	}	
	$print_string = implode(' ,',$val_array);
    }

    else {
	$pos2 = strpos ($val, "Raagamalika");
	if ($pos2 === false) {
	    $vals = explode (',',$val);
	    foreach ($vals as $vale){
		$vale = ltrim(rtrim($vale));
		if ($lang =='E'){	
		    array_push($val_array,"$vale");
		}
		else {
		    $valx_tag   = get_uc("$vale",'');
		    array_push($val_array,"$valx_tag");
		}	
	    }
	    $print_string = implode(' ,',$val_array);
	}
	else {
	    $val = str_replace("Raagamalika",'',$val);
	    $val = str_replace("(",'',$val);
	    $val = str_replace(")",'',$val);
	    $val = ltrim(rtrim($val));
	    if ($_GET['debug1'] == 1) { echo "HERE: $val<BR>"; }
	    $vals = explode (',',$val);
	    foreach ($vals as $vale){
		$vale = ltrim(rtrim($vale));
		if ($lang =='E'){	
		    array_push($val_array,"$vale");
		    $rm = "Raagamalika (";
		}
		else {
		    $valx_tag   = get_uc("$vale",'');
		    array_push($val_array,"$valx_tag");
		    $rm = get_uc("Raagamalika",'') . " (";
		}	
	    }
	    $print_string = $rm . implode(' ,',$val_array) . ")";
	}
    }

    echo "<a href=\"$link\">$print_string</a>";
/*
    $printstyle='pcells';
    if ( $cnt&1 ) {
	$printstyle = 'pcellsodd';
    }

    if ($link) {
	echo ( "<div class=ptables><a href=\"$link\">$print_string</a></div>\n");
    }
    else {
	echo ( "<div class=ptables>$print_string</div>\n");
    }
*/
}


function printLyricsContents($url,$lang){
//    echo "<div class=ptextleft>\n";
  echo "<div style=\"width:800px;padding-left:50px;font-size:10.5pt;font-family:\"HelveticaNeue-Light\", \"Helvetica Neue Light\", \"Helvetica Neue\", Helvetica, Arial, \"Lucida Grande\", sans-serif;text-align:left;\" >\n";
    $fh = fopen($url, "r");
    $adduni=0;
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
//	    else {
//	    echo "(1) $origlx (2) $lx<BR>";
//	    }
	    $origlx = $lx;
	    $lx = stripslashes($lx);

	    $newlx = explode(' ', $origlx);
	    $bar = $newlx[0];
	    if (!$bar) { $bar = $origlx; }
	    if ($lang == "E"){
		if (preg_match("/---/",$lx)){
		    next;
		}
//		else if (preg_match("/^<br>$/",$lx)){
//		    next;
//		}
		else if (preg_match("/^<br><br>$/",$lx)){
		    next;
		}
		else if (preg_match("/^$/",$lx)){
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
		    $lx  = $result;
		    if ($lx != $savelx){
		       echo "$lx";
 		    }
		    $savelx = $lx;
//		    echo "$result";
//		    echo "<br>";
		    next;
		}
		else if (preg_match("/[^a-zA-Z0-9]/", $lx)){

		    if ($lx != $savelx){
		       echo "$lx";
 		    }
		    $savelx = $lx;
		}
	    }
	    else {

		if (preg_match("/Added by/",$lx)){
		    $result = preg_replace("/\<i\>Added (.+)\<\/i\>/", "", $lx);	
		    $lx  = $result;
		    if ($lx != $savelx){
		       echo "$lx";
 		    }
		    $savelx = $lx;
//		    echo "$result";
//		    echo "<br>";
		    $adduni=1;
		    next;
		}
		else if (preg_match("/a|e|i|o|u|m|s/",$lx) ){
		    if ($adduni == 1) { $adduni = 0; }
		    next;	
		}
		else {
		    if ($adduni == 1){
	    		    if ($lx != $savelx){
		              echo "$lx";
 		            }
		    $savelx = $lx;
                 }
		}
	    }
	}
	fclose($fh);
    }
    echo "<br></div>\n";
}
function PopularSongsAvailable($str, $mode)
{
    $len  = strlen($str);
    if ($len > 8){
	$val8 = substr($str,0,8);
    }
    else {
	$val8 = $str;
    }
    
    $val8 = str_replace("a","%a%",$val8);
    $val8 = str_replace("e","%e%",$val8);
    $val8 = str_replace(" ","%",$val8);
    $val8 = str_replace(",","%",$val8);
    $val8 = str_replace("t","t%",$val8);


    $songlist = array();
    $valregexp  = expandStr($str);
    $valregexp = str_replace('**','*',$valregexp);

    $qry = "SELECT songid from PSONGS WHERE songstr regexp \"$valregexp\" or songstr like \"%$val8%\" or songstr like \"%$str%\"";
    if ($mode == 'Albums'){
        $qry = "SELECT songid from PASONGS WHERE songstr regexp \"$valregexp\" or songstr like \"%$val8%\" or songstr like \"%$str%\"";
    }
    $songlist = buildArrayFromQuery($qry, 'songid');

    if ($songlist[0] == ''){
	$substr_str = substr($str,0,6);
	$addl_qry = "SELECT S_ID FROM SONGS WHERE SOUNDEX(S_SONG)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(S_SONG,1,6))= SOUNDEX (\"$substr_str\")";
	if ($mode == 'Albums'){
	    $addl_qry = "SELECT S_ID FROM ALBUMS WHERE SOUNDEX(S_SONG)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(S_SONG,1,6))= SOUNDEX (\"$substr_str\")";
	}
	$songlist = buildArrayFromQuery($addl_qry, 'S_ID');
    }




    if ($songlist[0] > 0){ 
	return 1 ;
    }      
    else {
	return 0;
    }
}
function printPopularSongs($str,$mode)
{
    $len  = strlen($str);
    if ($len > 8){
	$val8 = substr($str,0,8);
    }
    else {
	$val8 = $str;
    }

    $save_val8 = $val8;

    global $_Master_songlist_script;
    global $_Master_song_script;
    global $_Master_movie_script;
    $songScript = $_Master_song_script;
    $movieScript = $_Master_movie_script;
    $asongScript = $_Master_albumsong_script;
    $nonmovieScript = $_Master_album_script;

    $val8 = str_replace("a","%a%",$val8);
    $val8 = str_replace("e","%e%",$val8);
    $val8 = str_replace(" ","%",$val8);
    $val8 = str_replace(",","%",$val8);
    $val8 = str_replace("t","t%",$val8);
    

    $songlist = array();
    $valregexp  = expandStr($str);
    $valregexp = str_replace('**','*',$valregexp);
    $qry = "SELECT DISTINCT songid from PSONGS WHERE songstr regexp \"$valregexp\" or songstr like \"%$val8%\" or songstr like \"%$str%\"";
    if ($mode == 'Albums'){
        $qry = "SELECT DISTINCT songid from PASONGS WHERE songstr regexp \"$valregexp\" or songstr like \"%$val8%\" or songstr like \"%$str%\"";
    }
    if ($_GET['show_sql'] == 1) { echo $qry; }
    $songlist = buildArrayFromQuery($qry, 'songid');

    $gpct=75;
    $opct=50;
    $songorder = array();
    $noheader=1;
    foreach ($songlist as $sid){
	$query = "SELECT S_SONG FROM SONGS where S_ID=$sid";
	if ($mode == 'Albums'){
		$query = "SELECT S_SONG FROM ASONGS where S_ID=$sid";
	}
	$result        = mysql_query($query);
	$num_results   = mysql_num_rows($result);
	$i=0;
	if ($num_results > 0) {
	    while ($i < $num_results){
		$song_name_found = mysql_result($result, $i, "S_SONG");
	    	similar_text(substr($song_name_found,0,12),substr($str,0,12),$pct);
		$songorder[$sid]=$pct;
		if ($songorder[$sid] == ''){
		    similar_text(substr($song_name_found,0,8),substr($str,0,8),$pct);
		    $songorder[$sid]=$pct;
		}
		$i++;
	    }
	}
    }


    $substr_str = substr($str,0,6);
    $addl_qry = "SELECT S_ID FROM SONGS WHERE SOUNDEX(S_SONG)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(S_SONG,1,6))= SOUNDEX (\"$substr_str\")";
    if ($mode == 'Albums'){
        $addl_qry = "SELECT S_ID FROM ASONGS WHERE SOUNDEX(S_SONG)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(S_SONG,1,6))= SOUNDEX (\"$substr_str\")";
    }
    $addl_songslist = buildArrayFromQuery($addl_qry, 'S_ID');
    if ($_GET['debug2013'] == 1) { echo $addl_qry, ":", print_r($addl_songslist); }
    foreach ($addl_songslist as $aml) {
	if ($songorder[$aml] == '') {
	    $songorder[$aml] = 51;
	}
    }

    foreach ($addl_songslist as $aml) {
	if ($movieorder[$aml] == '') {
	    $query = "SELECT S_SONG FROM SONGS where S_ID=$aml";
	    if ($mode == 'Albums'){
		$query = "SELECT S_SONG FROM ASONGS where S_ID=$aml";
	    }
	    $mname = runQuery($query, 'S_SONG');
	    similar_text(substr($mname,0,8),substr($str,0,8),$pct);
	    $songorder[$aml]=$pct;
	    if ($_GET['debug2013'] == 1) { echo "$mname: $query: $aml is $pct<BR>";}
//	    if ($pct > 51) { $songorder[$aml] = $pct; }
//	    else { $songorder[$aml] = 51; }
	}
    }

    arsort($songorder);
    $printed_some_song = 0;
    foreach ($songorder as $sid=>$pc){
	if ($pc > $opct){
	    $query = "SELECT S_SONG,S_MOVIE,S_YEAR,S_SINGERS,S_MUSICIAN,S_WRITERS,S_RAGA,M_ID from SONGS where S_ID=$sid";
	    $url_songscript = $songScript;
	    $url_moviescript = $movieScript;
	    if ($mode == 'Albums'){
		$query = "SELECT S_SONG,S_MOVIE,S_YEAR,S_SINGERS,S_MUSICIAN,S_WRITERS,S_RAGA,M_ID from ASONGS where S_ID=$sid";
		$url_songscript = $asongScript;
		$url_moviescript = $nonmovieScript;
	    }
	    $result        = mysql_query($query);
	    $num_results   = mysql_num_rows($result);
	    $i=0;
	    if ($num_results > 0) {
		while ($i < $num_results){
		    $song_name_found = mysql_result($result, $i, "S_SONG");
		    if ($song_name_found != '' && $noheader==1){
		        $printed_some_song=1;
			$noheader=0;
			$popsongs = 'Popular Songs';
			if ($_GET['lang'] != 'E') { $popsongs = get_uc($popsongs,''); }
			echo "<div class=pleftsubheading>$popsongs</div>";
			echo "<table class=ptables>\n";
			echo "<tr class=tableheader>\n";
			printDetailCellHeadsSorts ('Song',1,"$_Master_songlist_script?${_qs}");
			printDetailCellHeadsSorts ('Movie',2,"$_Master_songlist_script?${_qs}");
			printDetailCellHeadsSortsSmall ('Year',3,"$_Master_songlist_script?${_qs}");
			printDetailCellHeadsSorts ('Musician',4,"$_Master_songlist_script?${_qs}");
			printDetailCellHeadsSorts ('Lyricist',4,"$_Master_songlist_script?${_qs}");
			printDetailCellHeadsSorts ('Singers',5,"$_Master_songlist_script?${_qs}");
			echo "</tr>\n";
		    }
		    $mid = mysql_result($result, $i, "M_ID");
		    echo "<tr class=ptableslist>\n";
		    printDetailCells ("$song_name_found","$url_songscript?$sid",$i);
		    printDetailCells (mysql_result($result, $i, "S_MOVIE"),"$url_moviescript?$mid",$i);
		    printDetailCellsSmall (mysql_result($result, $i, "S_YEAR"),'',$i);
		    printDetailCells (mysql_result($result, $i, "S_MUSICIAN"),'',$i);
		    printDetailCells (mysql_result($result, $i, "S_WRITERS"),'',$i);
		    printDetailCells (mysql_result($result, $i, "S_SINGERS"),'',$i);
		    echo "</tr>";
		    $i++;
		}
	    }
	}	    
    }
    if ($_GET['debug2013'] == 1) { echo print_r($songlist); }
    if ($songlist[0] > 0 || $addl_songslist[0] > 0) { echo "</table>"; }
//    if ($songlist[0] > 0) { return 1; } else { return 0; }
      return $printed_some_song;
}

function PopularMoviesAvailable($str, $mode)
{
    $len  = strlen($str);
    if ($len > 8){
	$val8 = substr($str,0,8);
    }
    else {
	$val8 = $str;
    }
    
    $val8 = str_replace("a","%a%",$val8);
    $val8 = str_replace("e","%e%",$val8);
    $val8 = str_replace(" ","%",$val8);
    $val8 = str_replace(",","%",$val8);
    $val8 = str_replace("t","t%",$val8);


    $movielist = array();
    $valregexp  = expandStr($str);
    $valregexp = str_replace('**','*',$valregexp);

    $qry = "SELECT movieid from PMOVIES WHERE moviestr regexp \"$valregexp\" or moviestr like \"%$val8%\" or moviestr like \"%$str%\"";
    if ($mode == 'Albums'){
        $qry = "SELECT movieid from PAMOVIES WHERE moviestr regexp \"$valregexp\" or moviestr like \"%$val8%\" or moviestr like \"%$str%\"";
    }
    if ($_GET['debug2013'] == 1) { echo $qry; }
    $movielist = buildArrayFromQuery($qry, 'movieid');

    if ($movielist[0] == ''){
	$substr_str = substr($str,0,6);
	$addl_qry = "SELECT M_ID FROM MOVIES WHERE SOUNDEX(M_MOVIE)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(M_MOVIE,1,6))= SOUNDEX (\"$substr_str\")";
	if ($mode == 'Albums'){
	    $addl_qry = "SELECT M_ID FROM ALBUMS WHERE SOUNDEX(M_MOVIE)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(M_MOVIE,1,6))= SOUNDEX (\"$substr_str\")";
	}
	$movielist = buildArrayFromQuery($addl_qry, 'M_ID');
    }

    if ($movielist[0] > 0){ 
	return 1 ;
    }      
    else {
	return 0;
    }
}
function printPopularMovies($str,$mode)
{
    $len  = strlen($str);
    if ($len > 8){
	$val8 = substr($str,0,8);
    }
    else {
	$val8 = $str;
    }

    $save_val8 = $val8;

    global $_Master_songlist_script;
    global $_Master_song_script;
    global $_Master_movie_script;
    global $_Master_album_script;
    global $_Master_albumsong_script;

    $songScript = $_Master_song_script;
    $movieScript = $_Master_movie_script;
    $nonmovieScript = $_Master_album_script;
    $albumsongScript = $_Master_albumsong_script;

    $val8 = str_replace("a","%a%",$val8);
    $val8 = str_replace("e","%e%",$val8);
    $val8 = str_replace(" ","%",$val8);
    $val8 = str_replace(",","%",$val8);
    $val8 = str_replace("t","t%",$val8);
    

    $movielist = array();
    $valregexp  = expandStr($str);
    $valregexp = str_replace('**','*',$valregexp);
    $qry = "SELECT DISTINCT movieid from PMOVIES WHERE moviestr regexp \"$valregexp\" or moviestr like \"%$val8%\" or moviestr like \"%$str%\"";
    if ($mode == 'Albums'){
        $qry = "SELECT DISTINCT movieid from PAMOVIES WHERE moviestr regexp \"$valregexp\" or moviestr like \"%$val8%\" or moviestr like \"%$str%\"";
    }
    if ($_GET['show_sql'] == 1) { echo $qry; }
    $movielist = buildArrayFromQuery($qry, 'movieid');

    $gpct=75;
    $opct=50;
    $movieorder = array();
    $noheader=1;

    foreach ($movielist as $mid){

	$query = "SELECT M_MOVIE FROM MOVIES where M_ID=$mid";
	if ($mode == 'Albums'){
		$query = "SELECT M_MOVIE FROM ALBUMS where M_ID=$mid";
	}
	$result        = mysql_query($query);
	$num_results   = mysql_num_rows($result);
	$i=0;
	if ($num_results > 0) {
	    while ($i < $num_results){
		$movie_name_found = mysql_result($result, $i, "M_MOVIE");
	    	similar_text(substr($movie_name_found,0,12),substr($str,0,12),$pct);
		$movieorder[$mid]=$pct;
		if ($movieorder[$mid] == ''){
		    similar_text(substr($movie_name_found,0,8),substr($str,0,8),$pct);
		    $movieorder[$mid]=$pct;
		}
		$i++;
	    }
	}
    }

    $substr_str = substr($str,0,6);
    $addl_qry = "SELECT M_ID FROM MOVIES WHERE SOUNDEX(M_MOVIE)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(M_MOVIE,1,6))= SOUNDEX (\"$substr_str\")";
    if ($mode == 'Albums'){
        $addl_qry = "SELECT M_ID FROM ALBUMS WHERE SOUNDEX(M_MOVIE)=SOUNDEX(\"$str\") OR SOUNDEX(SUBSTRING(M_MOVIE,1,6))= SOUNDEX (\"$substr_str\")";
    }
    $addl_movieslist = buildArrayFromQuery($addl_qry, 'M_ID');
    if ($_GET['debug2013'] == 1) { echo $addl_qry, ":", print_r($addl_movieslist); }
    foreach ($addl_movieslist as $aml) {
	if ($movieorder[$aml] == '') {
	    $query = "SELECT M_MOVIE FROM MOVIES where M_ID=$aml";
	    if ($mode == 'Albums'){
		$query = "SELECT M_MOVIE FROM ALBUMS where M_ID=$aml";
	    }
	    $mname = runQuery($query, 'M_MOVIE');
	    similar_text(substr($mname,0,8),substr($str,0,8),$pct);
	    $movieorder[$aml]=$pct;
//	    if ($pct > 51) { $movieorder[$aml] = $pct; }
//	    else { $movieorder[$aml] = 51; }
	}
    }

   if ($_GET['debug2013'] == 1) { print_r($movieorder);}

    $url_songscript ='';
    $url_moviescript = '';

    arsort($movieorder);
    foreach ($movieorder as $mid=>$pc){
	if ($pc > $opct){
	    $query = "SELECT M_MOVIE,M_YEAR,M_MUSICIAN,M_WRITERS,M_DIRECTOR from MOVIES where M_ID=$mid";
	    $url_songscript  = $songScript;
	    $url_moviescript = $movieScript;
	    if ($mode == 'Albums'){
		$query = "SELECT M_MOVIE,M_YEAR,M_MUSICIAN,M_WRITERS,M_DIRECTOR from ALBUMS where M_ID=$mid";
		$url_songscript  = $albumsongScript;
		$url_moviescript = $nonmovieScript;
	    }
	    $result        = mysql_query($query);
	    $num_results   = mysql_num_rows($result);
	    $i=0;
	    if ($num_results > 0) {
		while ($i < $num_results){
		    $movie_name_found = mysql_result($result, $i, "M_MOVIE");

		    if ($movie_name_found != '' && $noheader==1){

			$noheader=0;
			$popmovies = 'Popular Movies';
			if ($mode == 'Albums') { $popmovies = 'Popular Albums';}
			if ($_GET['lang'] != 'E') { $popmovies = get_uc($popmovies,''); }
			echo "<div class=pleftsubheading>$popmovies</div>";
			echo "<table class=ptables>\n";
			echo "<tr class=tableheader>\n";
			if ($mode == 'Albums') {
			    printDetailCellHeadsSorts ('Album',1,"$_Master_movielist_script?${_qs}");
			}
			else {
			    printDetailCellHeadsSorts ('Movie',1,"$_Master_movielist_script?${_qs}");
			}
			printDetailCellHeadsSortsSmall ('Year',2,"$_Master_movielist_script?${_qs}");
			printDetailCellHeadsSorts ('Musician',3,"$_Master_movielist_script?${_qs}");
			printDetailCellHeadsSorts ('Lyricist',4,"$_Master_movielist_script?${_qs}");
			if ($mode == 'Albums'){
			    printDetailCellHeadsSorts ('Label',5,"$_Master_movielist_script?${_qs}");
			}
			else {
			    printDetailCellHeadsSorts ('Director',5,"$_Master_movielist_script?${_qs}");
			}

			echo "</tr>\n";
		    }

//		    $mid = mysql_result($result, $i, "M_ID");
		    echo "<tr class=ptableslist>\n";
		    printDetailCells ($movie_name_found,"$url_moviescript?$mid",$i);
		    printDetailCellsSmall (mysql_result($result, $i, "M_YEAR"),'',$i);
		    printDetailCells (mysql_result($result, $i, "M_MUSICIAN"),'',$i);
		    printDetailCells (mysql_result($result, $i, "M_WRITERS"),'',$i);
		    printDetailCells (mysql_result($result, $i, "M_DIRECTOR"),'',$i);
		    echo "</tr>";
		    $i++;
		}
	    }
	}	    
    }

    if ($movielist[0] > 0 || $addl_movieslist[0] > 0) { echo "</table>"; }
    if ($movielist[0] > 0 || $addl_movieslist[0] > 0) { return 1; } else { return 0; }
}

function expandStr($val){
    $len  = strlen($val);
    $vals = str_split($val);
    $qelems = '';

    $lenstart = 0;
    while ($lenstart < $len){
	if ($vals[$lenstart] != $prev && $vals[$lenstart] != 'h'){
	    $astr    = getAlternateLetters($vals[$lenstart]);
	    $astr    = str_replace('sh','s',$astr);
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

function printDetailCellsSmall ($val,$link,$cnt){

    $val_array = array();
    $key_tag   = get_uc("$key",'');
    $lang = $_SESSION['lang'];	

    if ($_GET['debug1'] == 1) { echo "HERE1: $val<BR>"; }
    $pos = strpos($val, ",");

    if ($pos === false) {
	if ($lang =='E'){	
	    array_push($val_array,"$val");
	}
	else {
	    $valx_tag   = get_uc("$val",'');
	    array_push($val_array,"$valx_tag");
	}	
	$print_string = implode(' ,',$val_array);
    }

    else {
	$pos2 = strpos ($val, "Raagamalika");
	if ($pos2 === false) {
	    $vals = explode (',',$val);
	    foreach ($vals as $vale){
		$vale = ltrim(rtrim($vale));
		if ($lang =='E'){	
		    array_push($val_array,"$vale");
		}
		else {
		    $valx_tag   = get_uc("$vale",'');
		    array_push($val_array,"$valx_tag");
		}	
	    }
	    $print_string = implode(' ,',$val_array);
	}
	else {
	    $val = str_replace("Raagamalika",'',$val);
	    $val = str_replace("(",'',$val);
	    $val = str_replace(")",'',$val);
	    $val = ltrim(rtrim($val));
	    if ($_GET['debug1'] == 1) { echo "HERE: $val<BR>"; }
	    $vals = explode (',',$val);
	    foreach ($vals as $vale){
		$vale = ltrim(rtrim($vale));
		if ($lang =='E'){	
		    array_push($val_array,"$vale");
		    $rm = "Raagamalika (";
		}
		else {
		    $valx_tag   = get_uc("$vale",'');
		    array_push($val_array,"$valx_tag");
		    $rm = get_uc("Raagamalika",'') . " (";
		}	
	    }
	    $print_string = $rm . implode(' ,',$val_array) . ")";
	}
    }
//  $print_string = implode(' ,',$val_array);


//    $printstyle='pcells';
    $printstyle='prowsveryshort';
    if ( $cnt&1 ) {
	$printstyle = 'prowsveryshortodd';
    }


    if ($link) {
	echo ( "<td class=$printstyle><a href=\"$link\">$print_string</a></td>\n");
    }
    else {
	echo ( "<td class=$printstyle>$print_string</td>\n");
    }
}

?>
