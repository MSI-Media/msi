<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");

    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['lang'] = $_SESSION['lang'];
    $profileScript = $_Master_profile_script;
    $songScript    = $_Master_songlist_script;
    $movieScript   = $_Master_movielist_script;
    $albumScript    = $_Master_albumlist_script;
    $albumsongScript   = $_Master_albumsonglist_script;

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");

    $startLet = $_GET['let'];
    if (!$startLet){
	$startLet = '201';
    }

    echo "<div class=psubheading>${startLet}0s..</div><P>";

    $msg = 'Select a Decade';
    if ($_GET['lang'] != 'E') { $msg = get_uc($msg,''); }
    echo "<div class=ptext> $msg : \n";
    foreach(array('193','194','195','196','197','198','199','200','201') as $letter){
	if ($startLet == $letter){
	    echo "${letter}0s .. | ";
	}
	else {
	    echo "<a href=\"$_Master_years?let=$letter\">${letter}0s</a> ... | ";
	}
    }
    echo "</div><P>";
  
    $years = buildArrayFromQuery("SELECT DISTINCT S_YEAR FROM SONGS WHERE S_YEAR like \"$startLet%\" ORDER BY S_YEAR",'S_YEAR');
    $ayears = buildArrayFromQuery("SELECT DISTINCT S_YEAR FROM ASONGS WHERE S_YEAR like \"$startLet%\" ORDER BY S_YEAR",'S_YEAR');
    foreach ($ayears as $ay){
       if (!in_array("$ay",$years)){
           array_push ($years, "$ay");
       }
    }
    rsort ($years);
    echo "<table width=100%>\n";
    printProfileHeaders('Year','Movies','Songs','Albums','Album Songs','Composers','Lyricists','Classification');
    $colname = "S_YEAR";
    $ycolname = "M_YEAR";
    foreach ($years as $art){

        
	echo "<tr>\n";
	printDetailCells($art,'',$cnt);

        $ylimit = runQuery("SELECT COUNT(M_ID) as ccn FROM MOVIES where $ycolname like \"$art%\" ",'ccn');
 	printDetailCells($ylimit,"$movieScript?year=$art&tag=Search&limit=$ylimit",$cnt); 
	
        $limit = runQuery("SELECT COUNT(S_ID) as ccn FROM SONGS where $colname like \"$art%\" ",'ccn');
	printDetailCells($limit,"$songScript?year=$art&tag=Search&limit=$limit",$cnt); 

        $alimit = runQuery("SELECT COUNT(M_ID) as ccn FROM ALBUMS where $ycolname like \"$art%\" ",'ccn');
 	printDetailCells($alimit,"$albumScript?year=$art&tag=Search&limit=$alimit",$cnt); 
	
        $aslimit = runQuery("SELECT COUNT(S_ID) as ccn FROM ASONGS where $colname like \"$art%\" ",'ccn');
	printDetailCells($aslimit,"$albumsongScript?year=$art&tag=Search&limit=$aslimit",$cnt); 

     $climit = runQuery("SELECT COUNT(DISTINCT(M_MUSICIAN)) as ccn FROM MOVIES where $ycolname like \"$art%\" ORDER BY M_MUSICIAN ",'ccn');
       $cclimit = runQuery("SELECT COUNT(M_MUSICIAN) as ccn FROM MOVIES where $ycolname like \"$art%\" ORDER BY M_MUSICIAN ",'ccn');
       printDetailCells($climit,"$movieScript?year=$art&tag=Search&limit=$cclimit",$cnt); 

        $llimit = runQuery("SELECT COUNT(DISTINCT(M_WRITERS)) as ccn FROM MOVIES where $ycolname like \"$art%\" ORDER BY M_WRITERS ",'ccn');
        $lllimit = runQuery("SELECT COUNT(M_WRITERS) as ccn FROM MOVIES where $ycolname like \"$art%\" ORDER BY M_WRITERS ",'ccn');
	printDetailCells($llimit,"$movieScript?year=$art&tag=Search&limit=$lllimit",$cnt); 

	printDetailCells("Classification","$profileScript?category=year&artist=$art&limit=$limit",$cnt);
	$cnt++;
	echo "</tr>";
    }

    echo "</table>";
   if (file_exists ("php/data/bar_data_${startLet}.txt")){
         doChronologicalBars("php/data/bar_data_${startLet}.txt");
    }
    mysql_close($cLink);
    printFancyFooters();
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
