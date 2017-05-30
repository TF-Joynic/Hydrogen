<?php

return
array(

    /**
     * preload files when application boots
     */
    PRELOAD_FILES => array(
        'lib/Psr/Http/message/src/MessageInterface.php'
    ),

    /**
     * loading map for some specified Class
     */
    CLASS_LOAD_MAP => array(

        \Hydrogen\Http\Request\Client\Curl::class => 'lib/Hydrogen/Http/Request/Client/Curl.php',
        \Hydrogen\Config\Config::class => 'lib/Hydrogen/Config/Config.php',
//        Psr\Http\Message\MessageInterface::class => 'lib/Psr/Http/message/src/MessageInterface.php',

    ),

);