<?php

namespace Hydrogen\Application;

use Hydrogen\Config\Config;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Load\Autoloader;
use Hydrogen\Mvc\Ctrl\Plugin\PluginInterface;

class ApplicationContext
{
    private static $_defaultModule = 'front';
    private static $_defaultCtrl = 'Index';
    private static $_defaultAct = 'index';

    private static $_ctrlClassPostfix = 'Ctrl';
    private static $_actMethodPostfix = 'Act';

    private static $_errorCtrl = 'Error';
    private static $_errorAct = 'index';

    private static $_modules = array();   // enabled modules
    private static $_moduleDir = APPLICATION_PATH;
    private static $_applicationConfigDir = 'config';
    private static $_templateDir = 'view';

    private static $_templatePostfix = 'phtml';

    private static $_viewRenderer = '';

    private static $_moduleInitFile = 'Init.php';

    private static $_classLoadConfigFile = 'ClassLoadConfig.php';

    // common filters
    private static $_filters = array();

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

    public static function setModuleDir($dir)
    {
        if (is_dir($dir))
            self::$_moduleDir = $dir;
    }

    public static function getModuleDir()
    {
        return self::$_moduleDir;
    }

    public static function setApplicationConfigDir($dir)
    {
        if (is_dir($dir))
            self::$_applicationConfigDir = $dir;
    }

    public static function getApplicationConfigDir()
    {
        return self::$_applicationConfigDir;
    }

    public static function getTemplateDir()
    {
        return self::$_templateDir;
    }

    public static function setTemplateDir($templateDir)
    {
        self::$_templateDir = $templateDir;
    }


    public static function setDefaultModule($module)
    {
        if ($module && is_string($module)) {
            self::$_defaultModule = $module;
        }
    }

    public static function getDefaultModule()
    {
        return self::$_defaultModule;
    }

    public static function getDefaultCtrl()
    {
        return self::$_defaultCtrl;
    }

    public static function getDefaultAct()
    {
        return self::$_defaultAct;
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
        return Autoloader::getInstance()->attachNamespace($namespace, $dir, $prepend);
    }

    public static function setErrorCtrl($ctrl_name)
    {
        if (is_string($ctrl_name) && 0 < strlen($ctrl_name)) {
            self::$_errorCtrl = $ctrl_name;
        }
    }

    public static function getErrorCtrl()
    {
        return self::$_errorCtrl;
    }

    public static function getErrorAct()
    {
        return self::$_errorAct;
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
        if (class_exists($ctrl) || !method_exists($ctrl, 'registerPlugin')) {
            return false;
        }

        return call_user_func_array(array($ctrl, 'registerPlugin'), array($plugin));
    }

    public static function clearPlugin(Ctrl $ctrl)
    {
        if (class_exists($ctrl) || !method_exists($ctrl, 'clearPlugin')) {
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

    /**
     * [
     *  Chain::class => $chainObj
     * ]
     *
     * @param $filterCtxArr array
     */
    public static function setFilters($filterCtxArr)
    {
        self::$_filters = $filterCtxArr;
    }

    public static function getFilters()
    {
        return self::$_filters;
    }
}