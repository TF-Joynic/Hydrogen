<?php

namespace Hydrogen\Log;

use Psr\Log\LogLevel;

class NullLogger extends \Psr\Log\NullLogger
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
        if (1) {

        }
    }
}