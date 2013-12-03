<?php session_start();
{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    include( 'GoogleChart.class.php' );
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");;	
    require_once("includes/utils.php");

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");

   $mode = $_GET['mode'];

    $vitalstatmsg = "Statistics on the Essential Data";
    if ($mode ==  'ALBUM'){
	$vitalstatmsg = "Statistics on the Non Movie Data";
    }

if ($_GET['lang'] != 'E'){
    $vitalstatmsg = get_uc($vitalstatmsg,'');	
}

echo "<div class=pheading>$vitalstatmsg</div>";
$counts = getLatestCount($mode);
$dcounts = getLatestDetailedCount($mode);
$ratecnt = runQuery("SELECT COUNT(DISTINCT(mid)) as ccn FROM RATINGS",'ccn');
$nmovies = $counts[1];
$nsongs  = $counts[0];
echo "<div align=center><table width=100% border=0 align=center>";

    $m1 = "Movies Related";
    $m2 = "Movies";
    if ($mode == 'ALBUM'){
	$m1 = "Albums Related";
	$m2 = "Albums";
    }

    $m3 = "Composers";
    $m4 = "Lyricists";
    $m5 = "Unreleased Movies";
    $m6 = "Dubbed Movies";
    $m7 = "Movies for which the Lyricists are available";
    $m8 = "Movies for which the Musicians are available";
    $m9 = "Movies for which Director Names is available";
    $m10 = "Movies for which Year of Release is available";
    $m11 = "Movies for which Posters are available";
    $m12 = "Movies for which Reviews are available";
    $m13 = "Movies for which Song Books are available";
    $m14 = "Movies for which Admin Ratings are available";
    $s1 = 'Songs Related';
    $s11 = 'Songs';
    $s2 = 'Songs for which the Lyrics are available';
    $s3 = 'Songs for which Lyrics are available in Malayalam';
   $s4 = 'Songs from Unreleased Movies';
    $s5 = 'Songs from Dubbed Movies';
    $s6 = 'Number of Audio Clips Available';
    $s7 = 'Number of Video Songs available';
    $s8 = 'Number of Karaoke Songs available';
    $s9 = 'Number of Unique Audio Clip Submitters';
    $s10 = 'Number of Unique Video Submitters';


    if ($_GET['lang']  != 'E') {
	$m1 = get_uc($m1,'');
	$m2 = get_uc($m2,'');
	$m3 = get_uc($m3,'');
	$m4 = get_uc($m4,'');
	$m5 = get_uc($m5,'');
	$m6 = get_uc($m6,'');
	$m7 = get_uc($m7,'');
	$m8 = get_uc($m8,'');
	$m9 = get_uc($m9,'');
	$m10 = get_uc($m10,'');
	$m11 = get_uc($m11,'');
	$m12 = get_uc($m12,'');
	$m13 = get_uc($m13,'');
	$m14 = get_uc($m14,'');


	$s1 = get_uc($s1,'');
	$s11 = get_uc($s11,'');
	$s2 = get_uc($s2,'');
	$s3 = get_uc($s3,'');
	$s4 = get_uc($s4,'');
	$s5 = get_uc($s5,'');
	$s6 = get_uc($s6,'');
	$s7 = get_uc($s7,'');
	$s8 = get_uc($s8,'');
	$s9 = get_uc($s9,'');
	$s10 = get_uc($s10,'');

    }


echo "<tr bgcolor=#000000><td colspan=3 class=fixedsmall><font color=#ffffff>$m1</font></td></tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m2</td><td colspan=2 class=fixedsmall>",$counts[1],"</td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m3</td><td colspan=2 class=fixedsmall>",$counts[2],"</td>";
echo "</tr>";
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m4</td><td colspan=2 class=fixedsmall>",$counts[3],"</td>";
echo "</tr>";

$lyrfiles = $counts[4];

    if ($mode != 'ALBUM'){
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m5</td><td class=fixedsmall>",$dcounts[1],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[1]*100/$nmovies),"%)</b></td>";
echo "</tr>";
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m6</td><td class=fixedsmall>",$dcounts[3],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[3]*100/$nmovies),"%)</b></td>";
echo "</tr>";
    }
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m7</td><td class=fixedsmall>",$dcounts[5],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[5]*100/$nmovies),"%)</b></td>";
echo "</tr>";


echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m8</td><td class=fixedsmall>",$dcounts[4],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[4]*100/$nmovies),"%)</b></td>";
echo "</tr>";


echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m9</td><td class=fixedsmall>",$dcounts[7],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[7]*100/$nmovies),"%)</b></td>";
echo "</tr>";


echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m10</td><td class=fixedsmall>",$dcounts[6],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[6]*100/$nmovies),"%)</b></td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m11</td><td class=fixedsmall>",$dcounts[13],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[13]*100/$nmovies),"%)</b></td>";
echo "</tr>";

    if ($mode != 'ALBUM'){
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m12</td><td class=fixedsmall>",$dcounts[14],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[14]*100/$nmovies),"%)</b></td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m13</td><td class=fixedsmall>",$dcounts[15],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[15]*100/$nmovies),"%)</b></td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$m14</td><td class=fixedsmall>",$ratecnt,"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$ratecnt*100/$nmovies),"%)</b></td>";
echo "</tr>";
    }

//------------------------------------------------------------------------------------------------------------------------


echo "<tr bgcolor=#000000><td colspan=3 class=fixedsmall><font color=#ffffff>$s1</font></td></tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s11</td><td colspan=2 class=fixedsmall>",$counts[0],"</td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s2</td><td class=fixedsmall>",$lyrfiles,"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$lyrfiles*100/$nsongs),"%)</b></td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s3</td><td class=fixedsmall>",$counts[5],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$counts[5]*100/$nsongs),"%)</b></td>";
echo "</tr>";



    if ($mode != 'ALBUM'){
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s4</td><td class=fixedsmall>",$dcounts[0],"</td><td class=fixedsmall>&nbsp;&nbsp; <b>(",sprintf("%.2f",$dcounts[0]*100/$nsongs),"%)</b></td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s5</td><td class=fixedsmall>",$dcounts[2],"</td><td class=fixedsmall>&nbsp;&nbsp; <b>(",sprintf("%.2f",$dcounts[2]*100/$nsongs),"%)</b></td>";
echo "</tr>";


    }
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s6</td><td class=fixedsmall>",$dcounts[8],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[8]*100/$nsongs),"%)</b></td>";
echo "</tr>";
echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s7</td><td class=fixedsmall>",$dcounts[10],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[10]*100/$nsongs),"%)</b></td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s8</td><td class=fixedsmall>",$dcounts[12],"</td><td class=fixedsmall><b>&nbsp;&nbsp; (",sprintf("%.2f",$dcounts[12]*100/$nsongs),"%)</b></td>";
echo "</tr>";

echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s9</td><td colspan=2 class=fixedsmall>",$dcounts[9],"</td>";
echo "</tr>";



echo "<tr bgcolor=#ffeeff>";
echo "<td class=fixedsmall>$s10</td><td class=fixedsmall colspan=2>",$dcounts[11],"</td>";
echo "</tr>";


echo "</table></div>";
//---------------

    printFancyFooters();
    mysql_close($cLink);

}
