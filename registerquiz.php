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
    $mode = $_GET['m'];
    if (!$mode) { $mode = 'lyricists'; }


    $d = $_POST['date'];
    $e = $_POST['email'];
    $a = $_POST['answers'];

    $ip = $_SERVER['REMOTE_ADDR'];

    if ($d && $e && $a){
	$v = runQuery("SELECT answer from QUIZ where qdate=\"$d\" and (email=\"$e\" or ipaddress=\"$ip\")",'answer');
	if ($v == ''){
	    $dbqry = "INSERT INTO QUIZ VALUES (\"$d\",\"$e\",\"$a\",\"$ip\",NOW());";
	    $res_funcQry = mysql_query($dbqry);
	    $mailHead = "From:MSI-System\r\nContent-Type: text/html";
	    $thanks = 'Thanks for Participating';
	    if ($_GET['lang'] != 'E') { $thanks  = get_uc($thanks,'');}
	    echo "<div class=ptables><font color=green>$thanks</font> $e [$d]</div>";
	    if ($res_funcQry){
		mail("msiadmins@googlegroups.com","MSI 2013 Quiz Submitted by $e for Date $d",$mailHead);
	    }
	    else {
		mail("msiadmins@googlegroups.com","MSI 2013 Quiz Submission for $e on Date $d (Wrong Answer)",$mailHead);
	    }
	}
	else if ($v == $a) {
	    $mailHead = "From:MSI-System\r\nContent-Type: text/html";
	    $thanks = 'Your response was already registered earlier either from your email or IP address';
	    if ($_GET['lang'] != 'E') { $thanks  = get_uc($thanks,'');}
	    echo "<div class=ptables><font color=green>$thanks</font> ($e [$d])</div>";
	    mail("msiadmins@googlegroups.com","MSI 2013 Quiz Submitted (Duplicate Effort) by $e ($ip already submitted) for Date $d",$mailHead);
	}
	else if ($v != $a) {
	    $mailHead = "From:MSI-System\r\nContent-Type: text/html";
	    $thanks = 'Your response was already registered earlier';
	    if ($_GET['lang'] != 'E') { $thanks  = get_uc($thanks,'');}
	    if ($ip != ''){
		echo "<div class=ptables><font color=red>$thanks </font>($e (IP:$ip) [$d])</div>";
	    }
	    else {
		echo "<div class=ptables><font color=red>$thanks </font>($e [$d])</div>";
	    }
	    mail("msiadmins@googlegroups.com","MSI 2013 Quiz Correction by $e for Date $d rejected",$mailHead);

	}


    }

    $leadmsg = 'Quiz Leaderboard';
    $participants = 'Participants So Far';
    $partmsg = 'Participants with Correct Answers So Far';
    if ($_GET['lang'] != 'E') { $leadmsg = get_uc($leadmsg,''); $participants = get_uc($participants,''); $partmsg=get_uc($partmsg,'');}
    $npartwinners = 0;
    $nparticipants = runQuery("SELECT COUNT(DISTINCT (email)) as ccn from QUIZ",'ccn');
    $emails = buildArrayFromQuery("SELECT DISTINCT email from QUIZ order by email",'email');

    $prev_months = array('12/13','11/13','10/13','09/13');

    $score = array();

    foreach ($emails as $em){
	$qry = "SELECT COUNT(email) as score FROM QUIZ,QUIZ_RESULTS where QUIZ.qdate = QUIZ_RESULTS.qdate and QUIZ.answer=QUIZ_RESULTS.answer and email=\"$em\"";
	$scor        = runQuery($qry, 'score');
	if ($scor > 0){
	    $npartwinners++;
	}
	$score[$em] += $scor*10;
    }

    $score99 = array();
    $score98 = array();
    $score97 = array();
    $score96 = array();

    foreach ($prev_months as $pm){
	foreach ($emails as $em){
	    $qry = "SELECT COUNT(email) as score FROM QUIZ,QUIZ_RESULTS where QUIZ.qdate like \"%/$pm\" and QUIZ.qdate = QUIZ_RESULTS.qdate and QUIZ.answer=QUIZ_RESULTS.answer and email=\"$em\"";
	    if ($_GET['debug2013']) { echo $qry, "<BR>"; }
	    $scor        = runQuery($qry, 'score');
	    if ($pm == '09/13') { $score99[$em] += $scor*10; }
	    else if ($pm == '10/13') { $score98[$em] += $scor*10; }
	    else if ($pm == '11/13') { $score97[$em] += $scor*10; }
	    else if ($pm == '12/13') { $score96[$em] += $scor*10; }
	}
    }




    $complete_scorecard = "Complete Scorecard";
    if ($_GET['lang'] != 'E') { $complete_scorecard = get_uc($complete_scorecard,''); }
    echo "<div class=pheading>$leadmsg</div>\n";
    echo "<div class=psubtitle><a href=\"scoreCard.php\">$complete_scorecard</a></div>";
    echo "<div class=psubheading>$participants : $nparticipants</div>\n";
    echo "<div class=psubheading>$partmsg : $npartwinners</div>\n";
    $currentTop10 = 'Top Scorers';
    if ($_GET['lang'] != 'E') { $currentTop10  = get_uc($currentTop10,'');}
    echo "<div class=pleftsubheading>$currentTop10</div>\n";


    echo( "<table class=ptables>\n");	
    echo "<tr class=tableheader>\n";
    printDetailCellHeads ('Players');
    foreach ($prev_months as $pm){
    printDetailCellHeads ("$pm");
    }
    printDetailCellHeads ('Total Score');
    echo "</tr>";
    arsort($score96);
    $top10 = 15;
    foreach ($score96 as $key=>$sc){
	if ($sc > 0 && $top10 > 0){ 
	    $name = explode('@',$key);
		echo "<tr>";
		printDetailCells ($name[0],'','');
		printDetailCells ($score96[$key],'','');
		printDetailCells ($score97[$key],'','');
		printDetailCells ($score98[$key],'','');
		printDetailCells ($score99[$key],'','');
		printDetailCells ($score[$key],'','');
		echo "</tr>";
		$top10--;
	}
    }

    echo "</table>";


