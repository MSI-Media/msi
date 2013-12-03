<?php session_start();
{
   error_reporting (E_ERROR);
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_bodycontents.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");
    require_once("_includes/_System.php");

    $_GET['encode']='utf';
    $input = $_GET['i'];

//   set_language();
   $cl = msi_dbconnect() ;
   printXheader('');

   $printBodyBlock=0;
   $errorfile = 'Error404_' . $_GET{'lang'} . ".txt";

    echo "<aside class=\"body_wrapper\">\n";
    echo "  <div class=\"main\">\n";
    if ($input == 2 || $qs == 'msi'){
        if ($_GET['lang'] != 'E'){
	    printHtmlContents("Writeups/WhatIsMSI.html");
	}
	else {
	    printHtmlContents("Writeups/WhatIsMSI_English.html");
	}
    }
    else if ($input == 3){
	printHtmlContents("Writeups/Fonts.html");
    }
    else if ($input == 4){
	printHtmlContents("Writeups/LiteraryIntro.html");
    }
    else if ($input == 5){
	printHtmlContents("Writeups/feedback.txt");
    }
    else if ($input == 'grp'){
	printHtmlContents("Writeups/Join.html");
    }
    else if ($input == 'ios'){
	if ($_GET['lang'] != 'E'){
	    printHtmlContents("ios/ios_malayalam.txt");
	}
	else {
	    printHtmlContents("ios/ios.txt");
	}
    }
    else if ($input == 'android'){
	if ($_GET['lang'] != 'E'){
	    printHtmlContents("ios/android_malayalam.txt");
	}
	else {
	    printHtmlContents("ios/android.txt");
	}
    }
    else if ($input == 'FB'){
	printHtmlContents("Writeups/Facebook.txt");
    }
    else if ($input == 'WS'){
	printHtmlContents("WS/MSI_Web_Services.txt");
    }
    else if ($input == 'articles'){
	showArticlesPage();
    }
    else if (preg_match("/R[0-9]/", $input)){
       $raga_index = str_replace("R","",$input);
       if (file_exists("Writeups/Raga-${raga_index}.txt")){
	   printHtmlContents("Writeups/Raga-${raga_index}.txt");
       }
       else {
	     printHtmlContents("Writeups/$errorfile");
       }
    }
    else if (preg_match("/C[0-9]/", $input)){
       $graph_index = str_replace("C","",$input);
       if ($graph_index == 1) { doGaugeCharts(); }
       if ($graph_index == 2) { doBars(); }
    }
    else if (preg_match("/H[0-9]/", $input)){
       $history_index = str_replace("H","",$input);
       if (file_exists("Writeups/History-${history_index}.txt")){
	   printHtmlContents("Writeups/History-${history_index}.txt");
       }
       else {
	     printHtmlContents("Writeups/$errorfile");
       }
    }
    else if (preg_match("/HT[0-9]/", $input)){
       $history_index = str_replace("HT","",$input);
       $trivia_file = "Writeups/HistoryTrivia/${history_index}_malayalam";
//     if ($_GET['lang'] != 'E') { $trivia_file .= '_malayalam';}
       if (file_exists("${trivia_file}.txt")){
	   printHtmlContents("${trivia_file}.txt");
       }
       else {
	     printHtmlContents("Writeups/$errorfile");
       }
    }
    else {

        if ($input == 'E'){

	     echo "<table width=100% class=ptables>\n";
             echo "<tr><td colspan=3 align=center >\n";
	     printHtmlContents("Writeups/$errorfile");
	     echo "</td></tr>";
        }
        if ($input == 'A'){
	     $errorfile = 'Error401_' . $_GET{'lang'} . ".txt";
             echo "<tr><td colspan=3 align=center>\n";
	     printHtmlContents("Writeups/$errorfile");
	     echo "</td></tr>";
        }
	$printBodyBlock=1;
    }
    echo "</div>";
    echo "</aside>\n";
    if ($printBodyBlock){
	bodyBlock();
//	askTheExperts();	
	echo "<div class=nonselectable>\n";
	displayDailyQuiz();		
	echo "</div>";
        printTop3Winners();
    }



    printFancyFooters();
    mysql_close($cl);
}
?>
