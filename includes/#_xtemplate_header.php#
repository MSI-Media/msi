<?php

require_once("_includes/_System.php");
function printLanguageLinks($ll)
{

//    if ($_SESSION['lang'] == 'E')   { $image_png = 'ReadM.png'; } else { $image_png = 'ReadE.png'; }
      if ($_SESSION['lang'] == 'E')   { $image_png = 'mal-N.png'; } else { $image_png = 'eng-N.png'; }
//    if ($_SESSION['lang'] == 'E')   { $image_png = 'View MSI in Malayalam'; } else { $image_png = 'View MSI in English'; }
    $script = str_replace('/','',$_SERVER['PHP_SELF']);
/*
    if ($_SERVER['HTTP_HOST'] == 'en.msidb.org' || $_SERVER['HTTP_HOST'] == 'ml.msidb.org'){
	echo " <li class=\"right\">\n";
    }	 
*/
    $changeurl = 0;	
    $pos = strpos($_SERVER['QUERY_STRING'], "cl=1");
    if ($pos !== false){
	$changeurl = 1;
    }
    
    if ($ll == 'en'){
       $script = str_replace('en.','',$script);
       $script = 'http://msidb.org/' . $script;

    }

    if ($_GET['debug2013'] == 1) { echo $script, ":", $ll, "<BR>"; }

    $qs = str_replace("?cl=1","",$_SERVER['QUERY_STRING']);
    $qs = str_replace("&cl=1","",$qs);
    $qs = str_replace("cl=1","",$qs);
    if ($ll != 'en'){
	$sc_str = "${script}?${qs}&cl=1";
    }
    else  {
	$sc_str = "${script}?${qs}";
    }

    $sc_str = str_replace("?&","?",$sc_str);
    if ($changeurl == 1){ 
	echo "<script>location.replace(\"${script}?${qs}\");</script>";
    }

    $sc_str = str_replace('&&','&',$sc_str);
    echo " <a href=\"${sc_str}\"><img src=\"images/${image_png}\" alt=\"\" width=175 height=\"29\"></a>\n";
}

