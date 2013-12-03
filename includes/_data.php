<?php

$username    = "msidbo6_admin";
$password    = "Lila00##))";
$database    = "msidbo6_malsongsdb";
$hostname    = "localhost";
$_MSILogo    = "images/MSI2012LogoUpdated.png";
$_MSILogoTransparent    = "images/MSI2012LogoTransparent.png";

$_RootDoc    = "http://msidb.org";
$_RootofMSI  = "http://msidb.org";
$_MasterRootofMSI  = "http://msidb.org";
$_RootOfMedia = "http://msidb.org";

if ($_SERVER['HTTP_HOST'] == 'en.msidb.org') {
   $_RotoofMSI = "http://en.msidb.org";
   $_MasterRootofMSI = "http://en.msidb.org";
   $_RootDoc = "http://en.msidb.org";
   $_RootOfMedia = "http://en.msidb.org";
}
$_GDMasterRootofMSI  = "http://malayalasangeetham.info";

$_RootDir     = "/home/msidbo6/public_html";
$_MasterRootDir     = "/home/msidbo6/public_html";
$_playerLoc          = "$_RootofMSI/published_clips";
$_username = '';

$_Master_profile_script = "displayProfile.php";
$_Master_movie_script   = "m.php";
$_Master_profile        = "profiles.php";
$_Master_song_script   = "s.php";
$_Master_newfaces = "showNewFaces.php";
$_Master_songlist_script   = "songs.php";
$_Master_contribs_script = "contribs.php";
$_Master_litpage = "lit.php";
$_Master_movielist_script   = "movies.php";
$_Master_albumlist_script   = "nonmovies.php";
$_Master_Submission_Record = 'recordSubmission.php';
$_Master_playlist = "playlist.php";
$_Master_shareplay = "shareplayListDetails.php";
$_Master_artistpic_upload = "uploadArtistPictures.php";
$_Master_playlist_sharer = "sharePlayList.php";
$_Master_playlist_file = "php/data/playlists.txt";
$_Master_index = "index.php";
$_Master_search_redirect = "rSearch.php";
$_Master_similarsearch = "similarSearch.php";
$_Master_vidscript = "vidsongs.php";
$_Master_albumsonglist_script   = "asongs.php";
$_Master_ragas = "Ragas.php";
$_Master_raga_desc = "describeRagas.php";
$_Master_Singers_script = "Singers.php";
$_Master_search_process   = "processSearch.php";
$_Master_quicksearch = "qSearch.php";
$_Master_search   = "search.php";
$_Master_album_script   = "a.php";
$_Master_albumsong_script   = "as.php";
$_Master_Columns_script = "Columns.php";
$_Master_Songbook_script = "sbook.php";
$_Master_SubmitTrailers_script = "submitTrailers.php";
$_Master_SubmitArticles_script = "submitArticles.php";
$_Master_Articles = "articles.php";
$_Master_Submitclips_script = "submitClips.php";
$_Master_Submitvids_script = "submitVids.php";
$_Master_Submitkars_script = "submitKaraokes.php";
$_Master_Managecomments_script = "manageComments.php";
$_Master_addcomments_script = "addsongcomments.php";
$_Master_Managelyrics_script = "manageLyrics.php";
$_Master_SubmitPictures_script = "submitPictures.php";
$_Master_promo_script = 'uploadPromos.php';
$_Master_apromo_script = 'uploadAlbumPromos.php';
$_Master_SubmitReviews_script = "submitReviews.php";
$_Master_contribs_script = "contribs.php";
$_Master_cacheremove_script = "RemoveCache.php";
$_Master_videoplayer = "playVideo.php";
$_Master_audioplayer = "playAudio.php";
$_Master_movie_edit = "editMovieIndex.php";
$_Master_upload_video = 'uploadVideo.php';
$_Master_upload_avideo = 'uploadAlbumVideo.php';
$_Master_upload_pics = 'uploadPictures.php';
$_Master_upload_kars = 'uploadKaraokes.php';
$_Master_upload_akars = 'uploadAlbumKaraokes.php';
$_Master_upload_revs = 'uploadReviews.php';
$_Master_upload_arevs = 'uploadAlbumReviews.php';
$_Master_years = 'Years.php';
$_Master_upload_apics = 'uploadAlbumPictures.php';
$_Master_lyrupdate = 'updateExistinglyrics.php';
$_Master_alyrupdate = 'updateExistingAlbumlyrics.php';
$_Master_latest = "latest.php";
$_Master_quiz_register   = "registerquiz.php";

$display = $_POST['display'];

if (!$display){
   $display = $_GET['display'];
}

