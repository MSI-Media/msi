<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    
    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");


    echo "<table class=ptables>\n";

    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dirlink   = "displayProfile.php?category=director&artist=";
    $dproclink = "processSearch.php?search_type=0&db=movies&director=";
    printTop10('M_DIRECTOR','Directors',$dirlink,$dproclink);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dprodlink = "processSearch.php?search_type=0&db=movies&producer=";
    $prodlink = "displayProfile.php?category=producer&artist=";
    printTop10('M_PRODUCER','Producers',$prodlink,$dprodlink);
    echo "</table></td></tr>\n";



    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=musician&artist=";
    $dl2 = "processSearch.php?search_type=0&db=movies&musician=";
    printTop10('M_MUSICIAN','Composers',$dl1,$dl2);
    $dl1  = "displayProfile.php?category=lyricist&artist=";
    $dl2 = "processSearch.php?search_type=0&db=movies&lyricist=";
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    printTop10('M_WRITERS','Lyricists',$dl1,$dl2);
    echo "</table></td></tr>\n";


    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=actors&artist=";
    $dl2 = "processSearch.php?search_type=0&db=movies&actor=";
    printTop10('M_CAST','Actors',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=singers&artist=";
    $dl2 = "processSearch.php?search_type=0&db=moviesongs&singers=";
    printTop10('S_SINGERS','Singers',$dl1,$dl2);
    echo "</table></td></tr>\n";


    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=story&artist=";
    $dl2 = "processSearch.php?search_type=0&db=movies&story=";
    printTop10('M_STORY','Story',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=editor&artist=";
    $dl2 = "";
    printTop10('M_EDITOR','Editor',$dl1,$dl2);
    echo "</table></td></tr>\n";


    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1  = "displayProfile.php?category=camera&artist=";
    $dl2 = "";
    printTop10('M_CAMERA','Cinematography',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=art+director&artist=";
    $dl2 = "";
    printTop10('M_ART','Art Directors',$dl1,$dl2);
    echo "</table></td></tr>\n";

    echo "<tr><td width=50%><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=dialog&artist=";
    $dl2 = "";
    printTop10('M_DIALOG','Dialog',$dl1,$dl2);
    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    $dl1   = "displayProfile.php?category=screenplay&artist=";
    $dl2 = "";
    printTop10('M_SCREENPLAY','Screenplay',$dl1,$dl2);
    echo "</table></td></tr>\n";

    echo "<tr><td width=50%><table class=ptableshalf>\n";


    $dl1   = "displayProfile.php?category=design&artist=";
    $dl2 = "";
    printTop10('M_DESIGN','Publicity Design',$dl1,$dl2);

    echo "</table></td><td valign=top><table class=ptableshalf>\n";
    echo "<tr><td>&nbsp;</td></tr>";
//    $dl1   = "displayProfile.php?category=design&artist=";
//    $dl2 = "";
//    printTop10('M_DESIGN','Publicity Design',$dl1,$dl2);
    echo "</table></td></tr>\n";

    echo "</table>";

    printFancyFooters();
    mysql_close($cLink);

}

function printTop10( $f,$t, $l, $dl){
    $top10 = 10;

    $start_tag = 'First';
    $end_tag = 'Last';
    $dura_tag = 'Duration';
    if ($_GET['lang'] != 'E'){
	$t = get_uc($t,'');
	$start_tag = get_uc($start_tag,'');
	$end_tag = get_uc($end_tag,'');
	$dura_tag = get_uc($dura_tag,'');
    }

    $qry = "SELECT artist,start,end,duration from LONGEVITY where category=\"$f\" order by duration DESC limit $top10";
    echo "<tr><td colspan=2 class=pleftsubheading>$t</td></tr>";
    $result      = mysql_query($qry);
    if ($result) {
	$i=0;
	$num_results = mysql_num_rows($result);
	echo "<tr><td colspan=2 class=prowsshort${printstyle}>&nbsp;</td>\n";
	echo "<td class=prowsveryshort${printstyle}>$start_tag</td>\n";
	echo "<td class=prowsveryshort${printstyle}>$end_tag</td>\n";
	echo "<td class=prowsveryshort${printstyle}>$dura_tag</td></tr>\n";
	while ($i < $num_results){
	    $art   = mysql_result($result,$i,"artist");
	    $start   = mysql_result($result,$i,"start");
	    $end   = mysql_result($result,$i,"end");
	    $dura   = mysql_result($result,$i,"duration");
	    $i++;
	    if ($_GET['lang'] != 'E') { $aname = get_uc($art,'');}
	    else { $aname = $art; }
	    echo "<tr><td colspan=2 class=prowsshort${printstyle}><a href=\"${l}${art}\">$aname</a></td>\n";
	    if ($dl){
		echo "<td class=prowsveryshort${printstyle}><a href=\"${dl}${art}&year=$start\">$start</a></td>\n";
		echo "<td class=prowsveryshort${printstyle}><a href=\"${dl}${art}&year=$end\">$end</a></td>\n";
		echo "<td class=prowsveryshort${printstyle}><a href=\"${dl}${art}\">$dura</a></td></tr>\n";
	    }
	    else {
		echo "<td class=prowsveryshort${printstyle}>$start</td>\n";
		echo "<td class=prowsveryshort${printstyle}>$end</td>\n";
		echo "<td class=prowsveryshort${printstyle}>$dura</td></tr>\n";
	    }
	}
    }
}
?>
