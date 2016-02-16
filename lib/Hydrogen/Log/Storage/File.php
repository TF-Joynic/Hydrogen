<?php

namespace Hydrogen\Log\Storage;

class File extends AbstractStorage
{
    private $_filename = null;
    private $_write_buff = 10240;

    public function __construct($fileName)
    {
        $this->_filename = $fileName;
    }

    public function record($level, $logStr)
    {
        $timeStr = '['.date('Y-m-d H:i:s O').']';

        $fp = fopen($this->_filename, 'ab');
        if (false !== $fp) {
            stream_set_write_buffer($fp, $this->_write_buff);
            fwrite($fp, $timeStr.' '.$logStr.PHP_EOL);
            fclose($fp);
        } else {
            return false;
        }

        return true;
    }

//    public function
}