function msi_dbconnect() {


   if ($_SERVER['HTTP_HOST'] == 'en.msidb.org') {
      $_SESSION['lang'] = 'E'; // store session data
      setcookie("Language", "E");   
   }
   else if ($_SERVER['HTTP_HOST'] == 'ml.msidb.org') {
       unset($_SESSION['lang']);
       setcookie("Language", "");   
   }


   if ($_GET['cl'] == 1){
     if(isset($_SESSION['lang'])) {
       unset($_SESSION['lang']);
       setcookie("Language", "");	 
     }
     else {
       $_SESSION['lang'] = 'E'; // store session data
       setcookie("Language", "E");	 
      }  

    }
 

   if(isset($_SESSION['lang'])) {	
       $_GET['lang'] = $_SESSION['lang'];
   }
   else if (isset($_COOKIE['Language'])){
       $_GET['lang'] = $_COOKIE['lang'];
   }

$username    = "msidbo6_admin";
$password    = "Lila00##))";
$database    = "msidbo6_malsongsdb";
$hostname    = "localhost";
$conLink = mysql_connect($hostname,  $username,$password);

if (mysql_errno() == 1045 || mysql_errno() == 1203 || (!mysql_select_db($database))) {
  // Failover to the 2nd database @ Inmotion itself
  $username    = "msidbo6_admin";
  $password    = "Lila00##))";
  $database    = "msidbo6_msidbo62_malsongsdb";
  $hostname    = "localhost";
  $conLink = mysql_connect($hostname,  $username,$password);
  if (mysql_errno() == 1045 || mysql_errno() == 1203 || (!mysql_select_db($database))) {
  // Failover to 3rd database @ Godaddy
    $username    = "malsongsdb5";
    $password    = "KeralTrichur33";
    $database    = "malsongsdb5";
    $hostname    = "malsongsdb5.db.5917791.hostedresource.com";
    $sock        = "malsongsdb5:/var/lib/mysql/mysql.sock";
    $conLink = mysql_connect($hostname,  $username,$password);
  }
}

mysql_query("SET NAMES utf8");
mysql_select_db($database) or die ("<div align=center class=mediumhead><img src=\"images/MSI Lo1
go Blog.png\" border=0><P>We are currently experiencing issues with our database, Please accept our sincere apologies for the inconvenience caused and come back soon.<br> MSI Administrators</div>");
if ($_GET['DB_VERSION'] == 1) { echo "<div stlye=\"padding-left:100px;\">Using $database</div>\n"; $_GET['DB_VERSION'] = ''; }

return $conLink;
}

