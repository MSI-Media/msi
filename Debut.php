<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['lang'] = $_SESSION['lang'];

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");
    drawTables();
    drawGraphs();
    printFancyFooters();
    mysql_close($cLink);

}
function drawTables()
{
global $_Master_newfaces;
$datafile = "php/data/debutants.txt"; 
$fh = fopen($datafile, "r");
$i=0;
echo "<table width=100% class=ptables align=center><tr><td width=60% valign=top>\n";
echo "<table width=100% >\n";
printProfileHeaders('Year','Musicians','Lyricists','Singers');
while (!feof($fh)){
    $i++;
    $ds = fgets($fh,1048576);
    $ds = ltrim(rtrim($ds));
    $lx = explode(',',$ds);
    $link = $_Master_newfaces;
    $year = $lx[0];
    $mus = $lx[1];
    $lyr = $lx[2];
    $sing = $lx[3];
    if ( $i&1 ) {
	$printstyle = 'odd';
    }
    if ($year != '' && ($mus > 0 || $lyr > 0 || $sing > 0) ){
	echo "<tr><td class=\"pcells${printstyle}\">$year</td><td class=\"pcells${printstyle}\"><a href=\"${link}?mode=M_MUSICIAN&year=$year\">$mus</a></td><td class=\"pcells${printstyle}\"><a href=\"${link}?mode=M_WRITERS&year=$year\">$lyr</td><td class=\"pcells${printstyle}\"><a href=\"${link}?mode=S_SINGERS&year=$year\">$sing</a></td></tr>\n";
	$printstyle = '';	
    }
}
echo "</table>"; 
echo "</td><td valign=top>\n";
echo "<div class=\"fb-like-box\" data-href=\"http://facebook.com/malayalasangeetham.info\" data-width=\"400\" data-height=\"600\" data-show-faces=\"true\" data-stream=\"true\" data-header=\"true\"></div>\n";
echo "</td></tr></table>\n";
}
function drawGraphs()
{
    $debmsg = 'Debutants Over the Years';
    $mmsg = 'Musicians';
    $lmsg = 'Lyricists';
    $smsg = 'Singers';
    $ymsg = 'Year';
    if ($_GET['lang'] != 'E'){ 
	$debmsg = get_uc($debmsg,''); 
	$mmsg = get_uc($mmsg,'');
	$lmsg = get_uc($lmsg,'');
	$smsg = get_uc($smsg,'');
	$ymsg = get_uc($ymsg,'');
    }
echo "<html>\n";
echo "  <head>\n";
echo "    <script type=\"text/javascript\" src=\"https://www.google.com/jsapi\"></script>\n";
echo "    <script type=\"text/javascript\">\n";
echo "      google.load(\"visualization\", \"1\", {packages:[\"corechart\"]});\n";
echo "      google.setOnLoadCallback(drawChart);\n";
echo "      function drawChart() {\n";
echo "        var data = google.visualization.arrayToDataTable([\n";
echo "          [\"$ymsg\", \"$mmsg\", \"$lmsg\", \"$smsg\"],\n";

$datafile = "php/data/debutants.txt"; 
$fh = fopen($datafile, "r");
while (!feof($fh)){
    $ds = fgets($fh,1048576);
    $ds = ltrim(rtrim($ds));
    $lx = explode(',',$ds);
    $year = $lx[0];
    $mus = $lx[1];
    $lyr = $lx[2];
    $sing = $lx[3];
    if ($year != ''){
	echo "          [\"$year\",  $mus,      $lyr,     $sing],\n";
    }
}
echo "        ]);\n";
echo "\n";
echo "        var options = {\n";
echo "          title: \"$debmsg\"\n";
echo "        };\n";
echo "\n";
echo "        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));\n";
echo "        chart.draw(data, options);\n";
echo "      }\n";
echo "    </script>\n";
echo "  </head>\n";
echo "  <body>\n";
echo "    <div id=\"chart_div\" style=\"width: 950px; height: 500px;\"></div>\n";
echo "  </body>\n";
echo "</html>\n";
}
?>
