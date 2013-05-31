<?php
function timeAgo($tm,$rcs = 0) {
   $cur_tm = time(); $dif = $cur_tm-$tm;
   $pds = array('second','minute','hour','day','week','month','year','decade');
   $lngh = array(1,60,3600,86400,604800,2630880,31570560,315705600);
   for($v = sizeof($lngh)-1; ($v >= 0)&&(($no = $dif/$lngh[$v])<=1); $v--); if($v < 0) $v = 0; $_tm = $cur_tm-($dif%$lngh[$v]);

   $no = floor($no); if($no <> 1) $pds[$v] .='s'; $x=sprintf("%d %s ",$no,$pds[$v]);
   if(($rcs == 1)&&($v >= 1)&&(($cur_tm-$_tm) > 0)) $x .= time_ago($_tm);
   return $x;
}

function formatSizeUnits($bytes) {
    if ($bytes >= 1073741824) {
        $bytes = number_format($bytes / 1073741824, 2) . ' GiB';
    } elseif ($bytes >= 1048576) {
        $bytes = number_format($bytes / 1048576, 2) . ' MiB';
    } elseif ($bytes >= 1024) {
        $bytes = number_format($bytes / 1024, 2) . ' KiB';
    } elseif ($bytes > 1) {
        $bytes = $bytes . ' bytes';
    } elseif ($bytes == 1) {
        $bytes = $bytes . ' byte';
    } else {
        $bytes = '0 bytes';
    }
    return $bytes;
}

function curPageURL() {
    $pageURL = 'http';
    if (!empty($_SERVER["HTTPS"])) {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}


define('DS', DIRECTORY_SEPARATOR);
$url = curPageURL();
$path = realpath('');
setlocale(LC_ALL, 'IND');
$dateFormat = '%c';
$finfo = new finfo(FILEINFO_MIME_TYPE);
$hideThisFile = array(
    '.', '..', 'Thumbs.db', 'desktop.ini', '.htaccess', '.git'
);
$hideThisPattern = array('/~$/');

$dirs = scandir($path);
$directories = $files = $items = array();

natcasesort($dirs);

$i = 0;
foreach ($dirs as $dir) {

    if (!is_readable($path.DS.$dir)) continue;

    if (in_array($dir, $hideThisFile)) continue;

    foreach($hideThisPattern as $pattern) {
        if (preg_match_all($pattern, $dir, $matches)) continue;
    }

    $items[$i] = array(
        'name' => $dir,
        'ext' => pathinfo($dir, PATHINFO_EXTENSION),
        'mime' => $finfo->file($path.DS.$dir),
        'size' => formatSizeUnits(filesize($path.DS.$dir)),
        'created' => strftime($dateFormat, filectime($path.DS.$dir)),
        'created_ago' => timeAgo(filectime($path.DS.$dir)),
        'modified' => strftime($dateFormat, filemtime($path.DS.$dir)),
        'modified_ago' => timeAgo(filemtime($path.DS.$dir)),
        'mime' => $finfo->file($path.DS.$dir),
    );

    if (is_dir($path.DS.$items[$i]['name'])) {
        $items[$i]['name'] = $items[$i]['name'].'/';
        $directories[] = $items[$i];
    } else {
        $files[] = $items[$i];
    }
    $i++;
}

$dirs = array_merge($directories, $files);

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Landing Page</title>
    <style>
        * {
            padding: 0; margin: 0;
        }
        html {
            font-size: 62.5%;
        }
        body {
            font-family: sans-serif;
            font-size: 1.3rem;
        }
        table {
            width: 100%;
            border: 1px solid #cecece;
            border-collapse: collapse;
        }
        thead tr {
            background: #e1e1e1;
        }
        table th {
            border: 1px solid #cecece;
            padding: 4px 8px;
            text-align: left;
        }
        table td {
            border-bottom: 1px solid #cecece;
            padding: 4px 8px;
        }
        .align-right {
            text-align: right
        }
    </style>
</head>
<body>
    <h1><?php echo $path ?></h1>
    <table>
        <thead id="thead">
            <tr>
                <th><a href="#">Name</a></th>
                <th><a href="#">Extension</a></th>
                <th><a href="#">Mime</a></th>
                <th><a href="#">Size</a></th>
                <th><a href="#">Modified</a></th>
                <th><a href="#">Created</a></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($dirs as $dir): ?>
            <tr>
                <td>
                    <a href="<?php echo $url.$dir['name'] ?>"><?php echo $dir['name'] ?></a>
                </td>
                <td><?php echo $dir['ext'] ?></td>
                <td><?php echo $dir['mime'] ?></td>
                <td class="align-right"><?php echo $dir['size'] ?></td>
                <td><?php echo $dir['modified'] ?> / <?php echo $dir['modified_ago'] ?> ago</td>
                <td><?php echo $dir['created'] ?> / <?php echo $dir['created_ago'] ?> ago</td>
            </tr>
            <?php endforeach ?>
        </tbody>
    <table>
</body>
</html>