function printHeadSection ($mode){

    $script = str_replace('/','',$_SERVER['PHP_SELF']);
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">\n";
    echo "<head>\n";
    echo "    <meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n";

    $qelems = getQueryComponents();
    $script = str_replace('_','',$script);
    if ($script == 'displayProfile.php'){
	$category = ucfirst($_GET['category']);
	$artist = $_GET['artist'];
	if ($category == 'Story' || $category == 'Screenplay' || $category == 'Dialog'){
	    $category = "$category Writer";
	}
	else if ($category == 'Camera'){
	    $category = "Cinematography";
	}
	else if ($category == "Design"){
	    $category = "Designer";
	}
	$_category = get_uc($category,'');
	$_artist = get_uc($artist,'');
	$title_str =     "Profile of Malayalam $category $artist";
        $meta_details   = "Profile of Malayalam $category $artist";
	$keywords     = "$category,$_category,$artist,$_artist,MalayalaSangeetham.Info";

	echo "        <title>$title_str</title>\n";
	echo "        <meta name=\"description\" content=\"$meta_details\">\n";
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";


    }
    else if ($script == 'manageLyrics.php') {

        $songid = $_GET['song_id'];
	$mode = $_GET['mode'];
	$table = 'SONGS';
	if ($mode == 'album'){ $table = 'ASONGS';}
	$title_str = getSongNameForTitleNew($songid,$table,1);
	echo "        <title>Add or Update Lyrics for $title_str</title>\n";

	$meta_details = getSongDetails($songid,$table);
	echo "        <meta name=\"description\" content=\"Update Lyrics for the Malayalam Song: $meta_details\">\n";

    }
    else if ($script == 'listSongs.php' || $script == 'songs.php' || $script == 'vidsongs.php'){
	$title_str = findDynamicTitle($_SERVER['QUERY_STRING']);
	$meta_details = findDynamicMeta($_SERVER['QUERY_STRING']);
	$keywords = findDynamicKeys($_SERVER['QUERY_STRING']);

	echo "        <title>List of Malayalam Songs by $title_str</title>\n";
	echo "        <meta name=\"description\" content=\"List of all Malayalam Movie Songs Categorized by $meta_details\">\n";
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }
    else if ($script == 'listMovies.php' || $script == 'movies.php') {
	$title_str = findDynamicTitle($_SERVER['QUERY_STRING']);
	$meta_details = findDynamicMeta($_SERVER['QUERY_STRING']);
	$keywords = findDynamicKeys($_SERVER['QUERY_STRING']);

	echo "        <title>List of Malayalam Movies by $title_str</title>\n";
	echo "        <meta name=\"description\" content=\"List of all Malayalam Movies Categorized by $meta_details\">\n";
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";

    }

    else if ($script == 'listAlbumSongs.php' || $script == 'asongs.php'){
	$title_str = findDynamicTitle($_SERVER['QUERY_STRING']);
	$meta_details = findDynamicMeta($_SERVER['QUERY_STRING']);
	$keywords = findDynamicKeys($_SERVER['QUERY_STRING']);

	echo "        <title>List of Malayalam Non Movie Songs by $title_str</title>\n";
	echo "        <meta name=\"description\" content=\"List of all Malayalam Non Movie Songs Categorized by $meta_details\">\n";
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }
    else if ($script == 'listAlbums.php' || $script == 'nonmovies.php') {
	$title_str = findDynamicTitle($_SERVER['QUERY_STRING']);
	$meta_details = findDynamicMeta($_SERVER['QUERY_STRING']);
	$keywords = findDynamicKeys($_SERVER['QUERY_STRING']);

	echo "        <title>List of Malayalam Non Movie Albums by $title_str</title>\n";
	echo "        <meta name=\"description\" content=\"List of all Malayalam Non Movie Albums, Dramas, Devotionals Categorized by $meta_details\">\n";
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";

    }

    else if ($script == 's.php'){
	$songid = $qelems[0];
	if ($songid < 1){
	    $songid = $_GET[$qlems[0]];
	}

	$title_str = getSongNameForTitleNew($songid,'SONGS',1);
	echo "        <title>$title_str</title>\n";

	$meta_details = getSongDetails($songid,'SONGS');
	echo "        <meta name=\"description\" content=\"Details of the Malayalam Song: $meta_details\">\n";

	$keywords     = getSongKeywords($songid,'SONGS');
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }   
    else if ($script == 'as.php'){
	$songid = $qelems[0];
	if ($songid < 1){
	    $songid = $_GET[$qlems[0]];
	}

	$title_str = getSongNameForTitleNew($songid,'ASONGS',1);
	echo "        <title>$title_str</title>\n";

	$meta_details = getSongDetails($songid,'ASONGS');
	echo "        <meta name=\"description\" content=\"Details of the Malayalam Album Song: $meta_details\">\n";

	$keywords = getSongKeywords($songid,'ASONGS');
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }
    else if ($script == 'm.php' ){

	$movid = $qelems[0];
	if ($movid < 1){
	    $movid = $_GET[$qlems[0]];
	}

	$title_str = getMovieNameForTitleNew($movid,'MOVIES',1);
	echo "        <title>$title_str</title>\n";

	$meta_details = getmoviedetailsExt($movid,'MOVIES');
	echo "        <meta name=\"description\" content=\"Details of the Malayalam Movie: $meta_details\">\n";

	$keywords = getMovieKeywords($movid,'MOVIES');
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }
    else if ($script == 'Columns.php'){
	$title_str = getColumnTitle($_SERVER['QUERY_STRING']);
	echo "        <title>$title_str</title>\n";

	$meta_str = getColumnMeta($_SERVER['QUERY_STRING']);
	echo "        <meta name=\"description\" content=\"$meta_str\">\n";

	$keywords = getColumnKeywords($_SERVER['QUERY_STRING']);
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }
    else if ($script == 'a.php'){
	$movid = $qelems[0];
	if ($movid < 1){
	    $movid = $_GET[$qlems[0]];
	}

	$title_str = getMovieNameForTitleNew($movid,'ALBUMS',1);
	echo "        <title>$title_str</title>\n";

	$meta_details = getmoviedetailsExt($movid,'ALBUMS');
	echo "        <meta name=\"description\" content=\"Details of the Malayalam Non Movie Album: $meta_details\">\n";

	$keywords = getMovieKeywords($movid,'ALBUMS');
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }
    else if ($script == 'ShowWishes.php'){
	$artist_name = $_GET['artist'];
	$title_str = "$artist_name wishing MalayalaSangeetham.Info the very best";
        $meta_details = "$artist_name wishing MalayalaSangeetham.Info a long and prosperous life and congratulate for its achievements";
	$keywords = "$artist_name,MalayalaSangeetham.Info,Wishes,Good Luck,Best of Luck,Malayalam Encyclopedia";
	echo "        <title>$title_str</title>\n";
	echo "        <meta name=\"description\" content=\"$keywords\">\n";
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }
    else {
	$title_str    = findLookupTitle($script,$_SERVER['QUERY_STRING']);
	$meta_details = findLookupMeta($script,$_SERVER['QUERY_STRING']);
	$keywords     = findLookupKeywords($script,$_SERVER['QUERY_STRING']);

	echo "        <title>$title_str</title>\n";
	echo "        <meta name=\"description\" content=\"$keywords\">\n";
	echo "        <meta name=\"keywords\" content=\"$keywords\">\n";
    }

    echo "    <meta property=\"fb:app_id\" content=\"176790735765869\"/>\n";
    echo "    <meta property=\"og:title\" content=\"$ogtitle\"/>\n";
    echo "    <meta property=\"og:description\" content=\"$ogdescription\"/>\n";
    echo "    <link rel=\"stylesheet\" href=\"./msi2013_setup/style.css\" type=\"text/css\" media=\"all\">\n";
/*
    echo "<!-- banner -->\n";

    echo "<link rel=\"stylesheet\" href=\"./msi2013_setup/coda-slider.css\" type=\"text/css\" media=\"screen\" title=\"no title\" charset=\"utf-8\">\n";
    echo "<script src=\"./msi2013_setup/jquery-1.2.6.js\" type=\"text/javascript\"></script>\n";
    echo "<script src=\"./msi2013_setup/jquery.scrollTo-1.3.3.js\" type=\"text/javascript\"></script>\n";
    echo "<script src=\"./msi2013_setup/jquery.localscroll-1.2.5.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
    echo "<script src=\"./msi2013_setup/jquery.serialScroll-1.2.1.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
    echo "<script src=\"./msi2013_setup/coda-slider.js\" type=\"text/javascript\" charset=\"utf-8\"></script>\n";
*/
    echo "    <script type=\"text/javascript\" src=\"js/search/creative_table_ajax-1.3.js\" charset=\"utf-8\"></script>\n";
    echo "    <script type=\"text/javascript\" src=\"js/tt/jquery.js\" charset=UTF-8></script>\n";
    echo "    <script type=\"text/javascript\" src=\"js/tt/main.js\" charset=UTF-8></script>\n";
    echo "    <script type=\"text/javascript\" src=\"js/tt/mainpreview.js\" charset=UTF-8></script>\n";

    echo "<!-- style -->\n";

    echo "<!--end-->\n";
    echo "<link type=\"text/css\" rel=\"stylesheet\" href=\"chrome-extension://cpngackimfmofbokmjmljamhdncknpmg/style.css\"><script type=\"text/javascript\" charset=\"utf-8\" src=\"chrome-extension://cpngackimfmofbokmjmljamhdncknpmg/page_context.js\"></script>\n";


    // Misc - Browser specfic javascript includes, function specific stylesheets

    if ($script == 's.php' || $script == 'm.php' || $script == 'as.php' || $script == 'mp.php' || $script == 'Columns.php' || $script == 'playVideo.php' || $script == 'latest.php') { 
	echo "<script type=\"text/javascript\" src=\"fplayer/flowplayer-3.2.8.min.js\"></script>\n";
    }


    if ($mode == 'Truncated'){
	echo "    <script type=\"text/javascript\" src=\"js/search/jquery-1.4.2.min.js\" charset=\"utf-8\"></script>\n";
	echo "    <script type=\"text/javascript\" src=\"js/search/creative_table_ajax-1.3.js\" charset=\"utf-8\"></script>\n";
	echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/search/creative4tables.css\">\n";
    }
    else if ($mode == 'NewLibs'){
	echo "    <script type=\"text/javascript\" src=\"js/search/jquery-1.4.2.min.js\" charset=\"utf-8\"></script>\n";
	echo "    <script type=\"text/javascript\" src=\"js/search/creative_table_ajax-1.3.js\" charset=\"utf-8\"></script>\n";
	echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/search/creative4tables.css\">\n";
    }
    else if ($mode == 'TruncatedTable'){
	echo "    <script type=\"text/javascript\" src=\"js/search/jquery-1.4.2.min.js\" charset=UTF-8></script>\n";
	echo "    <script type=\"text/javascript\" src=\"js/search/creative_table_ajax-1.3.js\" charset=UTF-8></script>\n";
	echo "    <link rel=\"stylesheet\" type=\"text/css\" href=\"css/search/creative.css\">\n";
    }
    else if ($mode == 'Popup'){

	echo "    <script type=\"text/javascript\" src=\"js/search/jquery-1.4.2.min.js\" charset=UTF-8></script>\n";
	echo "<script type=\"text/javascript\" src=\"http://gettopup.com/releases/latest/top_up-min.js\" charset=UTF-8></script> \n";
    }
    else if ($mode == 'Scrollable') {
       echo "<script src=\"http://cdn.jquerytools.org/1.2.7/full/jquery.tools.min.js\"></script> \n";
       echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/scrollable-horizontal.css\" /> \n";
       echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/scrollable-buttons.css\" /> \n";
       echo "<script type=\"text/javascript\" src=\"http://gettopup.com/releases/latest/top_up-min.js\" charset=UTF-8></script> \n";
    }
    else if ($mode == 'auto'){
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/default.css\" />\n";
	echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"css/jquery.smartsuggest.css\" />\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery-1.3.2.min.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"js/jquery.smartsuggest.js\"></script>\n";
	echo "<script type=\"text/javascript\" src=\"js/default.js\"></script>\n";

    }
    else {
	echo "    <script type=\"text/javascript\" src=\"js/search/jquery-1.4.2.min.js\" charset=UTF-8></script>\n";
//	echo "    <script type=\"text/javascript\" src=\"js/search/creative_table_ajax-1.3.js\" charset=\"utf-8\"></script>\n";
	echo "    <script type=\"text/javascript\" src=\"js/tt/jquery.js\" charset=UTF-8></script>\n";
	if ($mode == 'vidlist'){
	    echo "<script type=\"text/javascript\" src=\"html5lightbox/html5lightbox.js\"></script>\n";
	}
//
//      Inlining the following javascript for faster loading

	echo "    <script type=\"text/javascript\" src=\"js/tt/main.js\" charset=UTF-8></script>\n";
	echo "    <script type=\"text/javascript\" src=\"js/tt/mainpreview.js\" charset=UTF-8></script>\n";
    }

    $email_msg = 'Not a valid e-mail address';
    $search_criteria_msg = 'Please provide some text you can search on';
    if ($_GET['lang'] != 'E') { $email_msg= get_uc($email_msg,''); $search_criteria_msg = get_uc($search_criteria_msg,''); }

    echo "<div id=\"fb-root\"></div>\n";
    echo "<script>(function(d, s, id) {\n";
    echo "  var js, fjs = d.getElementsByTagName(s)[0];\n";
    echo "  if (d.getElementById(id)) return;\n";
    echo "  js = d.createElement(s); js.id = id;\n";
    $fb_lang = 'ml_IN';
    if ($_SESSION['lang'] == 'E'){
	$fb_lang = 'en_US';
    }
    echo "js.src = \"//connect.facebook.net/${fb_lang}/all.js#xfbml=1&appId=176790735765869\";\n";
    echo "  fjs.parentNode.insertBefore(js, fjs);\n";
    echo "}(document, 'script', 'facebook-jssdk'));</script>\n";

    // -- Liquid Slider -- 
    
    if ($script == 'index.php'){
    echo "<link rel=\"stylesheet\" href=\"./liquidslider/css/animate.css\"> \n";
    echo "<link rel=\"stylesheet\" href=\"./liquidslider/css/liquid-slider.css\">\n";
    
    echo "<script src=\"http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js\"></script>\n";
    echo "<script src=\"./liquidslider/js/jquery.easing.1.3.js\"></script>\n";
    echo "<script src=\"./liquidslider/js/jquery.touchSwipe.min.js\"></script>\n";
    echo "<script src=\"./liquidslider/js/jquery.liquid-slider.min.js\"></script>\n";
    echo "<script>\n";
    echo "$(function(){\n";
    echo "     $('#slider-id').liquidSlider();\n";
    echo "});\n";
    echo "</script>\n";
    }





    echo " <script type=\"text/javascript\">\n";

    echo "function validateForm()\n";
    echo "{\n";
    echo "    var x=document.forms[\"myForm\"][\"email\"].value;\n";
    echo "    var atpos=x.indexOf(\"@\");\n";
    echo "    var dotpos=x.lastIndexOf(\".\");\n";
    echo "if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)\n";
    echo "{\n";
    echo "    alert(\"$email_msg\");\n";
    echo "    return false;\n";
    echo "}\n";
    echo " else { document.submit(); }\n";
    echo "}\n";



    echo "function validateSearch()\n";
    echo	  "{\n";
    echo "var  x=document.forms[\"rsearch\"][\"search\"].value;\n";
    echo "if (x==null || x==\"\")\n";
    echo "  {\n";
    echo "  alert(\"$search_criteria_msg\");\n";
    echo "  return false;\n";
    echo "  }\n";
    echo " else { document.submit(); }\n";
    echo "}\n";


    echo "function validateMVForm()\n";
    echo "{\n";
    echo "    var x=document.forms[\"mailform\"][\"userid\"].value;\n";
    echo "    var atpos=x.indexOf(\"@\");\n";
    echo "    var dotpos=x.lastIndexOf(\".\");\n";
    echo "if (atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length)\n";
    echo "{\n";
    echo "    alert(\"$email_msg\");\n";
    echo "    return false;\n";
    echo "}\n";
    echo " else { document.submit(); }\n";
    echo "}\n";

    echo "	function disableSelection(target){\n";
    echo "if (typeof target.onselectstart!=\"undefined\") \n";
    echo "   target.onselectstart=function(){return false}\n";
    echo "else if (typeof target.style.MozUserSelect!=\"undefined\") \n";
    echo "     target.style.MozUserSelect=\"none\"\n";
    echo " else //All other route (ie: Opera)\n";
    echo "     target.onmousedown=function(){return false}\n";
    echo "target.style.cursor = \"default\";\n";
    echo "}\n";

    echo "function Disable_Control_C() {\n";
    echo "var keystroke = String.fromCharCode(event.keyCode).toLowerCase();\n";

    echo "if (event.ctrlKey && (keystroke == 'c' || keystroke == 'v')) {\n";
    echo "event.returnValue = false;\n";
    echo "}   \n";
    echo "}   \n";

    echo "</script>\n";


    echo "</head>\n";

    global $_username;

    $_username = $_SERVER['PHP_AUTH_USER'];
    if ($_username == '') { $_username = $_SERVER['REMOTE_USER']; }
    if ($_GET['auth'] == 1) { echo "Logged in as $_username<BR>"; }
    if ($_username != 'anoop' && $_username != 'ajay' && $_username != 'vijay' && $_username != 'sunny' && $_username != 'jaya' && $_username != 'drbhadran' && $_username != 'kalyani' && $_username != 'jija'  && $_username != 'dilip' && $_username != 'sidhardh'){
        if ($script != 'submitVids.php' && $script != 'submitTrailers.php' && $script != 'submitArticles.php' && $script != 'submitReviews.php' && $script != 'manageLyrics.php'){
           echo "<body oncopy='return false' oncut='return false' onpaste='return false' ondragstart=\"return false\" onselectstart=\"return false\" oncontextmenu=\"return false\" onkeydown=\"javascript:Disable_Control_C()\">\n";
           printRightClickBlocker();
           echo "<script>disableSelection(document.body);</script>";
        }
        else { 
            echo "<body>\n"; 
        }
    }
    else {
//       echo "<body oncopy='return false' oncut='return false' onpaste='return false' ondragstart=\"return false\" onselectstart=\"return false\" oncontextmenu=\"return false\" onkeydown=\"javascript:Disable_Control_C()\">\n";
       echo "<body>\n"; 
    }

}

