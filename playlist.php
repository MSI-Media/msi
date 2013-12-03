<?php session_start();
{
    error_reporting (E_ERROR);


    require_once("includes/utils.php");

    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_searchUtils.php");
    require_once("_includes/_moviePageUtils.php");

    $_GET['encode']='utf';




    $cLink = msi_dbconnect();
    mysql_query("SET NAMES utf8");
    printXHeader('');

    $db = $_GET['db'];
    if (!$db) { $db = 'videosongs'; }

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
    $search_audio    = "Search Gaanalokaveedhikalil";
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
    $find = "Create Playlists";
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
	$search_audio = get_uc($search_audio,'');
	$search_type = get_uc($search_type,'');

	$songbooks = get_uc($songbooks,'');
	$promos = get_uc($promos,'');
	$posters = get_uc($posters,'');
	$reviews = get_uc($reviews,'');
    }


    echo "<form method=post action=$_Master_search_process>\n";
    echo "<input type=hidden name=db value=$db>\n";
    echo "<table class=ptables>\n";

    if ($db == "videosongs"){

//	echo "<tr><td valign=top colspan=2><div class=tableheader> $find </div></td></tr>\n";
	echo "<tr><td colspan=4> <input type=checkbox name=search_type value=1>&nbsp;&nbsp;$search_type \n";
	echo "</td></tr>";

	echo "<tr bgcolor=#eeeeee><td align=left colspan=5>&nbsp;</td></tr>\n";

	echo "<tr><td colspan=2 width=50% align=left>$songname</td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input id=songname type=text size=50 name=songname placeholder=\"$ph_songname\" value=\"$_v_songname\"></div></td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td colspan=2 align=left>$moviename</td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input id=moviename type=text size=50 name=moviename onfocus=\"javascript:this.value=''\" value=\"$_v_moviename\" placeholder=\"$ph_moviename\"></div></td></tr>\n";
	echo "<tr><td align=left colspan=2 width=50%>$singer<br><div class=ptextsmaller>$sing_msg</div></td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input type=text size=50 id=singers name=singers id=\"singers\" placeholder=\"$ph_singer\" value=\"$_v_singername\"></div></td></tr>\n";




	echo "<tr bgcolor=#eeeeee><td colspan=2 align=left>$singstate</td>\n";
	echo "<td align=left colspan=3>\n";
	echo "<select name=singstatus>\n";
	echo "<option value=All>$sing_all</option>\n";
	echo "<option value=Duets>$sing_duet</option>\n";
	echo "<option value=Solos>$sing_solo</option>\n";
	echo "</select>\n";
	echo "</td></tr>";

	echo "<tr><td colspan=2 align=left>$lyricist</td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input type=text size=50 name=lyricist id=\"lyricist\" placeholder=\"$ph_lyricist\" value=\"$_v_lyricist\"></div></td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td colspan=2 align=left>$composer</td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input type=text size=50 name=musician id=\"composer\" placeholder=\"$ph_musician\" value=\"$_v_musician\"></div></td></tr>\n";

	echo "<tr><td align=left colspan=2 >$genre</td>\n";
	echo "<td align=left colspan=3><div class=\"box\" id=\"examples\"><input type=text size=50 name=genre id=\"genre\" placeholder=\"$ph_genre\" value=\"$_v_genre\"></div></td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td colspan=2 align=left>$raga</td>\n";
	echo "<td align=left colspan=3><div class=\"box\" id=\"examples\"><input id=\"raga\" type=text size=50 name=\"raga\" placeholder=\"$ph_raga\" value=\"$_v_raga\"></div></td></tr>\n";
	
	echo "<tr><td align=left colspan=2>$year<br><div colspan=2 class=ptextsmaller>$yr_msg</div></td>\n";
	echo "<td align=left colspan=3><div class=\"box\" id=\"examples\"><input type=text size=50 name=year value=\"$_v_year\"></div></td></tr>\n";
	echo "<tr bgcolor=#eeeeee><td align=left colspan=2 >$moviestate</td>\n";
	echo "<td  align=left colspan=3>\n";
	echo "<select name=moviestatus>\n";
	echo "<option value=All>$stat_all</option>\n";
	echo "<option value=Released>$stat_released</option>\n";
	echo "<option value=Unreleased>$stat_unreleased</option>\n";
	echo "<option value=Dubbed>$stat_dubbed</option>\n";
	echo "<option value=InProduction>$stat_prod</option>\n";
	echo "</select>\n";
	echo "</td>\n";
	echo "</td></tr>";

	echo "<tr><td align=left colspan=2 >$actor<br><div class=ptextsmaller>$act_msg</div></td>\n";
	echo "<td colspan=3 align=left><div class=\"box\" id=\"examples\"><input type=text id=\"actors\" size=50 name=actor value=\"$_v_actors\" placeholder=\"$ph_actors\"></div></td></tr>\n";

    }
    echo "<tr><td colspan=2>&nbsp;</td></tr>";
    echo "<tr><td colspan=2><input type=submit value=\"$find\"></td></tr>\n";
    echo "</table>";
    echo "</form>";
    echo "<table class=ptables><tr><td align=left valign=top >\n";
    printCustomPlaylists("Available Playlists");
    echo "</td></tr></table>";	
    printFancyFooters();
    mysql_close($cLink);

}
function printCustomPlaylists($titl){

	 global $_Master_playlist_file;
    if ($_GET['lang'] != 'E') { $titl = get_uc($titl,''); }
    echo "<div class=tableheader> $titl </div>\n";
    $artfile = $_Master_playlist_file;
    $fh = fopen($artfile, "r");
    if ($fh){  
	while (!feof($fh)){
	    $lx = fgets($fh,1048576);
	    $lx = ltrim(rtrim($lx));
	    $vals = explode ('|',$lx);
	    if ($vals[0] != '' && $vals[1] != '' ){
		if ($_GET['lang'] != 'E') { $vals[0] = get_uc($vals[0],''); }
		echo "<img src=\"images/redarrow.gif\">&nbsp;<a href=\"$vals[1]\">$vals[0]</a><br>\n";
	    }
	}
    }
}


?>
