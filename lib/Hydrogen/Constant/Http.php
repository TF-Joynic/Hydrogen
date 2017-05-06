<?php

/**
 * Request Method(s)
 */

// regard XML_HTTP_REQUEST as AJAX from the name
defined('HTTP_METH_AJAX') || define('HTTP_METH_AJAX', 'AJAX');

/**
 * HTTP Header(s)
 */
defined('HTTP_HEADER_HOST') || define('HTTP_HEADER_HOST', 'Host');
defined('HTTP_HEADER_CONNECTION') || define('HTTP_HEADER_CONNECTION', 'Connection');
defined('HTTP_HEADER_CACHE_CTRL') || define('HTTP_HEADER_CACHE_CTRL', 'Cache-Control');
defined('HTTP_HEADER_ACCEPT') || define('HTTP_HEADER_ACCEPT', 'Accept');
defined('HTTP_HEADER_UPGRADE_INSECURE_REQUESTS') || define('HTTP_HEADER_UPGRADE_INSECURE_REQUESTS', 'Upgrade-Insecure-Requests');
defined('HTTP_HEADER_USER_AGENT') || define('HTTP_HEADER_USER_AGENT', 'User-Agent');
defined('HTTP_HEADER_ACCEPT_ENCODING') || define('HTTP_HEADER_ACCEPT_ENCODING', 'Accept-Encoding');
defined('HTTP_HEADER_ACCEPT_LANGUAGE') || define('HTTP_HEADER_ACCEPT_LANGUAGE', 'Accept-Language');
defined('HTTP_HEADER_CONTENT_TYPE') || define('HTTP_HEADER_CONTENT_TYPE', 'Content-Type');