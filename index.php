<?php
define('DS', DIRECTORY_SEPARATOR);
$url = "http://{$_SERVER['HTTP_HOST']}/";
$path = realpath('');
setlocale(LC_ALL, 'IND');
$dateFormat = '%c';
$finfo = new finfo(FILEINFO_MIME_TYPE);
$hideThisFile = array(
    '.', '..', 'Thumbs.db', 'desktop.ini', '.htaccess',
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
        if (preg_match_all($pattern, $dir)) continue;
    }

    $items[$i] = array(
        'name' => $dir,
        'size' => filesize($path.DS.$dir),
        'created' => strftime($dateFormat, filectime($path.DS.$dir)),
        'modified' => strftime($dateFormat, filemtime($path.DS.$dir)),
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
        }
        table td {
            border-bottom: 1px solid #cecece;
            padding: 4px 8px;
        }
    </style>
</head>
<body>
    <table>
        <thead id="thead">
            <tr>
                <th><a href="#">Name</a></th>
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
                <td><?php echo $dir['size'] ?></td>
                <td><?php echo $dir['modified'] ?></td>
                <td><?php echo $dir['created'] ?></td>
            </tr>
            <?php endforeach ?>
        </tbody>
    <table>
</body>
</html>
