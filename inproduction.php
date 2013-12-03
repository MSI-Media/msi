<?php
 require_once("_includes/_data.php");
    location_replace("$_Master_search_process?db=movies&moviestatus=InProduction");

function location_replace($loc){
    echo "<script>location.replace(\"$loc\");</script>";
}

?>
