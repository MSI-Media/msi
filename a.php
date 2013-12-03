<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("includes/cache.php");
/*
    if (isNonAdminUser()){
	$ch = new cache($_GET['lang'],'Albums');
    }
*/
    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('Popup');
    $mid  = $_GET['mid'];
    if (!$mid){
	$mids = explode('&',$_SERVER['QUERY_STRING']);
	$mid = $mids[0];
    }
    $lang = $_GET['lang'];

    $query      = "SELECT * FROM ALBUMS WHERE ALBUMS.M_ID=$mid";
    $result     = mysql_query($query);
    $num_results=mysql_num_rows($result);
    $i=0;
    if ($num_results == 0){
	printContents("Writeups/MissingAlbum.html");
    }
    else {
	$i=0;
	$corv = "images/icon-edit.gif";
	$picv = "icons/Movie.png";

	$corvt = "Change the Details on this Album";
	$picvt = "Add a Picture of This Album";

	while ($i < $num_results){
	    $today = date("F j, Y, g:i a T");
//	    echo( "<div align=center class=fixedtiny>This file was generated on $today</div>");
	    $movieName = mysql_result($result,$i,"ALBUMS.M_MOVIE");
	    $mal_movieName = get_uc($movieName,'');
	    $myear = mysql_result($result,$i,"ALBUMS.M_YEAR");
	    $mal_myear = get_uc($myear,"");

            //$submissionMessage = file_get_contents("Writeups/albumSubmissionMessage.txt");
	    if ($_GET['lang'] == 'E'){
		echo "<div class=pheading>$movieName ($myear) </div>";
                //$submissionMessage = file_get_contents("Writeups/albumSubmissionMessageEnglish.txt");
	    }
	    else {
		$corvt = get_uc("$corvt",'');
		$picvt = get_uc("$picvt",'');
		echo "<div class=pheading>$mal_movieName ($mal_myear) </div>";
	    }

	$tt_details = "Change Details";
	$tt_picture = "Add a Poster or Picture of this album";

	if ($_GET['lang'] != 'E'){
	    $tt_details = get_uc($tt_details,'');
	    $tt_picture = get_uc($tt_picture,'');
        }



            if (file_exists("albumpics/${mid}.jpg") || file_exists("uploaded_album_pictures/${mid}.jpg")){
               echo "<div class=psubtitle> $submissionMessage <a href=\"editAlbumIndex.php?display=Change&id=$mid\"><img alt=\"$corvt\" class=\"tooltip\" title=\"$tt_details\" src=\"$corv\" height=20 border=0></a>  <a href=\"mailto:msiadmins@googlegroups.com?Subject=MSI 2012 [msidb.org] Additional Pictures for $movieName ($mid)\"><img src=\"$picv\" class=\"tooltip\" title=\"$tt_picture\" border=0 height=20></a> </div><P>";
            }
            else {
	       echo "<div class=psubtitle> $submissionMessage <a href=\"editAlbumIndex.php?display=Change&id=$mid\"><img alt=\"$corvt\" class=\"tooltip\" title=\"$tt_details\" src=\"$corv\" height=20 border=0></a>  <a href=\"submitPictures.php?mid=$mid&mode=ALBUMS\"><img src=\"$picv\" class=\"tooltip\" title=\"$tt_picture\" alt=\"$picvt\" border=0 height=20></a> </div><P>";
            }


            echo( "<table class=ptables>\n");
	    echo "<tr><td align=center>\n";	
	    printConsolidatedAlbumPictures ($mid,'Albums');
	    echo "</td></tr>\n";
	    echo "</table>";
	    $icnt = $i;
	    echo( "<table class=ptables>\n");
	    printDetailRows ('Label',mysql_result($result,$i,"ALBUMS.M_DIRECTOR"),'',$icnt++);
	    printDetailRows ('Musician',mysql_result($result,$i,"ALBUMS.M_MUSICIAN"),'Musicians',$icnt++);
	    printDetailRows ('Lyricist',mysql_result($result,$i,"ALBUMS.M_WRITERS"),'Lyricists',$icnt++);
	    printDetailRows ('Genre',mysql_result($result,$i,"ALBUMS.M_COMMENTS"),'Genres',$icnt++);
	    printDetailRows ('Singers',getDistinctSingers ($mid),'Singers',$icnt++);
	    $numSongs = runQuery("SELECT COUNT(S_ID) ccn from ASONGS WHERE M_ID=$mid",'ccn',$icnt++);
	    printDetailRows ('Number of Songs',$numSongs,'',$icnt++);
	    echo ("</table>");
/*
           echo "</td><tr><td>&nbsp;</td><td>\n";

*/
            echo "</td></tr></table>";
	    printFB($mid,'Albums');
//	    printAStills ($mid);
	    printSongs($mid,'Albums');
	    printContributors($mid,'Albums');

	    $i++;
	}
    }

    
    //$today = date("F j, Y, g:i a T");
    //echo( "<div align=center class=fixedtiny>This page was generated on $today</div>");

    $regenMsg = "Regenerate This Page";
    if ($_GET['lang'] != 'E') { $regenMsg = get_uc($regenMsg,''); }
    $today = date("F j, Y, g:i a T");
    echo( "<table class=ptables><tr><td align=center class=fixedtiny>This page was generated on $today | <a href=\"SecureCache.php?type=$_Master_album_script&typename=Albums&id=$mid\">$regenMsg</a> | <a href=\"http://msidb.org/admin/UpdateAlbum.php?id=$mid\"><img src=\"images/small_lock.jpg\" border=0></a></td></tr></table>");
    printFancyFooters();
    mysql_close($cLink);
