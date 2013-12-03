<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    

    $profileScript = $_Master_profile_script;
    $profilemaster = $_Master_profile;
    $movieScript = $_Master_movielist_script  ; 
    $songScript = $_Master_songlist_script   ;
    $albumScript = $_Master_albumlist_script   ;
    $asongScript = $_Master_albumsonglist_script   ;

    $_GET['encode']='utf';

    $yr = $_GET['y'];

    if (!$yr) { $yr = $_POST['y']; $_GET['y'] = $yr; }

    if (!$yr){
	$yr = date("Y");
    }


    $pdata = "php/data/Popular";

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");

    $pop_artists_tag = 'Most Popular Artists in Malayalam Movies';
    $yrtag = 'Year';
    $submit = 'Search';
    if ($_GET['lang'] != 'E'){
	$pop_artists_tag = get_uc($pop_artists_tag,'');
	$yrtag = get_uc($yrtag,'');
	$submit = get_uc("$submit",'');
    }
//  echo "<div class=pheading>$pop_artists_tag <br> $yrtag : $yr</div>";
    echo "<div class=pheading> $yrtag : $yr</div>";

    $topOne = $yr;
    $years = buildArrayFromQuery("SELECT DISTINCT M_YEAR FROM MOVIES where M_YEAR != '' && M_YEAR != 'Uncategorized' ORDER BY M_YEAR DESC",'M_YEAR');
    echo "<form action=popularArtists.php method=get>\n";
    echo "<select name=y>\n";
    echo "<option name=$topOne>$topOne</option>";
    foreach ($years as $year){
	echo "<option name=$year>$year</option>";
    }
    echo "</select>\n";
    echo "<input type=submit value=\"$submit\">\n";
    echo "</form>";
    echo "<table class=ptables>\n";

    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dirlink   = "displayProfile.php?category=director&artist=";
    $dproclink = "processSearch.php?db=movies&year=$yr&director=";
    printTop10($yr,'M_DIRECTOR','Directors',$dirlink,$dproclink);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dprodlink = "processSearch.php?db=movies&year=$yr&producer=";
    $prodlink = "displayProfile.php?category=producer&artist=";
    printTop10($yr,'M_PRODUCER','Producers',$prodlink,$dprodlink);
    echo "</table></td></tr>\n";



    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=musician&artist=";
    $dl2 = "processSearch.php?db=movies&year=$yr&musician=";
    printTop10($yr,'M_MUSICIAN','Composers',$dl1,$dl2);
    $dl1  = "displayProfile.php?category=lyricist&artist=";
    $dl2 = "processSearch.php?db=movies&year=$yr&lyricist=";
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    printTop10($yr,'M_WRITERS','Lyricists',$dl1,$dl2);
    echo "</table></td></tr>\n";


    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=actors&artist=";
    $dl2 = "processSearch.php?db=movies&year=$yr&actor=";
    printTop10($yr,'M_CAST','Actors',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=singers&artist=";
    $dl2 = "processSearch.php?db=moviesongs&year=$yr&singers=";
    printTop10($yr,'S_SINGERS','Singers',$dl1,$dl2);
    echo "</table></td></tr>\n";


    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=screenplay&artist=";
    $dl2 = "processSearch.php?db=movies&year=$yr&screenplay=";
    printTop10($yr,'M_SCREENPLAY','Screenplay',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=editor&artist=";
    $dl2 = "";//"processSearch.php?db=movies&year=$yr&editors=";
    printTop10($yr,'M_EDITOR','Editor',$dl1,$dl2);
    echo "</table></td></tr>\n";


    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1  = "displayProfile.php?category=camera&artist=";
    $dl2 = "";//"processSearch.php?db=movies&year=$yr&camera=";
    printTop10($yr,'M_CAMERA','Cinematography',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";

    $dl1   = "displayProfile.php?category=art+director&artist=";
    $dl2 = "";//"processSearch.php?db=movies&year=$yr&art=";
    printTop10($yr,'M_ART','Art Directors',$dl1,$dl2);
    echo "</table></td></tr>\n";

    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=banner&artist=";
    $dl2 = "";//"processSearch.php?db=movies&year=$yr&banner=";
    printTop10($yr,'M_BANNER','Banners',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=design&artist=";
    $dl2 = "";//"processSearch.php?db=movies&year=$yr&design=";
    printTop10($yr,'M_DESIGN','Publicity Design',$dl1,$dl2);
    echo "</table></td></tr>\n";




    echo "</table>";



    printFancyFooters();
    mysql_close($cLink);

}

function printTop10($yr, $f,$t, $l, $dl){
    $top10 = 10;
    $fpath = "php/data/Popular";
    $fw = fopen("$fpath/$yr/${f}.txt",'r');

    if ($_GET['lang'] != 'E'){
	$t = get_uc($t,'');
    }

    echo "<tr><td colspan=2 class=pleftsubheading>$t</td></tr>";
    if ($fw){
	while (!feof($fw)){
	    $printstyle='';
	    if ($top10 &1){
		$printstyle='odd';
	    }
	    $lx = fgets($fw,1048576);
	    $lxelems = explode('|',$lx);
	    $aname = $lxelems[0];
	    if ($lxelems[0] != "__" && $lxelems[0] != "-"){
		if ($_GET['lang'] != 'E'){
		    $lxelems[0] = get_uc($lxelems[0],'');
		}	    
		echo "<tr><td  class=prowsshort${printstyle}><a href=\"${l}${aname}\">$lxelems[0]</a></td>\n";
		if ($dl){
		    echo "<td class=prowsshort${printstyle}><a href=\"${dl}${aname}\">$lxelems[1]</a></td></tr>\n";
		}
		else {
		    echo "<td class=prowsshort${printstyle}>$lxelems[1]</td></tr>\n";
		}
		$top10--;
		if  ($top10 < 1){
		    break;
		}
	    }
	}
	fclose($fw);
    }
}
?>
