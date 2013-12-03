<?php session_start();

error_reporting (E_ERROR);

include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
$securimage = new Securimage();
require_once("updates/data.php");
require_once("updates/utils.inc");
require_once("updates/movieSearch.inc");
require_once("_includes/_xtemplate_header.php");


$addedtodbreq=1;
if ($securimage->check($_POST['captcha_code']) == false) {
  echo "<script>alert('The code you entered was incorrect.  Go back and try again.');</script>";
  echo "<script>history.back();</script>";
}
else {
$display = $_GET['display'];
if (!$display){
  $display = $_POST['display'];
}

if (!$display){
  $display="Search";
}

$_GET['encode']='utf';
$restrictedAccess=1;


// -- Database Name if requested. Different quarters are in different
//    databases. The table names are the same on all databases
//

if ($database == ""){
  $database     = "malsongsdb";
}

$conLink = msi_dbconnect();
    printHeaders('');
mysql_query("SET NAMES utf8");

//-----------------------------------------------
// Get requested data

if (!$skip || $skip == ""){
  $skip = false;
}

$freeval = $_GET['val'];
$freein  = $_GET['txt'];
if ($freeval != "" && $freein){
    $_GET[$freeval] = $freein;
}

$download    = $_GET['Download'];
$subtitle    = $_GET['subtitle'];
$hidefilters = $_GET[hidefilter];
$movie       = $_GET['m_movie'];
$year        = $_GET['m_year'];
$music       = $_GET['m_musician'];
$lyrics      = $_GET['m_writers'];
$director    = $_GET['m_director'];
$comments    = $_GET['m_comments'];
$submit      = $_GET['submit'];
$mid         = $_GET['id'];
$cast      =   $_GET['m_cast'];
if ($comments == 'Dubbed' || $comments == 'Unreleased'){
$rstate    = $comments;
$comments  = '';
}
$banner    =   $_GET['m_banner'];
$producer  =   $_GET['m_producer'];
$story     =   $_GET['m_story'];
$screenplay  =   $_GET['m_screenplay'];
$dialog      =   $_GET['m_dialog'];
$art         =   $_GET['m_art'];
$editor      =   $_GET['m_editor'];
$camera      =   $_GET['m_camera'];
$design       = $_GET['m_design'];
$distribution = $_GET['m_distribution'];
$dateofrelease  = $_GET['m_dateofrelease'];
$locked         = $_GET['m_locked'];
$bgm          = $_GET['m_bgm'];
$distinction  = $_GET['distinction'];
$mail        = $_POST['mail'];

$newEntry    = $_GET['new'];
$startsWith  = $_GET['startsWith'];
$exactmatch  = $_GET['exactmatch'];
$advanced    = $_GET['advanced'];
$browseorder = $_GET['browseorder'];

if (!$mail){
$mail = $_POST['display'];
}

if (($mail && !$mid) || $display == "Update" || ($mail == "Request Change" || $mail == "Request Addition")) {
  $movie  =   $_POST['m_movie'];
  $year   =   $_POST['m_year'];
  $music  =   $_POST['m_musician'];
  $lyrics =   $_POST['m_writers'];
  $cast =   $_POST['m_cast'];
  $banner =   $_POST['m_banner'];
  $producer =   $_POST['m_producer'];
  $story  =   $_POST['m_story'];
  $screenplay  =   $_POST['m_screenplay'];
  $editor =   $_POST['m_editor'];
  $dialog  =   $_POST['m_dialog'];

  $design = $_POST['m_design'];
  $distribution = $_POST['m_distribution'];
  $bgm = $_POST['m_bgm'];

  $art  =   $_POST['m_art'];

  $camera  =   $_POST['m_camera'];

  $id     =   $_POST['id'];

  $name   =   $_POST['userid'];

  $dateofrelease = $_POST['m_dateofrelease'];

  $locked = $_POST['m_locked'];

  $director = $_POST['m_director'];

  $comments = $_POST['comments'];

  $delete_entry = $_POST['delete_entry'];


 if ($mail == "Request Change" || $mail == "Request Addition") {
    if ($id == "") {$id = "99999"; }
     $addedtodbreq=1;
   $dbquery = "INSERT INTO DB_REQ VALUES ($id,\"MOVIES\", \"<br>Movie:$movie<br>Musician:$music<br>Lyricist:$lyrics<br>Year:$year<br>Director:$director<br>Producer:$producer<br>BGM:$bgm<br>Cast:$cast<br>Art:$art<br>Story:$story<br>Screenplay:$screenplay<br>Dialogs:$dialog<br>Banner:$banner<br>Editor:$editor<br>Design:$design<br>Distribution:$distribution<br>Camera:$camera</pre><br><pre>Comments:$comments</pre><br>Delete:$delete_entry<P>-$name<P>\", \"Pending\", NOW())";
    mysql_query($dbquery);
   }
  if (!$name){
    $name = "Anonymous";
  }
  $mid="";

  $mid2_chk = "SELECT * FROM MDETAILS WHERE M_ID=\"$id\"" ;
  $result     = mysql_query($mid2_chk);
  $num_results=mysql_num_rows($result);
  if ($num_results > 0){
    $midq    = "UPDATE MOVIES set M_MOVIE=\"$movie\",M_MUSICIAN=\"$music\",M_WRITERS=\"$lyrics\",M_YEAR=\"$year\",M_DIRECTOR=\"$director\",M_COMMENTS=\"$comments\" WHERE M_ID=\"$id\"";
      $mid2    = "UPDATE MDETAILS set M_CAST=\"$cast\",M_BANNER=\"$banner\",M_PRODUCER=\"$producer\",M_STORY=\"$story\",M_SCREENPLAY=\"$screenplay\",M_DIALOG=\"$dialog\",M_EDITOR=\"$editor\",M_ART=\"$art\",M_CAMERA=\"$camera\",M_BGM=\"$bgm\",M_DESIGN=\"$design\",M_DISTRIBUTION=\"$distribution\",M_PRODEXEC=\"$dateofrelease\",M_DIRECTOR=\"$locked\" where M_ID=\"$id\"";
  }
  else {
      $nid = getNextID('MDETAILS','mid');
      $mid2    = "INSERT INTO MDETAILS VALUES($nid,$id,\"$banner\",\"$locked\",\"$producer\",\"$cast\",\"$story\",\"$art\",\"$singers\",\"$editor\",\"$dialog\",\"$camera\",\"$lyrics\",\"$musician\",\"$screenplay\",\"$dateofrelease\",NOW(),\"$bgm\",\"$design\",\"$distribution\")";
  }



      $mailHead = "From:MSI-System\r\nContent-Type: text/html";
      $authuser = $_SERVER['PHP_AUTH_USER'];
      if (!$authuser){
                $authuser = $_SERVER['REMOTE_USER'];
     }
      if (!$authuser){
	  $authuser = 'Admins';
      }
   

  mail("msiadmins@googlegroups.com","MSI 2013 [msidb.org] Movie Details Edited by $authuser for Movie ID $id","<a href=\"$_RootOfMedia/m.php?$id\">Click Here To See the Edits</a>",$mailHead);
}


  echo "<table valign=top align=center border=0 width=100%>";

  echo "<tr>";



  echo "<td >";

if ($submit){

  if ($music == "Select One" && $lyrics == "Select One" && $director == "Select One"){

    if (!$year && !$movie && !$startsWith && !$director){

      echo "<script>alert(\"Too many matches.\\nPlease select some criteria from the search menu\");</script>\n";

    echo "<script>history.back();\n</script>\n";

    }

  }

}


if ($display == "Search"){

}


else if ($display == "Delete" || $display == "Update" || $display == "Edit" || $display == "Remove"){

    if ($mid){
	executeAdminQuery($display, $mid);
    }

  if ($mid2){
      if ($midq != ""){
         executeAdminQuery($display, $midq);
     }
      executeAdminQuery($display, $mid2);
  }

    $mal_movie = $_POST['m_m_movie'];
    if ($movie != "" && $mal_movie != ""){
	executeAdminQuery("Update",detailsUnicodeMap($movie,$mal_movie));
    }

    $mal_musician = $_POST['m_m_musician'];
    if ($music != "" && $mal_musician != ""){
	executeAdminQuery("Update",detailsUnicodeMap($music,$mal_musician));
    }

    $mal_art = $_POST['m_m_art'];
    if ($art != "" && $mal_art != ""){
	executeAdminQuery("Update",detailsUnicodeMap($art,$mal_art));
    }

    $mal_lyrics = $_POST['m_m_writers'];
    if ($lyrics != "" && $mal_lyrics != ""){
	executeAdminQuery("Update",detailsUnicodeMap($lyrics,$mal_lyrics));
    }

    $mal_dirtr = $_POST['m_m_director'];
    if ($director != "" && $mal_dirtr != ""){
	executeAdminQuery("Update",detailsUnicodeMap($director,$mal_dirtr));
    }

    $mal_banner = $_POST['m_m_banner'];
    if ($banner != "" && $mal_banner != ""){
	executeAdminQuery("Update",detailsUnicodeMap($banner,$mal_banner));
    }

    $mal_producer = $_POST['m_m_producer'];
    if ($producer != "" && $mal_producer != ""){
	executeAdminQuery("Update",detailsUnicodeMap($producer,$mal_producer));
    }
    $mal_story = $_POST['m_m_story'];

    if ($story != "" && $mal_story != ""){
	executeAdminQuery("Update",detailsUnicodeMap($story,$mal_story));
    }

    $mal_screenplay = $_POST['m_m_screenplay'];
    if ($screenplay != "" && $mal_screenplay != ""){
	executeAdminQuery("Update",detailsUnicodeMap($screenplay,$mal_screenplay));
    }

    $mal_dialog = $_POST['m_m_dialog'];
    if ($dialog != "" && $mal_dialog != ""){
	executeAdminQuery("Update",detailsUnicodeMap($dialog,$mal_dialog));
    }

    $mal_camera = $_POST['m_m_camera'];
    if ($camera != "" && $mal_camera != ""){
	executeAdminQuery("Update",detailsUnicodeMap($camera,$mal_camera));
    }

    $mal_editor = $_POST['m_m_editor'];
    if ($editor != "" && $mal_editor != ""){
	executeAdminQuery("Update",detailsUnicodeMap($editor,$mal_editor));
    }

    $mal_bgm = $_POST['m_m_bgm'];
    if ($bgm != "" && $mal_bgm != ""){
	executeAdminQuery("Update",detailsUnicodeMap($bgm,$mal_bgm));
    }

    $mal_design = $_POST['m_m_design'];
    if ($design != "" && $mal_design != ""){
	executeAdminQuery("Update",detailsUnicodeMap($design,$mal_design));
    }

    $mal_distribution = $_POST['m_m_distribution'];
    if ($distribution != "" && $mal_distribution != ""){
	executeAdminQuery("Update",detailsUnicodeMap($distribution,$mal_distribution));
    }
}

else if ($display == "Change"){


if ($mail){
 $mailHead = "From:$name\r\nContent-Type: text/html";

   if ($addedtodbreq != 1){
   if ($id == "") {$id = time(); }

   $dbquery = "INSERT INTO DB_REQ VALUES ($id,\"MOVIES\", \"<br>Movie:$movie<br>Musician:$music<br>Lyricist:$lyrics<br>Year:$year<br>Director:$director<br>Producer:$producer<br>BGM:$bgm<br>Cast:$cast<br>Art:$art<br>Story:$story<br>Screenplay:$screenplay<br>Dialogs:$dialog<br>Banner:$banner<br>Editor:$editor<br>Design:$design<br>Distribution:$distribution<br>Camera:$camera</pre><br><pre>Comments:$comments</pre><br>Delete:$delete_entry<P>-$name<P>\", \"Pending\", NOW())";
    mysql_query($dbquery);
  }


 mail("msiadmins@googlegroups.com","MSI 2013 [msidb.org] Movie Details Change Request","<br>Movie:$movie<br>Musician:$music<br>Lyricist:$lyrics<br>Year:$year<br>Director:$director<br>Producer:$producer<br>BGM:$bgm<br>Cast:$cast<br>Art:$art<br>Story:$story<br>Screenplay:$screenplay<br>Dialogs:$dialog<br>Banner:$banner<br>Editor:$editor<br>Design:$design<br>Distribution:$distribution<br>Camera:$camera</pre><br><pre>Comments:$comments</pre><br>Delete:$delete_entry<P>-$name<P> <a href=\"$_RootOfMedia/admin\">Login to Admin Panel To Change This Data in MSI</a>",$mailHead);
 $mid="";
 echo "<script>alert(\"Thanks for submitting the request.\\nWe will make the necessary updates soon\");</script>\n";
 echo "<script>history.back();</script>";
}


  printChangeRequest("$mid", "$download","$newEntry" );
}
else if ($display == "Help"){
  printContents("../html/Help.html");
}
else {
    printContents("../html/Home.html");
}

  echo "</td>";



  echo "</tr>";

  echo "</table>";
  echo "<script>history.back();</script>";
  mysql_close($conLink);
 //   printFooters();




//--- This array defines the navigation links on the bottom of the page

//

echo "<script src=\"http://www.google-analytics.com/urchin.js\" type=\"text/javascript\">\n";
echo "</script>\n";
echo "<script type=\"text/javascript\">\n";
echo  "_uacct = \"UA-2365461-1\";\n";
echo "urchinTracker();\n";
echo "</script>\n";

  printEndHtml();
}

?>









