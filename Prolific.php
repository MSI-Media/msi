<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");
    $_GET['lang'] = $_SESSION['lang'];
    $profileScript = $_Master_profile_script;
    $songScript    = $_Master_songlist_script;
    $movieScript   = $_Master_movielist_script;
    $albumScript    = $_Master_albumlist_script;
    $albumsongScript   = $_Master_albumsonglist_script;

    $_GET['encode']='utf';
    $mode = $_GET['m'];
    if (!$mode) { $mode = 'lyricists'; }

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");

    $startLet = $_GET['let'];
    if (!$startLet){
	$startLet = '201';
    }
    $title = ucfirst($mode);
    if ($_GET['lang'] != 'E') { $title = get_uc($title,''); }
    echo "<div class=psubheading>$title</div><P>";

    if ($mode == 'lyricists'){
	$q1 = "SELECT S_WRITERS, S_YEAR, COUNT( S_SONG ) AS ccn FROM SONGS GROUP BY S_WRITERS, S_YEAR ORDER BY ccn DESC LIMIT 25";
	$q1_title = array('Lyricists','Year','Songs','Movies');
	$q1_flds = array('S_WRITERS','S_YEAR','ccn');
	$q2_flds = array('M_WRITERS','M_YEAR','ccn');
	$q1_tags = array('lyricists','year','songs');
	$q2_tags = array('lyricist','year','songs');
    }
    else if ($mode == 'musicians'){
	$q1 = "SELECT S_MUSICIAN, S_YEAR, COUNT( S_SONG ) AS ccn FROM SONGS GROUP BY S_MUSICIAN, S_YEAR ORDER BY ccn DESC LIMIT 25";
	$q1_title = array('Composers','Year','Songs','Movies');
	$q1_flds = array('S_MUSICIAN','S_YEAR','ccn');
	$q2_flds = array('M_MUSICIAN','M_YEAR','ccn');
	$q1_tags = array('musician','year','songs');
	$q2_tags = array('musician','year','songs');
    }
    else if ($mode == 'singers'){
	$q1 = "SELECT S_SINGERS, S_YEAR, COUNT( S_SONG ) AS ccn FROM SONGS GROUP BY S_SINGERS, S_YEAR ORDER BY ccn DESC LIMIT 25";
	$q1_title = array('Singers','Year','Songs','Movies');
	$q1_flds = array('S_SINGERS','S_YEAR','ccn');
	$q2_flds = array('M_SINGERS','M_YEAR','ccn');
	$q1_tags = array('singers','year','songs');
	$q2_tags = array('singers','year','songs');
    }


    $cnt=0;
    echo "<table width=100%>\n";
    echo "<tr>";
    foreach ($q1_title as $q1t){
	printDetailCellHeads($q1t);
    }
    echo "</tr>";
    $res_funcQry = mysql_query("$q1");
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i = 0;
    while ($i < $num_funcQry){
	$v0 = mysql_result($res_funcQry, $i, $q1_flds[0]);
	$v1 = mysql_result($res_funcQry, $i, $q1_flds[1]);
	$v2 = mysql_result($res_funcQry, $i, $q1_flds[2]);
	echo "<tr>";
	$sqry = "SELECT COUNT(S_ID) as ccn FROM SONGS where ($q1_flds[0] like \"$v0\" or $q1_flds[0] like \"%,$v0,%\" or $q1_flds[0] like \"$v0,%\" or $q1_flds[0] like \"%,$v0\" ) and $q1_flds[1] like \"$v1\" ";
	$slimit = runQuery($sqry, 'ccn');
	if ($q1_tags[0] == 'singers'){
	    $mqry = "SELECT COUNT(DISTINCT M_ID) as ccn FROM SONGS where ($q1_flds[0] like \"$v0\" or $q1_flds[0] like \"%,$v0,%\" or $q1_flds[0] like \"$v0,%\" or $q1_flds[0] like \"%,$v0\" ) and $q1_flds[1] like \"$v1\" ";
	}
	else {
	    $mqry = "SELECT COUNT(DISTINCT M_ID) as ccn FROM MOVIES where ($q2_flds[0] like \"$v0\" or $q2_flds[0] like \"%,$v0,%\" or $q2_flds[0] like \"$v0,%\" or $q2_flds[0] like \",%$v0\"  ) and $q2_flds[1] like \"$v1\" ";
	}
	$mlimit = runQuery($mqry, 'ccn');

	printDetailCells($v0,"$profileScript?category=$q1_tags[0]&artist=$v0",$cnt);
	printDetailCells($v1,"$profileScript?category=$q1_tags[1]&artist=$v1",$cnt);
	printDetailCells($slimit,"$songScript?tag=Search&$q2_tags[0]=$v0&$q1_tags[1]=$v1&category=$q1_tags[0]&limit=$slimit",$cnt);
	printDetailCells($mlimit,"$movieScript?tag=Search&$q2_tags[0]=$v0&$q1_tags[1]=$v1&category=$q1_tags[0]&limit=$mlimit",$cnt);
	echo "</tr>";
	$cnt++;
	$i++;
    }


    
    echo "</table>";

    printFancyFooters();
    mysql_close($cLink);

}
function doChronologicalBars($barfile)
{

    echo "    <script type='text/javascript' src='https://www.google.com/jsapi'></script>\n";
    echo "    <script type='text/javascript'>\n";
    echo "    google.load('visualization', '1', {packages:['corechart']});\n";
    echo "google.setOnLoadCallback(drawChart);\n";
    echo "function drawChart() {\n";
    echo "    var data = new google.visualization.DataTable();\n";
    $movies = 'Movies';
    $directors = 'Movie Songs';
    $musicians = 'Albums';
    $lyricists = 'Album Songs';
    $year = 'Year';
    $title = "Malayalam Movies and Albums Through The Decades";
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
    echo "      title: '',\n";
    echo "      hAxis: {title: '$year', titleTextStyle: {color: 'red'}}\n";
    echo "    };\n";
    
    echo "    var chart = new google.visualization.ColumnChart(document.getElementById('chart_2'));\n";
    echo "    chart.draw(data, options);\n";
    echo "}\n";
    echo "    </script>\n";
    echo "    <div align=center id='chart_2' style='width: 100%; height: 500px;'></div>\n";


}
?>
