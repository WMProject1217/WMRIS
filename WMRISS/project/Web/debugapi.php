<?php
global $WMRISS_JsonDump;
$WMRISS_ArrayKeyDump = array_keys($WMRISS_JsonDump);             
foreach ($WMRISS_ArrayKeyDump as $WMRISS_TEMP_NOWID) {
$api_json = json_decode($WMRISS_JsonDump[$WMRISS_TEMP_NOWID]);
echo "[$WMRISS_TEMP_NOWID]Name: $api_json->name ,Type: $api_json->type ,Uptime: $api_json->uptime ,CPU: ".$api_json->cpucoreval."C".$api_json->cputhreadval."T ($api_json->cpuusage) ,RAM: $api_json->memused / $api_json->memtotal ($api_json->memusage) ,DISK: $api_json->diskused / $api_json->disktotal ($api_json->diskusage)\n";
}
?>