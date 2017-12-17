<?php

namespace Hydrogen\Application;


use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Load\Autoloader;
use Hydrogen\Mvc\Ctrl\Plugin\PluginInterface;
use Hydrogen\Utils\Str;

class ApplicationContext
{
    private static $_defaultModuleName = 'front';
    private static $_defaultCtrlName = 'Index';
    private static $_defaultActName = 'index';

    private static $_ctrlClassPostfix = 'Ctrl';
    private static $_actMethodPostfix = 'Act';

    private static $_errorCtrlName = 'Error';
    private static $_errorActName = 'index';

    private static $_modules = array();   // enabled modules
    private static $_moduleDirPath = APPLICATION_PATH;
    private static $_applicationConfigDirName = 'config';
    private static $_templateDirName = 'view';

    private static $_templatePostfix = 'phtml';

    private static $_viewRenderer = '';

    private static $_moduleInitFile = 'Init.php';

    private static $_classLoadConfigFile = 'ClassLoadConfig.php';

    private static $_filterInstances = array();

    private static $_interceptorInstances = array();

    public static function setEnabledModules($modules)
    {
        if (!is_array($modules)) {
            $modules = array($modules);
        }

        self::$_modules = $modules;
    }

    public static function getEnabledModules()
    {
        return self::$_modules;
    }

    public static function setModuleDirPath($dir)
    {
        if (Str::isDirStr($dir))
            self::$_moduleDirPath = $dir;
    }

    public static function getModuleDirPath()
    {
        return self::$_moduleDirPath;
    }

    public static function setApplicationConfigDirName($dir)
    {
        if (Str::isDirStr($dir))
            self::$_applicationConfigDirName = $dir;
    }

    public static function getApplicationConfigDirName()
    {
        return self::$_applicationConfigDirName;
    }

    public static function getTemplateDirName()
    {
        return self::$_templateDirName;
    }

    public static function setTemplateDirName($templateDir)
    {
        if (Str::isDirStr($templateDir))
            self::$_templateDirName = $templateDir;
    }

    public static function setDefaultModuleName($module)
    {
        if ($module && is_string($module)) {
            self::$_defaultModuleName = $module;
        }
    }

    public static function getDefaultModuleName()
    {
        return self::$_defaultModuleName;
    }

    public static function getDefaultCtrlName()
    {
        return self::$_defaultCtrlName;
    }

    public static function getDefaultActName()
    {
        return self::$_defaultActName;
    }

    public static function getCtrlClassPostfix()
    {
        return self::$_ctrlClassPostfix;
    }

    public static function setCtrlClassPostfix($postfix = 'Ctrl')
    {
        if (0 < strlen($postfix) && $postfix != self::$_ctrlClassPostfix) {
            self::$_ctrlClassPostfix = $postfix;
        }
    }

    public static function getActMethodPostfix()
    {
        return self::$_actMethodPostfix;
    }

    public static function setActMethodPostfix($postfix = 'Act')
    {
        if (0 < strlen($postfix) && $postfix != self::$_actMethodPostfix) {
            self::$_actMethodPostfix = $postfix;
        }
    }

    public static function setModuleInitFileName($filename)
    {
        self::$_moduleInitFile = $filename;
    }

    public static function getModuleInitFileName()
    {
        return self::$_moduleInitFile;
    }

    public static function setNamespaceDir($namespace, $dir, $prepend = false)
    {
        if (!Str::isDirStr($dir))
            return false;

        return Autoloader::getInstance()->attachNamespace($namespace, $dir, $prepend);
    }

    public static function setErrorCtrlName($ctrl_name)
    {
        if (is_string($ctrl_name) && 0 < strlen($ctrl_name)) {
            self::$_errorCtrlName = $ctrl_name;
        }
    }

    public static function getErrorCtrlName()
    {
        return self::$_errorCtrlName;
    }

    public static function getErrorActName()
    {
        return self::$_errorActName;
    }

    /**
     * register plugin to one controller
     *
     * @param Ctrl $ctrl
     * @param PluginInterface $plugin
     * @return mixed
     */
    public static function registerPlugin(Ctrl $ctrl, PluginInterface $plugin)
    {
        if (!class_exists($ctrl, false) || !method_exists($ctrl, 'registerPlugin')) {
            return false;
        }

        return call_user_func_array(array($ctrl, 'registerPlugin'), array($plugin));
    }

    public static function clearPlugin(Ctrl $ctrl)
    {
        if (!class_exists($ctrl, false) || !method_exists($ctrl, 'clearPlugin')) {
            return false;
        }

        return call_user_func_array(array($ctrl, 'clearPlugin'), array());
    }

    /**
     * @return string
     */
    public static function getTemplatePostfix()
    {
        return self::$_templatePostfix;
    }

    /**
     * @param string $templatePostfix
     */
    public static function setTemplatePostfix($templatePostfix)
    {
        self::$_templatePostfix = $templatePostfix;
    }

    /**
     * @return string
     */
    public static function getClassLoadConfigFile()
    {
        return self::$_classLoadConfigFile;
    }

    /**
     * @param $classLoadConfigFile
     */
    public static function setClassLoadConfigFile($classLoadConfigFile)
    {
        self::$_classLoadConfigFile = $classLoadConfigFile;
    }

    public static function setInterceptorInstances($interceptorCtxArr)
    {
        self::$_interceptorInstances = $interceptorCtxArr;
    }

    public static function getInterceptorInstances()
    {
        return self::$_interceptorInstances;
    }

    /**
     * [
     *  Chain::class => $chainObj
     * ]
     *
     * @param $filterCtxArr array
     */
    public static function setFilterInstances($filterCtxArr)
    {
        self::$_filterInstances = $filterCtxArr;
    }

    public static function getFilterInstances()
    {
        return self::$_filterInstances;
    }
}