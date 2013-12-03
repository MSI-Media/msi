<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_xtemplate_header.php");

    $_UserPics = "pics/UserPics/TN";

    $profileScript = $_Master_profile_script;
    $_GET['encode']='utf';
    printXHeader('');
    $rootc = "contributors";
    $startLet = $_GET['let'];
    $cLink = msi_dbconnect();

    $maincontrib = "Contributors";


    if ($_GET['lang'] != 'E'){
	$maincontrib = get_uc("$maincontrib", '');
    }


    if ($startLet != ''){	
	echo "<a href=\"$_Master_contribs_script\">$maincontrib</a> | ";
    }
    else {
	echo "$maincontrib | ";
    }

    foreach(range('A','Z') as $letter){
	if ($startLet == $letter){
	    echo "$letter | ";
	}
	else {
	    echo "<a href=\"$_Master_contribs_script?let=$letter\">$letter</a> | ";
	}
    }
    echo "</div>\n";


    $rowheads = array ('Song Lyrics','Album Song Lyrics','Audio Clips','Karaoke Files','Album Audio Clips','Song Video Links','Album Video Links','Movie Still Pictures','Publicity Details','Album Still Pictures','Movie Reviews','Song Books','Artist Profiles');
    $links = array (
	'Song Lyrics' => "$_Master_songlist_script?tag=Search&lyricsowner=",
	'Album Song Lyrics' => "$_Master_albumsonglist_script?tag=Search&lyricsowner=",
	'Audio Clips' => "$_Master_songlist_script?tag=Search&clipsowner=",
	'Karaoke Files' => "$_Master_songlist_script?tag=Search&karaokeowner=",
	'Album Audio Clips' => "$_Master_albumsonglist_script?tag=Search&clipsowner=",
	'Song Video Links' => "$_Master_songlist_script?tag=Search&videosowner=",
	'Album Video Links' => "$_Master_albumsonglist_script?tag=Search&videosowner=",
	'Movie Reviews' => "$_Master_movielist_script?tag=Search&reviews=",
	'Song Books' => "$_Master_movielist_script?tag=Search&songbooks=",
	'Movie Still Pictures' => "$_Master_movielist_script?tag=Search&pictures=",
	'Publicity Details' => "$_Master_movielist_script?tag=Search&promos=",
	'Album Still Pictures' => "$_Master_albumlist_script?tag=Search&pictures=",
	'Artist Profiles' => "",
	);


    $rowtitles = array (
	'Song Lyrics' => 'Movies',
	'Album Song Lyrics' => 'Albums',
	'Audio Clips' => 'Movies',
	'Karaoke Files' => 'Karaokes',
	'Album Audio Clips' => 'Albums',
	'Song Video Links' => 'Movies',
	'Album Video Links' => 'Albums',
	'Movie Still Pictures' => 'Movies',
	'Publicity Details' => 'Promos',
	'Album Still Pictures' => 'Albums',
	'Movie Reviews' => 'Reviews',
	'Song Books' => 'Song Books',
	'Artist Profiles' => 'Profiles',
	);


    echo "<table class=ptables>\n";
    echo "<tr class=tableheader>\n";
    echo "<th colspan=2>&nbsp;</th>\n";

    $lt = 'Lyrics';
    $at = 'Audio';
    $vt = 'Videos';
    $pt = 'Pictures';

    if ($_GET['lang'] != 'E'){
	$lt = get_uc($lt,'');
	$at = get_uc($at,'');
	$vt = get_uc($vt,'');
	$pt = get_uc($pt,'');
    }

    echo "<th align=center colspan=2>$lt</th>\n";
    echo "<th align=center colspan=3>$at</th>\n";
    echo "<th align=center colspan=2>$vt</th>\n";
    echo "<th align=center colspan=3>$pt</th>\n";
    echo "<th align=center colspan=3>&nbsp;</th>\n";

    echo "<tr>\n";
    printDetailCellSmallHeadsExt("Contributor");
    foreach ($rowtitles as $rt) {
	printDetailCellSmallHeads($rt);
    }
    echo "</tr>\n";

    $data = parseAndBuild("$rootc/ContributorsList.txt",'');

    $topContributors = array();
    if ($startLet){
	foreach (array_keys ($data) as $k) {
	    $firstlet = strtoupper (substr($k, 0 , 1));
	    if ($firstlet == $startLet){
	        $klist = explode('_',$k);
		$user = $klist[0];
		if (!in_array($user,$topContributors)){
		    array_push($topContributors,"$klist[0]");
		}
	    }
	}
    }
    else {
	$topContributors = array('Jija Subramanian','jaalakam','kcbaburaj','Jayalakshmi Ravindranath','EK Jayachandran','Vijayakrishnan VS','pvsreekumar','vikasvenattu','Sreedevi Pillai','Rajagopal','Jacob John','Susie','maathachan','George Mampilly','madhavabhadran','vamadevan','anoopmenon','anoopadoor','Indu','samshayalu','Vijayakumar PP','Dilip CS','B Vijayakumar','Firoz','Manu','Kalyani','venu','Nityanandha','Shakeeb Vakkom','Sam','sreejumv','Latha Nair','AG Suresh','babumeleth','Adarsh KR','Sunny Joseph','shine_s2000','Preethy Unnikrishnan','Parvathy venugopal','gaanasnehi','Sidhardh Ramesh','Variath Madhavan Kutty','Jayashree','Winston Morris','karkodakan.p','haridas','nsalby','dhanyaraj208','shibujacob71','sureshkoyiloth','ctbvvkv','josejustin2006','gopalakrishnannairnavjeevan');
    }
