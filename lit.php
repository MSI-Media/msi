<?php session_start();

//    error_reporting (E_ERROR);
    $_GET['lang'] = $_SESSION['lang'];

    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('Popup');

    $lang = $_GET['lang'];

    $tag = $_SERVER['QUERY_STRING'];	
    if (strpos("$tag","&") != false){
	$vars = explode ('&', $tag);
	$tag = $vars[0];
}
     $start_letter = $_GET['tag'];
     if (!$start_letter){
     	$start_letter = "A";
     }     		

if ($tag == 'works'){

    $query = "SELECT * FROM LITERATURE where L_WORK like \"$start_letter%\" order by L_WORK";
    $res_funcQry = mysql_query($query);
    $num_funcQry = mysql_num_rows($res_funcQry);
    echo "<table class=ptables>\n";


     echo "<tr><td class=pcells colspan=3> പേരിന്റെ ആദ്യാക്ഷരം  : \n";
     foreach(range('A','Z') as $letter){
	 if ($start_letter == $letter){
	     echo "$letter | ";
	 }
	 else {
	     echo "<a href=\"$_Master_lit_page?works&tag=$letter\">$letter</a> | ";
	 }
     }
     echo "</td></tr>";
//    $_work = 'Work'; $_author = 'Author' ; $_movie= 'Movie';
    echo "<tr>\n";
    printDetailCellHeads('Literary Work');
    printDetailCellHeads('Author');
    printDetailCellHeads('Movie');
    echo "</tr>";
//    echo "<tr ><td class=\"pcells${printstyle}\">$_work </td><td class=\"pcells${printstyle}\">$_author</td><td class=\"pcells${printstyle}\"><a href=\"$url\">$_movie</a></td></tr>\n";
    while ($i < $num_funcQry){
	$mid    = mysql_result($res_funcQry, $i, "L_ID");
	$work   = mysql_result($res_funcQry, $i, "L_WORK");
	$author = mysql_result($res_funcQry, $i, "L_AUTHOR");
	$genre  = mysql_result($res_funcQry, $i, "L_GENRE");
	$movie  = runQuery("SELECT M_MOVIE FROM MOVIES WHERE M_ID=$mid","M_MOVIE");
	$url    = "$_Master_movie_script?$mid";
	if ($_GET['lang'] != 'E'){
	    $mwork  = get_uc("$work","");
	    $mauthor = get_uc("$author","");
	    $mgenre= get_uc("$genre","");
	    $mmovie = get_uc("$movie","");
	}
	else {
	    $mwork  = $work;
	    $mauthor = $author;
	    $mgenre= $genre;
	    $mmovie = $movie;
	}

	if ( $i&1 ) {
	    $printstyle = 'odd';
	}
	
	if ($genre != ""){
	    echo "<tr ><td class=\"pcells${printstyle}\">$mwork ($mgenre) </td><td class=\"pcells${printstyle}\">$mauthor</td><td class=\"pcells${printstyle}\"><a href=\"$url\">$mmovie</a></td></tr>\n";
	}
	else {
	    echo "<tr ><td class=\"pcells${printstyle}\">$mwork </td><td class=\"pcells${printstyle}\">$mauthor</td><td class=\"pcells${printstyle}\"><a href=\"$url\">$mmovie</a></td></tr>\n";
	}
	$printstyle='';
	$i++;
    }
    echo "</table>";
}
else if ($tag == "authors"){

    $query = "SELECT * FROM LITERATURE where L_AUTHOR like \"$start_letter%\" order by L_AUTHOR";
    $res_funcQry = mysql_query($query);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i=0;
    echo "<table class=ptables>\n";
    echo "<tr><td align=center  class=pcells colspan=3> പേരിന്റെ ആദ്യാക്ഷരം  : \n";
     foreach(range('A','Z') as $letter){
	 if ($start_letter == $letter){
	     echo "$letter | ";
	 }
	 else {
	     echo "<a href=\"$_Master_lit_page?authors&tag=$letter\">$letter</a> | ";
	 }
     }
	echo "</td></tr>";

    echo "<tr>\n";
    printDetailCellHeads('Author');
    printDetailCellHeads('Literary Work');
    printDetailCellHeads('Movie');
    echo "</tr>";
    while ($i < $num_funcQry){
	$mid    = mysql_result($res_funcQry, $i, "L_ID");
	$work   = mysql_result($res_funcQry, $i, "L_WORK");
	$author = mysql_result($res_funcQry, $i, "L_AUTHOR");
	$genre  = mysql_result($res_funcQry, $i, "L_GENRE");
	$movie  = runQuery("SELECT M_MOVIE FROM MOVIES WHERE M_ID=$mid","M_MOVIE");
	$url    = "$_Master_movie_script?$mid";
	if ($_GET['lang'] != 'E'){
	    $mwork = get_uc("$work","");
	    $mauthor = get_uc("$author","");
	    $mgenre = get_uc("$genre","");
	    $mmovie = get_uc("$movie","");
	}
	else {
	    $mwork = $work;
	    $mauthor = $author;
	    $mgenre = $genre;
	    $mmovie = $movie;
	}

	if ($pauthor == "" || $pauthor != $author){
	    echo "<tr><td colspan=3 class=pcells >&nbsp;</td></tr>";
	}

	if ( $i&1 ) {
	    $printstyle = 'odd';
	}
	if ($pauthor != $author){
	    echo "<tr><td class=\"prowsshort${printstyle}\">$mauthor</td><td class=\"prowsshort${printstyle}\">$mwork</td><td class=\"prowsshort${printstyle}\"><a href=\"$url\">$mmovie</a></td></tr>\n";
	}
	else {
	    echo "<tr><td class=\"prowsshort${printstyle}\">&nbsp;</td><td class=\"prowsshort${printstyle}\">$mwork</td><td class=\"prowsshort${printstyle}\"><a href=\"$url\">$mmovie</a></td></tr>\n";
	}
	$printstyle = '';
	$pauthor = $author;
	$i++;
    }
    echo "</table>";
}
else if ($tag == "books"){
    echo "<table class=ptables>\n";
    $query = "SELECT * FROM REFS order by category,book";
    $res_funcQry = mysql_query($query);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i=0;

    printDetailCellHeads('Book');
    printDetailCellHeads('Author/Editor');
    printDetailCellHeads('Publisher');
    while ($i < $num_funcQry){
	$cat    = mysql_result($res_funcQry, $i, "category");
	$work   = mysql_result($res_funcQry, $i, "book");
	$author = mysql_result($res_funcQry, $i, "author");    
	$publisher = mysql_result($res_funcQry, $i, "publisher");    

	if ($_GET['lang'] != 'E'){
	    $mcategory = get_uc("$cat","");
	    $mwork = get_uc($work, "");
	    $mauthor = get_uc($author, "");
	    $mpublisher = get_uc($publisher,"");
	}
	else {
	    $mcategory = $cat;
	    $mwork = $work;
	    $mauthor = $author;
	    $mpublisher = $publisher;
	}

	if (!$mcategory){
	    $mcategory = $cat;
	}
	if (!$mwork){
	    $mwork = $work;
	}
	if (!$mauthor){
	    $mauthor = $author;
	}
	if (!$mpublisher){
	    $mpublisher = $publisher;
	}
	if ($pcat != $cat){
	    echo "<tr bgcolor=#fffeee><td class=pcellheads colspan=3>$mcategory</td></tr>";
	}

	if ( $i&1 ) {
	    $printstyle = 'odd';
	}

	if (file_exists("$_RootDir/references/${work} F.jpg")){
	    echo "<tr bgcolor=#FFFFEE><td class=\"pcells${printstyle}\"><a href=\"$_RootofMSI/references/${work} F.jpg\" toptions=\"group=books,effect=fade\">$mwork</a></td><td class=\"pcells${printstyle}\">$mauthor</td><td class=\"pcells${printstyle}\">$mpublisher</td></tr>\n";
	}
	else {
	    echo "<tr bgcolor=#FFFFEE><td class=\"pcells${printstyle}\">$mwork</td><td class=\"pcells${printstyle}\">$mauthor</td><td class=\"pcells${printstyle}\">$mpublisher</td></tr>\n";
	}
	$printstyle='';
	$pcat = $cat;
	$mcategory = "";
	$mwork = "";
	$mauthor = "";
	$mpublisher = "";
	
	$i++;
    }
    echo "</table>";

}

