<?php

namespace Hydrogen\Config\Reader;

use Hydrogen\Config\Exception\FileNotFoundException;
use Hydrogen\Config\Exception\ReadFailedException;
use Hydrogen\Debug\Variable;

class Ini implements ReaderInterface
{
    const SOURCE_TYPE_FILE = 'file';
    const SOURCE_TYPE_STRING = 'string';

    private $_sanner_mode = INI_SCANNER_NORMAL;

    private $_keySeparator = '.';
    private $_inheritSeparator = ':';

    private $_source_data = '';
    private $_source_type = '';

    public function __construct($source_data)
    {
        if (null != $source_data && is_string($source_data)) {

            if ('.ini' == strtolower(strrchr($source_data, '.'))) {

                if (!file_exists($source_data)) {

                    throw new FileNotFoundException(
                        "File {$source_data} not found");

                }

                $this->_source_type = self::SOURCE_TYPE_FILE;

            } else {
                $this->_source_type = self::SOURCE_TYPE_STRING;
            }

            $this->_source_data = $source_data;

        } else {

            throw new \InvalidArgumentException('Illegal argument or argument type :'
                . $source_data);
            
        }
    }

    public function getSourceType()
    {
        return $this->_source_type;
    }

    public function setKeySeparator($keySeparator)
    {
        if (null != $keySeparator && is_string($keySeparator)) {
            $this->_keySeparator = $keySeparator;
        }

        return true;
    }

    public function getKeySeparator()
    {
        return $this->_keySeparator;
    }

    public function setInheritSeparator($inheritSeparator)
    {
        if (null != $inheritSeparator && is_string($inheritSeparator)) {
            $this->_inheritSeparator = $inheritSeparator;
        }

        return true;
    }

    public function getInheritSeparator()
    {
        return $this->_inheritSeparator;
    }

    public function setSannerMode($sanner_mode)
    {
        if (!in_array($sanner_mode, array(INI_SCANNER_NORMAL, INI_SCANNER_RAW))) {
            return false;
        }

        $this->_sanner_mode = $sanner_mode;
        return true;
    }

    public function getSannerMode()
    {
        return $this->_sanner_mode;
    }

    public function read($section = '')
    {
        $scanner_mode = $this->_sanner_mode;

        $arr = self::SOURCE_TYPE_FILE == $this->_source_type
            ? parse_ini_file($this->_source_data, true, $scanner_mode)
            : parse_ini_string($this->_source_data, true, $scanner_mode);

        /*echo "<pre>";
        print_r($arr);
        echo "</pre>";exit;*/

        if (false === $arr) {
            throw new ReadFailedException("Read file failed, possibly caused by invalid file");
        }

        $config = array();

        $inheritSeparator = $this->getInheritSeparator();
        foreach ($arr as $sect => $value) {

            $sect = trim($sect, $inheritSeparator." \t\r\n\0\x0B");
            if (0 == strlen($sect)) {
                continue;
            }

            if (false !== strpos($sect, $inheritSeparator)) {

                // means it is a valid inherited section
                $inheritContextArr = explode($inheritSeparator, $sect);
                if (2 < count($inheritContextArr)) {
                    throw new ReadFailedException('Too many inherit seprator! One is limit!');
                }

                $sect = $inheritContextArr[0];
                $parentSect = $inheritContextArr[1];
                if ($parentSect && isset($config[$parentSect])) {
                    $config[$sect] = $config[$parentSect];
                }
            } else {
                $config[$sect] = array();
            }

            if (is_array($value)) {

                $config[$sect] = array_replace_recursive($config[$sect],
                    $this->_nestValue($value));

            } else {
                $config = $this->_nestValue($arr);
                break;
            }
        }

        if (is_string($section) && 0 < strlen($section)) {
            if (isset($config[$section])) {
                return $config[$section];
            } else {

                throw new \UnexpectedValueException('Invalid section data type or
                 specified section not found: '.$section);

            }
        } else {
            return $config;
        }
    }

    private function _nestValue($value) {
        $nestValue = array();
        $keySeparator = $this->getKeySeparator();

        foreach ($value as $keys => $v) {
            if (false !== strpos(trim($keys, $keySeparator), $keySeparator)) {
                $keysArr = explode($keySeparator, $keys);

                $first = array_shift($keysArr);
                $next = array(
                    implode($keySeparator, $keysArr) => $v
                );

                if (isset($nestValue[$first])) {

                    $nestValue[$first] = array_replace_recursive($nestValue[$first],
                        $this->_nestValue($next));

                } else {
                    $nestValue[$first] = $this->_nestValue($next);
                }
            } else {
                $nestValue[$keys] = $v;
            }
        }

        return $nestValue;
    }
}