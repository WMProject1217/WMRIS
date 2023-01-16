<?php 
use Workerman\Worker;
use Workerman\Timer;
use Workerman\Protocols\Http\Request;
use Workerman\Protocols\Http\Response;
use Workerman\Connection\TcpConnection;

function WMSizeFormatize($SIZEBYTE) {
    $SIZENUMT = intval($SIZEBYTE);
    if ($SIZEBYTE < 0) {
        $SIZENUM = -($SIZENUM);
        $SIZEOFFST = 1;
    } else {
        $SIZEOFFST = 0;
    }
    $SIZEADDR = "B";
    if ($SIZENUMT > 1000) {
        $SIZENUMT = $SIZENUMT / 1024;
        $SIZEADDR = "KB";
        if ($SIZENUMT > 1000) {
            $SIZENUMT = $SIZENUMT / 1024;
            $SIZEADDR = "MB";
            if ($SIZENUMT > 1000) {
                $SIZENUMT = $SIZENUMT / 1024;
                $SIZEADDR = "GB";
                if ($SIZENUMT > 1000) {
                    $SIZENUMT = $SIZENUMT / 1024;
                    $SIZEADDR = "TB";
                    if ($SIZENUMT > 1000) {
                        $SIZENUMT = $SIZENUMT / 1024;
                        $SIZEADDR = "PB";
                        if ($SIZENUMT > 1000) {
                            $SIZENUMT = $SIZENUMT / 1024;
                            $SIZEADDR = "EB";
                        }
                    }
                }
            }
        }
    }
    if ($SIZEOFFST == 1) {
        $SIZENUMT = -($SIZENUMT);
    }
    return round($SIZENUMT,2) . $SIZEADDR;
}
/*function WMMemorySizeAdd($RAX,$RBX) {
    $RAX_MEMSIZE = floatval($RAX);
    if (substr($RAX,-2) == "TB") {
        $RAX_MEMSIZE = $RAX_MEMSIZE * 1024 * 1024;
    } elseif (substr($RAX,-2) == "GB") {
        $RAX_MEMSIZE = $RAX_MEMSIZE * 1024;
    } elseif (substr($RAX,-2) == "MB") {
        $RAX_MEMSIZE = $RAX_MEMSIZE;
    } elseif (substr($RAX,-2) == "KB") {
        $RAX_MEMSIZE = $RAX_MEMSIZE / 1024;
    }
    $RBX_MEMSIZE = floatval($RBX);
    if (substr($RBX,-2) == "TB") {
        $RBX_MEMSIZE = $RBX_MEMSIZE * 1024 * 1024;
    } elseif (substr($RBX,-2) == "GB") {
        $RBX_MEMSIZE = $RBX_MEMSIZE * 1024;
    } elseif (substr($RBX,-2) == "MB") {
        $RBX_MEMSIZE = $RBX_MEMSIZE;
    } elseif (substr($RBX,-2) == "KB") {
        $RBX_MEMSIZE = $RBX_MEMSIZE / 1024;
    }
    $TotalValue = $RAX_MEMSIZE + $RBX_MEMSIZE;
    if ($TotalValue > 1000) {
        $TotalValue = round(($TotalValue / 1024),2) . "GB";
    } else {
        $TotalValue = round($TotalValue,2) . "MB";
    }
    return $TotalValue;
}*/

require_once __DIR__ . '/../vendor/autoload.php';

$WMRISS_JsonDump = [];

$web = new Worker("http://0.0.0.0:12200");
$web->count = 1;
$web->name = 'WMCSIS';

define('WEBROOT', __DIR__ . DIRECTORY_SEPARATOR .  'Web');

$web->onWorkerStart = function(Worker $web)
{
    $time_interval = 2;
    Timer::add($time_interval, function()
    {
        global $WMRISS_JsonDump;
        //array_push($WMRISS_JsonDump,date("h:i:sa"));
        echo var_export($WMRISS_JsonDump,true);
    });
};

$web->onMessage = function (TcpConnection $connection, Request $request) {
    global $WMRISS_JsonDump;
    $_GET = $request->get();
    $path = $request->path();
    if ($path === '/') {
        $connection->send(exec_php_file(WEBROOT.'/index.php'));
        //$connection->send(var_export($WMRISS_JsonDump,true));
        return;
    } else if ($path === '/in') {
        $WMRISS_JsonDump[$request->get('id', 0)] = $request->get('json', '{"status":"404"}');
        $connection->send('{"status":"200"}');
        return;
    } else if ($path === '/out') {
        $connection->send($WMRISS_JsonDump[$request->get('id', 0)]);
        return;
    }
    $file = realpath(WEBROOT. $path);
    if (false === $file) {
        $connection->send(new Response(404, array(), '<h3>404 Not Found</h3>'));
        return;
    }
    // Security check
    if (strpos($file, WEBROOT) !== 0) {
        $connection->send(new Response(400));
        return;
    }
    if (\pathinfo($file, PATHINFO_EXTENSION) === 'php') {
        $connection->send(exec_php_file($file));
        return;
    }

    $if_modified_since = $request->header('if-modified-since');
    if (!empty($if_modified_since)) {
        // Check 304.
        $info = \stat($file);
        $modified_time = $info ? \date('D, d M Y H:i:s', $info['mtime']) . ' ' . \date_default_timezone_get() : '';
        if ($modified_time === $if_modified_since) {
            $connection->send(new Response(304));
            return;
        }
    }
    $connection->send((new Response())->withFile($file));
};

function exec_php_file($file) {
    \ob_start();
    try {
        include $file;
    } catch (\Exception $e) {
        echo $e;
    }
    return \ob_get_clean();
}

if(!defined('GLOBAL_START'))
{
    Worker::runAll();
}

