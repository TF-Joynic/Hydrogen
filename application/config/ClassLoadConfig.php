<?php

return
array(

    /**
     * preload files when application boots
     */
    PRELOADFILES => array(
        'lib/Psr/Http/message/src/MessageInterface.php'
    ),

    /**
     * loading map for some specified Class
     */
    CLASSLOADMAP => array(

        \Hydrogen\Http\Request\Client\Curl::class => 'lib/Hydrogen/Http/Request/Client/Curl.php',
        \Hydrogen\Config\Config::class => 'lib/Hydrogen/Config/Config.php',
//        Psr\Http\Message\MessageInterface::class => 'lib/Psr/Http/message/src/MessageInterface.php',

    ),

);