function addShareLinks()
{


echo "<!-- AddThis Button BEGIN -->\n";
echo "<div class=\"addthis_toolbox addthis_default_style \">\n";
echo "<a class=\"addthis_button_preferred_1\"></a>\n";
echo "<a class=\"addthis_button_preferred_2\"></a>\n";
echo "<a class=\"addthis_button_preferred_3\"></a>\n";
echo "<a class=\"addthis_button_preferred_4\"></a>\n";
echo "<a class=\"addthis_button_compact\"></a>\n";
echo "<a class=\"addthis_counter addthis_bubble_style\"></a>\n";
echo "</div>\n";
echo "<script type=\"text/javascript\">var addthis_config = {\"data_track_addressbar\":true};</script>\n";
echo "<script type=\"text/javascript\" src=\"//s7.addthis.com/js/300/addthis_widget.js#pubid=malayalasangeetham\"></script>\n";
echo "<script type=\"text/javascript\">\n";
echo "var addthis_config = addthis_config||{};\n";
echo "addthis_config.data_track_addressbar = false;\n";
echo "</script>\n";
echo "<!-- AddThis Button END -->\n";
/*
	echo "<!-- AddThis Button BEGIN -->\n";
	echo "<div class=\"addthis_toolbox addthis_default_style \" style=\"float:right;\">\n";
	echo "<a class=\"addthis_button_facebook_like\" fb:like:layout=\"button_count\"></a>\n";
	echo "<a class=\"addthis_button_tweet\"></a>\n";
        echo "<a class=\"addthis_button_google_plusone\" g:plusone:size=\"medium\"></a>\n";
	echo "<a class=\"addthis_button_pinterest_pinit\"></a>\n";
	echo "<a class=\"addthis_counter addthis_pill_style\"></a>\n";
	echo "</div>\n";
	echo "<script type=\"text/javascript\">var addthis_config = {\"data_track_addressbar\":true};</script>\n";
	echo "<script type=\"text/javascript\" src=\"//s7.addthis.com/js/300/addthis_widget.js#pubid=malayalasangeetham\"></script>\n";
	echo "<!-- AddThis Button END -->\n";
*/
}