/*
    if (isNonAdminUser()){
	$ch->close();
    }
*/
}
function printAlbumPictures ($mid,$tag,$mov,$mmov){

    $picPath1  = "albumpics";	
    $pic_array = array();
    global $_MasterRootDir;
    $picPath2 = "$_MasterRootDir/_aPhotosfrmBlog";
    $picPath2URL = "$_MasterRootofMSI/_aPhotosfrmBlog";

    if (file_exists("$picPath1/${mid}.jpg")){
	array_push($pic_array,"$picPath1/${mid}.jpg");
    }

    if (file_exists("$picPath2/${mid}.jpg")){
	array_push($pic_array,"$picPath2URL/${mid}.jpg");
    }

    foreach (range(0,20) as $number) {
  
       if (file_exists("$picPath2/${mid}_$number.jpg")){
         array_push($pic_array,"$picPath2URL/${mid}_$number.jpg");
      } 
    }
    if ($pic_array[0] != ""){
        echo "<table class=ptables><tr><td>\n";
    	echo "<div class=pheading>\n";
	foreach ($pic_array as $pics){

	    echo "<a href=\"$pics\" class=\"preview\"><img src=\"$pics\" border=0 height=200 onclick=\"javascript:return false;\" onMouseOver=\"javascript:window.status=''\" onmousedown=\"if(event.button==2){return false;}\"></a>\n";
	}
    	echo "</div>\n";		
        echo "</td></tr></table>";
    }

}


function printConsolidatedAlbumPictures ($mid,$tag,$mov,$mmov){

    global $_MasterRootDir;
    $_GDMasterRootofMSI = "http://msidb.info";
    $picPath1  = "albumpics";
    $picPath2 = "$_MasterRootDir/_aPhotosfrmBlog";
    $picPath2URL = "$_MasterRootofMSI/_aPhotosfrmBlog";

    if ($tag == 'Albums'){
        $picPath1  = "albumpics";	
    }
    $pic_array = array();

    if (file_exists("$picPath1/${mid}.jpg")){
	array_push($pic_array,"$picPath1/${mid}.jpg");
    }

    if (file_exists("$picPath2/${mid}.jpg")){
	array_push($pic_array,"$picPath2URL/${mid}.jpg");
    }
    foreach (range(0,20) as $number) {
       if (file_exists("$picPath2/${mid}_$number.jpg")){
         array_push($pic_array,"$picPath2URL/${mid}_$number.jpg");
      } 
    }
//    echo "<div class=pheading>\n";
    if ($pic_array[0] != ""){
	foreach ($pic_array as $pics){
	    echo "<a href=\"$pics\" class=\"preview\" style=\"border:1px solid #fff;\" src=\"$vals[3]\"  ><img src=\"$pics\" border=0 height=100 width=100 onclick=\"javascript:return false;\" onmousedown=\"if(event.button==2){return false;}\">\n";
	}
    }
//    echo "</div>\n";
}


function getDistinctSingers($mid){
    $singers = array();

    foreach (buildArrayFromQuery("SELECT DISTINCT S_SINGERS FROM ASONGS WHERE M_ID=\"$mid\"",'S_SINGERS') as $sns){
	$sns_array = explode(',',$sns);
	foreach ($sns_array as $sn){
	    $sn = trim($sn);
	    if ($sn != "Chorus" && $sn != "-" && $sn != "Insrtumental") {
		if (!in_array("$sn",$singers)){
		    array_push ($singers,"$sn");
		}
	    }
	}
    }
    sort($singers);
    return implode (',',$singers);
}

?>