//    sort($topContributors);
    natcasesort($topContributors);
    $cnt=0;
    foreach ($topContributors as $tc){
	echo "<tr>\n";
	if (file_exists("$_UserPics/${tc}.jpg")){
	    printDetailCellsWithPreviewExtended("$tc","$_UserPics/${tc}.jpg");
	}
	else {
	    printDetailCellsWithPreviewExtended("$tc",'');
	}

	$count = array();
	foreach ($rowheads as $rh){
	    //$count = $data["$tc"]["$rh"];
           $count = $data["${tc}_${rh}"];
	    $lcount = $count;
	    if ($rh == 'Song Lyrics'){
		$lcount = runQuery("SELECT COUNT(S_ID) ccn FROM SONGS WHERE S_LYROWNER like \"%$tc%\"",'ccn');
	    }
	    else if ($rh == 'Album Song Lyrics'){
		$lcount = runQuery("SELECT COUNT(S_ID) ccn FROM ASONGS WHERE S_LYROWNER like \"%$tc%\"",'ccn');
	    }
	    $url   = $links["$rh"];
	    if ($url != "") {
		$url  .= "$tc&limit=$lcount";
		printDetailCells($count,$url,$cnt);
	    }
	    else {
		printDetailCells($count,'',$cnt);
	    }
	}
	echo "</tr>";
	$cnt++;
    }

    echo "</table>\n";
    printFancyFooters();
    mysql_close($cLink);


}
function printSimpleRows($key, $val, $t){
    print "<tr><td class=prowsshort>$key</td><td class=prowsshort>$val</td></tr>\n";
}
function parseAndBuild ($file){
    $data = "";
    $fh = fopen ($file,'r');
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $elems = explode(':',$lx);
	    //$data["$elems[0]"]["$elems[1]"] = $elems[2];
	    $masterString = $elems[0] . "_" . $elems[1];
	    $data["$masterString"] = $elems[2];
	}
	fclose($fh);
    }

    return $data;
}
function printDetailCellsWithPreviewExtended ($val,$link){

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
	echo ("<td colspan=2 class=pcells><a href=\"$link\" class=\"screenshot\" rel=\"$link\" onclick=\"javascript:return false;\">$print_string</a></td>");
    }
    else {
	echo ( "<td colspan=2 class=pcells>$print_string</td>\n");
    }
}
function printDetailCellSmallHeadsExt ($val){

    if ($_GET['lang']=='E'){
	echo ( "<th bgcolor=#ffffff colspan=2 class=ptextsmaller>$val</th>\n");
    }
    else {
	$val_tag   = get_uc("$val",'');
	echo ( "<th bgcolor=#ffffff colspan=2 class=ptextsmaller>$val_tag</th>\n");
    }
}
?>