function findLookupTitle($script,$query){
    
    $title = runQuery("SELECT keywords from TLOOKUPS where script=\"$script\" and options=\"$query\"",'keywords');
    $title_words = explode(',',$title);
    return $title_words[0];
}

function findLookupMeta($script,$query){
    return ;
}
function findLookupKeywords($script,$query){
    $title_words = runQuery("SELECT keywords from TLOOKUPS where script=\"$script\" and options=\"$query\"",'keywords');
    return $title_words;
}

function getQueryComponents()
{

    $qstring=array();
    $vals = explode('&', $_SERVER['QUERY_STRING']);
    foreach ($vals as $pairs){
	$pairvals = explode('=',$pairs);
	array_push($qstring,$pairvals[0]);
    }
    return $qstring;
}

function getSongDetails ($sid, $mode)
{

    if ($sid > 0) {
	$query       = "SELECT S_MUSICIAN,S_WRITERS,S_SINGERS FROM $mode WHERE S_ID=$sid";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $musician = mysql_result($res_funcQry, $i, "S_MUSICIAN");
	    $lyricist = mysql_result($res_funcQry, $i, "S_WRITERS");
	    $singer   = mysql_result($res_funcQry, $i, "S_SINGERS");
	    if ($_GET['lang'] != 'E'){
		$_musician = get_uc($musician,'');
		$_lyricist = get_uc($lyricist,'');
		$_singer   = get_uc($singer,'');
	    }
	    $i++;
	}
	return "Composer: $musician | $_musician, Lyricist: $lyricist | $_lyricist, Singers: $singer | $_singer";
    }
    else {
	return "";
    }
}

