<?php session_start();

$coms = $_POST['comms'];
$name = $_POST['uname'];
$sid  = $_POST['sid'];
$mode = $_POST['mode'];
$table = 'SONGS';
if ($mode == 'album'){
 $table = 'ASONGS';
//	exit();
}

// To block stupids like Vishal - Added by Sunish on 17-05-2011 - Also added line 22-24
$blacklist = array(
	'vishal.sat@rediffmail.com',
	'vs72132@gmail.com',
  'Vishal.Sathyan',
  'Vishal Sathyan',
  'vs72132@gmail.com (Vishal Sathyan)',
  'Vishal Sathyan <vs72132@gmail.com>'
);

//error_reporting (E_ERROR);

include_once $_SERVER['DOCUMENT_ROOT'] . '/securimage/securimage.php';
$securimage = new Securimage();
require_once("includes/data.php");
//require_once("updates/utils.inc");
require_once("updates/movieSearch.inc");

if ($securimage->check($_POST['captcha_code']) == false) {
  echo "<script>alert('The code you entered was incorrect. Go back and try again.');</script>";
}
else if(in_array(rtrim(ltrim($_POST['uname'])),$blacklist)) {
	echo "<script>alert('Thanks for submitting the comments.\nWe will make the necessary updates soon');</script>";
}
else {

$conLink = msi_dbconnect();


if (!$coms || !$name){
  echo "<script>alert(\"Please provide comments and your name/contact\");</script>";
}
else {
    if (!$sid or $sid < 1){
	  echo "<script>alert(\"There is a problem with your request. Please contact the administrator via email before proceeding with more updates\");</script>\n";
  }
  else {
  if (strpos($name, "@") !== false){
  $name = "$name" . "@msiguest.com";
  }
      $xcom = str_replace("\r","<br>",$coms);
      $mailHead = "From:$name\r\nContent-Type: text/html";
			// Added by Sunish to trace Vishal Sat
			$info = "<br /><br />Added to trace spammers. Ignore the following info:<br /><br />IP Address: " . $_SERVER['REMOTE_ADDR'] . "<br />Browser Details: " . $_SERVER['HTTP_USER_AGENT'];
		// End of Additions by Sunish
      mail("msiadmins@googlegroups.com","MSI 2012 [msidb.org] $mode Song Update Request","<br>[Song ID: $sid]&nbsp; $xcom<p><img src=\"$_RootOfMedia/images/arrow.gif\" border=0>&nbsp;<a href=\"$_RootOfMedia/admin/index.php?display=Update&type=auto${mode}&sid=$sid\">Manage These Comments From Admin Panel</a>$info",$mailHead);
      echo "<script>alert(\"Thanks for submitting the comments.\\nWe will make the necessary updates soon\");</script>\n";
      //quickAdd($xcom,$name, $sid,$table);
      $dbquery = "INSERT INTO DB_REQ VALUES ($sid,\"$table\", \"$xcom\", \"Pending\", NOW())";
      mysql_query($dbquery);
  }
}
mysql_close($conLink);
}
echo "<script>history.back();</script>";



function quickAdd($coms, $name, $sid,$table){

  $pattern = '/http/';
  preg_match($pattern,$coms,$matches,PREG_OFFSET_CAPTURE);
  if ($matches[0] == "") {
  $oqry =  "SELECT S_COMMENTS from $table where S_ID=$sid"; 
  $result      = mysql_query($oqry);
  $num_results = mysql_num_rows($result);
  $i=0;

  while ($i < $num_results){

    $scomments   = mysql_result($result,$i,"S_COMMENTS");
    $i++;

  }

/*
  $scomments = str_replace("\"","",$scomments);	
  $dt = date("D M j G:i:s T Y"); 
  $comments = $scomments . "<br>" . "Added by $name on $dt " . "<br>" . $coms ;
  if ($sid > 0){
    $nqry = "UPDATE $table set S_COMMENTS=\"$comments\" where S_ID=$sid";
    $result      = mysql_query($nqry);
    $num_results = mysql_num_rows($result);
    if ($num_results > 0){
      echo "<script>alert(\"Comments Updated\");</script>";
    }
  }
*/
}



}
?>