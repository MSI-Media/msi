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

    $cLink = msi_dbconnect();
    printXHeader('');
    mysql_query("SET NAMES utf8");

    $_GET['encode']='utf';


    $leadmsg = 'Quiz Leaderboard';
    $participants = 'Participants So Far';
    $partmsg = 'Participants with Correct Answers So Far';
    if ($_GET['lang'] != 'E') { $leadmsg = get_uc($leadmsg,''); $participants = get_uc($participants,''); $partmsg=get_uc($partmsg,'');}
    $npartwinners = 0;
    $nparticipants = runQuery("SELECT COUNT(DISTINCT (email)) as ccn from QUIZ",'ccn');
    $emails = buildArrayFromQuery("SELECT DISTINCT email from QUIZ order by email",'email');

    $score = array();
    foreach ($emails as $em){
	$qry = "SELECT COUNT(email) as score FROM QUIZ,QUIZ_RESULTS where QUIZ.qdate = QUIZ_RESULTS.qdate and QUIZ.answer=QUIZ_RESULTS.answer and email=\"$em\"";
	$scor        = runQuery($qry, 'score');
	if ($scor > 0){
	    $npartwinners++;
	}
	$score[$em] += $scor*10;
    }



    echo "<div class=pheading>$leadmsg</div>\n";
    echo "<div class=psubheading>$participants : $nparticipants</div>\n";
    echo "<div class=psubheading>$partmsg : $npartwinners</div>\n";
    $currentTop10 = 'All Winners So Far';
    if ($_GET['lang'] != 'E') { $currentTop10  = get_uc($currentTop10,'');}
    echo( "<table class=ptables>\n");	
    echo "<tr><td align=center valign=top width=50%>\n";
    echo "<div class=pheading>$currentTop10</div>\n";
    echo( "<table width=90%>\n");
    echo "<tr class=tableheader>\n";
    printDetailCellHeads ('Players');
    printDetailCellHeads ('Score');
    echo "</tr>";
    arsort($score);
    foreach ($score as $key=>$sc){
	if ($sc > 0){ 
	    $name = explode('@',$key);
	    echo "<tr>";
	    printDetailCells ($name[0],'','');
	    printDetailCells ($sc,'','');
	    echo "</tr>";
	}
    }

    echo "</table>";

    echo "</td><td valign=top>\n";

    $leadmsg = 'Quiz Answers Till Date';
    $quizfile = "php/data/Quiz.txt";
    $Answer   = 'Answer';
    if ($_GET['lang'] != 'E') { 
	$leadmsg = get_uc($leadmsg,''); 
	$quizfile = "php/data/Quiz_Malayalam.txt";
	$Answer = get_uc($Answer,'');
    }

    $qry = "SELECT * FROM QUIZ_RESULTS";
    mysql_query("SET NAMES utf8");
    $result      = mysql_query($qry);
    if ($result) {
	$num_results = mysql_num_rows($result);
	$i=0;
	while ($i < $num_results){
	    $qdate   = mysql_result($result,$i,"qdate");
	    $qans    = mysql_result($result,$i,"answer");
	    $answer{"$qdate"} = $qans;
	    if ($_GET['debug2013'] == 1) { echo "Adding $qans for $qdate<BR>";}
	    $i++;
	}
    }
    $str = time();
    $today = date("d/m/y", $str);
    $fh = fopen($quizfile, "r");
    if ($fh){  
        echo "<div class=pheading>$leadmsg</div>\n";
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $lexus = explode('|',$lx);
	    $ansurl = ltrim(rtrim($lexus[7]));
	    if ($lexus[0] == $today){
		break;
	    }
	    else {
		$ans = $answer{ltrim(rtrim($lexus[0]))};
		if (!$ans) { $ans=4;}
		$anstag = $ans+1;
		if ($ansurl != ''){
		    echo "<div class=pcellslong>" , "($lexus[0]) $lexus[1]&nbsp;<br><font color=red>$Answer: &nbsp;</font><a href=\"$ansurl\">", $lexus[$anstag], "</a></div>";
		}
		else {
		    echo "<div class=pcellslong>" , "($lexus[0]) $lexus[1]&nbsp;<br><font color=red>$Answer: &nbsp;</font>", $lexus[$anstag], "</div>";
		}
	    }
	}
	fclose($fh);
    }
    echo "<div class=\"fb-like-box\" data-href=\"http://facebook.com/malayalasangeetham.info\" data-width=\"400\" data-height=\"800\" data-show-faces=\"true\" data-stream=\"false\" data-header=\"true\"></div>\n";
    echo "</td></tr></table>";



    printFancyFooters();

    mysql_close($cLink);

}
?>
