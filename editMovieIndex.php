<?php session_start();
//error_reporting (E_ERROR);

require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("updates/_movieSearch.php");



$display = $_GET['display'];
if (!$display){
  $display = $_POST['display'];
}

if ($display == 'Edit'){
    global $_username;
    $_username = $_SERVER['PHP_AUTH_USER'];
    if ($_username == '') { $_username = $_SERVER['REMOTE_USER']; }
    if ($_GET['auth'] == 1) { echo "Logged in as $username<BR>"; }
    if ($_username != 'anoop' && $_username != 'ajay' && $_username != 'vijay' && $_username != 'sunny' && $_username != 'jaya' && $_username != 'kalyani'){
	echo "<script>location.replace(\"http://msidb.org/401.shtml\");</script>";
    }
}

$typename = "Movies";
$type = "m.php";
$id  = $_GET['id'];

$langs = array ('M','E');
foreach ($langs as $lang) {
$cachedir = "cache/$lang/$typename";

if ($id > 0){
if (file_exists ("$cachedir/%2F${type}%3F${id}")){
   unlink ("$cachedir/%2F${type}%3F${id}");
}
if (file_exists ("$cachedir/%2F${type}%3F${id}%26cl%3D1")){
   unlink ("$cachedir/%2F${type}%3F${id}%26cl%3D1");
}
if (file_exists ("$cachedir/%2F${type}%3F${id}%26%26cl%3D1")){
   unlink ("$cachedir/%2F${type}%3F${id}%26%26cl%3D1");
}
}
}

if (!$display){
    $display="Search";
}

$_GET['encode']='utf';
$restrictedAccess=1;



if ($database == ""){
  $database     = "malsongsdb";
}


$conLink = msi_dbconnect();
printXHeader('');


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

    $umovie = get_uc($movie,'');
    $umusic = get_uc($music,'');
    $ulyrics = get_uc($lyrics,'');
    $udirector = get_uc($director,'');
    $uart = get_uc($art,'');
    $ucast = get_uc($cast,'');
    $ubanner = get_uc($banner,'');
    $uproducer = get_uc($producer,'');
    $ustory = get_uc($story,'');
    $uscreenplay = get_uc($screenplay,'');
    $udialog = get_uc($dialog,'');
    $ueditor = get_uc($editor,'');
    $udesign = get_uc($design,'');
    $ucamera = get_uc($camera,'');
    $udistribution = get_uc($distribution,'');
    $ubgm = get_uc($bgm,'');


    $umidq    = "UPDATE UMOVIES set M_MOVIE=\"$umovie\",M_MUSICIAN=\"$umusic\",M_WRITERS=\"$ulyrics\",M_YEAR=\"$year\",M_DIRECTOR=\"$udirector\" WHERE M_ID=\"$id\"";
    $umid2    = "UPDATE UDETAILS set M_CAST=\"$ucast\",M_BANNER=\"$ubanner\",M_PRODUCER=\"$uproducer\",M_STORY=\"$ustory\",M_SCREENPLAY=\"$uscreenplay\",M_DIALOG=\"$udialog\",M_EDITOR=\"$ueditor\",M_ART=\"$uart\",M_CAMERA=\"$ucamera\",M_BGM=\"$ubgm\",M_DESIGN=\"$udesign\",M_DISTRIBUTION=\"$udistribution\" where M_ID=\"$id\"";

  }
  else {
      $nid = getNextID('MDETAILS','mid');
      $mid2    = "INSERT INTO MDETAILS VALUES($nid,$id,\"$banner\",\"$locked\",\"$producer\",\"$cast\",\"$story\",\"$art\",\"$singers\",\"$editor\",\"$dialog\",\"$camera\",\"$lyrics\",\"$musician\",\"$screenplay\",\"$dateofrelease\",NOW(),\"$bgm\",\"$design\",\"$distribution\")";
  }