function printXheader($mode)
{
	global $_Master_index;
	global $_Master_search_redirect;
	date_default_timezone_set('Asia/Calcutta');	

	$browser = new Browser();
	$version = explode ('.',$browser->getVersion());
	$majver = $version[0];

	if($browser->getBrowser() == 'Internet Explorer')
	{
	    if ($majver < 7) {
		echo "<div align=center>\n";
		echo "<img src=\"images/MSI.png\"><P>\n";
		echo "We are Sorry To Inform you that you are running a version of Microsoft Internet Explorer Browser that is very old. Please upgrade IE to a later version or use Google Chrome, Mozilla Firefox or Apple Safari for the best experience with MSI";
		echo "</div>";
		exit;
	    }
	}

    printHeadSection($mode);
    echo "            	<header class=\"header_wrapper\">\n";
    echo "                    <div class=\"main\">\n";
    echo "                        <div class=\"top\">\n";
    echo "                        <div class=\"column-one\">\n";
    echo "                        	<div class=\"left_block\">\n";
    echo "                            	<a href=\"$_Master_index\"><img src=\"images/MSI2012LogoUpdated.png\" height=70 width=300 alt=\"MSI Logo\"></a>\n";
    echo "                            </div>\n";
    echo "                            <div class=\"right_block\">\n";
    echo "                            	<div class=\"list_icon\">\n";
    echo "                                	<nav class=\"topico\">\n";



    echo "      <a href=\"http://twitter.com/#!MSI_MusicMovies\" target=\"_new\"><img src=\"./icons/socialmedia/twitter.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";
    echo " 	<a href=\"http://www.youtube.com/malayalasangeetham\" target=\"_new\"><img class=\"any\" src=\"./icons/socialmedia/youtube.png\" onmouseover=\"this.src=./icons/socialmedia/PNG/youtube.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";
    echo "      <a href=\"http://facebook.com/malayalasangeetham.info\" target=\"_new\"><img src=\"./icons/socialmedia/facebook.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";
    echo "      <a href=\"http://malayalasangeetham.blogspot.com\" target=\"_new\"><img src=\"./icons/socialmedia/bloggr.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";
    echo "      <a href=\"http://plus.google.com/u/0/118172512910477069354/posts\" target=\"_new\"><img src=\"./icons/socialmedia/googleplus.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";
    echo "      <a href=\"http://pinterest.com/msimusicmovies/boards/\" target=\"_new\"><img src=\"icons/socialmedia/pinterest.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";    
    echo "      <a href=\"http://x.co/msiios\" target=\"_new\"><img src=\"./msi2013_setup/ico-7.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";
    echo "      <a href=\"https://play.google.com/store/apps/details?id=org.music.MSIGUI&feature=search_result#?t=W251bGwsMSwxLDEsIm9yZy5tdXNpYy5NU0lHVUkiXQ..\" target=\"_new\"><img src=\"./msi2013_setup/ico-8.png\" alt=\"\" height=\"29\" width=\"30\"></a>\n";
    if ($_SERVER['HTTP_HOST'] != 'en.msidb.org' && $_SERVER['HTTP_HOST'] != 'ml.msidb.org'){
        printLanguageLinks('');
    }
    else if ($_SERVER['HTTP_HOST'] == 'en.msidb.org'){
        printLanguageLinks('en');
    }

    



    echo "                                    </nav>\n";
    echo "                                </div>\n";
    echo "                          </div>\n";
    echo "                            	<div class=\"form_block\">\n";
    echo "                                	<form method=\"post\" name=\"rsearch\" action=\"$_Master_search_redirect\" class=\"form_one\" onsubmit=\"javascript:return validateSearch();\">\n";
    echo "                                    	<span>\n";

    $placeholder = "Search For Songs Movies Artists Ragas ...";
    if ($_GET['lang'] != 'E') { $placeholder=get_uc($placeholder,''); }
    $search_string = $_GET['search'];
    echo "                                        	<input type=\"text\" value =\"$search_string\" name=\"search\" placeholder=\"$placeholder\" required size=30>\n";
    echo "                                            <select name=db>\n";
    echo "                                            	<option value=all>All</option>\n";
    $movt     = "Movies";    $songt    = "Songs";    $albt     = "Albums";    $albsongt = "Album Songs";    $ragat    = "Ragas";    $artt     = "Artists"; $yeart = 'Year'; 
    if ($_GET['lang'] != 'E') { $movt = get_uc($movt,'');$songt = get_uc($songt,'');$albt = get_uc($albt,'');$albsongt = get_uc($albsongt,'');$ragat = get_uc($ragat,'');$artt = get_uc($artt,''); $yeart=get_uc($yeart,'');}
    echo "                                                <option value=movies>$movt</option>\n";
    echo "                                                 <option value=moviesongs>$songt</option>\n";
    echo "                                                <option value=albums>$albt</option>\n";
    echo "                                                <option value=albumsongs>$albsongt</option>\n";
    echo "                                                <option value=raga>$ragat</option>\n";
    echo "                                                <option value=year>$yeart</option>\n";
    echo "                                                 <option value=artist>$artt</option>\n";
    echo "                                            </select>\n";
//  echo "    <input type=\"image\" src=\"images/searchbg.png\" border=0 style=\"height:20px;margin-right:40px;margin-top:30px\">\n";
    echo "                                                <button type=\"submit\" class=\"form-one-button\"></button>\n";
    echo "                                        </span>\n";
    echo "                                    </form>\n";
    echo "                                 </div>\n";



    if ($_GET['lang'] == 'E'){
	printHtmlContents("_includes/_NavigationMenu.html");
    }
    else {
	printHtmlContents("_includes/_NavigationMenu_UC.html");
    }
    echo "<aside class=\"body_wrapper\">\n";
    echo "  <div class=\"main\">\n";
    addShareLinks();		   
    printHeaderFileContents();

}



