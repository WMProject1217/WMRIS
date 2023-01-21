<?php
global $WMRISS_JsonDump;
$WMRISS_ArrayKeyDump = array_keys($WMRISS_JsonDump);
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <link rel='icon' href='./favicon.ico' type='image/x-icon' />
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <link rel="stylesheet" href="./semantic.min.css">
    <title>WMRISS Server Status</title>
    <link rel="stylesheet" href="./index.css">
    <script>
        var getJSON = function(url) {
            return new Promise(function(resolve, reject) {
                var xhr = new XMLHttpRequest();
                xhr.open('get', url, true);
                xhr.responseType = 'json';
                xhr.onload = function() {
                    var status = xhr.status;
                    if (status == 200) {
                        resolve(xhr.response);
                    } else {
                        reject(status);
                    }
                };
                xhr.send();
            });
        };

        function remixListData(item, index, arr) {
            arr[index][0] = index * 2;
        }
    </script>
</head>

<body>
    <noscript>
        <strong>We're sorry but WMRISS doesn't work properly without JavaScript enabled. Please enable it to continue.</strong>
    </noscript>
    <script>
        var tableViewThTexts = [];
        $("#table tbody th").each(function() {
            tableViewThTexts.push($(this).text());
        });
        $("#table tbody tr").each(function() {
            var tableViewTds = $(this).find("td");
            for (var i = 0; i < tableViewTds.length; i++) {
                $(tableViewTds[i]).attr("data-label", tableViewThTexts[i]);
            }
        });
        function FormatizeSize(size) {
            var offst = 0;
            if (size < 0) {
                size = -size;
                offst = 1;
            }
            var sizer = size;
            var sizedd = "B";
            if (sizer > 1000) {
                sizer = sizer / 1024;
                sizedd = "KB";
                if (sizer > 1000) {
                    sizer = sizer / 1024;
                    sizedd = "MB";
                    if (sizer > 1000) {
                        sizer = sizer / 1024;
                        sizedd = "GB";
                        if (sizer > 1000) {
                            sizer = sizer / 1024;
                            sizedd = "TB";
                            if (sizer > 1000) {
                                sizer = sizer / 1024;
                                sizedd = "PB";
                                if (sizer > 1000) {
                                    sizer = sizer / 1024;
                                    sizedd = "EB";
                                }
                            }
                        }
                    }
                }
            }
            if (offst == 1) {
                sizer = -sizer;
            }
            return sizer.toFixed(2) + " " + sizedd;
        }
    </script>
    <div id="app" data-v-app="">
        <div class="ui vertical masthead center aligned" id="header">
            <div id="header-content">
                <h1 class="ui inverted header">Server Status</h1>
                <p>Servers' Probes Set up with WMRISS</p>
            </div>
        </div>
        <div class="container">
            <table class="ui basic unstackable table" id="table">
                <thead>
                    <tr>
                        <th data-label="运行状态" id="statusnow">运行状态</th>
                        <th data-label="设备名称" id="name">设备名称</th>
                        <th data-label="设备类型" id="type">设备类型</th>
                        <th data-label="设备位置" id="location">设备位置</th>
                        <th data-label="开机时长" id="uptime">开机时长</th>
                        <th data-label="CPU" id="cpu">CPU</th>
                        <th data-label="内存" id="ram">内存</th>
                        <th data-label="硬盘" id="hdd">硬盘</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    //{"status":"RUN","name":"POWEREDGER720.net.wm","type":"Windows Server 2012 R2","location":"Local Network","uptime":"11:45:14","cpucoreval":"8","cputhreadval":"16","cpuusage":"5.8%","memtotal":"31.96GB","memused":"4.83GB","memfree":"27.13GB","memusage":"15.1%","disktotal":"5.27TB","diskused":"4.68TB","diskusage":"86.78%"}                
                    foreach ($WMRISS_ArrayKeyDump as $WMRISS_TEMP_NOWID) {
                        $api_json = json_decode($WMRISS_JsonDump[$WMRISS_TEMP_NOWID]);
                        echo '<tr>
<td id="rtstatus_' . $WMRISS_TEMP_NOWID . '">' . $api_json->status . '</td>
<td id="name_' . $WMRISS_TEMP_NOWID . '">' . $api_json->nickname . '</td>
<td id="type_' . $WMRISS_TEMP_NOWID . '">' . $api_json->type . '</td>
<td id="location_' . $WMRISS_TEMP_NOWID . '">' . $api_json->location . '</td>
<td id="uptime_' . $WMRISS_TEMP_NOWID . '">' . $api_json->uptime . '</td>
<td><canvas id="cpucanvas_' . $WMRISS_TEMP_NOWID . '" width="160" height="50" style="border-style: solid; border-width: 1px;"></canvas><br><span id="cpucoreval_' . $WMRISS_TEMP_NOWID . '">' . $api_json->cpucoreval . '</span>C<span id="cputhreadval_' . $WMRISS_TEMP_NOWID . '">' . $api_json->cputhreadval . '</span>T <span id="cpuusage_' . $WMRISS_TEMP_NOWID . '">' . $api_json->cpuusage . '</span></td>
<td><canvas id="memcanvas_' . $WMRISS_TEMP_NOWID . '" width="160" height="50" style="border-style: solid; border-width: 1px;"></canvas><br><span id="memused_' . $WMRISS_TEMP_NOWID . '">' . $api_json->memused . '</span> / <span id="memtotal_' . $WMRISS_TEMP_NOWID . '">' . $api_json->memtotal . '</span> (<span id="memusage_' . $WMRISS_TEMP_NOWID . '">' . $api_json->memusage . '</span>)</td>
<td><canvas id="diskcanvas_' . $WMRISS_TEMP_NOWID . '" width="160" height="50" style="border-style: solid; border-width: 1px;"></canvas><br><span id="diskused_' . $WMRISS_TEMP_NOWID . '">' . $api_json->diskused . '</span> / <span id="disktotal_' . $WMRISS_TEMP_NOWID . '">' . $api_json->disktotal . '</span> (<span id="diskusage_' . $WMRISS_TEMP_NOWID . '">' . $api_json->diskusage . '</span>)</td>
</tr>';
                        echo "<script>";
                        echo 'window.cpupoints_' . $WMRISS_TEMP_NOWID . ' = [[0,50]];
window.mempoints_' . $WMRISS_TEMP_NOWID . ' = [[0,50]];
window.diskpoints_' . $WMRISS_TEMP_NOWID . ' = [[0,50]];
function requestListData_' . $WMRISS_TEMP_NOWID . '(genDateStart,genDateEnd,contrNo) {
    var strhostpath = window.location.href;
    strhostpath = strhostpath.substring(0,strhostpath.lastIndexOf(":") + 1) + location.port;;
    getJSON(strhostpath + "/out?id=' . $WMRISS_TEMP_NOWID . '").then(function(data) {
        console.log(data);
        if (data.status != "RUN") {
            document.getElementById("rtstatus_' . $WMRISS_TEMP_NOWID . '").innerHTML = "<div style=' . "'background-color: #EE0000;color: #FFFFFF;'" . '>故障</div>";
            return ;
        }
        document.getElementById("rtstatus_' . $WMRISS_TEMP_NOWID . '").innerHTML = "<div style=' . "'background-color: #00EE00;color: #FFFFFF;'" . '>运行</div>";;
        document.getElementById("name_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.nickname;
        document.getElementById("type_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.type;
        document.getElementById("location_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.location;
        document.getElementById("uptime_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.uptime;
        document.getElementById("cpucoreval_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.cpucoreval;
        document.getElementById("cputhreadval_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.cputhreadval;
        document.getElementById("cpuusage_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.cpuusage + "%";
        document.getElementById("memtotal_' . $WMRISS_TEMP_NOWID . '").innerHTML = FormatizeSize(data.memtotal);
        document.getElementById("memused_' . $WMRISS_TEMP_NOWID . '").innerHTML = FormatizeSize(data.memused);
        document.getElementById("memusage_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.memusage + "%";
        document.getElementById("disktotal_' . $WMRISS_TEMP_NOWID . '").innerHTML = FormatizeSize(data.disktotal);
        document.getElementById("diskused_' . $WMRISS_TEMP_NOWID . '").innerHTML = FormatizeSize(data.diskused);
        document.getElementById("diskusage_' . $WMRISS_TEMP_NOWID . '").innerHTML = data.diskusage + "%";
        if (window.cpupoints_' . $WMRISS_TEMP_NOWID . '.length > 80) {
            window.cpupoints_' . $WMRISS_TEMP_NOWID . '.shift();
            window.cpupoints_' . $WMRISS_TEMP_NOWID . '.forEach(remixListData);
        }
        window.cpupoints_' . $WMRISS_TEMP_NOWID . '.push([window.cpupoints_' . $WMRISS_TEMP_NOWID . '.length * 2,(100 - parseInt(data.cpuusage)) / 2]);
        var cpucanvas_' . $WMRISS_TEMP_NOWID . ' = document.getElementById("cpucanvas_' . $WMRISS_TEMP_NOWID . '");
        var cpuctx_' . $WMRISS_TEMP_NOWID . ' = cpucanvas_' . $WMRISS_TEMP_NOWID . '.getContext("2d");
        cpuctx_' . $WMRISS_TEMP_NOWID . '.clearRect(0,0,320,50);
        cpuctx_' . $WMRISS_TEMP_NOWID . '.beginPath();
        cpuctx_' . $WMRISS_TEMP_NOWID . '.moveTo(0,window.cpupoints_' . $WMRISS_TEMP_NOWID . '[0][1]);
        cpuctx_' . $WMRISS_TEMP_NOWID . '.strokeStyle = "rgb(17,125,187)";
        for (var i = 0 ; i < window.cpupoints_' . $WMRISS_TEMP_NOWID . '.length; i++){
            cpuctx_' . $WMRISS_TEMP_NOWID . '.lineTo(window.cpupoints_' . $WMRISS_TEMP_NOWID . '[i][0],window.cpupoints_' . $WMRISS_TEMP_NOWID . '[i][1]);
        }
        cpuctx_' . $WMRISS_TEMP_NOWID . '.stroke();
        
        if (window.mempoints_' . $WMRISS_TEMP_NOWID . '.length > 80) {
            window.mempoints_' . $WMRISS_TEMP_NOWID . '.shift();
            window.mempoints_' . $WMRISS_TEMP_NOWID . '.forEach(remixListData);
        }
        window.mempoints_' . $WMRISS_TEMP_NOWID . '.push([window.mempoints_' . $WMRISS_TEMP_NOWID . '.length * 2,(100 - parseInt(data.memusage)) / 2]);
        var memcanvas_' . $WMRISS_TEMP_NOWID . ' = document.getElementById("memcanvas_' . $WMRISS_TEMP_NOWID . '");
        var memctx_' . $WMRISS_TEMP_NOWID . ' = memcanvas_' . $WMRISS_TEMP_NOWID . '.getContext("2d");
        memctx_' . $WMRISS_TEMP_NOWID . '.clearRect(0,0,320,50);
        memctx_' . $WMRISS_TEMP_NOWID . '.beginPath();
        memctx_' . $WMRISS_TEMP_NOWID . '.moveTo(0,window.mempoints_' . $WMRISS_TEMP_NOWID . '[0][1]);
        memctx_' . $WMRISS_TEMP_NOWID . '.strokeStyle = "rgb(139,18,147)";
        for (var i = 0 ; i < window.mempoints_' . $WMRISS_TEMP_NOWID . '.length; i++){
            memctx_' . $WMRISS_TEMP_NOWID . '.lineTo(window.mempoints_' . $WMRISS_TEMP_NOWID . '[i][0],window.mempoints_' . $WMRISS_TEMP_NOWID . '[i][1]);
        }
        memctx_' . $WMRISS_TEMP_NOWID . '.stroke();

        if (window.diskpoints_' . $WMRISS_TEMP_NOWID . '.length > 80) {
            window.diskpoints_' . $WMRISS_TEMP_NOWID . '.shift();
            window.diskpoints_' . $WMRISS_TEMP_NOWID . '.forEach(remixListData);
        }
        window.diskpoints_' . $WMRISS_TEMP_NOWID . '.push([window.diskpoints_' . $WMRISS_TEMP_NOWID . '.length * 2,(100 - parseInt(data.diskusage)) / 2]);
        var diskcanvas_' . $WMRISS_TEMP_NOWID . ' = document.getElementById("diskcanvas_' . $WMRISS_TEMP_NOWID . '");
        var diskctx_' . $WMRISS_TEMP_NOWID . ' = diskcanvas_' . $WMRISS_TEMP_NOWID . '.getContext("2d");
        diskctx_' . $WMRISS_TEMP_NOWID . '.clearRect(0,0,320,50);
        diskctx_' . $WMRISS_TEMP_NOWID . '.beginPath();
        diskctx_' . $WMRISS_TEMP_NOWID . '.moveTo(0,window.diskpoints_' . $WMRISS_TEMP_NOWID . '[0][1]);
        diskctx_' . $WMRISS_TEMP_NOWID . '.strokeStyle = "rgb(105,140,82)";
        for (var i = 0 ; i < window.diskpoints_' . $WMRISS_TEMP_NOWID . '.length; i++){
            diskctx_' . $WMRISS_TEMP_NOWID . '.lineTo(window.diskpoints_' . $WMRISS_TEMP_NOWID . '[i][0],window.diskpoints_' . $WMRISS_TEMP_NOWID . '[i][1]);
        }
        diskctx_' . $WMRISS_TEMP_NOWID . '.stroke();
    }, function(status) {
        console.error("Fail to connect " + strhostpath + "/out?id=' . $WMRISS_TEMP_NOWID . '");
    });
}
requestListData_' . $WMRISS_TEMP_NOWID . '();
setInterval(function () {
requestListData_' . $WMRISS_TEMP_NOWID . '();
},2000);';
                        echo "</script>";
                    }
                    ?>

                </tbody>
            </table>
            <div id="cards">
                <div class="ui doubling three column grid"></div>
            </div>
        </div>
        <div class="footer" style="padding-bottom: 15px;">
            <div>Powered by <a href="https://github.com/WMProject1217/WMRIS">WMRISS</a></div>
            <div>UI by <a href="https://github.com/cokemine/hotaru_theme">hotaru_theme</a></div>
        </div>
    </div>
</body>