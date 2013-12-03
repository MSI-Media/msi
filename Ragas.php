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

    $startLet = $_GET['let'];
    if (!$startLet){
	$startLet = 'A';
    }

    echo "<div class=ptext>\n";
    foreach(range('A','Z') as $letter){
	if ($startLet == $letter){
	    echo "$letter | ";
	}
	else {
	    echo "<a href=\"$_Master_ragas?let=$letter&lang=$_GET[lang]\">$letter</a> | ";

	}
    }
    echo "</div>";

    $ragas = buildArrayFromQuery("SELECT DISTINCT S_RAGA FROM SONGS WHERE S_RAGA not like \"%alika%\" and S_RAGA like \"$startLet%\" ORDER BY S_RAGA",'S_RAGA');
    $alragas = buildArrayFromQuery("SELECT DISTINCT S_RAGA FROM ASONGS WHERE S_RAGA not like \"%alika%\" and S_RAGA like \"$startLet%\" ORDER BY S_RAGA",'S_RAGA');




    foreach ($alragas as $alr){
	if (!in_array("$alr",$ragas)){
	    array_push  ($ragas,"$alr");
	}
    }
    sort ($ragas);
    echo "<table width=100%>\n";
    printProfileHeaders('Raga','Movie Songs','Album Songs','Classification');
    $cnt=0;
    foreach ($ragas as $rg){
	echo "<tr>\n";
	if (file_exists("Ragas-Support/Info/${rg}.txt")){

	    printDetailCells($rg,"$_Master_raga_desc?raga=$rg");
	}
	else {
	    printDetailCells($rg,'');
	}
	printDetailCells(runQuery("SELECT COUNT(S_ID) as ccn FROM SONGS where S_RAGA=\"$rg\"",'ccn'),'',$cnt);
	printDetailCells(runQuery("SELECT COUNT(S_ID) as ccn FROM ASONGS where S_RAGA=\"$rg\"",'ccn'),'',$cnt);
	printDetailCells("Classification","$_Master_profile_script?category=raga&artist=$rg",$cnt);
	echo "</tr>";
	$cnt++;
    }

    echo "</table>";
    printFancyFooters();
    mysql_close($cLink);

}

?>