//  $leadmsg = 'Quiz Answers Till Date';
    $leadmsg    = 'Answer from Previous Day';
    $complete_scorecard = "Complete Answers";
    if ($_GET['lang'] != 'E') { $complete_scorecard = get_uc($complete_scorecard,''); }
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
    $yesterday = date("d/m/y", $str - (60 * 60 * 24));
    $fh = fopen($quizfile, "r");
    if ($fh){  

        echo "<div class=pheading>$leadmsg ($yesterday)</div>\n";
	echo "<div class=psubtitle><a href=\"scoreCard.php\">$complete_scorecard</a></div>";
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $lexus = explode('|',$lx);
	    $ansurl = ltrim(rtrim($lexus[7]));
	    if ($lexus[0] == $today){
		break;
	    }
	    else if ($lexus[0] == $yesterday) {
		$ans = $answer{ltrim(rtrim($lexus[0]))};
		if (!$ans) { $ans=4;}
		$anstag = $ans+1;
		if ($ansurl != ''){
		    echo "<div class=pcellslong>" , "($lexus[0]) $lexus[1]&nbsp;<font color=red>$Answer: &nbsp;</font><a href=\"$ansurl\">", $lexus[$anstag], "</a></div>";
		}
		else {
		    echo "<div class=pcellslong>" , "($lexus[0]) $lexus[1]&nbsp;<font color=red>$Answer: &nbsp;</font>", $lexus[$anstag], "</div>";
		}
	    }
	}
	fclose($fh);
    }
    printWinnersPodium();	

    if ($_GET['lang']!='E'){ 
	$footfile = 'Writeups/registerquiz_footer_malayalam.txt';
    }
    else { 	$footfile = 'Writeups/registerquiz_footer.txt';}
    printHtmlContents("$footfile");

    printFancyFooters();

    mysql_close($cLink);

//    echo "<script>history.back();</script>";
}
function printWinnersPodium()
{
    $leadmsg = "Winners from Last Month";
    if ($_GET['lang'] != 'E'){
	$leadmsg = get_uc($leadmsg,''); 
    }
    $str = time();
//    $lmonth = date("m ([ \t.-])* YY", $str);
    echo "<div class=pheading>$leadmsg</div>\n";

    $winners = "pics/Quiz/winners/112013";
    $sorted_pics = scandir("$winners/photos");
    echo "<table class=ptableshighlight2>\n";
    echo "<tr bgcolor=#666666>\n";
    $cnt = 0;
    foreach  ($sorted_pics as $p){
	if ($p != '.' && $p != '..') {
	    $pname = str_replace(".jpg",'',$p);
	    echo "<td width=50% bgcolor=#ffffff valign=top>\n";
	    echo "<div valign=top align=center><img src=\"$winners/photos/$p\" height=100 width=100><br>";
	    $pxname = trim(str_replace(range(0,9),'',$pname));

	    if ($_GET['lang'] != 'E') { $pname4dis = get_uc($pxname); } else { $pname4dis = $pxname; }
	    echo "<b>$pname4dis</b><br></div>";
	    echo "<div style=\"padding-left:20px;padding-right:20px\">\n";
	    printHtmlContents("$winners/writeups/${pname}.txt");
	    echo "</div>";
	    echo "<hr class=faded>\n";
	    echo "</td>\n";
	    $cnt++;
	    if ($cnt == 2 || $cnt == 4 || $cnt == 6 || $cnt == 8 || $cnt == 10 || $cnt == 12){
		echo "</tr><tr bgcolor=#666666>";
	    }
	}
    }
    echo "</tr></table>";
}
?>
