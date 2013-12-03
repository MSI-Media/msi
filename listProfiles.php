<?php session_start();

{
    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_profileUtils.php");
    require_once("includes/searchUtils.php");
    require_once("includes/cache.php");

    $cLink = msi_dbconnect();
    printXHeader('Popup');

    $user     = $_GET['u'];	
    $category = $_GET['c'];
    
    getProfilesAndLinks("$category","$user");


    printFancyFooters();
    mysql_close($cLink);



}
function getProfilesAndLinks($c,$u)
{

    echo "<form name=listProfiles.php method=get>\n";

    $categories = buildArrayFromQuery("SELECT DISTINCT P_CATEGORY FROM PROFILES ORDER BY P_CATEGORY",'P_CATEGORY');
    $users      = buildArrayFromQuery("SELECT DISTINCT P_USER FROM PROFILES ORDER BY P_USER",'P_USER');

    $_artist   = 'Artist Name';
    $_category = 'Field of Contribution';
    $_author   = 'Author Name';
    $_date     = 'Publish Date';
    if ($_GET['lang'] != 'E') { 
	$_artist = get_uc($_artist,'');
	$_category = get_uc($_category,'');
	$_author = get_uc($_author,'');
	$_date = get_uc($_date,'');
    }
    echo "<div class=textblock>\n";
    echo "$_category:";
    echo "<select name=c>\n";
    echo "<option value=\"\">All</option>\n";
    foreach ($categories as $cat){
	echo "<option value=\"$cat\">$cat</option>\n";
    }
    echo "</select>";
    echo "$_author:";
    echo "<select name=u>\n";
    echo "<option value=\"\">All</option>\n";
    foreach ($users as $use){
	echo "<option value=\"$use\">$use</option>\n";
    }
    echo "</select>";
    echo "</div>";
    echo "<input type=submit value=List>\n";
    echo "</form>\n";

    if ($c && !$u) {
	$q = "SELECT DISTINCT P_ARTIST,P_CATEGORY FROM PROFILES WHERE P_CATEGORY=\"$c\" order by P_ARTIST";
    }
    else if ($u && !$c) {
	$q = "SELECT DISTINCT P_ARTIST,P_CATEGORY FROM PROFILES WHERE  P_USER=\"$u\" order by P_ARTIST";
    }
    else if ($u && $c){
	$q = "SELECT DISTINCT P_ARTIST,P_CATEGORY FROM PROFILES WHERE P_USER=\"$u\" and P_CATEGORY=\"$c\" order by P_ARTIST";
    }
    else {
	$q = "SELECT DISTINCT P_ARTIST,P_CATEGORY FROM PROFILES order by P_ARTIST ";
    }

    $res_qry = mysql_query($q);
    $num_qry = mysql_num_rows($res_qry);

    global $_Master_profile_script;
    if ($num_qry > 0){
	echo "<table class=ptables>\n";
//	echo "<tr class=prows><td><b>$_artist</b></td><td><b>$_category</b></td><td><b>$_author</b></td><td><b>$_date</b></td></tr>";
	echo "<tr class=prows><td><b>$_artist</b></td><td><b>$_category</b></td><td><b>$_author</b></td></tr>";
	while ($i < $num_qry){
	    $p_art   = mysql_result($res_qry, $i, "P_ARTIST");
	    $p_cat   = mysql_result($res_qry, $i, "P_CATEGORY");
	    $userlist = buildArrayFromQuery("SELECT P_USER FROM PROFILES WHERE P_ARTIST=\"$p_art\"",'P_USER');
	    $p_user  = implode (',',$userlist);
//	    $p_user  = mysql_result($res_qry, $i, "P_USER");
//	    $p_date  = mysql_result($res_qry, $i, "P_ATS");
	    $pa = $p_art;
	    $pc = $p_cat;
	    $us = $p_user;
//	    $da = $p_date;
	    $pctag = strtolower($p_cat);
	    if ($pctag != ''){
		$catstring = "category=$pctag&artist=$pa";
	    }
	    else { $catstring = ''; }
	    if ($_GET['lang'] != 'E') { $pa = get_uc($pa,''); $pc = get_uc($pc,''); $us = get_uc($us,''); }
	    $printstyle = 'prows';
	    if ( $i&1 ) {
		$printstyle .= 'odd';
	    }

	    echo "<tr class=\"$printstyle\">\n";
	    if ($catstring != ''){
		echo "<td ><a href=\"${_Master_profile_script}?${catstring}\">$pa</a></td><td width=40%>$pc</td><td>$us</td>";
	    }
	    else {
		echo "<td >$pa</td><td>$pc</td><td width=40%>$us</td>";
	    }
	    echo "</tr>";
	    $i++;
	}
	echo "</table>";
    }
}
