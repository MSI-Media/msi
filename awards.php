<?php session_start();
{
    error_reporting (E_ERROR);

    $_GET['lang'] = $_SESSION['lang'];

    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");


    $cLink = msi_dbconnect();
    printXHeader('');

    $mode = 'STATE_AWARDS';

    $_yr = 'Year';
    $_composer = 'Composer';
    $_lyricist = 'Lyricist';
    $_male = 'Male Singer';
    $_female ='Female Singer';

    $title = "Kerala State Music Awards";
    if ($_GET['mode'] == 'National'){
	$mode = 'NATIONAL_AWARDS';
	$title = "National Awards for Malayalam Movies";
    }

    if ($_GET['lang'] != 'E'){
	$_composer = get_uc($_composer,'');
	$_lyricist = get_uc($_lyricist,'');
	$_male = get_uc($_male,'');
	$_female = get_uc($_female,'');
	$title = get_uc($title,'');
    }

   echo "<div class=pheading>$title</div><br>";

   $query = "SELECT * from $mode ORDER BY award_year DESC";
   $res_funcQry = mysql_query($query);
   $num_funcQry = mysql_num_rows($res_funcQry);
   $i = 0;


   echo "<table class=ptables>\n";
   echo "<tr bgcolor=#eeeeff><td valign=top ><b>$_yr</b></td><td valign=top   class=\"prowsshort${printstyle}\"><b>$_composer</b></td><td valign=top   class=\"prowsshort${printstyle}\"><b>$_lyricist</b></td><td valign=top   class=\"prowsshort${printstyle}\"><b>$_male</b></td><td valign=top   class=\"prowsshort${printstyle}\"><b>$_female</b></td></tr>";

   while ($i < $num_funcQry){

       $printstyle='';
       if ( $i&1 ) {
	   $printstyle = 'odd';
       }
       $year = mysql_result($res_funcQry, $i, "award_year");
       $mus  = mysql_result($res_funcQry, $i, "award_musician");
       $lyr  = mysql_result($res_funcQry, $i, "award_lyricist");
       $male  = mysql_result($res_funcQry, $i, "award_male");
       $female  = mysql_result($res_funcQry, $i, "award_female");
       $fmus  = mysql_result($res_funcQry, $i, "award_musician_movie");
       $flyr  = mysql_result($res_funcQry, $i, "award_lyricist_movie");
       $fmale  = mysql_result($res_funcQry, $i, "award_male_movie");
       $ffemale  = mysql_result($res_funcQry, $i, "award_female_movie");


       $muslink = "$_Master_profile_script?category=musician&artist=$mus";
       $lyrlink = "$_Master_profile_script?category=lyricist&artist=$lyr";
       $malelink = "$_Master_profile_script?category=singer&artist=$male";
       $femalelink = "$_Master_profile_script?category=singer&artist=$female";

       if (strpos ($ffemale,",") !== false){
	   $ffemalelink = SearchLinks($ffemale);
       }
       else {
	   $ffemalelink = "$_Master_search_process?db=movies&moviename=$ffemale";
       }
       if (strpos ($fmale,",") !== false){
	   $fmalelink = SearchLinks($fmale);
       }
       else {
	   $fmalelink = "$_Master_search_process?db=movies&moviename=$fmale";
       }
       if (strpos ($flyr,",") !== false){
	   $flyrlink = SearchLinks($flyr);
       }
       else {
	   $flyrlink = "$_Master_search_process?db=movies&moviename=$flyr";
       }
       if (strpos($fmus,",") !== false){
	   $fmuslink = SearchLinks($fmus);
       }
       else {
	   $fmuslink = "$_Master_search_process?db=movies&moviename=$fmus";
       }

       if ($_GET['lang'] != 'E'){
	   $mus = get_uc($mus,'');
	   $lyr = get_uc($lyr,'');
	   $male = get_uc($male,'');
	   $female = get_uc($female,'');
	   $fmus = get_uc($fmus,'');
	   $flyr = get_uc($flyr,'');
	   $fmale = get_uc($fmale,'');
	   $ffemale = get_uc($ffemale,'');
       }
       echo "<tr class=\"prowshort${printstyle}\" >\n";

       if ($fmus != ''){
	   if (strpos ($fmus,",") !== false){
              echo "<td valign=top  class=\"prowsveryshort${printstyle}\">$year</td><td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$muslink\">$mus</a> $fmuslink</a>)</td>";
	   }
	   else {
              echo "<td valign=top  class=\"prowsveryshort${printstyle}\">$year</td><td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$muslink\">$mus</a> (<a href=\"$fmuslink\">$fmus</a>)</td>";
	   }
       }
       else {
              echo "<td valign=top  class=\"prowsveryshort${printstyle}\">$year</td><td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$muslink\">$mus</a></td>";
       }
       if ($flyr != ''){
	   if (strpos ($flyr,",") !== false){
	       echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$lyrlink\">$lyr</a> ($flyrlink)</td>";
	   }
	   else {
	       echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$lyrlink\">$lyr</a> (<a href=\"$flyrlink\">$flyr</a>)</td>";
	   }
       }
       else {
	   echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$lyrlink\">$lyr</a> </td>\n";
       }

       if ($fmale != ''){
	   if (strpos ($fmale,",") !== false){
	       echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$malelink\">$male</a> ($fmalelink)</td>";
	   }
	   else {
	       echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$malelink\">$male</a> (<a href=\"$fmalelink\"> $fmale</a>)</td>";
	   }
       }
       else {
	   echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$malelink\">$male</a></td>";
       }


       if ($ffemale != ''){
	   if (strpos ($ffemale,",") !== false){
	       echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$femalelink\">$female</a> ($ffemalelink)</td>";
	   }
	   else {
	       echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$femalelink\">$female</a> (<a href=\"$ffemalelink\"> $ffemale</a>)</td>";

	   }
       }
       else {
	   echo "<td valign=top  class=\"prowsshort${printstyle}\"><a href=\"$femalelink\">$female</a> </td>";
       }

       echo "</tr>";

       $i++;
   }

   echo "</table>";

   printFancyFooters();
   mysql_close($cLink);
}
function SearchLinks($lnk){

    $retlnk = array();
    $lnks = explode (',',$lnk);
    foreach ($lnks as $ln){
    $lns = $ln;
    if ($_GET['lang'] != 'E'){
	$lns = get_uc($ln,'');
    }
	array_push ($retlnk ,"<a href=\"qSearch.php?q=$ln\">$lns</a>");
    }

//    print_r($retlnk);

    return implode (',',$retlnk);

}
?>