function getSongKeywords ($sid, $mode)
{

    if ($sid > 0) {
	$query       = "SELECT S_SONG,S_MOVIE,S_RAGA,S_MUSICIAN,S_WRITERS,S_SINGERS FROM $mode WHERE S_ID=$sid";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $musician = mysql_result($res_funcQry, $i, "S_MUSICIAN");
	    $lyricist = mysql_result($res_funcQry, $i, "S_WRITERS");
	    $singer   = mysql_result($res_funcQry, $i, "S_SINGERS");
	    $movie    = mysql_result($res_funcQry, $i, "S_MOVIE");
	    $song    = mysql_result($res_funcQry, $i, "S_SONG");
	    $raga    = mysql_result($res_funcQry, $i, "S_RAGA");
	    $_musician = get_uc($musician,'');
	    $_lyricist = get_uc($lyricist,'');
	    $_singer   = get_uc($singer,'');
	    $_movie   = get_uc($movie,'');
	    $_song    = get_uc($song,'');
	    $_raga    = get_uc($raga,'');
	    $i++;
	}
	$rstr = "$musician ,$_musician, $lyricist , $_lyricist,$singer , $_singer, $song, $_song, $movie, $_movie";
	if ($raga != ''){
	    $rstr .= ",$raga, $_raga";
	}
	return $rstr;
    }
    else {
	return "";
    }
}

function getMovieKeywords ($mid, $mode)
{

    if ($sid > 0) {
	$query       = "SELECT MOVIES.M_MUSICIAN,MOVIES.M_WRITERS,MOVIES.M_DIRECTOR,MDETAILS.M_PRODUCER,MDETAILS.M_CAST FROM $mode,MDETAILS WHERE MOVIES.M_ID=$mid and MOVIES.M_ID=MDETAILS.M_ID";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $musician = mysql_result($res_funcQry, $i, "M_MUSICIAN");
	    $lyricist = mysql_result($res_funcQry, $i, "M_WRITERS");
	    $director   = mysql_result($res_funcQry, $i, "MOVIES.M_DIRECTOR");
	    $movie    = mysql_result($res_funcQry, $i, "M_MOVIE");
	    $producer    = mysql_result($res_funcQry, $i, "M_PRODUCER");
	    $actors    = mysql_result($res_funcQry, $i, "M_CAST");
	    $_musician = get_uc($musician,'');
	    $_lyricist = get_uc($lyricist,'');
	    $_singer   = get_uc($singer,'');
	    $_movie   = get_uc($movie,'');
	    $_producer    = get_uc($producer,'');
	    $_actors    = get_uc($actors,'');
	    $i++;
	}
	$rstr = "$musician ,$_musician, $lyricist , $_lyricist,$director , $_director, $producer, $_producer, $actors, $_actors";
	return $rstr;
    }
    else {
	return "";
    }
}
function getSongNameForTitleNew ($sid, $mode)
{

    if ($sid > 0) {
	$query       = "SELECT S_SONG,S_MOVIE,S_YEAR FROM $mode WHERE S_ID=$sid";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $song_name  = mysql_result($res_funcQry, $i, "S_SONG");
	    $movie_name = mysql_result($res_funcQry, $i, "S_MOVIE");
	    $_movie_name = get_uc($movie_name,'');
	    $_song_name = get_uc($song_name,'');
	    $year       = mysql_result($res_funcQry, $i, "S_YEAR");
	    $i++;
	}
	return "$song_name ($movie_name [$year]) | $_song_name ($_movie_name [$year])";

    }
    else {
	return "";
    }

}

