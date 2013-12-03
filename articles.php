<?php session_start();
{
    error_reporting (E_ERROR);
    require_once("_includes/_data.php");
    require_once("_includes/_xtemplate_header.php");
    require_once("_includes/_moviePageUtils.php");

    require_once("includes/utils.php");

    $_GET['encode']='utf';
    $input = $_GET['i'];

    $cLink = msi_dbconnect();
    printXHeader('');
  


    mysql_query("SET NAMES utf8");
    
//      Articles
        printArticleList();
//      

    printFancyFooters();
    mysql_close($cLink);

}

?>
