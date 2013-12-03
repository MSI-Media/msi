<?php  session_start();

{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_data.php");
    require_once("_includes/_moviePageUtils.php");


    $raga = $_GET['raga'];

    $_GET['encode']='utf';

    $cLink = msi_dbconnect();
    printXHeader('');
    $_GET['lang'] = $_SESSION['lang'];

    if ($_GET['lang'] != 'E'){
	$ragat = get_uc("$raga",'');
    }
    else { $ragat = $raga; }

    echo "<h1 class=pheading>$ragat</h1>\n";
    echo "<div class=psubtitle>Data Provided by Vikas S and Adarsh KR  </div><P>\n";
    $sdirs = array();
    $sdir = opendir ("Ragas-Support/Sruthi");
    while ($sfh = readdir ($sdir)){
	if (!is_dir("$sfh")){
	    if ($sfh != "." && $sfh != ".."){
		$sruthname = $sfh;
		$sruthname = str_replace(".mp3","",$sruthname);
		$sruthnameDisplay = str_replace("-Sharp","#",$sruthname);
		$sruthnameDisplay = str_replace("Sruthi ","",$sruthnameDisplay);
		$sruthname = str_replace("Sruthi ","",$sruthname);
		array_push ($sdirs,"<a href=\"displaySruthi.php?n=$sruthname\"  title=\"Sruthi $sruthnameDisplay\">$sruthnameDisplay</a>");
	    }
	}
   }
   sort ($sdirs);
   echo "<div class=psubheading>Sruthis: ",join (" | ",$sdirs), "</div>";


    echo "<table class=ptables><tr>\n";
    if (file_exists("Ragas-Support/Info/${raga}.txt")){
	echo "<td class=fixedsmall width=30% valign=top>\n";
	echo "<div class=subtitle>Raga Notes</div>\n";
	printRagaContents("Ragas-Support/Info/${raga}.txt");
    }
    echo "<P>";
    if (file_exists("Ragas-Support/Images/${raga}.png")){
	echo "<div class=subtitle>Chords Illustration</div>\n";
	echo "<img src=\"Ragas-Support/Images/${raga}.png\" align=center border=0>\n";
    }
    else if (file_exists("Ragas-Support/Images/${raga}-arohana.png")){
	echo "<div class=subtitle>Chords Illustration</div>\n";
	echo "<h2 class=_songs>Aarohana</h2>\n";
	echo "<img src=\"Ragas-Support/Images/${raga}-arohana.png\" align=center border=0>\n";
	echo "<h2 class=_songs>Avarohana</h2>\n";
	echo "<img src=\"Ragas-Support/Images/${raga}-avarohana.png\" align=center border=0>\n";
    }
    echo "<p>";
    
    if (file_exists("Ragas-Support/Audio/${raga}.mp3")){
	echo "<P>";
	echo "<div class=subtitle>Audio Raga Demonstration</div>\n";
	echo	 "<div align=left class=fixedsmall>Listen to $raga on keyboard by clicking on the player</font></div><br>\n";
	printFlashPlayer("Ragas-Support/Audio/${raga}.mp3","$raga");
    }
    if (file_exists("Ragas-Support/Notes/${raga}.html")){
	echo "<P>";
	echo "<div class=subtitle>Demonstration of Chords for $raga</div>\n";
	echo	 "<div align=left class=fixedsmall>Click on the links below to download / listen</font></div><br> Inversion is used wherever required. For example, F-Major is played as C-F-A instead of F-A-C<p>";
	printContents("Ragas-Support/Notes/${raga}.html");
    }
    echo "</td>";
    echo "<td class=fixedsmall valign=top align=center>\n";
    $info = "Malayalam Movie and Album Songs in ";
    if ($_GET['lang'] != 'E'){
       get_uc("$info",'');
    }
    echo "<div align=left class=fixedsmall><a href=\"_displayProfile.php?category=raga&artist=$raga\">$info $raga</a></div>\n";
    $firstlet = strtoupper(substr($raga,0,1));
    if (file_exists("Ragas101/${firstlet}/${raga}.txt")){
	echo "<div class=pcellslong>\n";
	printContents("Ragas101/${firstlet}/${raga}.txt");
	echo "</div>";
    }
    else if (file_exists("Ragas101/${firstlet}/${raga}.jpg")){
	echo "<img src=\"Ragas101/${firstlet}/${raga}.jpg\" align=left class=ImageBorder>\n";
    }

    echo "</td></tr>\n";
    
    echo "</table>";
    printFancyFooters();
    mysql_close($cLink);

}

function printFlashPlayer($mp3,$title){
    $_RootOfMedia = "http://malayalasangeetham.info";
    $dir1="published_clips";
    echo "<script language=\"JavaScript\" src=\"$_RootOfMedia/$dir1/audio-player.js\"></script>\n";
    echo "<object type=\"application/x-shockwave-flash\" data=\"$_RootOfMedia/$dir1/player.swf\" id=\"audioplayer1\" autostart=\"true\" height=\"24\" width=\"290\">\n";
    echo "<param name=\"movie\" value=\"$_RootOfMedia/$dir1/player.swf\">\n";
    echo "<param name=\"FlashVars\" value=\"playerID=1&amp;autostart=true&amp;soundFile=$mp3\">\n";
    echo "<param name=\"quality\" value=\"high\">\n";
    echo "<param name=\"menu\" value=\"false\">\n";
    echo "<param name=\"wmode\" value=\"transparent\">\n";
    echo "</object> \n";
}
function printRagaContents($url){
	$fh = fopen($url, 'r');
	echo "<table width=100% bgcolor=#000000>\n";
        if ($fh){  

	    while (!feof($fh)){
		$lx = fgets($fh,1048576);
		if (preg_match("/a|e|i|o|u/",$lx)){
		    $lx = str_replace(":","</td><td class=fixedsmall>",$lx);
		    echo "<tr bgcolor=#ffffff><td class=fixedsmall>$lx</td></tr>\n";
		}
	    }
	    fclose($fh);
	    echo "</table>";
	}
}

?>