function set_language()
{
    if ($_GET['cl'] == 1){
	if(isset($_SESSION['lang'])) {
	    unset($_SESSION['lang']);
	    setcookie("Language", "");	 
	}
	else {
	    $_SESSION['lang'] = 'E'; // store session data
		setcookie("Language", "E");	 
	}  
    }
   if(isset($_SESSION['lang'])) {	
       $_GET['lang'] = $_SESSION['lang'];
   }
   else if (isset($_COOKIE['Language'])){
       $_GET['lang'] = $_COOKIE['lang'];
   }

    $changeurl = 0;	
    $script = str_replace('/','',$_SERVER['PHP_SELF']);
    $pos = strpos($_SERVER['QUERY_STRING'], "cl=1");
    if ($pos !== false){
	$changeurl = 1;
    }

    $qs = str_replace("?cl=1","",$_SERVER['QUERY_STRING']);
    $qs = str_replace("&cl=1","",$qs);
    $qs = str_replace("cl=1","",$qs);
    $sc_str = "${script}?${qs}&cl=1";
    $sc_str = str_replace("?&","?",$sc_str);
    if ($changeurl == 1){ 
	echo "<script>location.replace(\"${script}?${qs}\");</script>";
    }
    $qs = str_replace("?cl=1","",$_SERVER['QUERY_STRING']);
}

function getMovieNameForTitleNew ($mid, $mode)
{
    $mid = str_replace("mid=","",$mid);
    if ($mid > 0) {
	$query       = "SELECT M_MOVIE,M_YEAR FROM $mode WHERE M_ID=$mid";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $movie_name = mysql_result($res_funcQry, $i, "M_MOVIE");
	    $year       = mysql_result($res_funcQry, $i, "M_YEAR");
	    if ($_GET['lang'] != 'E'){
		$_movie_name = get_uc($movie_name,'');
	    }
	    $i++;
	}
	if ($_movie_name != '') { 
	    return "$movie_name [$year] | $_movie_name [$year]";
	}
	else {
	    return "$movie_name [$year]";
	}
    }
    else {
	return "";
    }

}

function getmoviedetailsExt ($mid, $mode)
{
    $mid = str_replace("mid=","",$mid);
    if ($mid > 0) {
	$query       = "SELECT M_MOVIE,M_MUSICIAN,M_WRITERS,M_DIRECTOR,M_YEAR FROM $mode WHERE M_ID=$mid";
	$res_funcQry = mysql_query($query);
	$num_funcQry = mysql_num_rows($res_funcQry);
	$i = 0;
	while ($i < $num_funcQry){
	    $movie_name = mysql_result($res_funcQry, $i, "M_MOVIE");
	    $movie_mus = mysql_result($res_funcQry, $i, "M_MUSICIAN");
	    $movie_lyr = mysql_result($res_funcQry, $i, "M_WRITERS");
	    $movie_dir = mysql_result($res_funcQry, $i, "M_DIRECTOR");
	    $year       = mysql_result($res_funcQry, $i, "M_YEAR");
	    if ($_GET['lang'] != 'E'){
		$_movie_name = get_uc($movie_name,'');
		$_movie_mus = get_uc($movie_mus,'');
		$_movie_lyr = get_uc($movie_lyr,'');
		$_movie_dir = get_uc($movie_dir,'');
	    }
	    $i++;
	}
	return "Movie Name: $movie_name | $_movie_name , Composer: $movie_mus | $_movie_mus, Lyricist: $movie_lyr | $_movie_lyr, Director: $movie_dir | $_movie_dir";
    }
    else {
	return "";
    }
}

function printRightClickBlocker() 
{

echo "<script language=JavaScript>\n";
echo "<!--\n";
//var message="Function Disabled!";
echo "function clickIE4(){\n";
echo "  if (event.button==2){\n";
    //alert(message);
echo "    return false;  \n";
echo "  }\n";
echo "}\n";
echo "function clickNS4(e){\n";
echo " if (document.layers||document.getElementById&&!document.all){\n";
echo "   if (e.which==2||e.which==3){\n";
   //alert(message);
echo "     return false;\n";
echo "   }\n";
echo " }\n";
echo "}\n";
echo "if (document.layers){\n";
echo "   document.captureEvents(Event.MOUSEDOWN);\n";
echo "   document.onmousedown=clickNS4;\n";
echo "}\n";
echo "else if (document.all&&!document.getElementById){\n";
echo "   document.onmousedown=clickIE4;\n";
echo "}\n";
echo "document.oncontextmenu=new Function(\"return false\")\n";
echo "// --> \n";
echo "</script>\n";
}

