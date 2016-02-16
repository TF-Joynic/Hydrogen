<?php
global $except_dirs;
$except_dirs = array(
    '.idea',
    '.svn'
);

if (isset($argv[1]) && $argv[1]) {
    $path = $argv[1];

    chmodFileOrDir($path);

    echo "~Fin~".PHP_EOL;
}

function chmodFileOrDir($Dir) {
    global $except_dirs;

    $ret = scandir($Dir);
    unset($ret[0], $ret[1]);
    
    foreach ($ret as $r) {
        $fileName = $Dir.DIRECTORY_SEPARATOR.$r;
        if (is_dir($fileName) && !in_array($fileName, $except_dirs)) {
            chmodFileOrDir($fileName);
        } elseif (file_exists($fileName)) {
            chmod($fileName, 0755);
            fwrite(STDOUT, "'{$fileName}' chmod ok".PHP_EOL);
        } else {
            fwrite(STDOUT, "'{$fileName}' invalid!".PHP_EOL);
        }
    }
}