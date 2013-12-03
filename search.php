<?php session_start();
{
    error_reporting (E_ERROR);

    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("includes/utils.php");
    require_once("_includes/_moviePageUtils.php");
    $_GET['encode']='utf';

//  printHeaders('auto');

    $cLink = msi_dbconnect();
    printXheader('auto');
    mysql_query("SET NAMES utf8");

    $db = $_GET['db'];
    if (!$db) { $db = 'movies'; }

    $singer = 'Singers';
    $composer = 'Composer';
    $lyricist = 'Lyricist';
    $year = 'Year';
    $video = "Video";
    $audio = "Audio";
    $karaoke = "Karaoke";

    $posters = "Poster";
    $reviews = "Reviews";
    $songbooks       = "Song Books";
    $promos = "Promotional Material";

    $nonavailability = "Non Availability";
    $availability    = "Availability (Songs Related)";
    $mavailability   = "Availability (Movies and Albums Related)";
    $lyrics          = "Lyrics";
    $mlyrics         = "Unicode Lyrics";
    $director        = "Director";
    $producer        = "Producer";
    $actor           = "Actor";
    $raga            = "Raga";
    $bgm             = "Background Music";
    $story           = "Story";
    $screenplay      = "Screenplay";
    $movies          = "Movies";
    $moviesongs      = "Movie Songs";
    $albums          = "Albums";
    $albumsongs      = "Non Movie Songs";
    $allsongs        = "All Songs";
    $genre = "Genre";
    $databases       = "Search Collection";
    $search_articles = "Search Articles Collection";
//    $search_audio    = "Search Gaanalokaveedhikalil";
    $search_type     = "Similar Sounding Search";

    $_v_moviename = $_GET['movie'];
    if ($db == "albums" && !$_v_moviename) {
	$_v_moviename = $_GET['album'];
    }
    $_v_songname = $_GET['songname'];
    if (!$_v_songname) { $_v_songname = $_GET['song']; }
    $_v_musician = $_GET['musician'];
    $_v_lyricist = $_GET['lyricist'];
    $_v_raga     = $_GET['raga'];
    $_v_singername  = $_GET['singers'];
    $_v_genre    = $_GET['genre'];
    $_v_year     = $_GET['year'];
    $_v_actors   = $_GET['actor'];
    $_v_story   = $_GET['story'];
    $_v_screenplay   = $_GET['screenplay'];
    $_v_producer = $_GET['producer'];
    $_v_director = $_GET['director'];
    $_v_bgm = $_GET['bgm'];


    $moviename  = "Movie Name";
    $albumname  = "Album Name";
    $moviestate = "Movie Status";

    $singstate = 'Song Status';
    $sing_all = 'All';
    $sing_duet = 'Duets';
    $sing_solo = 'Solos';

    $songname   = "Song Name";
    $stat_all = "All";
    $stat_released = "Released";
    $stat_unreleased = "Unreleased";
    $stat_dubbed = "Dubbed";
    $stat_prod = "In Production";


    $ph_moviename = "Movie Name";
    $ph_albumname = "Album Name";
    $ph_story = "Name of the Story Writer";
    $ph_songname = "Starting Words";
    $ph_musician = "Musician Name";
    $ph_lyricist = "Lyricist Name";
    $ph_singer   = "Singer Names";
    $ph_actors   = "Actor Names";

    $ph_director = "Director Name";
    $ph_producer = "Producer Name";
    $ph_bgm      = "Background Musician";
    $ph_screenplay    = "Name of the Screenplay Writer";
    $ph_genre    = "Select a Genre";
    $ph_raga     = "Raga of the Song";
    $ph_label    = "Production Company";

    $act_msg = "Use comma to separate more than one actors";
    $sing_msg = "Use comma to separate more than one singers";
    $yr_msg = "Use 3 characters for decade - Ex: 198 for 1980s";
    $find = "Find";
    $label = 'Label';
    $profile = "Profiles";
    if ($_GET['lang'] != 'E'){
	$ph_moviename = get_uc($ph_moviename,'');
	$ph_albumname = get_uc($ph_albumname,'');
	$ph_story = get_uc($ph_story,'');
	$ph_songname = get_uc($ph_songname,'');
	$ph_musician = get_uc($ph_musician,'');
	$ph_lyricist = get_uc($ph_lyricist,'');
	$ph_singer = get_uc($ph_singer,'');
	$ph_actors = get_uc($ph_actors,'');
	$ph_label  = get_uc($ph_label,'');

	$ph_raga = get_uc($ph_raga,'');
	$ph_director = get_uc($ph_director,'');
	$ph_bgm = get_uc($ph_bgm,'');
	$ph_screenplay = get_uc($ph_screenplay,'');
	$ph_producer = get_uc($ph_producer,'');
	$ph_director = get_uc($ph_director,'');
	$ph_genre = get_uc($ph_genre,'');

	$act_msg = get_uc($act_msg,'');
	$sing_msg = get_uc($sing_msg,'');
	$yr_msg = get_uc($yr_msg,'');
	$stat_all = get_uc($stat_all,'');

	$sing_all = get_uc($sing_all,'');
	$sing_duet = get_uc($sing_duet,'');
	$sing_solo = get_uc($sing_solo,'');


	$stat_released = get_uc($stat_released,'');
	$stat_unreleased = get_uc($stat_unreleased,'');
	$stat_dubbed = get_uc($stat_dubbed,'');
	$stat_prod = get_uc($stat_prod,'');
	$moviename = get_uc($moviename, '');
	$albumname = get_uc($albumname, '');
	$songname = get_uc($songname, '');
	$singer = get_uc($singer,'');
	$moviestate = get_uc($moviestate,'');
	$singstate = get_uc($singstate,'');

	$composer = get_uc($composer,'');
	$lyricist = get_uc($lyricist,'');
	$year = get_uc($year,'');
	$find = get_uc($find,'');
	$video = get_uc($video,'');
	$audio = get_uc($audio,'');
	$profile = get_uc($profile,'');
	$karaoke = get_uc($karaoke,'');

	$availability = get_uc($availability,'');
	$mavailability = get_uc($mavailability,'');
	$nonavailability = get_uc($nonavailability,'');
	$lyrics = get_uc($lyrics,'');
	$mlyrics = get_uc($mlyrics,'');
	$director = get_uc($director,'');
	$genre = get_uc($genre,'');
	$producer = get_uc($producer,'');
	$label = get_uc($label,'');
	$actor = get_uc($actor,'');


	$raga = get_uc($raga,'');
	$bgm = get_uc($bgm,'');
	$story = get_uc($story,'');
	$screenplay = get_uc($screenplay,'');
	$movies = get_uc($movies,'');
	$moviesongs = get_uc($moviesongs,'');
	$albums = get_uc($albums,'');
	$albumsongs = get_uc($albumsongs,'');
	$allsongs = get_uc($allsongs,'');
	$databases = get_uc($databases,'');
	$search_articles = get_uc($search_articles, '');
//	$search_audio = get_uc($search_audio,'');
	$search_type = get_uc($search_type,'');

	$songbooks = get_uc($songbooks,'');
	$promos = get_uc($promos,'');
	$posters = get_uc($posters,'');
	$reviews = get_uc($reviews,'');
    }

//    $search_audio = "<a href=\"GLV.php\" target=\"_new\">$search_audio</a>";

      $search_script = $_Master_search;
    echo "<form method=post action=$_Master_search_process>\n";
    echo "<input type=hidden name=db value=$db>\n";
    echo "<P><table width=100% class=ptables>\n";
    echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";
    echo "<tr>";


    if ($db == "movies"){

        echo "<td align=left>";
    	echo "<tr><td align=left class=psubheadinghi>&nbsp; <input type=radio name=db value='movies' onclick='javascript:location.replace(\"$searchScript?db=movies\");' checked>&nbsp;$movies \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='moviesongs' onclick='javascript:location.replace(\"$searchScript?db=moviesongs\");'>&nbsp;$moviesongs \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='albums' onclick='javascript:location.replace(\"$searchScript?db=albums\");'>&nbsp;$albums \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='albumsongs' onclick='javascript:location.replace(\"$searchScript?db=albumsongs\");'>&nbsp;$albumsongs\n";
	echo "</td></tr><tr>";

	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";

	echo "<td align=left>";
	echo "<tr><td> <input type=checkbox name=search_type value=1>&nbsp;&nbsp;$search_type\n";
	echo "<td> <input type=checkbox name=profiles value=1>&nbsp;&nbsp;$profile\n";
	echo "</td><td> <input type=checkbox name=articles value=1>&nbsp;&nbsp;$search_articles\n";
//	echo "</td><td> <input type=checkbox name=audiofiles value=1>&nbsp;&nbsp;$search_audio\n";
	echo "</td></tr>";

	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";

	echo "<tr><td width=20% align=left>$moviename</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=moviename value=\"$_v_moviename\" onfocus=\"javascript:this.value=''\" placeholder=\"$ph_moviename\"></div></td>\n";
	echo "<td align=left width=20%>$composer</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=musician id=\"composer\" value=\"$_v_musician\" placeholder=\"$ph_musician\"></div></td></tr>\n";

	echo "<tr bgcolor=#eeeeee><td align=left>$lyricist</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=\"lyricist\" size=20 name=lyricist value=\"$_v_lyricist\" placeholder=\"$ph_lyricist\"></div></td>\n";
	echo "<td align=left>$bgm</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=bgm id=\"bgm\" placeholder=\"$ph_bgm\" value=\"$_v_bgm\"></td></div></tr>\n";
//	echo "<td align=left>$producer</td>\n";
//	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=\"producer\" type=text size=20 id=\"producer\" name=\"producer\" placeholder=\"$ph_producer\"></td></tr>\n";

//	echo "<tr><td align=left>$year<br><div class=ptextsmaller>$yr_msg</div></td>\n";
//	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=year></div></td>\n";

	echo "<tr><td align=left>$producer</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=\"producer\" type=text size=20 id=\"producer\" value=\"$_v_producer\" name=\"producer\" placeholder=\"$ph_producer\"></td>\n";
	echo "<td align=left>$director</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=director value=\"$_v_director\" id=\"director\" placeholder=\"$ph_director\"></div></td></tr>\n";

//	echo "<tr><td align=left>$actor<br><div class=ptextsmaller>$act_msg</div></td>\n";
//	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=actor id=actors value=\"$_v_actors\" placeholder=\"$ph_actors\"></div></td>\n";

//	echo "<tr><td align=left>$moviestate</td>\n";
//	echo "<td align=left>\n";
//	echo "<select name=moviestatus>\n";
//	echo "<option value=All>$stat_all</option>\n";
//	echo "<option value=Released>$stat_released</option>\n";
//	echo "<option value=Unreleased>$stat_unreleased</option>\n";
//	echo "<option value=Dubbed>$stat_dubbed</option>\n";
//	echo "<option value=InProduction>$stat_prod</option>\n";
//	echo "</select>\n";
//	echo "</td>\n";

//	echo "<td align=left>$bgm</td>\n";
//	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=bgm id=\"bgm\" value=\"$_v_bgm\" placeholder=\"$ph_bgm\"></td></div></tr>\n";

	echo "<tr bgcolor=#eeeeee><td align=left>$story</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=story id=\"story\" value=\"$_v_story\" placeholder=\"$ph_story\"></div></td>\n";
	echo "<td align=left>$screenplay</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=screenplay id=\"screenplay\" value=\"$_v_screenplay\" placeholder=\"$ph_screenplay\"></div></td></tr>\n";

	echo "<tr><td align=left>$year<br><div class=ptextsmaller>$yr_msg</div></td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=year value=\"$_v_year\"></div></td>\n";
	echo "<td align=left>$moviestate</td>\n";
	echo "<td align=left>\n";
	echo "<select name=moviestatus>\n";
	echo "<option value=All>$stat_all</option>\n";
	echo "<option value=Released>$stat_released</option>\n";
	echo "<option value=Unreleased>$stat_unreleased</option>\n";
	echo "<option value=Dubbed>$stat_dubbed</option>\n";
	echo "<option value=InProduction>$stat_prod</option>\n";
	echo "</select>\n";
	echo "</td></tr>\n";

	echo "<tr bgcolor=#eeeeee><td align=left>$actor<br><div class=ptextsmaller>$act_msg</div></td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input type=text size=50 name=actor id=actors value=\"$_v_actors\" placeholder=\"$ph_actors\"></div></td>\n";
	echo "</td></tr>";


	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4> <b>$mavailability </b> <input type=checkbox name=promos value=1>&nbsp;&nbsp;$promos |\n";
	echo "<input type=checkbox name=songbooks value=1>&nbsp;&nbsp;&nbsp;&nbsp;$songbooks | \n";
	echo "<input type=checkbox name=reviews value=1>&nbsp;&nbsp;$reviews | \n";
	echo "<input type=checkbox name=posters value=1>&nbsp;&nbsp;$posters | \n";
	echo "</td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4>\n";


	echo "<tr><td colspan=4 align=center>\n";
    }
    else if ($db == "albums"){


        echo "<td align=left>";
    	echo "<tr><td align=left class=psubheading>&nbsp; <input type=radio name=db value='movies' onclick='javascript:location.replace(\"$searchScript?db=movies\");' >&nbsp;$movies \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='moviesongs' onclick='javascript:location.replace(\"$searchScript?db=moviesongs\");'>&nbsp;$moviesongs \n";
	echo "</td><td align=left class=psubheadinghi>&nbsp; <input type=radio name=db value='albums' onclick='javascript:location.replace(\"$searchScript?db=albums\");' checked>&nbsp;$albums \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='albumsongs' onclick='javascript:location.replace(\"$searchScript?db=albumsongs\");'>&nbsp;$albumsongs\n";
	echo "</td></tr><tr>";
	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";



	echo "<td align=left>";
	echo "<tr><td> <input type=checkbox name=search_type value=1>&nbsp;&nbsp;$search_type \n";
	echo "<td> <input type=checkbox name=profiles value=1>&nbsp;&nbsp;$profile\n";
	echo "</td><td> <input type=checkbox name=articles value=1>&nbsp;&nbsp;$search_articles\n";
//	echo "</td><td> <input type=checkbox name=audiofiles value=1>&nbsp;&nbsp;$search_audio\n";
	echo "</td></tr>";

	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";

	echo "<tr><td align=left width=20%>$albumname</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=albumname placeholder=\"$ph_albumname\" value=\"$_v_moviename\"></div></td>\n";
	echo "<td align=left width=20%>$composer</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text id=\"composer\" size=20 name=musician placeholder=\"$ph_musician\" value=\"$_v_musician\"></div></td></tr>\n";
	
	echo "<tr bgcolor=#eeeeee><td align=left>$lyricist</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=\"lyricist\" type=text size=20 name=lyricist placeholder=\"$ph_lyricist\" value=\"$_v_lyricist\"></div></td>\n";
	echo "<td align=left>$genre</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=\"genre\" type=text size=20 name=\"genre\" placeholder=\"$ph_genre\" value=\"$_v_genre\"></div></td></tr>\n";
	
	echo "<tr><td align=left>$year<br><div class=ptextsmaller>$yr_msg</div></td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=year></div></td>\n";
	echo "<td align=left>$label</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 id=\"labels\" name=director placeholder=\"$ph_label\" value=\"$_v_director\"></div></td></tr>\n";

	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4 align=center>\n";
    }
    else if ($db == "moviesongs"){

        echo "<td align=left>";
    	echo "<tr><td align=left class=psubheading>&nbsp; <input type=radio name=db value='movies' onclick='javascript:location.replace(\"$searchScript?db=movies\");' >&nbsp;$movies \n";
	echo "</td><td align=left class=psubheadinghi>&nbsp; <input type=radio name=db value='moviesongs' onclick='javascript:location.replace(\"$searchScript?db=moviesongs\");' checked>&nbsp;$moviesongs \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='albums' onclick='javascript:location.replace(\"$searchScript?db=albums\");' >&nbsp;$albums \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='albumsongs' onclick='javascript:location.replace(\"$searchScript?db=albumsongs\");'>&nbsp;$albumsongs\n";
	echo "</td></tr>";
	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";

	echo "<td align=left>";
	echo "<tr><td> <input type=checkbox name=search_type value=1>&nbsp;&nbsp;$search_type \n";
	echo "<td> <input type=checkbox name=profiles value=1>&nbsp;&nbsp;$profile\n";
	echo "</td><td> <input type=checkbox name=articles value=1>&nbsp;&nbsp;$search_articles\n";
//	echo "</td><td> <input type=checkbox name=audiofiles value=1>&nbsp;&nbsp;$search_audio\n";
	echo "</td></tr>";

	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";

	echo "<tr><td width=20% align=left>$songname</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=songname type=text size=20 name=songname placeholder=\"$ph_songname\" value=\"$_v_songname\"></div></td>\n";
	echo "<td align=left width=20%>$singer<br><div class=ptextsmaller>$sing_msg</div></td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 id=singers name=singers id=\"singers\" placeholder=\"$ph_singer\" value=\"$_v_singername\"></div></td></tr>\n";

	echo "<tr bgcolor=#eeeeee><td align=left>$moviename</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=moviename type=text size=20 name=moviename onfocus=\"javascript:this.value=''\" value=\"$_v_moviename\" placeholder=\"$ph_moviename\"></div></td>\n";


	echo "<td align=left>$singstate</td>\n";
	echo "<td align=left>\n";
	echo "<select name=singstatus>\n";
	echo "<option value=All>$sing_all</option>\n";
	echo "<option value=Duets>$sing_duet</option>\n";
	echo "<option value=Solos>$sing_solo</option>\n";
	echo "</select>\n";
	echo "</td></tr>";

//	echo "<td align=left>$moviestate</td>\n";
//	echo "<td align=left>\n";
//	echo "<select name=moviestatus>\n";
//	echo "<option value=All>$stat_all</option>\n";
//	echo "<option value=Released>$stat_released</option>\n";
//	echo "<option value=Unreleased>$stat_unreleased</option>\n";
//	echo "<option value=Dubbed>$stat_dubbed</option>\n";
//	echo "<option value=InProduction>$stat_prod</option>\n";
//	echo "</select>\n";
//	echo "</td>\n";
//	echo "</td></tr>";

	echo "<tr><td align=left>$lyricist</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=lyricist id=\"lyricist\" placeholder=\"$ph_lyricist\" value=\"$_v_lyricist\"></div></td>\n";
	echo "<td align=left>$composer</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=musician id=\"composer\" placeholder=\"$ph_musician\" value=\"$_v_musician\"></div></td></tr>\n";

	echo "<tr bgcolor=#eeeeee><td align=left>$genre</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=genre id=\"genre\" placeholder=\"$ph_genre\" value=\"$_v_genre\"></div></td>\n";
	echo "<td align=left>$raga</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=\"raga\" type=text size=20 name=\"raga\" placeholder=\"$ph_raga\" value=\"$_v_raga\"></div></td></tr>\n";
	
	echo "<tr><td align=left>$year<br><div class=ptextsmaller>$yr_msg</div></td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=year value=\"$_v_year\"></div></td>\n";
	echo "<td align=left>$moviestate</td>\n";
	echo "<td align=left>\n";
	echo "<select name=moviestatus>\n";
	echo "<option value=All>$stat_all</option>\n";
	echo "<option value=Released>$stat_released</option>\n";
	echo "<option value=Unreleased>$stat_unreleased</option>\n";
	echo "<option value=Dubbed>$stat_dubbed</option>\n";
	echo "<option value=InProduction>$stat_prod</option>\n";
	echo "</select>\n";
	echo "</td>\n";
	echo "</td></tr>";

	echo "<tr bgcolor=#eeeeee><td align=left>$actor<br><div class=ptextsmaller>$act_msg</div></td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input type=text id=\"actors\" size=50 name=actor value=\"$_v_actors\" placeholder=\"$ph_actors\"></div></td></tr>\n";

	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4> <b>$availability </b> <input type=checkbox name=video value=1>&nbsp;&nbsp;$video |\n";
	echo "<input type=checkbox name=audio value=1>&nbsp;&nbsp;&nbsp;&nbsp;$audio | \n";
	echo "<input type=checkbox name=karaoke value=1>&nbsp;&nbsp;$karaoke | \n";
	echo "<input type=checkbox name=lyrics value=1>&nbsp;&nbsp;$lyrics | \n";
	echo "<input type=checkbox name=mlyrics value=1>&nbsp;&nbsp;$mlyrics \n";
	echo "</td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4> <b>$nonavailability </b> <input type=checkbox name=n_video value=1>&nbsp;&nbsp;$video |\n";
	echo "<input type=checkbox name=n_audio value=1>&nbsp;&nbsp;&nbsp;&nbsp;$audio | \n";
	echo "<input type=checkbox name=n_karaoke value=1>&nbsp;&nbsp;$karaoke | \n";
	echo "<input type=checkbox name=n_lyrics value=1>&nbsp;&nbsp;$lyrics | \n";
	echo "<input type=checkbox name=n_mlyrics value=1>&nbsp;&nbsp;$mlyrics \n";
	echo "</td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4 align=center>\n";
    }
    else if ($db == "albumsongs") {

        echo "<td align=left>";
    	echo "<tr><td align=left class=psubheading>&nbsp; <input type=radio name=db value='movies' onclick='javascript:location.replace(\"$searchScript?db=movies\");' >&nbsp;$movies \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='moviesongs' onclick='javascript:location.replace(\"$searchScript?db=moviesongs\");' >&nbsp;$moviesongs \n";
	echo "</td><td align=left class=psubheading>&nbsp; <input type=radio name=db value='albums' onclick='javascript:location.replace(\"$searchScript?db=albums\");' >&nbsp;$albums \n";
	echo "</td><td align=left class=psubheadinghi>&nbsp; <input type=radio name=db value='albumsongs' onclick='javascript:location.replace(\"$searchScript?db=albumsongs\");' checked>&nbsp;$albumsongs\n";
	echo "</td></tr>";
	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";


	echo "<td align=left>";
	echo "<tr><td> <input type=checkbox name=search_type value=1>&nbsp;&nbsp;$search_type \n";
	echo "<td> <input type=checkbox name=profiles value=1>&nbsp;&nbsp;$profile\n";
	echo "</td><td> <input type=checkbox name=articles value=1>&nbsp;&nbsp;$search_articles\n";
//	echo "</td><td> <input type=checkbox name=audiofiles value=1>&nbsp;&nbsp;$search_audio\n";
	echo "</td></tr>";
	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";

	echo "<tr><td align=left width=20%>$songname</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=songname type=text size=20 name=songname placeholder=\"$ph_songname\" value=\"$_v_songname\"></div></td>\n";
	echo "<td align=left width=20%>$albumname</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=albumname placeholder=\"$ph_albumname\" value=\"$_v_moviename\"></div></td>\n";
	echo "</td></tr>";

//	echo "<tr bgcolor=#eeeeee><td align=left>$singer</td>\n";
	echo "<tr bgcolor=#eeeeee><td align=left>$singer<br><div class=ptextsmaller>$sing_msg</div></td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=singers type=text size=20 name=singers id=singers placeholder=\"$ph_singer\" value=\"$_v_singername\"></div></td>\n";

	echo "<td align=left>$singstate</td>\n";
	echo "<td align=left>\n";
	echo "<select name=singstatus>\n";
	echo "<option value=All>$sing_all</option>\n";
	echo "<option value=Duets>$sing_duet</option>\n";
	echo "<option value=Solos>$sing_solo</option>\n";
	echo "</select>\n";
	echo "</td></tr>";



	echo "<tr><td align=left>$lyricist</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text id=\"lyricist\" size=20 name=lyricist placeholder=\"$ph_lyricist\" value=\"$_v_lyricist\"></div></td>\n";
	echo "<td align=left>$composer</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=musician id=\"composer\" placeholder=\"$ph_musician\" value=\"$_v_musician\"></div></td></tr>\n";

	echo "<tr bgcolor=#eeeeee><td align=left>$raga</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input id=\"raga\" type=text size=20 id=\"raga\" name=\"raga\" placeholder=\"$ph_raga\" value=\"$_v_raga\"></div></td>\n";
	echo "<td align=left>$genre</td>\n";
	echo "<td align=left><div class=\"box\" id=\"examples\"><input type=text size=20 name=genre id=\"genre\" placeholder=\"$ph_genre\" value=\"$_v_genre\"></div></td></tr>\n";

	echo "<tr><td align=left>$year<br><div class=ptextsmaller>$yr_msg</div></td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input type=text size=20 value=\"$_v_year\" name=year></div></td></tr>\n";

	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4> <b>$availability </b> <input type=checkbox name=video value=1>&nbsp;&nbsp;$video |\n";

	echo "<input type=checkbox name=audio value=1>&nbsp;&nbsp;$audio | \n";
	echo "&nbsp;";
//	echo "<input type=checkbox name=karaoke value=1>&nbsp;&nbsp;$karaoke | \n";
	echo "<input type=checkbox name=lyrics value=1>&nbsp;&nbsp;$lyrics | \n";
	echo "<input type=checkbox name=mlyrics value=1>&nbsp;&nbsp;$mlyrics \n";
	echo "</td></tr>\n";
	echo "<tr bgcolor=#3D352A><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4> <b>$nonavailability </b> <input type=checkbox name=n_video value=1>&nbsp;&nbsp;$video |\n";
	echo "<input type=checkbox name=n_audio value=1>&nbsp;&nbsp;&nbsp;&nbsp;$audio | \n";
//	echo "<input type=checkbox name=n_karaoke value=1>&nbsp;&nbsp;$karaoke | \n";
	echo "<input type=checkbox name=n_lyrics value=1>&nbsp;&nbsp;$lyrics | \n";
	echo "<input type=checkbox name=n_mlyrics value=1>&nbsp;&nbsp;$mlyrics \n";
	echo "</td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td align=left colspan=4>&nbsp;</td></tr>\n";
	echo "<tr><td colspan=4 align=center>\n";
    }

    echo "<P><input type=submit style=\"font-size:2em;width:20%\" value=$find>\n";
    echo "</td></tr></table>";
    echo "</form>";
    printFancyFooters();
    mysql_close($cLink);

}

?>