function findDynamicKeys($qs){
    $qelems = explode('&',$qs);
    $ret_str = array();
    foreach ($qelems as $qe){
	$qe_elems = explode ('=',$qe);
	$k = ucfirst($qe_elems[0]);
	$v = $qe_elems[1];
	$v = str_replace("%20"," ",$v);
	if ($k != 'Tag' && $k != 'Limit' && $k != 'Page_num' && $k != 'Category' && $k != 'Artist' && $k != 'Alimit' && $k != 'Sl' && $k != 'Songstate' && $k != 'Noshare' && $k != 'Videos'){
	    if ($k == 'Karaoke') { $v = 'Availability'; }
	    array_push ($ret_str, "$k,$v");
	    $uc_equiv = get_uc($k,'') . ',' . get_uc($v, '');
	    array_push ($ret_str, "$uc_equiv");
	}
    }
    if ($_GET['tag'] == 'Last100' || $_GET['tag'] ==  'LastAddedSongsList'){
       array_push($ret_str,"Last 100 Songs Added to MSI");
    }
    else if ($_GET['tag'] == 'LastUpdatedSongsList'){
       array_push($ret_str,"Last Updated 100 Songs");
    }
    else if ($_GET['tag'] == 'LastUpdatedMoviesList'){
       array_push($ret_str,"Last Updated 100 Movies");
    }
    else if ($_GET['tag'] == 'LastAddedMoviesList'){
       array_push($ret_str,"Last 100 Movies Added to MSI");
    }
    else if ($_GET['tag'] == 'MissingSongs'){
       array_push($ret_str,"Movies with Missing Songs in MSI");
    }

    if ($_GET['category'] != ''){
	$c = $_GET['category'];
	$a = $_GET['artist'];
	array_push ($ret_str,"$c $a");
	$uc_equiv = get_uc($c,'') . ',' . get_uc($a, '');
	array_push ($ret_str, "$uc_equiv");
    }
    return implode (',' , $ret_str);

}
function findDynamicMeta($qs){
    $qelems = explode('&',$qs);
    $ret_str = array();
    foreach ($qelems as $qe){
	$qe_elems = explode ('=',$qe);
	$k = ucfirst($qe_elems[0]);
	$v = $qe_elems[1];
	$v = str_replace("%20"," ",$v);

	if ($k != 'Tag' && $k != 'Limit' && $k != 'Page_num' && $k != 'Category' && $k != 'Artist' && $k != 'Alimit' && $k != 'Sl' && $k != 'Songstate' && $k != 'Noshare' && $k != 'Videos'){
	    if ($k == 'Karaoke') { $v = 'Availability'; }
	    array_push ($ret_str, "$k $v");
	    $uc_equiv = get_uc($k,'') . ' ' . get_uc($v, '');
	    array_push ($ret_str, "$uc_equiv");
	}
    }
    if ($_GET['tag'] == 'Last100' || $_GET['tag'] ==  'LastAddedSongsList'){
       array_push($ret_str,"Last 100 Songs Added to MSI");
    }
    else if ($_GET['tag'] == 'LastUpdatedSongsList'){
       array_push($ret_str,"Last Updated 100 Songs");
    }
    else if ($_GET['tag'] == 'LastUpdatedMoviesList'){
       array_push($ret_str,"Last Updated 100 Movies");
    }
    else if ($_GET['tag'] == 'LastAddedMoviesList'){
       array_push($ret_str,"Last 100 Movies Added to MSI");
    }
    else if ($_GET['tag'] == 'MissingSongs'){
       array_push($ret_str,"Movies with Missing Songs in MSI");
    }

    if ($_GET['category'] != ''){
	$c = $_GET['category'];
	$a = $_GET['artist'];
	array_push ($ret_str,"$c $a");
	$uc_equiv = get_uc($c,'') . ' ' . get_uc($a, '');
	array_push ($ret_str, "$uc_equiv");
    }
    return implode (',' , $ret_str);

}
function findDynamicTitle($qs){

    $qelems = explode('&',$qs);
    $ret_str = array();
    foreach ($qelems as $qe){
	$qe_elems = explode ('=',$qe);
	$k = ucfirst($qe_elems[0]);
	$v = $qe_elems[1];
	$v = str_replace("%20"," ",$v);

	if ($k != 'Tag' && $k != 'Limit' && $k != 'Page_num' && $k != 'Category' && $k != 'Artist' && $k != 'Alimit' && $k != 'Sl' && $k != 'Songstate' && $k != 'Noshare' && $k != 'Videos' && $k != 'Singtype'){
	    if ($k == 'Karaoke') { $v = 'Availability'; }
	    array_push ($ret_str, "$k $v");
	}
    }
    if ($_GET['tag'] == 'Last100' || $_GET['tag'] ==  'LastAddedSongsList'){
       array_push($ret_str,"Last 100 Songs Added to MSI");
    }
    else if ($_GET['tag'] == 'LastUpdatedSongsList'){
       array_push($ret_str,"Last Updated 100 Songs");
    }
    else if ($_GET['tag'] == 'LastUpdatedMoviesList'){
       array_push($ret_str,"Last Updated 100 Movies");
    }
    else if ($_GET['tag'] == 'LastAddedMoviesList'){
       array_push($ret_str,"Last 100 Movies Added to MSI");
    }
    else if ($_GET['tag'] == 'MissingSongs'){
       array_push($ret_str,"Movies with Missing Songs in MSI");
    }

    if ($_GET['category'] != ''){
	$c = $_GET['category'];
	$a = $_GET['artist'];
	array_push ($ret_str,"$c $a");
    }
    return implode (',' , $ret_str);
}
function getColumnTitle($qs)
{
    $coltitle = $_GET['cn'];
    $ord = $_GET['e'];
    $title = runQuery("SELECT title from ACOLUMNS WHERE coltitle=\"$coltitle\" and ordr = \"$ord\"",'title');
    $utitle = runQuery("SELECT utitle from ACOLUMNS WHERE coltitle=\"$coltitle\" and ordr = \"$ord\"",'utitle');
    return "$title | $utitle";
}

function getColumnMeta($qs)
{
    $coltitle = $_GET['cn'];
    $ord = $_GET['e'];
    $colname = runQuery("SELECT name from COLNAMES WHERE tag=\"$coltitle\"",'name');
    $title = runQuery("SELECT title from ACOLUMNS WHERE coltitle=\"$coltitle\" and ordr = \"$ord\"",'title');
    $utitle = runQuery("SELECT utitle from ACOLUMNS WHERE coltitle=\"$coltitle\" and ordr = \"$ord\"",'utitle');
    return "$colname - $title | $utitle";
}
function getColumnKeywords($qs)
{
    $coltitle = $_GET['cn'];
    $ord = $_GET['e'];
    $tags = runQuery("SELECT tags from ACOLUMNS WHERE coltitle=\"$coltitle\" and ordr = \"$ord\"",'tags');
    return "$tags";
}
?>
