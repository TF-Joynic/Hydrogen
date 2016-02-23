<?php

namespace Hydrogen\Application\Execute;

use Hydrogen\Config\Config;
use Hydrogen\Mvc\Ctrl\Ctrl;
use Hydrogen\Load\Autoloader;
use Hydrogen\Mvc\Ctrl\Plugin\PluginInterface;

class Executor
{
    private static $_instance = null;

    private $_default_module = 'front';
    private $_default_ctrl = 'Index';
    private $_default_act = 'index';

    private $_ctrlClassPostfix = 'Ctrl';
    private $_actMethodPostfix = 'Act';

    private $_error_ctrl_name = 'Error';

    private $_modules = array();   // available modules
    private $_moduleDir = APPLICATION_PATH;
    private $_applicationConfigDir = 'config';

    private $_module_init_file = 'Init.php';

    private function __construct()
    {
        // $config = new Config();
    }

    public static function getInstance()
    {
        if (null == self::$_instance) {
            self::$_instance = new self();
        }

        return self::$_instance;
    }

    public function setAvailableModules($modules)
    {
        if (!is_array($modules)) {
            $modules = array($modules);
        }

        $this->_modules = $modules;

        return $this;
    }

    public function getAvailableModules()
    {
        return $this->_modules;
    }

    public function setModuleDir($dir)
    {
        if (is_dir($dir))
            $this->_moduleDir = $dir;

        return $this;
    }

    public function getModuleDir()
    {
        return $this->_moduleDir;
    }

    public function setApplicationConfigDir($dir)
    {
        if (is_dir($dir))
            $this->_applicationConfigDir = $dir;

        return $this;
    }

    public function getApplicationConfigDir()
    {
        return $this->_applicationConfigDir;
    }

    public function setDefaultModule($module)
    {
        if ($module && is_string($module)) {
            $this->_default_module = $module;
        }

        return $this;
    }

    public function getDefaultModule()
    {
        return $this->_default_module;
    }

    public function getDefaultCtrl()
    {
        return $this->_default_ctrl;
    }

    public function getDefaultAct()
    {
        return $this->_default_act;
    }

    public function getCtrlClassPostfix()
    {
        return $this->_ctrlClassPostfix;
    }

    public function setCtrlClassPostfix($postfix = 'Ctrl')
    {
        if (0 < strlen($postfix) && $postfix != $this->_ctrlClassPostfix) {
            $this->_ctrlClassPostfix = $postfix;
        }

        return $this;
    }

    public function getActMethodPostfix()
    {
        return $this->_actMethodPostfix;
    }

    public function setActMethodPostfix($postfix = 'Act')
    {
        if (0 < strlen($postfix) && $postfix != $this->_actMethodPostfix) {
            $this->_actMethodPostfix = $postfix;
        }

        return $this;
    }

    public function setModuleInitFileName($filename)
    {
        $this->_module_init_file = $filename;
        return $this;
    }

    public function getModuleInitFileName()
    {
        return $this->_module_init_file;
    }

    public function setNamespaceDir($namespace, $dir, $prepend = false)
    {
        return Autoloader::getInstance()->attachNamespace($namespace, $dir, $prepend);
    }

    public function setErrorCtrlName($ctrl_name)
    {
        if (is_string($ctrl_name) && 0 < strlen($ctrl_name)) {
            $this->_error_ctrl_name = $ctrl_name;
        }

        return $this;
    }

    public function getErrorCtrlName()
    {
        return $this->_error_ctrl_name;
    }

    /**
     * register plugin to one controller
     *
     * @param Ctrl $ctrl
     * @param PluginInterface $plugin
     * @return mixed
     */
    public function registerPlugin(Ctrl $ctrl, PluginInterface $plugin)
    {
        if (class_exists($ctrl) || !method_exists($ctrl, 'registerPlugin')) {
            return false;
        }

        return call_user_func_array(array($ctrl, 'registerPlugin'), array($plugin));
    }

    public function clearPlugin(Ctrl $ctrl)
    {
        if (class_exists($ctrl) || !method_exists($ctrl, 'clearPlugin')) {
            return false;
        }

        return call_user_func_array(array($ctrl, 'clearPlugin'), array());
    }

	private function __clone()
	{}
}