function printMinimalHeader($mode)
{
	global $_Master_index;
    printHeadSection($mode);
    echo "            	<header class=\"header_wrapper\">\n";
    echo "                    <div class=\"main\">\n";
    echo "                        <div class=\"top\">\n";
    echo "                        <div class=\"column-one\">\n";
    echo "                        	<div class=\"left_block\">\n";
    echo "                            	<a href=\"$_Master_index\"><img src=\"images/MSI2012LogoUpdated.png\" height=70 width=300 alt=\"MSI Logo\"></a>\n";
    echo "                            </div>\n";
    echo "                            <div class=\"right_block\">\n";
    echo "                            	<div class=\"list_icon\">\n";
    echo "                                	<nav class=\"topico\">\n";
    echo "                                    	<ul>\n";

    echo "      <li><a href=\"http://twitter.com/#!MSI_MusicMovies\" target=\"_new\"><img src=\"./icons/socialmedia/twitter.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";
    echo " 	<li><a href=\"http://www.youtube.com/malayalasangeetham\" target=\"_new\"><img class=\"any\" src=\"./icons/socialmedia/youtube.png\" onmouseover=\"this.src=./icons/socialmedia/PNG/youtube.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";
    echo "      <li><a href=\"http://facebook.com/malayalasangeetham.info\" target=\"_new\"><img src=\"./icons/socialmedia/facebook.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";
    echo "      <li><a href=\"http://malayalasangeetham.blogspot.com\" target=\"_new\"><img src=\"./icons/socialmedia/bloggr.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";
    echo "      <li><a href=\"http://plus.google.com/u/0/118172512910477069354/posts\" target=\"_new\"><img src=\"./icons/socialmedia/googleplus.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";
    echo "      <li><a href=\"http://pinterest.com/msimusicmovies/boards/\" target=\"_new\"><img src=\"icons/socialmedia/pinterest.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";    
    echo "      <li><a href=\"http://x.co/msiios\" target=\"_new\"><img src=\"./msi2013_setup/ico-7.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";
    echo "      <li><a href=\"https://play.google.com/store/apps/details?id=org.music.MSIGUI&feature=search_result#?t=W251bGwsMSwxLDEsIm9yZy5tdXNpYy5NU0lHVUkiXQ..\" target=\"_new\">
<img src=\"./msi2013_setup/ico-8.png\" alt=\"\" height=\"29\" width=\"30\"></a></li>\n";
    echo "                                        </ul>\n";
    echo "                                    </nav>\n";
    echo "                                </div>\n";
    echo "                          </div>\n";
    echo "                            	<div class=\"form_block\">\n";
    echo "                                	<form method=\"post\" action=\"$_Master_search_redirect\" class=\"form_one\">\n";
    echo "                                    	<span>\n";
    $placeholder = "Search For Songs Movies Artists Ragas ...";
    if ($_GET['lang'] != 'E') { $placeholder=get_uc($placeholder,''); }
    $search_string = $_GET['search'];
    echo "                                        	<input type=\"text\" value=\"$search_string\" name=\"search\" placeholder=\"$placeholder\" required size=30>\n";
    echo "                                            <select name=db>\n";
    echo "                                            	<option value=all>All</option>\n";
    $movt     = "Movies";    $songt    = "Songs";    $albt     = "Albums";    $albsongt = "Album Songs";    $ragat    = "Ragas";    $artt     = "Artists"; $yeart = 'Year'; 
    if ($_GET['lang'] != 'E') { $movt = get_uc($movt,'');$songt = get_uc($songt,'');$albt = get_uc($albt,'');$albsongt = get_uc($albsongt,'');$ragat = get_uc($ragat,'');$artt = get_uc($artt,''); $yeart=get_uc($yeart,'');}
    echo "                                                <option value=movies>$movt</option>\n";
    echo "                                                 <option value=moviesongs>$songt</option>\n";
    echo "                                                <option value=albums>$albt</option>\n";
    echo "                                                <option value=albumsongs>$albsongt</option>\n";
    echo "                                                <option value=raga>$ragat</option>\n";
    echo "                                                <option value=year>$yeart</option>\n";
    echo "                                                 <option value=artist>$artt</option>\n";
    echo "                                            </select>\n";
    echo "                                                <button type=\"submit\" class=\"form-one-button\"></button>\n";
    echo "                                        </span>\n";
    echo "                                    </form>\n";
    echo "                                 </div>\n";



    echo "<aside class=\"body_wrapper\">\n";
    echo "  <div class=\"main\">\n";
    printHeaderFileContents();

}

function printHtmlContents($url){
  
    $fh = fopen($url, "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    echo $lx;
	}
	fclose($fh);
    }
    
}

function printFancyFooters(){

	 global $_Master_index;
    $scriptName =  $_SERVER['PHP_SELF'] ;
    $script     = $scriptName;
    global $_GDMasterRootofMSI,$_RootofMSI;
    $scriptName = $_GDMasterRootofMSI  . $_SERVER['PHP_SELF'] ;
    if ($_SERVER['QUERY_STRING'] != ''){
       $scriptName .= '?' . $_SERVER['QUERY_STRING'];   
    }
    $scriptName = str_replace('/_','/',$scriptName);	

    echo "<div class=ptables>\n";

	 $browser = new Browser();
	 $version = explode ('.',$browser->getVersion());
	 $majver = $version[0];
	 $browsername = $browser->getBrowser();






//      echo "<P><div class=\"fb-like\" data-href=\"$scriptName\" data-send=\"true\" data-width=\"1000\" data-show-faces=\"false\" data-font=\"lucida grande\"></div>\n";



      if ($script != "/$_Master_index") {
	  echo " <P><div id=\"disqus_thread\"></div>\n";
	  echo " <script type=\"text/javascript\">\n";
	  echo "     var disqus_shortname = 'msidev'; \n";
	  echo "     (function() {\n";
	  echo "         var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;\n";
	  echo "         dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';\n";
	  echo "         (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);\n";
	  echo "     })();\n";
	  echo " </script>\n";
	  echo " <noscript>Please enable JavaScript to view the <a href=\"http://disqus.com/?ref_noscript\">comments powered by Disqus.</a></noscript>\n";
	  echo " <a href=\"http://disqus.com\" class=\"dsq-brlink\">comments powered by <span class=\"logo-disqus\">Disqus</span></a>\n";
      }
    printHtmlContents("_includes/_Footer.html");		
//    echo "<table class=ptables><tr><td align=center class=fixedtiny>Running on $browsername ($majver)</td></tr></table>";
    echo "	 </body></html>\n";

}


