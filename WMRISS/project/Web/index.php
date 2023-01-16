<pre style="white-space: pre-wrap; word-wrap: break-word;">
<?php
global $WMRISS_JsonDump;
echo var_export($WMRISS_JsonDump,true);
$WMRISS_HOSTPATH = "127.0.0.1:12200";
echo "<br>";
$WMRISS_ArrayKeyDump = array_keys($WMRISS_JsonDump);
print_r($WMRISS_ArrayKeyDump);
$api_json = json_decode($WMRISS_JsonDump[0]);
?>
</pre>