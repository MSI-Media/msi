<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    //require_once("includes/cache.php");
    //$ch = new cache($_GET['lang'],'Profiles');
    

    $profileScript = $_Master_profile_script;
    $profilemaster = $_Master_profile;
    $movieScript = $_Master_movielist_script  ; 
    $songScript = $_Master_songlist_script   ;
    $albumScript = $_Master_albumlist_script   ;
    $asongScript = $_Master_albumsonglist_script   ;

    $_GET['encode']='utf';
    $yr = $_GET['year'] ;
    $start = $_GET['s'];
    $end = $_GET['e'];

    $pdata = "php/data/Popular";

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");

    if ($yr){
	$years = array("$yr");
    }   
    else if ($start && $end) {
	$years = buildArrayFromQuery("SELECT DISTINCT M_YEAR FROM MOVIES where M_YEAR != '' && M_YEAR != 'Uncategorized' and M_YEAR >= $start && M_YEAR <= $end ORDER BY M_YEAR DESC",'M_YEAR');
    }

    foreach ($years as $yr){ 
	if (!file_exists("$pdata/$yr")){
	    mkdir ("$pdata/$yr",0777,true);
	}
	echo "<div class=pcellslong>Generating Actor Lists for $yr ..</div>";
	$actor_list = getActorCounts($yr);
	$fh = fopen ("$pdata/$yr/M_CAST.txt",'w');
	foreach ($actor_list as $a=>$c){
	    $a = ltrim(rtrim($a));
	    if ($a != '' && $a != 'Uncategorized'){
		fwrite($fh, "$a|$c\n");
	    }
	}

	echo "<div class=pcellslong>Generating Singer Lists for $yr ..</div>";
	$singer_list = getSingerCounts($yr);
	$fh = fopen ("$pdata/$yr/S_SINGERS.txt",'w');
	foreach ($singer_list as $a=>$c){
	    $a = ltrim(rtrim($a));
	    if ($a != '' && $a != 'Uncategorized'){
		fwrite($fh, "$a|$c\n");
	    }
	}


//	$q1 = "SELECT M_MUSICIAN,COUNT(M_ID) ccn FROM MOVIES where M_YEAR=\"$yr\" GROUP BY M_MUSICIAN ORDER BY ccn DESC";
//	generateFiles ($q1,$yr,'M_MUSICIAN');

//	$q2 = "SELECT M_WRITERS,COUNT(M_ID) ccn FROM MOVIES where M_YEAR=\"$yr\" GROUP BY M_WRITERS ORDER BY ccn DESC";
//	generateFiles ($q2,$yr,'M_WRITERS');


	echo "<div class=pcellslong>Generating Musician Lists for $yr ..</div>";
	$actor_list = getMusicianCounts($yr);
	$fh = fopen ("$pdata/$yr/M_MUSICIAN.txt",'w');
	foreach ($actor_list as $a=>$c){
	    $a = ltrim(rtrim($a));
	    if ($a != '' && $a != 'Uncategorized' && $a != 'No Songs'){
		fwrite($fh, "$a|$c\n");
	    }
	}

	echo "<div class=pcellslong>Generating Lyricist Lists for $yr ..</div>";
	$actor_list = getLyricistCounts($yr);
	$fh = fopen ("$pdata/$yr/M_WRITERS.txt",'w');
	foreach ($actor_list as $a=>$c){
	    $a = ltrim(rtrim($a));
	    if ($a != '' && $a != 'Uncategorized' && $a != 'No Songs'){
		fwrite($fh, "$a|$c\n");
	    }
	}



	$q3 = "SELECT M_DIRECTOR,COUNT(M_ID) ccn FROM MOVIES where M_YEAR=\"$yr\" GROUP BY M_DIRECTOR ORDER BY ccn DESC";
	generateFiles ($q3,$yr,'M_DIRECTOR');
	$q4 = "SELECT M_PRODUCER,COUNT(MDETAILS.M_ID) ccn FROM MOVIES,MDETAILS where MOVIES.M_YEAR=\"$yr\" and MDETAILS.M_ID=MOVIES.M_ID GROUP BY M_PRODUCER ORDER BY ccn DESC";
	generateFiles ($q4,$yr,'M_PRODUCER');
	$q5 = "SELECT M_CAMERA,COUNT(MDETAILS.M_ID) ccn FROM MOVIES,MDETAILS where MOVIES.M_YEAR=\"$yr\" and MDETAILS.M_ID=MOVIES.M_ID GROUP BY M_CAMERA ORDER BY ccn DESC";
	generateFiles ($q5,$yr,'M_CAMERA');
	$q6 = "SELECT M_ART,COUNT(MDETAILS.M_ID) ccn FROM MOVIES,MDETAILS where MOVIES.M_YEAR=\"$yr\" and MDETAILS.M_ID=MOVIES.M_ID GROUP BY M_ART ORDER BY ccn DESC";
	generateFiles ($q6,$yr,'M_ART');
	$q7 = "SELECT M_EDITOR,COUNT(MDETAILS.M_ID) ccn FROM MOVIES,MDETAILS where MOVIES.M_YEAR=\"$yr\" and MDETAILS.M_ID=MOVIES.M_ID GROUP BY M_EDITOR ORDER BY ccn DESC";
	generateFiles ($q7,$yr,'M_EDITOR');
	$q8 = "SELECT M_BANNER,COUNT(MDETAILS.M_ID) ccn FROM MOVIES,MDETAILS where MOVIES.M_YEAR=\"$yr\" and MDETAILS.M_ID=MOVIES.M_ID GROUP BY M_BANNER ORDER BY ccn DESC";
	generateFiles ($q8,$yr,'M_BANNER');
	$q9 = "SELECT M_DESIGN,COUNT(MDETAILS.M_ID) ccn FROM MOVIES,MDETAILS where MOVIES.M_YEAR=\"$yr\" and MDETAILS.M_ID=MOVIES.M_ID GROUP BY M_DESIGN ORDER BY ccn DESC";
	generateFiles ($q9,$yr,'M_DESIGN');
	$q10 = "SELECT M_SCREENPLAY,COUNT(MDETAILS.M_ID) ccn FROM MOVIES,MDETAILS where MOVIES.M_YEAR=\"$yr\" and MDETAILS.M_ID=MOVIES.M_ID GROUP BY M_SCREENPLAY ORDER BY ccn DESC";
	generateFiles ($q10,$yr,'M_SCREENPLAY');
    }

    printFancyFooters();
    mysql_close($cLink);

}
function generateFiles($qry, $yr, $tag)
{
    echo "<div class=pcellslong>Generating $tag Lists for $yr ..</div>";
    $pdata = "php/data/Popular";
    $res_array  = array();
    $result      = mysql_query($qry);
    if ($result) {
	$i=0;
	$num_results = mysql_num_rows($result);
	while ($i < $num_results){
	    $art   = mysql_result($result,$i,"$tag");
	    $cnt   = mysql_result($result,$i,"ccn");
	    $res_array["$art"] = $cnt;
	    $i++;
	    
	}
    }
    arsort($res_array);
    $fh = fopen ("$pdata/$yr/${tag}.txt",'w');
    foreach ($res_array as $a=>$c){
	$a = ltrim(rtrim($a));
	if ($a != '' && $a != 'Uncategorized'){
	    fwrite($fh, "$a|$c\n");
	}
    }
    return $res_array;
}
function getActorCounts($yr)
{
    $act_count = array();
    $act_list = array();
    $uniq_act_list = array();
    $actors = buildArrayFromQuery("SELECT M_CAST FROM MOVIES,MDETAILS WHERE M_YEAR=\"$yr\" and MOVIES.M_ID=MDETAILS.M_ID",'M_CAST');
    foreach ($actors as $ac){
	$act_list = explode(',',$ac);
	foreach($act_list as $v){
	    $v = ltrim(rtrim($v));
	    if(!in_array($v, $uniq_act_list)){
		array_push($uniq_act_list,"$v");
	    }
	}
    }
    sort($uniq_act_list);
    foreach ($uniq_act_list as $an){
	$q = "SELECT COUNT(MOVIES.M_ID) ccn FROM MDETAILS,MOVIES where MOVIES.M_YEAR=\"$yr\" and MOVIES.M_ID=MDETAILS.M_ID and (MDETAILS.M_CAST like \"$an\" OR MDETAILS.M_CAST like \"$an,%\" OR MDETAILS.M_CAST like \"%,$an,%\" OR MDETAILS.M_CAST like \"%,$an\" OR MDETAILS.M_CAST like \"$an ,%\" OR MDETAILS.M_CAST like \"%, $an,%\" OR MDETAILS.M_CAST like \"%, $an\" OR MDETAILS.M_CAST like \"%,$an ,%\" OR MDETAILS.M_CAST like \"%, $an ,%\")";
	$acnt = runQuery($q, 'ccn');
	$act_count["$an"] = $acnt;
    }
    arsort($act_count);
    return $act_count;
}

