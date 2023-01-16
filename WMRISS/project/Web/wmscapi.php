<?php
global $WMRISS_JsonDump;
$WMRISS_ArrayKeyDump = array_keys($WMRISS_JsonDump);             
echo $WMRISS_JsonDump[$_GET['id']];
?>