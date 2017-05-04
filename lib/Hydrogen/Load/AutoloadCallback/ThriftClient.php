<?php
/**
 * Created by IntelliJ IDEA.
 * User: xiaolei
 * Date: 17/5/4
 * Time: 17:52
 */

namespace Hydrogen\Load\AutoloadCallback;

use Hydrogen\Load\Autoloader;

class ThriftClient extends AbstractAutoloadCallback
{
    public static function autoLoad($class_name)
    {
        $class_name = str_replace(self::NAMESPACE_SEPARATOR, DIRECTORY_SEPARATOR, $class_name);
        if (false !== stripos($class_name, 'thrift')) {
            $class_name = str_replace('Client', '', $class_name);

            Autoloader::getInstance()->loadClass($class_name.'.php');
        }
    }
}