//  $mailHead = "From:malayalasangeetham@gmail.com\r\nContent-Type: text/html";

      $mailHead = "From:MSI-System\r\nContent-Type: text/html";
      $authuser = $_SERVER['PHP_AUTH_USER'];
      if (!$authuser){
                $authuser = $_SERVER['REMOTE_USER'];
     }
      if (!$authuser){
	  $authuser = 'Admins';
      }
      if ($authuser == "ajay"){
      $authuser = 'Admin';
      }


  mail("msiadmins@googlegroups.com","MSI 2012 [msidb.org] Movie Details Edited by $authuser for Movie ID $id","<a href=\"$_RootOfMedia/m.php?$id\">Click Here To See the Edits</a>",$mailHead);
}
if ($display == "Delete" || $display == "Update" || $display == "Edit" || $display == "Remove"){

    if ($mid){
	executeAdminQuery($display, $mid,$conLink);
    }

  if ($mid2){
      if ($midq != ""){
         executeAdminQuery($display, $midq, $conLink);
     }
// New queries for Unicode Table Movies
     if ($umidq != ""){
         executeAdminQuery($display, $umidq, $conLink);
     }
     if ($umid2 != ""){
         executeAdminQuery($display, $umid2, $conLink);
     }
      executeAdminQuery($display, $mid2, $conLink);
  }

    $mal_movie = $_POST['m_m_movie'];
    if ($movie != "" && $mal_movie != ""){
	executeAdminQuery("Update",detailsUnicodeMap($movie,$mal_movie), $conLink);
    }

    $mal_musician = $_POST['m_m_musician'];
    if ($music != "" && $mal_musician != ""){
	executeAdminQuery("Update",detailsUnicodeMap($music,$mal_musician), $conLink);
    }

    $mal_art = $_POST['m_m_art'];
    if ($art != "" && $mal_art != ""){
	executeAdminQuery("Update",detailsUnicodeMap($art,$mal_art), $conLink);
    }

    $mal_lyrics = $_POST['m_m_writers'];
    if ($lyrics != "" && $mal_lyrics != ""){
	executeAdminQuery("Update",detailsUnicodeMap($lyrics,$mal_lyrics), $conLink);
    }

    $mal_dirtr = $_POST['m_m_director'];
    if ($director != "" && $mal_dirtr != ""){
	executeAdminQuery("Update",detailsUnicodeMap($director,$mal_dirtr), $conLink);
    }

    $mal_banner = $_POST['m_m_banner'];
    if ($banner != "" && $mal_banner != ""){
	executeAdminQuery("Update",detailsUnicodeMap($banner,$mal_banner), $conLink);
    }

    $mal_producer = $_POST['m_m_producer'];
    if ($producer != "" && $mal_producer != ""){
	executeAdminQuery("Update",detailsUnicodeMap($producer,$mal_producer), $conLink);
    }
    $mal_story = $_POST['m_m_story'];

    if ($story != "" && $mal_story != ""){
	executeAdminQuery("Update",detailsUnicodeMap($story,$mal_story), $conLink);
    }

    $mal_screenplay = $_POST['m_m_screenplay'];
    if ($screenplay != "" && $mal_screenplay != ""){
	executeAdminQuery("Update",detailsUnicodeMap($screenplay,$mal_screenplay), $conLink);
    }

    $mal_dialog = $_POST['m_m_dialog'];
    if ($dialog != "" && $mal_dialog != ""){
	executeAdminQuery("Update",detailsUnicodeMap($dialog,$mal_dialog), $conLink);
    }

    $mal_camera = $_POST['m_m_camera'];
    if ($camera != "" && $mal_camera != ""){
	executeAdminQuery("Update",detailsUnicodeMap($camera,$mal_camera), $conLink);
    }

    $mal_editor = $_POST['m_m_editor'];
    if ($editor != "" && $mal_editor != ""){
	executeAdminQuery("Update",detailsUnicodeMap($editor,$mal_editor), $conLink);
    }

    $mal_bgm = $_POST['m_m_bgm'];
    if ($bgm != "" && $mal_bgm != ""){
	executeAdminQuery("Update",detailsUnicodeMap($bgm,$mal_bgm), $conLink);
    }

    $mal_design = $_POST['m_m_design'];
    if ($design != "" && $mal_design != ""){
	executeAdminQuery("Update",detailsUnicodeMap($design,$mal_design), $conLink);
    }

    $mal_distribution = $_POST['m_m_distribution'];
    if ($distribution != "" && $mal_distribution != ""){
	executeAdminQuery("Update",detailsUnicodeMap($distribution,$mal_distribution), $conLink);
    }
}

else if ($display == "Change"){


if ($mail){
 $mailHead = "From:$name\r\nContent-Type: text/html";
 mail("msiadmins@googlegroups.com","MSI 2012 [msidb.org] Movie Details Change Request","<br>Movie:$movie<br>Musician:$music<br>Lyricist:$lyrics<br>Year:$year<br>Director:$director<br>Producer:$producer<br>BGM:$bgm<br>Cast:$cast<br>Art:$art<br>Story:$story<br>Screenplay:$screenplay<br>Dialogs:$dialog<br>Banner:$banner<br>Editor:$editor<br>Design:$design<br>Distribution:$distribution<br>Camera:$camera</pre><br><pre>Comments:$comments</pre><br>Delete:$delete_entry<P>-$name<P> <a href=\"$_RootOfMedia/php/createMovieIndex.php?display=Edit&id=$id\">Login to Admin Panel To Change This Data in MSI</a>",$mailHead);
 $mid="";
 mysql_close($conLink);
 echo "<script>alert(\"Thanks for submitting the request.\\nWe will make the necessary updates soon\");</script>\n";
 echo "<script>history.back();</script>";
}


  printChangeRequest("$mid", "$download","$newEntry" );
}

  echo "</td>";



  echo "</tr>";

  echo "</table>";
    printFancyFooters();
  mysql_close($conLink);




//--- This array defines the navigation links on the bottom of the page

//------------------------------------------------------------------------------------------------------

function updateQueries($qry_ary){



  $updated = false;

  //  echo "<div class=fixedhead><b>Update Log</b></div><p>";

  echo "<table width=90% border=0>";

  foreach ($qry_ary as $qry){

    $updated = true; 

//  echo "<tr><td class=fixedfont>Executing $qry ";

    echo "<tr><td class=fixedfont>Your requests for updating the data are being processed...";

    $res_funcQry = mysql_query($qry);

   

    if ($res_funcQry){

      echo "... [<font color=green>Success</font>]</td></tr>";

    }

    else {

      echo "...[<font color=red>Failed</font>]</td></tr>";

    }

  }

  if (!$updated){

    echo "<script>alert(\"No valid data updates requested\")</script>";

    echo "<script>history.back()</script>";

  }

  echo "</table>";



  return $updated;

}

function getNextID($table, $primaryKey){



    $chart_id = 0;

    $query = "SELECT $primaryKey from $table ORDER BY $primaryKey DESC LIMIT 1";

    $res_funcQry = mysql_query($query);

    $num_funcQry = mysql_num_rows($res_funcQry);

    $i = 0;

    while ($i < $num_funcQry){

	$chart_id = mysql_result($res_funcQry, $i, "$primaryKey");

	$i++;

    }

    $chart_id++;

    return $chart_id;

}
?>









