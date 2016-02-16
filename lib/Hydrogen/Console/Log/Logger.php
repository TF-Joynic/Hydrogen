<?php

namespace Hydrogen\Console;

use Psr\Log\LogLevel;
use Psr\Log\NullLogger;

class Logger extends NullLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed  $level
     * @param string $message
     * @param array  $context
     *
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        // noop
        if (in_array($level, array(LogLevel::INFO, LogLevel::NOTICE))) {
            return ;
        }



    }
}