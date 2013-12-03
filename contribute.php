<?php session_start();


{
    error_reporting (E_ERROR);
    require_once("includes/utils.php");
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    $_GET['lang'] = $_SESSION['lang'];
    require_once("_includes/_moviePageUtils.php");

    $cLink = msi_dbconnect();
    printXHeader('');
    /*	   Find Counts of Lyrics Needed (Both English and Unicode), Clips Needed, Videos Needed, Posters Needed for both Movies and Albums   */

    $q1 = "SELECT COUNT(S_ID) as ccn from SONGS WHERE S_LYR  != 'Y'";
    $q1v = "SELECT COUNT(S_ID) as ccn FROM SONGS,UTUBE WHERE SONGS.S_ID=UTUBE.UT_ID and (SONGS.S_LYR='N' and UTUBE.UT_STAT='Published')";
    $q2 = "SELECT COUNT(S_ID) as ccn from SONGS WHERE S_MLYR != 'Y'";
    $q2v = "SELECT COUNT(S_ID) as ccn FROM SONGS,UTUBE WHERE SONGS.S_ID=UTUBE.UT_ID and (SONGS.S_MLYR='N' and UTUBE.UT_STAT='Published')";
    $q3 = "SELECT COUNT(S_ID) as ccn from SONGS WHERE S_CLIP != 'Y'";
    $q4 = "SELECT COUNT(MOVIES.M_ID) as ccn FROM MOVIES LEFT JOIN PICTURES ON MOVIES.M_ID = PICTURES.M_ID WHERE PICTURES.M_ID IS NULL";
    $q5 = "SELECT COUNT(SONGS.S_ID)  as ccn FROM SONGS LEFT JOIN UTUBE  ON UTUBE.UT_ID = SONGS.S_ID WHERE (UTUBE.UT_ID IS NULL or UTUBE.UT_URL = \"\" or UTUBE.UT_STAT != 'Published')";
    $q6 = "SELECT COUNT(MOVIES.M_ID) as ccn FROM MOVIES LEFT JOIN MD_LINKS  ON MD_LINKS.M_ID = MOVIES.M_ID WHERE MD_LINKS.M_ID IS NULL";

    $q7 = "SELECT COUNT(S_ID) as ccn from ASONGS WHERE S_LYR  != 'Y'";
    $q8 = "SELECT COUNT(S_ID) as ccn from ASONGS WHERE S_MLYR != 'Y'";
    $q9 = "SELECT COUNT(S_ID) as ccn from ASONGS WHERE S_CLIP != 'Y'";
    $q10 = "SELECT COUNT(ALBUMS.M_ID) as ccn FROM ALBUMS LEFT JOIN APICTURES ON ALBUMS.M_ID = APICTURES.M_ID WHERE APICTURES.M_ID IS NULL";
    $q11 = "SELECT COUNT(ASONGS.S_ID)  as ccn FROM ASONGS LEFT JOIN ALBUM_UTUBE  ON ALBUM_UTUBE.UT_ID = ASONGS.S_ID WHERE (ALBUM_UTUBE.UT_ID IS NULL or ALBUM_UTUBE.UT_URL = \"\" or ALBUM_UTUBE.UT_STAT != 'Published')";
    echo( "<table class=ptables>\n");
    echo ("<tr><td valign=top width=60%>\n");
    echo( "<table class=ptables>\n");
    printDetailHeadingRows ('Movies','2');
    printDetailListingRows ('Manglish Lyrics',runQuery($q1,'ccn'),"$_Master_songlist_script?tag=Search&missing=ml");
    printDetailListingRows ('No Lyrics and Videos Available',runQuery($q1v,'ccn'),"$_Master_songlist_script?tag=Search&missing=mlv");
    printDetailListingRows ('Unicode Lyrics',runQuery($q2,'ccn'),"$_Master_songlist_script?tag=Search&missing=ul");
    printDetailListingRows ('No Unicode Lyrics and Videos Available',runQuery($q2v,'ccn'),"$_Master_songlist_script?tag=Search&missing=ulv");
    printDetailListingRows ('Audio Clips',runQuery($q3,'ccn'),"$_Master_songlist_script?tag=Search&missing=audio");
    printDetailListingRows ('Pictures',runQuery($q4,'ccn'),"$_Master_movielist_script?tag=Search&missing=pictures");
    printDetailListingRows ('Videos',runQuery($q5,'ccn'),"$_Master_songlist_script?tag=Search&missing=video");
    printDetailListingRows ('Reviews',runQuery($q6,'ccn'),"$_Master_movielist_script?tag=Search&missing=reviews");

    printDetailHeadingRows ('Albums','2');
    printDetailListingRows ('Manglish Lyrics',runQuery($q7,'ccn'),"$_Master_albumsonglist_script?tag=Search&missing=ml");
    printDetailListingRows ('Unicode Lyrics',runQuery($q8,'ccn'),"$_Master_albumsonglist_script?tag=Search&missing=ul");
    printDetailListingRows ('Audio Clips',runQuery($q9,'ccn'),"$_Master_albumsonglist_script?tag=Search&missing=audio");
    printDetailListingRows ('Pictures',runQuery($q10,'ccn'),"$_Master_albumlist_script?tag=Search&missing=pictures");
    printDetailListingRows ('Videos',runQuery($q11,'ccn'),"$_Master_albumsonglist_script?tag=Search&missing=video");
    echo ("</table>");
    echo ("</td><td valign=top width=40%>\n");
//    printGoogleFriendConnect();

    echo ("</td></tr></table>\n");

    mysql_close($cLink);

    printHtmlContents("_includes/_Footer.html");
}

?>



