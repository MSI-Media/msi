<?php

$tot_years = array();
$doLines   = 0;
function artistData ($artist, $category, $class)
{


    $artistname = get_uc("$artist",'');
    $categoryname        = get_uc("$category",'');

    global $_Master_movielist_script, $_Master_albumlist_script,$_Master_songlist_script,$_Master_albumsonglist_script,$_Master_ragas;
    global $_Master_Singers_script,$_Master_profile;
    $songListScript      = $_Master_songlist_script;
    $albumSongListScript = $_Master_albumsonglist_script;


    if ($_GET['lang'] == 'E'){
	$artistname = $artist;
	$categoryname= $category;
    }
    if ($category == 'director'){
	$pictureLeaf = 'pics/Directors';
	$writeupLeaf = 'Writeups/Directors';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (MOVIES.M_DIRECTOR=\"$artist\" or MOVIES.M_DIRECTOR like \"$artist,%\" or MOVIES.M_DIRECTOR like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category == 'year'){
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and MOVIES.M_YEAR=\"$artist\"  order by M_YEAR LIMIT 500";
    }
    else if ($category == 'producer'){
	$pictureLeaf = 'pics/Producers';
	$writeupLeaf = 'Writeups/Producers';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_BANNER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.M_PRODUCER=\"$artist\"  or  MDETAILS.M_PRODUCER like \"$artist,%\" or MDETAILS.M_PRODUCER like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category == 'editor'){
	$pictureLeaf = 'pics/Editors';
	$writeupLeaf = 'Writeups/Editors';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.M_EDITOR=\"$artist\" or  MDETAILS.M_EDITOR like \"$artist,%\" or MDETAILS.M_EDITOR like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category  == 'genre'){
	$limit = runQuery("SELECT COUNT(M_ID) ccn from ALBUMS WHERE M_COMMENTS=\"$artist\"",'ccn');
	echo "<script>location.replace(\"$_Master_albumlist_script?$category=$artist&limit=$limit\");</script>";
    }
    else if ($category == 'camera'){
	$pictureLeaf = 'pics/Camera';
	$writeupLeaf = 'Writeups/Camera';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.M_CAMERA=\"$artist\"  or  MDETAILS.M_CAMERA like \"$artist,%\" or MDETAILS.M_CAMERA like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category == 'distribution'){
	$writeupLeaf = 'Writeups/Distributors';
    ;
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.M_DISTRIBUTION=\"$artist\"  or  MDETAILS.M_DISTRIBUTION like \"$artist,%\" or MDETAILS.M_DISTRIBUTION like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category == 'banner'){
	$writeupLeaf = 'Writeups/Banners';
        $pictureLeaf = 'pics/Banners';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.M_BANNER=\"$artist\"  or  MDETAILS.M_BANNER like \"$artist,%\" or MDETAILS.M_BANNER like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category == 'art director' || $category == 'art'){
	$pictureLeaf = 'pics/Art';
	$writeupLeaf = 'Writeups/Art';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.M_ART=\"$artist\"  or  MDETAILS.M_ART like \"$artist,%\" or MDETAILS.M_ART like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category == 'design')
    {
	$pictureLeaf = 'pics/Design';
	$writeupLeaf = 'Writeups/Design';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and (M_DESIGN=\"$artist\"  or  MDETAILS.M_DESIGN like \"$artist,%\" or MDETAILS.M_DESIGN like \"%,$artist\") order by M_YEAR LIMIT 500";
    }
    else if ($category == 'date of release')
    {
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and M_PRODEXEC=\"$artist\" order by MOVIES.M_MOVIE LIMIT 500";
    }
    else if ($category == 'singers')
    {
	$pictureLeaf = 'pics/Singers';
	$writeupLeaf = 'Writeups/Singers';
	$ListQry = "SELECT SONGS.S_MUSICIAN,SONGS.S_WRITERS,SONGS.S_RAGA,SONGS.S_YEAR from SONGS where (SONGS.S_SINGERS like \"$artist\" or SONGS.S_SINGERS like \"$artist,%\" or SONGS.S_SINGERS like \"%,$artist\" or SONGS.S_SINGERS like \"%,$artist,%\") order by S_YEAR";
    }
    else if ($category == 'actors')
    {
	$pictureLeaf = 'pics/Actors';
	$writeupLeaf = 'Writeups/Actors';
	

     //$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER,M_STORY from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID AND (MDETAILS.M_CAST like \"%,$artist,%\" or MDETAILS.M_CAST like \"%, $artist,%\" or MDETAILS.M_CAST like \"%,$artist ,%\" or MDETAILS.M_CAST like \"$artist,%\" or MDETAILS.M_CAST like \"%, $artist\" or MDETAILS.M_CAST like \"%,$artist\" or MDETAILS.M_CAST like \"$artist ,%\")order by MOVIES.M_YEAR LIMIT 1000;";

$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER,M_STORY from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID AND ( MDETAILS.M_CAST like \"$artist\" or MDETAILS.M_CAST like \"$artist,%\" or MDETAILS.M_CAST like \" $artist ,%\" or MDETAILS.M_CAST like \"%, $artist\" or MDETAILS.M_CAST like \"%,$artist\" or MDETAILS.M_CAST like \"%,$artist,%\" or MDETAILS.M_CAST like \"%, $artist,%\" or MDETAILS.M_CAST like \"%, $artist ,%\" or MDETAILS.M_CAST like \"%,$artist ,%\" or MDETAILS.M_CAST like \"%,$artist\"   or MDETAILS.M_CAST like \"%, $artist\"   or MDETAILS.M_CAST like \"$artist,%\"  or MDETAILS.M_CAST like \"%,$artist ,%\" or MDETAILS.M_CAST like \"%$artist ,%\" )order by MOVIES.M_YEAR LIMIT 1000;";


    }
    else if ($category == 'lyricist')
    {
	$pictureLeaf = 'pics/Lyricists';
	$writeupLeaf = 'Writeups/Lyricists';
    }
    else if ($category == 'musician')
    {
	$pictureLeaf = 'pics/Musicians';
	$writeupLeaf = 'Writeups/Composers';
    }
    else if ($category == 'bgm' || $category == 'background music')
    {
	$pictureLeaf = 'pics/Musicians';
	$writeupLeaf = 'Writeups/Composers';
	$ListQry = "SELECT MOVIES.M_ID,MOVIES.M_YEAR,MOVIES.M_MOVIE,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER from MOVIES,MDETAILS where MOVIES.M_ID=MDETAILS.M_ID and M_BGM like \"%$artist%\" order by M_YEAR LIMIT 500";
    }
    else if ($category == 'story' || $category == 'dialog' || $category == 'screenplay')
    {
	if ($category == 'story'){
	    $relevantColumn = 'M_STORY';;
	}
	else if ($category == 'dialog'){
	    $relevantColumn = 'M_DIALOG';
	}
	else {
	    $relevantColumn = 'M_SCREENPLAY';
	} 
	$pictureLeaf = 'pics/Screenplay';
	$writeupLeaf = 'Writeups/Screenplay';
	$titleTag    =  get_uc(ucfirst("$category"),'');
	$ListQry     = "SELECT MOVIES.M_ID,MOVIES.M_MOVIE,MOVIES.M_YEAR,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER,MDETAILS.M_STORY,MDETAILS.M_DIALOG,MDETAILS.M_SCREENPLAY FROM MOVIES,MDETAILS WHERE MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.$relevantColumn=\"$artist\" or MDETAILS.$relevantColumn like \"%,$artist%\"  or MDETAILS.$relevantColumn like \"$artist,%\")  order by M_YEAR LIMIT 500";
    }
    else if ($category == 'sound')
    {
	$pictureLeaf = 'pics/Sound';
	$writeupLeaf = 'Writeups/Sound';
    }
    else if ($category == 'still')
    {
	$pictureLeaf = 'pics/Still';
	$writeupLeaf = 'Writeups/Still';
    }
    else if ($category == 'choreography')
    {
	$pictureLeaf = 'pics/Choreography';
	$writeupLeaf = 'Writeups/Choreography';
    }
    else if ($category == 'makeup')
    {
	$pictureLeaf = 'pics/Makeup';
	$writeupLeaf = 'Writeups/Makeup';
    }
    else if ($category == 'critics')
    {
	$pictureLeaf = 'pics/Critics';
	$writeupLeaf = 'Writeups/Critics';
    }
    else if ($category == 'dubbing')
    {
	$pictureLeaf = 'pics/Dubbing';
	$writeupLeaf = 'Writeups/Dubbing';
    }
    $ucfCategory = ucfirst($category);

    $picArray = array();
    $picpos = strpos($artist, ",");
    if ($picpos === false){
	if ($category == 'lyricist'){
	    $origartist = $artist;
	    $artistname = stripTrads($artist);
	}
	else {
	 $artistname = $artist;
	 $origartist = $artistname;
       }     
	$artistFileName  = similar_file_exists("$pictureLeaf/${artistname}.jpg");
	if (file_exists("${artistFileName}"))
	{
	    array_push ($picArray, "${artistFileName}");
	}
    }
    else
    { 
	$pics = explode(',',$artist);
	foreach ($pics as $artst){
	    $artst = ltrim(rtrim($artst));
	    $artistFileName  = similar_file_exists("$pictureLeaf/${artst}.jpg");
	    if (file_exists("${artistFileName}"))
	    {
		array_push ($picArray, "${artistFileName}");
	    }
	}
    }

    if ($_GET['lang'] != 'E'){
       $artistname = get_uc($artistname);
    }

    $cate = substr("$category", 0, -1);	
    if ($_GET['debug1'] == 1) { echo     "SELECT category from MARTISTS WHERE name=\"$artist\" and category like \"%$cate%\"<BR>"; echo "$cate<BR>";}
    $areas_of_work = runQuery("SELECT category from MARTISTS WHERE name=\"$artist\" and category like \"%$cate%\" ",'category');

    if ( $areas_of_work != '') {    
      if ($picArray[0] == ''){
         $areas = explode(',',$areas_of_work);
    if ($_GET['debug1'] == 1) { print_r($areas); echo $ucfCategory;}
    $ucfCategoryLimited   = substr("$ucfCategory", 0, -1);	
         if (in_array("$ucfCategory",$areas) || in_array("$ucfCategoryLimited",$areas)){
         foreach ($areas as $area){
	 if ($area == 'Art Director') { $area = 'Art'; }
            if ($_GET['debug1'] == 1)  { echo "pics/$area/${artist}.jpg<BR>"; }

	    $artistFileName   = similar_file_exists("pics/$area/${artist}.jpg");
	    $artistFilesName  = similar_file_exists("pics/${area}s/${artist}.jpg");
	    if (file_exists("${artistFileName}"))
	    {
		array_push ($picArray, "${artistFileName}");
		if ($_GET['debug1'] == 1)  { echo "$artistFileName<BR>";}
		break;
	    }
	    else if (file_exists("${artistFilesName}")){
		array_push ($picArray, "${artistFilesName}");
		if ($_GET['debug1'] == 1)  { echo "$artistFilesName<BR>";}
		break;
            }
/*
            if (file_exists("pics/$area/${artist}.jpg")){
               array_push ($picArray,"pics/$area/${artist}.jpg");
               break;
            }
            else if (file_exists("pics/${area}s/${artist}.jpg")){
               array_push ($picArray,"pics/${area}s/${artist}.jpg");
               break;
            }
*/
	 }
         }
      }
 
    }
    echo "<div class=pheading>$artistname </div>\n";
    $dob = runQuery("SELECT CONCAT(birth,'-',death)  ccn FROM DOB  WHERE artist=\"$artist\"",'ccn');
    if ($dob != '') {
	$dob = str_replace('-0','-',$dob);
	echo "<div class=psubtitle>$dob </div>\n";
    }
    echo "<div class=psubheading>",ucfirst($categoryname),"<BR>";
    $comprehensivelist = "View The Comprehensive List";
    $catdisplayname    = ucwords($category);
    if ($_GET['lang'] != 'E'){
	$comprehensivelist = get_uc($comprehensivelist,'');
	$catdisplayname    = get_uc($category,'');
    }
    if ($category == 'singers'){
	echo "<div class=psubtitle><a href=\"$_Master_Singers_script\">$catdisplayname - $comprehensivelist</a></div>\n";
    }
    else if ($category == 'raga'){
	echo "<div class=psubtitle><a href=\"$_Master_ragas\">$catdisplayname - $comprehensivelist</a></div>\n";
    }
    else {
	echo "<div class=psubtitle><a href=\"$_Master_profile?category=$category\">$catdisplayname - $comprehensivelist</a></div>\n";
    }
    if ($category == 'raga'){
	$ragaheader = 'Movie Songs';
	$aragaheader = 'Album Songs';
	$martist = $artist;
	if ($_GET['lang'] != 'E'){
	    $aragaheader = get_uc($aragaheader,'');
	    $ragaheader = get_uc($ragaheader,'');
	    $martist = get_uc($artist,'');
	}
	$rlimit = runQuery("SELECT COUNT(S_ID) ccn from SONGS WHERE S_RAGA like \"%$artist%\"",'ccn');
	$ralimit = runQuery("SELECT COUNT(S_ID) ccn from ASONGS WHERE S_RAGA like \"%$artist%\"",'ccn');
	$rslink = "$_Master_songlist_script?tag=Search&raga=$artist&limit=$rlimit&page_num=1";
	$raslink = "$_Master_albumsonglist_script?tag=Search&raga=$artist&limit=$ralimit&page_num=1";

	echo "<div class=psubtitle>$martist: <a href=\"$rslink\">$ragaheader</a> | <a href=\"$raslink\">$aragaheader</a></div>\n";
    }
    if ($category != "raga" && $category != "banner" && $category != "distribution"){
	foreach ($picArray as $pic){
	    if ($pic) {
		echo "<img class=\"preview\" src=\"$pic\" border=0 height=100>\n";
	    }
	}
    }
    echo "</div><P>";

  
    if ($areas_of_work != ''){
	printArrayList($artist,'Areas of Contributions',$areas_of_work);
    }

    $relatives = runQuery("SELECT COUNT(id) ccn from RELATIVES where artist1=\"$artist\" or artist2=\"$artist\"",'ccn');
    if ($relatives > 0) { printRelationshipsList($artist,$category);}

    printAdditionalArtistPictures($category,$artist);
    echo "<div class=pcellslong>\n";
  
    if ($_GET['lang'] != 'E'){
	if (file_exists("${writeupLeaf}/${artist}.txt")){
	    $writeup = "${writeupLeaf}/${artist}.txt";
	    printContents("$writeup");
	}
	else if (file_exists("${writeupLeaf}/${artist}.html")){
	    $writeup = "${writeupLeaf}/${artist}.html";
	    printContents("$writeup");
	}
        else  if ($areas_of_work != '') {  

           $areas = explode(',',$areas_of_work);
            if (in_array("$ucfCategory",$areas)){
           foreach ($areas as $area){
            if (file_exists("Writeups/$area/${artist}.txt")){
               $writeup = "Writeups/$area/${artist}.txt";
               printContents("$writeup");
               break;
            }
            else if (file_exists("Writeups/${area}s/${artist}.txt")){
               $writeup = "Writeups/${area}s/${artist}.txt";
               printContents("$writeup");
               break;
            }
            else  if (file_exists("Writeups/$area/${artist}.html")){
               $writeup = "Writeups/$area/${artist}.html";
               printContents("$writeup");
               break;
            }
            else if (file_exists("Writeups/${area}s/${artist}.html")){
               $writeup = "Writeups/${area}s/${artist}.html";
               printContents("$writeup");
               break;
            }
           }
          }
        }
    }
    else if ($_GET['lang'] == 'E') {
	if (file_exists("${writeupLeaf}/${artist}_English.txt")){
	    $writeup = "${writeupLeaf}/${artist}_English.txt";
	    printContentsWithBreaks("$writeup");
	}
	else if (file_exists("${writeupLeaf}/${artist}_English.html")){
	    $writeup = "${writeupLeaf}/${artist}_English.html";
	    printContentsWithBreaks("$writeup");
	}
        else  if ($areas_of_work != '') {  
           $areas = explode(',',$areas_of_work);
            if (in_array("$ucfCategory",$areas)){
           foreach ($areas as $area){
            if (file_exists("Writeups/$area/${artist}_English.txt")){
               $writeup = "Writeups/$area/${artist}_English.txt";
               printContentsWithBreaks("$writeup");
            }
           }
          }
        }
    }
    else {
	if ($_GET['lang'] != 'E'){
	    $writeup = "writeups/notavailable.txt";
	}
	else {
	    $writeup = "writeups/notavailable_english.txt";
	}
        printContents("$writeup");
    }
    echo "</div>";

    $url   = "$songListScript?tag=Search&$category=$artist&";					 
    $aurl  = "$albumSongListScript?tag=Search&$category=$artist&";					 

    if ($category == "singers") {
	$ListQry = "SELECT S_MUSICIAN,S_WRITERS,S_RAGA,S_YEAR  FROM SONGS WHERE (S_SINGERS like \"$artist,%\" or S_SINGERS like \"%,$artist\" or S_SINGERS like \"%,$artist,%\" or S_SINGERS like \"$artist\" or S_SINGERS like \" $artist ,%\" or S_SINGERS like \"%, $artist\" or S_SINGERS like \"%, $artist,%\" or S_SINGERS like \"%, $artist ,%\" or S_SINGERS like \"%,$artist ,%\" or S_SINGERS like \"%, $artist\" or S_SINGERS like \"%$artist ,%\" ) order by S_YEAR ";

	$aListQry = "SELECT S_MUSICIAN,S_WRITERS,S_RAGA,S_YEAR  FROM ASONGS WHERE (S_SINGERS like \"$artist,%\" or S_SINGERS like \"%,$artist\" or S_SINGERS like \"%,$artist,%\" or S_SINGERS like \"$artist\" or S_SINGERS like \" $artist ,%\" or S_SINGERS like \"%, $artist\" or S_SINGERS like \"%, $artist,%\" or S_SINGERS like \"%, $artist ,%\" or S_SINGERS like \"%,$artist ,%\" or S_SINGERS like \"%, $artist\" or S_SINGERS like \"%$artist ,%\" ) order by S_YEAR ";

    }   
    else if ($category == 'musician'){
	$ListQry = "SELECT S_WRITERS,S_SINGERS,S_RAGA,S_YEAR from SONGS where (S_MUSICIAN like \"$artist\" or S_MUSICIAN like \"$artist,%\" or S_MUSICIAN like \"%,$artist\" or S_MUSICIAN like \"%,$artist,%\") order by S_YEAR";
	$aListQry = "SELECT S_WRITERS,S_SINGERS,S_RAGA,S_YEAR from ASONGS where (S_MUSICIAN like \"$artist\" or S_MUSICIAN like \"$artist,%\" or S_MUSICIAN like \"%,$artist\" or S_MUSICIAN like \"%,$artist,%\") order by S_YEAR";
    }
    else if ($category == 'lyricist'){
	$ListQry = "SELECT S_MUSICIAN,S_SINGERS,S_RAGA,S_YEAR from SONGS where (S_WRITERS like \"$artist\" or S_WRITERS like \"$artist,%\" or S_WRITERS like \"%,$artist\" or S_WRITERS like \"%,$artist,%\") order by S_YEAR";
	$aListQry = "SELECT S_MUSICIAN,S_SINGERS,S_RAGA,S_YEAR from ASONGS where (S_WRITERS like \"$artist\" or S_WRITERS like \"$artist,%\" or S_WRITERS like \"%,$artist\" or S_WRITERS like \"%,$artist,%\") order by S_YEAR";
    }
    else if ($category == 'raga'){
	$ListQry = "SELECT S_MUSICIAN,S_SINGERS,S_WRITERS,S_YEAR from SONGS where (S_RAGA like \"$artist\"  or S_RAGA like \"$artist,%\" or S_RAGA like \"%,$artist\" or S_RAGA like \"%,$artist,%\") and  S_RAGA not like \"%Raagamalika%\" order by S_YEAR";
	$aListQry = "SELECT S_MUSICIAN,S_SINGERS,S_WRITERS,S_YEAR from ASONGS where (S_RAGA like \"$artist\" or S_RAGA like \"$artist,%\" or  S_RAGA like \"%,$artist\" or S_RAGA like \"%,$artist,%\") and S_RAGA not like \"%Raagamalika%\"  order by S_YEAR";
    }
    else if ($category == 'year'){
	$ListQry = "SELECT S_MUSICIAN,S_SINGERS,S_WRITERS,S_RAGA from SONGS where (S_YEAR like \"$artist\" or S_YEAR like \"$artist,%\" or S_YEAR like \"%,$artist\" or S_YEAR like \"%,$artist,%\") order by S_YEAR";
	$aListQry = "SELECT S_MUSICIAN,S_SINGERS,S_WRITERS,S_RAGA from ASONGS where (S_YEAR like \"$artist\" or S_YEAR like \"$artist,%\" or S_YEAR like \"%,$artist\" or S_YEAR like \"%,$artist,%\") order by S_YEAR";

    }

    global $tot_years,$doLines;
    if ($category == 'musician' || $category =='lyricist' || $category == 'raga' || $category == 'singers' || $category == 'year'){

	$songsIntroFile = "Writeups/profileSongsIntro.html";
	$albumsongsIntroFile = "Writeups/profileASongsIntro.html";
	if ($_GET['lang'] == 'E'){
	    $songsIntroFile = "Writeups/profileSongsIntroEng.html";
	    $albumsongsIntroFile = "Writeups/profileASongsIntroEng.html";
	}
	tableData ($ListQry,$category,$url,"$songsIntroFile","Movies",$artist,'',$origartist);
	tableData ($aListQry,$category,$aurl,"$albumsongsIntroFile","Albums",$artist,'',$origartist);
    }
    else if ($category != "sound" && $category != "still" && $category != "critics" && $category != "makeup" && $category != "dubbing" && $category != "choreography" ){
	listData($ListQry,$category);
    }


    if ($doLines) { ksort($tot_years);	    doLines($category,$tot_years,$artist); }

}


function printAdditionalArtistPictures($category,$artist)
{
    $img_cnt = 1;
    $category = ucfirst($category);
    $some_pictures = "Some Rare Photographs";
    if ($_GET['lang'] != 'E') { $some_pictures = get_uc($some_pictures,'');}
    $directory = "additional_artist_pics/${category}/${artist}";
    if (is_dir("$directory")){
	echo "<div align=center><table align=center width=100%>\n";
	$images = glob($directory . "/*.jpg");
	if ($images[0] != ''){
	    echo "<tr><td align=center valign=top colspan=4><div class=tableheader> $some_pictures </div></td></tr>\n";
	}
	foreach($images as $image)
	{
	    if ($img_cnt == 1 || $img_cnt == 5 || $img_cnt == 9){
		if ($img_cnt == 5 && $img_cnt == 8){
		    echo "</tr>";
		}
		echo "<tr>\n";
	    }
	    echo "<td align=center><a href=\"$image\" class=preview toptions=\"group=links,effect=fade\"><img class=preview src=\"$image\" width=200 height=200 border=0></a></td>\n";
	    $img_cnt++;
	}
	echo "</tr></table></div>";
    }
    return;
}
function similar_file_exists($filename) {
  if (file_exists($filename)) {
    return $filename;
  }
  $dir = dirname($filename);
  $files = glob($dir . '/*');
  $lcaseFilename = strtolower($filename);
  foreach($files as $file) {
    if (strtolower($file) == $lcaseFilename) {
      return $file;
    }
  }
  return $filename;
}

function listData ($ListQry, $category)
{
	global $_Master_movie_script;
    if ($_GET['show_sql'] == 1){
	echo $ListQry, "<BR>";
    }
    global $tot_years, $doLines;
    $yrCount= array();

    $res_funcQry = mysql_query($ListQry);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;

    if ($num_funcQry > 0) {
	$hdr = "Available Movies";
	if ($_GET['lang'] != 'E'){
	    $hdr = get_uc("$hdr",'');
	}
	echo "<div class=ptextsmall>$hdr : $num_funcQry</div>";


    echo "<table class=ptables>\n";

    printDetailCellHeads("Movie");
    if ($category == 'story' || $category == 'dialog' || $category == 'screenplay'){
	printDetailCellHeads("Story");
	printDetailCellHeads("Screenplay");
	printDetailCellHeads("Dialog");
    }
    printDetailCellHeads("Year");
    if ($category != "producer" && $category != 'story' && $category != 'dialog' && $category != 'screenplay') {
	printDetailCellHeads("Producer");
    }
    if ($category != "director") {
	printDetailCellHeads("Director");
    }
    while ($i < $num_funcQry){
	$mov_ID   = mysql_result($res_funcQry, $i, "MOVIES.M_ID");
	$mov_Name = mysql_result($res_funcQry, $i, "MOVIES.M_MOVIE");
	$mov_Year = mysql_result($res_funcQry, $i, "MOVIES.M_YEAR");
	$tot_years[$mov_Year]++;
        if (!in_array($mov_Year,$yrCount)) {
            array_push ($yrCount,"$mov_Year");
        }
	echo "<tr>";

	printDetailCells ($mov_Name, "$_Master_movie_script?$mov_ID",$i);
	if ($category == 'story' || $category == 'dialog' || $category == 'screenplay'){
	    $mov_Story = mysql_result($res_funcQry, $i, "MDETAILS.M_STORY");
	    $mov_Screen= mysql_result($res_funcQry, $i, "MDETAILS.M_SCREENPLAY");
	    $mov_Dia   = mysql_result($res_funcQry, $i, "MDETAILS.M_DIALOG");
	    printDetailCells ($mov_Story, "",$i);
	    printDetailCells ($mov_Dia, "",$i);
	    printDetailCells ($mov_Screen, "",$i);
	}
	printDetailCells ($mov_Year, "",$i);
	if ($category != "producer" && $category != 'story' && $category != 'dialog' && $category != 'screenplay') {
	    $mov_Prod = mysql_result($res_funcQry, $i, "MDETAILS.M_PRODUCER");
	    printDetailCells ($mov_Prod, "",$i);
	}
	if ($category != "director") {
	    $mov_Dir  = mysql_result($res_funcQry, $i, "MOVIES.M_DIRECTOR");
	    printDetailCells ($mov_Dir, "",$i);
	}
	echo "</tr>";
	$i++;
    }
    echo "</table>\n";
    //print_r($yrCount);
    if (sizeof($tot_years) > 3 && sizeof($yrCount) > 1) {
	    $doLines = 1;
    }
}
else {
     $missMsg = "No Information Available";
     if ($_GET['lang'] != 'E') { $missMsg = get_uc($missMsg,''); }
     echo "<div class=psubheading>$missMsg</div>";
}
}

function tableData ($ListQry,$category,$url, $fil, $title,$art,$lim, $origartist)
{

    global $tot_years, $doLines;
    $muss = array();
    $lyrs = array();
    $sings = array();
    $rags = array();
    $yrs = array();
    global $_Master_movielist_script, $_Master_albumlist_script,$_Master_songlist_script,$_Master_albumsonglist_script;

    $movieListScript      = $_Master_movielist_script;
    $albumListScript      = $_Master_albumlist_script;
    $movieSongListScript      = $_Master_songlist_script;
    $albumSongListScript      = $_Master_albumsonglist_script;
    $linklist = $movieListScript;
    $slinklist = $movieSongListScript;

       if ($_GET['show_sql'] == 1){
	echo $ListQry, "*********<BR>";
    }
if ($title == 'Albums') 
{ $linklist = $albumListScript; $slinklist = $albumSongListScript; }
  
    $res_funcQry = mysql_query($ListQry);
    $num_funcQry = mysql_num_rows($res_funcQry);

    $i = 0;
    $otitle = $title;
    $songtitle = "Songs";
    $tnum = $num_funcQry;
    if ($num_funcQry > 0) {
        //printDetailHeadingDivs($title);
        if ($_GET['lang'] != 'E') { $title = get_uc($title,''); $songtitle = get_uc($songtitle,'');}

	echo "<div class=pcellslong>\n";	     
	printContents($fil);
	$ltable = 'MOVIES';
	if ($otitle == 'Albums') { $ltable = 'ALBUMS'; }
	if ($category == 'musician') { $ltag = 'M_MUSICIAN'; }
	else if ($category == 'lyricist') { $ltag = 'M_WRITERS'; }
	else if ($category == 'year') { $ltag = 'M_YEAR'; }
	if ($ltable != '' && $ltag != '') {
	    $lim = runQuery("SELECT COUNT(M_ID) ccn FROM $ltable WHERE $ltag like \"%$origartist%\"",'ccn');
	}
	if ($category != 'raga' && $category != 'singers'){
	    echo "<div class=pcellslong><a href=\"${linklist}?tag=Search&$category=$art&limit=$lim\">$title ($lim)</a> | ";
	    echo "<a href=\"${slinklist}?tag=Search&$category=$art&limit=$tnum\">$songtitle ($tnum)</a></div>";
	}
	else if ($category == 'singers'){
	    echo "<div class=pcellslong><a href=\"${slinklist}?tag=Search&$category=$art&limit=$tnum\">$songtitle ($tnum)</a></div>";
	}
	echo "</div>\n";
   }

    while ($i < $num_funcQry) {
	if ($category != 'musician'){
	    $mus = mysql_result($res_funcQry, $i, "S_MUSICIAN");
	    $muss["$mus"]++;
	}
	if ($category != 'lyricist'){
	    $lyr = mysql_result($res_funcQry, $i, "S_WRITERS");
	    $lyrs["$lyr"]++;
	}
	if ($category != 'raga' && $category != 'year'){
	    $rag = mysql_result($res_funcQry, $i, "S_RAGA");
	    if (strpos($rag,"Raagamalika") === false) {
		$rags["$rag"]++;
	    }
	}
	if ($category != 'singers'){
	    $sing = mysql_result($res_funcQry, $i, "S_SINGERS");
	    $sings["$sing"]++;
	}
	if ($category != 'year'){
	    $yr  = mysql_result($res_funcQry, $i, "S_YEAR");
	    $yrs["$yr"]++;
	    if ($_GET['debug'] == 1) { echo "Incrementing $yr count <BR>"; }
	    $tot_years[$yr]++;
	}
	$i++;
    }


    if ($num_funcQry > 0) {
//        echo "<div class=pboxlines>\n";
	echo( "<table class=ptables>\n");
	echo "<tr>\n";

	if ($category != 'musician'){
	    echo "<td valign=top>\n";
	    printShortTables('musician',$muss,$url);
	}

	if ($category != 'lyricist'){
	    echo "</td><td valign=top>\n";
	    printShortTables('lyricist',$lyrs,$url);
	}

	if ($category != 'singers'){
	    echo "<td valign=top>\n";
	    printShortTables('singers',$sings,$url);
	}

	if ($category != 'raga' && $category != 'year'){
	    echo "<td valign=top>\n";
	    printShortTables('raga',$rags,$url);
	}

	if ($category != 'year'){
	    echo "</td><td valign=top>\n";
	    arsort($yrs);
	    echo( "<table class=pstables>\n");
	    $sstr = "Songs";
	    if ($_GET['lang'] != 'E'){	
		$sstr = get_uc($sstr,'');
	    }

	    printCustomDetailRowHeadings("Year",$sstr);	
	    $cnt=10;
	    foreach ($yrs as $k => $v){
		$url .= "category=$category&year=$k&limit=$v&page_num=1";
		printCustomDetailRows("$k","$v","$url",$cnt);
		$cnt--;
		if ($cnt == 0){	    break ; }
	    }

	    echo "</table>\n";
	    $doLines = 1;
	}
	echo "</td></tr></table>";
//	echo "</div>";
    }


    if (sizeof($tot_years) < 3 || sizeof(array_keys($tot_years)) < 1) {
	    $doLines = 0;
    }

}

function printShortTables ($category,$arr, $masterUrl)
{
    natrsort($arr);
    echo( "<table class=pstables>\n");
    $cnt=10;
    $sstr = "Songs";
    if ($_GET['lang'] != 'E'){	
	$sstr = get_uc($sstr,'');
    }
    printCustomDetailRowHeadings(ucfirst($category),$sstr);
    foreach ($arr as $k => $v){
	if ($k != "")  {
	    $url = $masterUrl . "category=$category&artist=$k&limit=$v&page_num=1";
	    printCustomDetailRows("$k","$v","$url", $cnt);
	    $cnt--;
	    if ($cnt == 0){	    break;	}
	}
    }
    echo "</table>\n";
}
function natrsort(&$array)
{
    natsort($array);
    $array = array_reverse($array);
}
function printCustomDetailRowHeadings ($key , $val) 
{
    $key_tag   = get_uc("$key",'');
/*
    if ($_GET['lang']=='E'){
	echo ( "<tr><th class=tableheader>$key</th><td class=tableheader>$val</th></tr>\n");
    }
    else {
	echo ( "<tr><th class=tableheader>$key_tag</td><th class=tableheader>$val</th></tr>\n");
    }	
*/
    if	($_GET['lang']=='E'){
	echo ( "<tr><td class=\"prowsshort${printstyle}\">$key</td><td class=\"prowsveryshort${printstyle}\">$val</td></tr>\n");
    }
    else {
	echo ( "<tr><td class=\"prowsshort${printstyle}\">$key_tag</td><td class=\"prowsveryshort${printstyle}\">$val</td></tr>\n");
    }

}
function printCustomDetailRows ($key, $val, $url, $cnt){

    $val_array = array();
    $pos = strpos($val, ",");
    $key_tag   = get_uc("$key",'');

    if ($_GET['lang']=='E'){
	array_push($val_array,"<a href=\"$url\">$val</a>");
    }
    else {
	$val_tag   = get_uc("$val",'');
	array_push($val_array,"<a href=\"$url\">$val_tag</a>");
    }

    $print_string = implode(' ,',$val_array);

    $printstyle='';
    if ( $cnt&1 ) {
	$printstyle = 'odd';
    }

    if	($_GET['lang']=='E'){
	echo ( "<tr><td class=\"prowsshort${printstyle}\">$key</td><td class=\"prowsveryshort${printstyle}\">$print_string</td></tr>\n");
    }
    else {
	echo ( "<tr><td class=\"prowsshort${printstyle}\">$key_tag</td><td class=\"prowsveryshort${printstyle}\">$print_string</td></tr>\n");
    }
}

function getRelevantArticles($tags){

    $relart = "Relevant Articles";

    if ($_GET['lang'] != 'E'){
	$relart = get_uc($relart,'');
    }

    $tagarray = array();
    foreach ($tags as $tag){
	if ($tag != ''){
  	      array_push ($tagarray, " tags like \"$tag\" or tags like \"%,$tag\" or tags like \"%,$tag,%\" or tags like \"$tag,%\" ");
	}
    }

    if ($tagarray[0] != ''){
	$query = "SELECT title,url from ARTICLES WHERE (" . implode (' or ',$tagarray) . ") ";
	if ($_GET['debug2013'] == 1) { echo $query ; }
	$result        = mysql_query($query);
	$num_results   = mysql_num_rows($result);
    
	$i=0;
	if ($num_results > 0) {
	    echo "<div class=pcellheads><b>$relart</b></div><ul>\n";
	    while ($i < $num_results){
		$title = mysql_result($result, $i, "title");
		$url = mysql_result($result, $i, "url");
		echo "<div class=pcellslong><a href=\"$url\" target=\"_new\">$title</a></div>\n";
		$i++;
	    }
	    echo "</div>";
	}
    }
}


function stripTrads($ugenre) 
{
    $ugenre = str_replace('Traditional','',$ugenre);
    $ugenre = str_replace('പരമ്പരാഗതം','',$ugenre);
    $ugenre = str_replace('(','',$ugenre);
    $ugenre = str_replace(')','',$ugenre);
   
    return ltrim(rtrim($ugenre));
}
?>
