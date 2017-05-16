<?php

return
array(

    PRELOADFILES => array(
        'lib/Psr/Http/message/src/MessageInterface.php'
    ),

    CLASSLOADMAP => array(

        Psr\Http\Message\MessageInterface::class => 'lib/Psr/Http/message/src/MessageInterface.php',

    ),

);