function getSingerCounts($yr)
{
    $act_count = array();
    $act_list = array();
    $uniq_act_list = array();
    $actors = buildArrayFromQuery("SELECT S_SINGERS FROM SONGS WHERE S_YEAR=\"$yr\"",'S_SINGERS');
    foreach ($actors as $ac){
	$act_list = explode(',',$ac);
	foreach($act_list as $v){
	    $v = ltrim(rtrim($v));
	    if(!in_array($v, $uniq_act_list)){
		array_push($uniq_act_list,"$v");
	    }
	}
    }
    sort($uniq_act_list);
    foreach ($uniq_act_list as $an){
	$q = "SELECT COUNT(SONGS.S_ID) ccn FROM SONGS where S_YEAR=\"$yr\" and (SONGS.S_SINGERS like \"$an\" OR SONGS.S_SINGERS like \"$an,%\" OR SONGS.S_SINGERS like \"%,$an,%\" OR SONGS.S_SINGERS like \"%,$an\" OR SONGS.S_SINGERS like \"$an ,%\" OR SONGS.S_SINGERS like \"%, $an,%\" OR SONGS.S_SINGERS like \"%, $an\" OR SONGS.S_SINGERS like \"%,$an ,%\" OR SONGS.S_SINGERS like \"%, $an ,%\")";
	$acnt = runQuery($q, 'ccn');
	$act_count["$an"] = $acnt;
    }
    arsort($act_count);
    return $act_count;
}


