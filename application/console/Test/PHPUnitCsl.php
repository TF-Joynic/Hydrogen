<?php

namespace application\console\Test;

class PHPUnitCsl extends TestCsl
{
    public function runAct($component = 'Http/Request/Curl')
    {
        if (!$component) {
            echo "parameter needed!".PHP_EOL;
        }

        $target = 'HydrogenTest'.self::NS_SEPARATOR.
            str_replace(array('/', '\\'), self::NS_SEPARATOR, $component).'Test';

        $target = realpath(__DIR__.'/../../../lib').'/'.str_replace(array('/', '\\'), DIRECTORY_SEPARATOR, $target).'.php';
        $phpunitBootFile = (__DIR__.'/_autoload.php');
//        var_dump($phpunitBootFile);exit;
        system("phpunit --bootstrap $phpunitBootFile {$target}");
    }
}