function printMinimalFancyFooters(){

    $scriptName =  $_SERVER['PHP_SELF'] ;
    $script     = $scriptName;
    global $_GDMasterRootofMSI,$_RootofMSI;
    $scriptName = $_GDMasterRootofMSI  . $_SERVER['PHP_SELF'] ;
    if ($_SERVER['QUERY_STRING'] != ''){
       $scriptName .= '?' . $_SERVER['QUERY_STRING'];   
    }
    $scriptName = str_replace('/_','/',$scriptName);	
    echo "<div class=ptables>\n";
    printHtmlContents("_includes/_Footer.html");		
    echo "	 </body></html>\n";
}



function printHeaderFileContents(){

    $scriptName = str_replace ('/2012/','',$_SERVER['PHP_SELF']);
    $scriptName = str_replace ('/~msidbo6/','',$scriptName);
    $scriptName = str_replace ('/','',$scriptName);
    $scriptName = str_replace ('.php','',$scriptName);

    // ----------- Special Case 
    $scriptName = str_replace('_','',$scriptName);

    $header_file_name  = "${scriptName}_header";

//    if ($_GET['debug2013'] == 1) { echo 'HFN:', $scriptName, ":", $header_file_name, "<BR>"; }

    if (($scriptName == 'submitMovies' || $scriptName == 'submitSongs') && $_SERVER['QUERY_STRING'] == 't=ALBUM'){
	$header_file_name  = "${scriptName}_ALBUM_header";
    }

    if ($scriptName == 'movies' || $scriptName == 'songs' || $scriptName == 'nonmovies' || $scriptName == 'asongs'){
    if ($scriptName == 'movies' ){	$headingtext = "Movies Listing"; }
    if ($scriptName == 'songs' ){	$headingtext = "Songs Listing"; }
    if ($scriptName == 'nonmovies' ){	$headingtext = "Non Movies Listing"; }
    if ($scriptName == 'asongs' ){	$headingtext = "Non Movie Songs Listing"; }
	$qstr  = str_replace('tag=Search&','',$_SERVER['QUERY_STRING']);
	$qs  = explode('&',$qstr);
	$cat = explode('=',$qs[0]);
	$catname = $cat[1];

	if ($scriptName == 'listSongs') {$headingtext = 'Movie Songs Listing' ;}

	else if ($scriptName == 'listAlbums') 
	{ 
	    if ($cat[0] == 'dmusician' || $cat[0] == 'dlyricist' || $cat[0] == 'ddirector'){
		$headingtext = 'Dramas Listing' ;
	    }
	    else {
		$headingtext = 'Albums Listing' ;
	    }
	}
	else if ($scriptName == 'listAlbumSongs') { $headingtext = 'Albums Songs Listing' ;}
	if ($_SESSION['lang'] != "E"){
	    $headingtext = get_uc("$headingtext","");
	}
	echo "<div class=pheading>$headingtext</div>";	

	$header_file_name  = "${scriptName}_${catname}_header";
    }
    if ($scriptName == 'lit'){
	$qs  = explode('&',$_SERVER['QUERY_STRING']);
	$qst = $qs[0];
	$header_file_name  = "${scriptName}_${qst}_header";
    }
    if ($scriptName == 'index'){
	$qs  = explode('&',$_SERVER['QUERY_STRING']);
	$qst = $qs[0];
        $qst = str_replace('i=','',$qst);
	$header_file_name  = "${scriptName}_${qst}_header";
    }
    if ($scriptName == 'profiles'){
	$qs  = explode('&',$_SERVER['QUERY_STRING']);
	$cat = explode('=',$qs[0]);
	$catname = $cat[1];
	$header_file_name  = "${scriptName}_${catname}_header";
    }

    if ($_SESSION['lang'] != 'E') {
       $header_file_name .= "_malayalam";
    }

//    if ($_GET['debug2013'] == 1) { echo 'HFN:', $header_file_name, "<BR>"; }
    if (file_exists("Writeups/${header_file_name}.txt")){
//        echo "<div class=ptables>\n";	
	printHtmlContents("Writeups/${header_file_name}.txt");
//	echo "</div>";

    }

}

?>