function getMusicianCounts($yr)
{
    $act_count = array();
    $act_list = array();
    $uniq_act_list = array();
    $actors = buildArrayFromQuery("SELECT M_MUSICIAN FROM MOVIES WHERE M_YEAR=\"$yr\"",'M_MUSICIAN');
    foreach ($actors as $ac){
	$act_list = explode(',',$ac);
	foreach($act_list as $v){
	    $v = ltrim(rtrim($v));
	    if(!in_array($v, $uniq_act_list)){
		array_push($uniq_act_list,"$v");
	    }
	}
    }
    sort($uniq_act_list);
    foreach ($uniq_act_list as $an){
	$q = "SELECT COUNT(MOVIES.M_ID) ccn FROM MOVIES where MOVIES.M_YEAR=\"$yr\"  and (MOVIES.M_MUSICIAN like \"$an\" OR MOVIES.M_MUSICIAN like \"$an,%\" OR MOVIES.M_MUSICIAN like \"%,$an,%\" OR MOVIES.M_MUSICIAN like \"%,$an\" OR MOVIES.M_MUSICIAN like \"$an ,%\" OR MOVIES.M_MUSICIAN like \"%, $an,%\" OR MOVIES.M_MUSICIAN like \"%, $an\" OR MOVIES.M_MUSICIAN like \"%,$an ,%\" OR MOVIES.M_MUSICIAN like \"%, $an ,%\")";
	$acnt = runQuery($q, 'ccn');
	$act_count["$an"] = $acnt;
    }
    arsort($act_count);
    return $act_count;
}


function getLyricistCounts($yr)
{
    $act_count = array();
    $act_list = array();
    $uniq_act_list = array();
    $actors = buildArrayFromQuery("SELECT M_WRITERS FROM MOVIES WHERE M_YEAR=\"$yr\"",'M_WRITERS');
    foreach ($actors as $ac){
	$act_list = explode(',',$ac);
	foreach($act_list as $v){
	    $v = ltrim(rtrim($v));
	    if(!in_array($v, $uniq_act_list)){
		array_push($uniq_act_list,"$v");
	    }
	}
    }
    sort($uniq_act_list);
    foreach ($uniq_act_list as $an){
	$q = "SELECT COUNT(MOVIES.M_ID) ccn FROM MOVIES where MOVIES.M_YEAR=\"$yr\"  and (MOVIES.M_WRITERS like \"$an\" OR MOVIES.M_WRITERS like \"$an,%\" OR MOVIES.M_WRITERS like \"%,$an,%\" OR MOVIES.M_WRITERS like \"%,$an\" OR MOVIES.M_WRITERS like \"$an ,%\" OR MOVIES.M_WRITERS like \"%, $an,%\" OR MOVIES.M_WRITERS like \"%, $an\" OR MOVIES.M_WRITERS like \"%,$an ,%\" OR MOVIES.M_WRITERS like \"%, $an ,%\")";
	$acnt = runQuery($q, 'ccn');
	$act_count["$an"] = $acnt;
    }
    arsort($act_count);
    return $act_count;
}

?>