else if ($tag == "pattupusthakams"){

    echo "<table class=ptables>\n";

     echo "<tr bgcolor=#ffffff><td align=center colspan=3 > പേരിന്റെ ആദ്യാക്ഷരം  : \n";
     foreach(range('A','Z') as $letter){
	 if ($start_letter == $letter){
	     echo "$letter | ";
	 }
	 else {
	     echo "<a href=\"$_Master_lit_page?pattupusthakams&tag=$letter\">$letter</a> | ";
	 }
     }
     echo "</td></tr>";

    echo "</table>\n";
    $query = "SELECT P_ID,P_MOVIE FROM PPUSTHAKAM order by P_MOVIE";
    $res_funcQry = mysql_query($query);
    $num_funcQry = mysql_num_rows($res_funcQry);
    $i=0;
   echo "<table class=ptables>\n";
    echo "<tr>\n";
    printDetailCellHeads ('Movie');
    printDetailCellHeads ('Story');
    printDetailCellHeads ('Producer');
    echo "</tr>\n";
    while ($i < $num_funcQry){
        $mov     = mysql_result($res_funcQry, $i, "P_MOVIE");
        $movid   = mysql_result($res_funcQry, $i, "P_ID");
        $query2 = "SELECT MOVIES.M_MOVIE,MDETAILS.M_STORY,MDETAILS.M_PRODUCER FROM MOVIES,MDETAILS where MOVIES.M_ID=$movid and MDETAILS.M_ID=$movid and MOVIES.M_MOVIE like \"$start_letter%\"";
        $res_funcQry2 = mysql_query($query2);
        $num_funcQry2 = mysql_num_rows($res_funcQry2);
        $ii=0;
	if ( $ii&1 ) {
	    $printstyle = 'odd';
	}
        while ($ii < $num_funcQry2){
            $movname     = mysql_result($res_funcQry2, $ii, "MOVIES.M_MOVIE");
            $story       = mysql_result($res_funcQry2, $ii, "MDETAILS.M_STORY");
            $producer    = mysql_result($res_funcQry2, $ii, "MDETAILS.M_PRODUCER");
	    if ($_GET['lang'] != 'E'){
		$m_mov = get_uc("$movname","");
		$m_story = get_uc ("$story", "");
		$m_producer = get_uc ("$producer", "");
	    }
	    else {
		$m_mov = $movname;
		$m_story =$story;
		$m_producer = $producer;
	    }
            echo "<tr>\n";
	    echo "<td class=\"prowsshort${printstyle}\"><a href=\"$_Master_Songbook_script?movie=$mov&movn=$m_mov&mid=$movid\">$m_mov</td>\n";
	    echo "<td class=\"prowsshort${printstyle}\">$m_story</td>\n";
	    echo "<td class=\"prowsshort${printstyle}\">$m_producer</td>\n";
            echo "</tr>\n";
            $ii++;                                                                                                                                                                         
        }
	$printstyle='';
	$i++;
    }
echo "</table>\n";

}
    printFancyFooters();
    mysql_close($cLink);






?>
