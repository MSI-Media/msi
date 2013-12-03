<?php  session_start();

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
    $movieScript = $_Master_movielist_script;
	$songScript = $_Master_songlist_script;
	$albumScript = $_Master_albumlist_script;
	$albumsongScript = $_Master_albumsonglist_script;

	printDetailHeadingDivs ("Sorted Alphabetically");
	echo "<table class=ptables>\n";
	
	echo "<tr class=pheading>\n";
	printDetailCellHeads ('Movies');
	printDetailCellHeads ('Movie Songs');
	printDetailCellHeads ('Albums');
	printDetailCellHeads ('Album Songs');
	echo "</tr>";
	
  $letters = range('A', 'Z');

  // Iterate over 26 letters.
  foreach ($letters as $letter) {
    $limit = runQuery("SELECT COUNT(M_ID) ccn from MOVIES WHERE M_MOVIE like \"$letter%\"",'ccn');
    $url  = "$movieScript?tag=Search&startlet=$letter&limit=$limit";
	$limit2 = runQuery("SELECT COUNT(S_ID) ccn from SONGS WHERE S_SONG like \"$letter%\"",'ccn');
	$url2 = "$songScript?tag=Search&startlet=$letter&limit=$limit2";
	
	$limit3 = runQuery("SELECT COUNT(M_ID) ccn from ALBUMS WHERE M_MOVIE like \"$letter%\"",'ccn');
    $url3  = "$albumScript?tag=Search&startlet=$letter&limit=$limit3";
	$limit4 = runQuery("SELECT COUNT(S_ID) ccn from ASONGS WHERE S_SONG like \"$letter%\"",'ccn');
	$url4 = "$albumsongScript?tag=Search&startlet=$letter&limit=$limit4";
  	echo "<tr class=pcells><td class=pcells><a href=\"$url\">$letter ... </a></td><td class=pcells><a href=\"$url2\">$letter ...</a>
	<td class=pcells><a href=\"$url3\">$letter ... </a></td><td class=pcells><a href=\"$url4\">$letter ...</a>
	</tr>";
  }

	echo "</table>";
    printFancyFooters();
    mysql_close($cLink);

}


?>
